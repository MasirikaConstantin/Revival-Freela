<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class AuthorizeNetController extends Controller
{
  private $gateway;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('authorize.net')->first();
    $authorizeNetData = json_decode($data->information, true);

    $this->gateway = Omnipay::create('AuthorizeNetApi_Api');

    $this->gateway->setAuthName($authorizeNetData['api_login_id']);
    $this->gateway->setTransactionKey($authorizeNetData['transaction_key']);

    if ($authorizeNetData['sandbox_status'] == 1) {
      $this->gateway->setTestMode(true);
    }
  }

  public function index(Request $request, $data, $paymentFor)
  {
    $allowedCurrencies = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
      return redirect()->back()->with('error', 'Invalid currency for authorize.net payment.')->withInput();
    }

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Authorize.Net';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    if ($paymentFor == 'service') {
      $serviceSlug = $data['slug'];
    }

    if ($request->filled('opaqueDataValue') && $request->filled('opaqueDataDescriptor')) {
      // generate a unique merchant site transaction ID
      $transactionId = rand(100000000, 999999999);

      $response = $this->gateway->authorize([
        'amount' => sprintf('%0.2f', $data['grandTotal']),
        'currency' => $currencyInfo->base_currency_text,
        'transactionId' => $transactionId,
        'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
        'opaqueDataValue' => $request->opaqueDataValue
      ])->send();

      if ($response->isSuccessful()) {
        if ($paymentFor == 'service') {
          $orderProcess = new OrderProcessController();

          // store service order information in database
          $selected_service = Service::where('id', $data['serviceId'])->select('seller_id')->first();
          if ($selected_service->seller_id != 0) {
            $data['seller_id'] = $selected_service->seller_id;
          } else {
            $data['seller_id'] = null;
          }
          $orderInfo = $orderProcess->storeData($data);

          // generate an invoice in pdf format
          $invoice = $orderProcess->generateInvoice($orderInfo);

          // then, update the invoice field info in database
          $orderInfo->update(['invoice' => $invoice]);

          // send a mail to the customer with the invoice
          $orderProcess->prepareMail($orderInfo);

          return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
        } else {
          // update info in db
          $invoice = $data['invoice'];

          $invoice->update([
            'payment_status' => 'paid',
            'payment_method' => 'Authorize.Net',
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
        if ($paymentFor == 'service') {
          return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
        } else {
          return redirect()->route('pay.cancel');
        }
      }
    } else {
      if ($paymentFor == 'service') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      } else {
        return redirect()->route('pay.cancel');
      }
    }
  }
}
