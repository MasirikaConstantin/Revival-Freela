<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterwaveController extends Controller
{
  private $public_key, $secret_key;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('flutterwave')->first();
    $flutterwaveData = json_decode($data->information, true);

    $this->public_key = $flutterwaveData['public_key'];
    $this->secret_key = $flutterwaveData['secret_key'];
  }

  public function index(Request $request, $data, $paymentFor)
  {
    $allowedCurrencies = array('BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('error', 'Invalid currency for flutterwave payment.')->withInput();
    }

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Flutterwave';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    if ($paymentFor == 'service') {
      $title = 'Order A Service';
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.flutterwave.notify', ['slug' => $serviceSlug]);

      $customerName = $request['name'];
      $customerEmail = $request['email_address'];
      $customerPhone = $request['phone_number'];
    } else {
      $title = 'Purchase Items';
      $notifyURL = route('pay.flutterwave.notify');

      $invoice = $data['invoice'];

      $customerName = $invoice->user_full_name;
      $customerEmail = $invoice->user_email_address;
      $customerPhone = $invoice->user_phone_number;
    }

    // send payment to flutterwave for processing
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode([
        'tx_ref' => 'FLW | ' . time(),
        'amount' => $data['grandTotal'],
        'currency' => $currencyInfo->base_currency_text,
        'redirect_url' => $notifyURL,
        'payment_options' => 'card,banktransfer',
        'customer' => [
          'email' => $customerEmail,
          'phone_number' => $customerPhone,
          'name' => $customerName
        ],
        'customizations' => [
          'title' => $title,
          'description' => $title . ' via Flutterwave.'
        ]
      ]),
      CURLOPT_HTTPHEADER => array(
        'authorization: Bearer ' . $this->secret_key,
        'content-type: application/json'
      )
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $responseData = json_decode($response, true);

    //curl end
    // put some data in session before redirect to flutterwave url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);

    if ($responseData['status'] === 'success') {
      return redirect($responseData['data']['link']);
    } else {
      return redirect()->back()->with('error', 'Error: ' . $responseData['message'])->withInput();
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

    if ($urlInfo['status'] == 'successful') {
      $txId = $urlInfo['transaction_id'];

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txId}/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'authorization: Bearer ' . $this->secret_key,
          'content-type: application/json'
        )
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      $responseData = json_decode($response, true);


      if ($responseData['status'] === 'success') {
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
            'payment_method' => 'Flutterwave',
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
