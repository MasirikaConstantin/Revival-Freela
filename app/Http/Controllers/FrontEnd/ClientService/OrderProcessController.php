<?php

namespace App\Http\Controllers\FrontEnd\ClientService;

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
use App\Http\Controllers\FrontEnd\PaymentGateway\PhonePeController;
use App\Http\Controllers\FrontEnd\PaymentGateway\YocoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PerfectMoneyController;
use App\Http\Controllers\FrontEnd\PaymentGateway\ToyyibpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaytabsController;
use App\Http\Controllers\FrontEnd\PaymentGateway\IyzicoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MyFatoorahController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MidtransController;
use App\Http\Controllers\FrontEnd\PaymentGateway\XenditController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\ClientService\OrderProcessRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\ClientService\Form;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceOrder;
use App\Models\ClientService\ServicePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrderProcessController extends Controller
{
  public function index(OrderProcessRequest $request, $slug)
  {
    $allData = [
      'userId' => Auth::guard('web')->user()->id,
      'orderNumber' => uniqid(),
      'name' => $request['name'],
      'emailAddress' => $request['email_address']
    ];

    // get service-package information
    if ($request->session()->has('package_id')) {
      $packageId = $request->session()->get('package_id');
      $allData['packageId'] = $packageId;

      $package = ServicePackage::query()->findOrFail($packageId);
      $allData['packagePrice'] = $package->current_price;
    } else {
      $allData['packageId'] = null;
      $allData['packagePrice'] = null;
    }

    // get service-addon informations
    $addonPrice = 0.00;
    if ($request->session()->has('addons')) {
      $addonIds = $request->session()->get('addons');

      $addons = [];

      foreach ($addonIds as $addonId) {
        $serviceAddon = ServiceAddon::query()->findOrFail($addonId);

        $addonData = [
          'id' => $serviceAddon->id,
          'price' => $serviceAddon->price
        ];

        array_push($addons, $addonData);

        $addonPrice += floatval($serviceAddon->price);
      }
      $allData['addons'] = json_encode($addons);
      $allData['addonPrice'] = $addonPrice;
    } else {
      $allData['addons'] = null;
      $allData['addonPrice'] = null;
    }

    // calculate grand-total of the service
    if (isset($package)) {
      $basicInfo = Basic::select('tax')->first();
      $tax = ($basicInfo->tax / 100) * (floatval($package->current_price) + $addonPrice);
      $allData['tax_percentage'] = $basicInfo->tax;
      $allData['tax'] = $tax;
      $allData['grandTotal'] = floatval($package->current_price) + $addonPrice + $tax;
    } else {
      $allData['grandTotal'] = null;
    }

    // get service information
    $allData['slug'] = $slug;
    $allData['serviceId'] = ServiceContent::query()->where('slug', '=', $slug)->pluck('service_id')->first();

    // get data of form input-fields
    $formId = $request->session()->get('form_id');
    $form = Form::query()->find($formId);
    $inputFields = $form->input()->orderBy('order_no', 'asc')->get();

    if (count($inputFields) > 0) {
      $infos = [];

      foreach ($inputFields as $inputField) {
        if ($inputField->type == 8) {
          $inputName = 'form_builder_' . $inputField->name;
        } else {
          $inputName = $inputField->name;
        }

        if (array_key_exists($inputName, $request->all())) {
          if ($request->hasFile($inputName)) {
            $originalName = $request->file($inputName)->getClientOriginalName();
            $uniqueName = UploadFile::store('./assets/file/zip-files/', $request->file($inputName));

            $infos[$inputField->name] = [
              'originalName' => $originalName,
              'value' => $uniqueName,
              'type' => $inputField->type
            ];
          } else {
            $infos[$inputName] = [
              'value' => $request[$inputName],
              'type' => $inputField->type
            ];
          }
        }
      }

      $allData['infos'] = json_encode($infos);
    } else {
      $allData['infos'] = null;
    }
    if ($request['quote_btn_status'] == 1) {
      $allData['currencyText'] = null;
      $allData['currencyTextPosition'] = null;
      $allData['currencySymbol'] = null;
      $allData['tax_percentage'] = null;
      $allData['tax'] = null;
      $allData['currencySymbolPosition'] = null;
      $allData['paymentMethod'] = null;
      $allData['gatewayType'] = null;
      $allData['paymentStatus'] = 'pending';
      $allData['orderStatus'] = 'pending';
      $selected_service = Service::where('id', $allData['serviceId'])->select('seller_id')->first();
      $allData['seller_id'] = $selected_service->seller_id == 0 ? null : $selected_service->seller_id;
      // store service order information in database
      $this->storeData($allData);

      return redirect()->route('service.place_order.complete', ['slug' => $slug, 'via' => 'quote']);
    } else {
      // redirect to respective payment-gateway controller
      if (!$request->exists('gateway')) {
        $request->session()->flash('error', 'Please select a payment method.');

        return redirect()->back()->withInput();
      } else if ($request['gateway'] == 'paypal') {
        $paypal = new PayPalController();

        return $paypal->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'instamojo') {
        $instamojo = new InstamojoController();

        return $instamojo->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'paystack') {
        $paystack = new PaystackController();

        return $paystack->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'flutterwave') {
        $flutterwave = new FlutterwaveController();

        return $flutterwave->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'razorpay') {
        $razorpay = new RazorpayController();

        return $razorpay->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'mercadopago') {
        $mercadopago = new MercadoPagoController();

        return $mercadopago->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'mollie') {
        $mollie = new MollieController();

        return $mollie->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'stripe') {
        $stripe = new StripeController();

        return $stripe->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'paytm') {
        $paytm = new PaytmController();

        return $paytm->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'authorize.net') {
        $authorizenet = new AuthorizeNetController();

        return $authorizenet->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'phonepe') {
        $phonepe = new PhonePeController();

        return $phonepe->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'yoco') {
        $yoco = new YocoController();

        return $yoco->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'perfect_money') {
        $perfect_money = new PerfectMoneyController();

        return $perfect_money->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'toyyibpay') {
        $toyyibpay = new ToyyibpayController();

        return $toyyibpay->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'paytabs') {
        $paytabs = new PaytabsController();

        return $paytabs->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'iyzico') {
        $iyzico = new IyzicoController();

        return $iyzico->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'myfatoorah') {
        $myfatoorah = new MyFatoorahController();

        return $myfatoorah->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'midtrans') {
        $midtrans = new MidtransController();

        return $midtrans->index($request, $allData, 'service');
      } else if ($request['gateway'] == 'xendit') {
        $xendit = new XenditController();

        return $xendit->index($request, $allData, 'service');
      } else {
        $offline = new OfflineController();

        return $offline->index($request, $allData, 'service');
      }
    }
  }

  public function storeData($data)
  {
    if (!is_null($data['seller_id'])) {
      $currentMembership = SellerPermissionHelper::userPackage($data['seller_id']);
      $seller_membership_id = $currentMembership->id;
    } else {
      $seller_membership_id = null;
    }
    $orderInfo = ServiceOrder::query()->create([
      'user_id' => $data['userId'],
      'seller_id' => $data['seller_id'],
      'order_number' => $data['orderNumber'],
      'name' => $data['name'],
      'email_address' => $data['emailAddress'],
      'informations' => $data['infos'],
      'service_id' => $data['serviceId'],
      'package_id' => $data['packageId'],
      'seller_membership_id' => $seller_membership_id,
      'package_price' => $data['packagePrice'],
      'addons' => $data['addons'],
      'addon_price' => $data['addonPrice'],
      'tax_percentage' => $data['tax_percentage'],
      'tax' => $data['tax'],
      'grand_total' => $data['grandTotal'],
      'currency_text' => $data['currencyText'],
      'currency_text_position' => $data['currencyTextPosition'],
      'currency_symbol' => $data['currencySymbol'],
      'currency_symbol_position' => $data['currencySymbolPosition'],
      'payment_method' => $data['paymentMethod'],
      'gateway_type' => $data['gatewayType'],
      'payment_status' => $data['paymentStatus'],
      'order_status' => $data['orderStatus'],
      'receipt' => array_key_exists('receiptName', $data) ? $data['receiptName'] : null,
      'conversation_id' => array_key_exists('conversation_id', $data) ? $data['conversation_id'] : null
    ]);

    return $orderInfo;
  }

  public function generateInvoice($orderInfo)
  {
    $invoiceName = $orderInfo->order_number . '.pdf';

    $directory = '/assets/file/invoices/service/';
    @mkdir(public_path($directory), 0775, true);

    $fileLocation = $directory . $invoiceName;

    $arrData['orderInfo'] = $orderInfo;

    // get system language
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    // get service title
    $service = $orderInfo->service()->first();
    $arrData['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();

    // get package title
    $package = $orderInfo->package()->first();
    $arrData['packageTitle'] = $package->name;

    PDF::loadView('frontend.service.invoice', $arrData)->save(public_path($fileLocation));

    return $invoiceName;
  }

  public function prepareMail($orderInfo)
  {
    // get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'service_order')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $websiteTitle = Basic::query()->pluck('website_title')->first();

    $customerName = $orderInfo->name;
    $orderNumber = $orderInfo->order_number;

    $orderLink = '<br/><a href="' . route('user.service_order.details', ['id' => $orderInfo->id]) . '" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; color: #fff; background-color: #007bff; border-color: #007bff; border-radius: 4px; padding: 6px 12px; font-size: 16px; line-height: 1.5; cursor: pointer; text-decoration: none;">Order Details</a><br/>';

    // replacing with actual data
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{order_number}', $orderNumber, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{order_link}', $orderLink, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $orderInfo->email_address;

    $mailData['invoice'] = public_path('assets/file/invoices/service/' . $orderInfo->invoice);
    BasicMailer::sendMail($mailData);
    return;
  }

  public function complete($slug, Request $request)
  {
    $misc = new MiscellaneousController();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $queryResult['payVia'] = $request->input('via');

    return view('frontend.payment.success', $queryResult);
  }

  public function cancel($slug, Request $request)
  {
    $request->session()->flash('error', 'Sorry, an error has occured!');
    $service_content = ServiceContent::where('slug', $slug)->select('service_id')->first();
    if ($service_content) {
      return redirect()->route('service.payment_form.check', ['slug' => $slug, 'id' => $service_content->service_id]);
    } else {
      return redirect()->route('index');
    }
  }
}
