<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Http\Helpers\Instamojo;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Exception;
use Illuminate\Http\Request;

class InstamojoController extends Controller
{
  private $api;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('instamojo')->first();
    $instamojoData = json_decode($data->information, true);

    if ($instamojoData['sandbox_status'] == 1) {
      $this->api = new Instamojo($instamojoData['key'], $instamojoData['token'], 'https://test.instamojo.com/api/1.1/');
    } else {
      $this->api = new Instamojo($instamojoData['key'], $instamojoData['token']);
    }
  }

  public function index(Request $request, $data, $paymentFor)
  {
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', 'Invalid currency for instamojo payment.')->withInput();
    }

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Instamojo';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    if ($paymentFor == 'service') {
      $title = 'Order A Service';
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.instamojo.notify', ['slug' => $serviceSlug]);

      $customerName = $request['name'];
      $customerEmail = $request['email_address'];
      $customerPhone = $request['phone_number'];
    } else {
      $title = 'Purchase Items';
      $notifyURL = route('pay.instamojo.notify');

      $invoice = $data['invoice'];

      $customerName = $invoice->user_full_name;
      $customerEmail = $invoice->user_email_address;
      $customerPhone = $invoice->user_phone_number;
    }

    try {
      $response = $this->api->paymentRequestCreate(array(
        'purpose' => $title,
        'amount' => round($data['grandTotal'], 2),
        'buyer_name' => $customerName,
        'email' => $customerEmail,
        'send_email' => false,
        'phone' => $customerPhone,
        'send_sms' => false,
        'redirect_url' => $notifyURL
      ));

      // put some data in session before redirect to instamojo url
      $request->session()->put('arrData', $data);
      $request->session()->put('paymentFor', $paymentFor);
      $request->session()->put('paymentId', $response['id']);

      return redirect($response['longurl']);
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Sorry, transaction failed!')->withInput();
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');
    $paymentId = $request->session()->get('paymentId');

    if ($paymentFor == 'service') {
      $serviceSlug = $arrData['slug'];
    }

    $urlInfo = $request->all();

    if ($urlInfo['payment_request_id'] == $paymentId) {
      // remove this session datas
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');

      if ($paymentFor == 'service') {
        $orderProcess = new OrderProcessController();

        // store service order information in database
        $selected_service = Service::where('id', $arrData['serviceId'])->select('seller_id')->first();
        if ($selected_service->seller_id != 0) {
          $arrData['seller_id'] = $selected_service->seller_id;
        } else {
          $arrData['seller_id'] = null;
        }
        $orderInfo = $orderProcess->storeData($arrData);

        // generate an invoice in pdf format
        $invoice = $orderProcess->generateInvoice($orderInfo);

        // then, update the invoice field info in database
        $orderInfo->update(['invoice' => $invoice]);

        // send a mail to the customer with the invoice
        $orderProcess->prepareMail($orderInfo);

        return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
      } else {
        // update info in db
        $invoice = $arrData['invoice'];

        $invoice->update([
          'payment_status' => 'paid',
          'payment_method' => 'Instamojo',
          'gateway_type' => 'online'
        ]);

        $pay = new PayController();

        // generate an invoice in pdf format
        $pay->generateInvoice($invoice);

        // send a mail to the customer with the invoice
        $pay->prepareMail($invoice);

        return redirect()->route('pay.complete', ['via' => 'online']);
      }
    } else {
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');

      if ($paymentFor == 'service') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      } else {
        return redirect()->route('pay.cancel');
      }
    }
  }
}
