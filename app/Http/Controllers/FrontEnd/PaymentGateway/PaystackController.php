<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Illuminate\Http\Request;

class PaystackController extends Controller
{
  private $api_key;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('paystack')->first();
    $paystackData = json_decode($data->information, true);

    $this->api_key = $paystackData['key'];
  }

  public function index(Request $request, $data, $paymentFor)
  {
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'NGN' or not
    if ($currencyInfo->base_currency_text !== 'NGN') {
      return redirect()->back()->with('error', 'Invalid currency for paystack payment.')->withInput();
    }

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Paystack';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    if ($paymentFor == 'service') {
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.paystack.notify', ['slug' => $serviceSlug]);

      $customerEmail = $request['email_address'];
    } else {
      $notifyURL = route('pay.paystack.notify');

      $invoice = $data['invoice'];

      $customerEmail = $invoice->user_email_address;
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL            => 'https://api.paystack.co/transaction/initialize',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST  => 'POST',
      CURLOPT_POSTFIELDS     => json_encode([
        'amount'       => intval($data['grandTotal']) * 100,
        'email'        => $customerEmail,
        'callback_url' => $notifyURL
      ]),
      CURLOPT_HTTPHEADER     => [
        'authorization: Bearer ' . $this->api_key,
        'content-type: application/json',
        'cache-control: no-cache'
      ]
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $transaction = json_decode($response, true);

    // put some data in session before redirect to paystack url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);

    if ($transaction['status'] == true) {
      return redirect($transaction['data']['authorization_url']);
    } else {
      return redirect()->back()->with('error', 'Error: ' . $transaction['message'])->withInput();
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');

    if ($paymentFor == 'service') {
      $serviceSlug = $arrData['slug'];
    }

    $urlInfo = $request->all();

    if ($urlInfo['trxref'] === $urlInfo['reference']) {
      // remove this session datas
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');

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
          'payment_method' => 'Paystack',
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

      if ($paymentFor == 'service') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      } else {
        return redirect()->route('pay.cancel');
      }
    }
  }
}
