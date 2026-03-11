<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\FrontEnd\PaymentGateway\AuthorizeNetController;
use App\Http\Controllers\FrontEnd\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PayPalController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\StripeController;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Invoice;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PDF;

class PayController extends Controller
{
  public function index(Request $request)
  {
   
    if ($request->exists('invoice')) {

      $invoice  = Invoice::where('uniq_id',  $request->invoice)->firstOrFail();
      $invoice_setings = Basic::select('is_invoice')->first();
      $invoiceId = $invoice->id;

      if ($invoice->payment_status == 'paid' || $invoice_setings->is_invoice != 1) {
        return abort(404);
      }

      Session::put('invoice_id', $invoiceId);
      $queryResult['invoice'] = $invoice;
    }

    // check for 'user authentication'
    if (Auth::guard('web')->check() == false) {
      $request->session()->put('redirectTo', url()->full());

      return redirect()->route('user.login');
    }

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_invoice_payment', 'meta_description_invoice_payment')->first();


    $queryResult['invoiceUniqid'] = $invoice->uniq_id;
    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();

    $authorizenet = OnlineGateway::query()->whereKeyword('authorize.net')->first();
    $anetInfo = json_decode($authorizenet->information);

    if ($anetInfo->sandbox_status == 1) {
      $queryResult['anetSource'] = 'https://jstest.authorize.net/v1/Accept.js';
    } else {
      $queryResult['anetSource'] = 'https://js.authorize.net/v1/Accept.js';
    }

    $queryResult['anetClientKey'] = $anetInfo->public_client_key;
    $queryResult['anetLoginId'] = $anetInfo->api_login_id;

    $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();
    return view('frontend.payment.form', $queryResult);
  }

  public function pay(Request $request)
  {
    // validation
    if ($request->gateway == 'stripe') {
      $request->validate([
        'card_number' => 'required',
        'cvc_number' => 'required',
        'expiry_month' => 'required',
        'expiry_year' => 'required'
      ]);
    }

    $invoiceId = Session::get('invoice_id');

    $invoice = Invoice::query()->find($invoiceId);
    $allData['invoice'] = $invoice;
    $allData['grandTotal'] = floatval($invoice->grand_total);

    // redirect to respective payment-gateway controller
    if (!$request->exists('gateway')) {
      $request->session()->flash('error', 'Please select a payment method.');

      return redirect()->back()->withInput();
    } else if ($request['gateway'] == 'paypal') {
      $paypal = new PayPalController();

      return $paypal->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'instamojo') {
      $instamojo = new InstamojoController();

      return $instamojo->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'paystack') {
      $paystack = new PaystackController();

      return $paystack->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'flutterwave') {
      $flutterwave = new FlutterwaveController();

      return $flutterwave->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'razorpay') {
      $razorpay = new RazorpayController();

      return $razorpay->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'mercadopago') {
      $mercadopago = new MercadoPagoController();

      return $mercadopago->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'mollie') {
      $mollie = new MollieController();

      return $mollie->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'stripe') {
      $stripe = new StripeController();

      return $stripe->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'paytm') {
      $paytm = new PaytmController();

      return $paytm->index($request, $allData, 'invoice');
    } else if ($request['gateway'] == 'authorize.net') {
      $authorizenet = new AuthorizeNetController();

      return $authorizenet->index($request, $allData, 'invoice');
    } else {
      $offline = new OfflineController();

      return $offline->index($request, $allData, 'invoice');
    }
  }

  public function generateInvoice($invoice)
  {
    // generate pdf
    $queryResult['info'] = Basic::query()->select('favicon', 'invoice_logo')->first();
    $queryResult['invoice'] = $invoice;
    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $invoiceName = uniqid() . '.pdf';

    Session::put('invoice_name', $invoiceName);

    $directory = './assets/file/invoices/';
    @mkdir($directory, 0775, true);

    $fileLocation = $directory . $invoiceName;

    PDF::loadView('backend.invoice.document', $queryResult)->save(public_path($fileLocation));

    return;
  }

  public function prepareMail($invoice)
  {
    // get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'payment_success')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $customerName = $invoice->user_full_name;
    $invoiceNumber = $invoice->invoice_number;

    // replacing with actual data
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{invoice_number}', $invoiceNumber, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $invoice->user_email_address;

    $invoiceName = Session::get('invoice_name');

    $mailData['invoice'] = 'assets/file/invoices/' . $invoiceName;

    BasicMailer::sendMail($mailData);

    // delete the invoice from storage
    @unlink(public_path('assets/file/invoices/' . $invoiceName));

    // forget session data
    Session::forget(['invoice_id', 'invoice_name']);

    return;
  }

  public function complete(Request $request)
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['payVia'] = $request->input('via');

    return view('frontend.payment.success', $queryResult);
  }

  public function cancel(Request $request)
  {
    $request->session()->flash('error', 'Sorry, an error has occured!');

    return redirect()->route('pay');
  }
}
