<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalController extends Controller
{
  private $api_context;

  public function __construct()
  {
    $data = OnlineGateway::query()->whereKeyword('paypal')->first();
    $paypalData = json_decode($data->information, true);

    $paypal_conf = Config::get('paypal');
    $paypal_conf['client_id'] = $paypalData['client_id'];
    $paypal_conf['secret'] = $paypalData['client_secret'];
    $paypal_conf['settings']['mode'] = $paypalData['sandbox_status'] == 1 ? 'sandbox' : 'live';

    $this->api_context = new ApiContext(
      new OAuthTokenCredential(
        $paypal_conf['client_id'],
        $paypal_conf['secret']
      )
    );

    $this->api_context->setConfig($paypal_conf['settings']);
  }

  public function index(Request $request, $data, $paymentFor)
  {
    $currencyInfo = $this->getCurrencyInfo();

    // changing the currency before redirect to PayPal
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = $data['grandTotal'] / $rate;
    }

    $paypalTotal = $currencyInfo->base_currency_text === 'USD' ? $data['grandTotal'] : $convertedTotal;

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'PayPal';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    if ($paymentFor == 'service') {
      $title = 'Order A Service';
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.paypal.notify', ['slug' => $serviceSlug]);
      $cancelURL = route('service.place_order.cancel', ['slug' => $serviceSlug]);
    } else {
      $title = 'Purchase Items';
      $notifyURL = route('pay.paypal.notify');
      $cancelURL = route('pay.cancel');
    }

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    $item_1 = new Item();
    $item_1->setName($title)
      /** item name **/
      ->setCurrency('USD')
      ->setQuantity(1)
      ->setPrice($paypalTotal);

    /** unit price **/
    $item_list = new ItemList();
    $item_list->setItems(array($item_1));

    $amount = new Amount();
    $amount->setCurrency('USD')
      ->setTotal($paypalTotal);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($item_list)
      ->setDescription($title . ' via PayPal');

    $redirect_urls = new RedirectUrls();
    $redirect_urls->setReturnUrl($notifyURL)
      /** Specify return URL **/
      ->setCancelUrl($cancelURL);

    $payment = new Payment();
    $payment->setIntent('Sale')
      ->setPayer($payer)
      ->setRedirectUrls($redirect_urls)
      ->setTransactions(array($transaction));

    try {
      $payment->create($this->api_context);
    } catch (\PayPal\Exception\PPConnectionException $ex) {
      return redirect($cancelURL)->with('error', $ex->getMessage());
    }

    foreach ($payment->getLinks() as $link) {
      if ($link->getRel() == 'approval_url') {
        $redirectURL = $link->getHref();
        break;
      }
    }

    // put some data in session before redirect to paypal url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('paymentId', $payment->getId());

    if (isset($redirectURL)) {
      /** redirect to paypal **/
      return Redirect::away($redirectURL);
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

    if (empty($urlInfo['token']) || empty($urlInfo['PayerID'])) {
      if ($paymentFor == 'service') {
        return redirect()->route('service.place_order.cancel', ['slug' => $serviceSlug]);
      } else {
        return redirect()->route('pay.cancel');
      }
    }

    /** Execute The Payment **/
    $payment = Payment::get($paymentId, $this->api_context);
    $execution = new PaymentExecution();
    $execution->setPayerId($urlInfo['PayerID']);
    $result = $payment->execute($execution, $this->api_context);

    if ($result->getState() == 'approved') {
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
          'payment_method' => 'PayPal',
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
