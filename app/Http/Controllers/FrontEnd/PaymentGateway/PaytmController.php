<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\Seller;
use Illuminate\Http\Request;

class PaytmController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {
    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'INR' or not
    if ($currencyInfo->base_currency_text !== 'INR') {
      return redirect()->back()->with('error', 'Invalid currency for paytm payment.')->withInput();
    }

    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Paytm';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    if ($paymentFor == 'service') {
      $serviceSlug = $data['slug'];
      $notifyURL = route('service.place_order.paytm.notify', ['slug' => $serviceSlug]);

      $customerEmail = $request['email_address'];
      $customerPhone = $request['phone_number'];
    } else {
      $notifyURL = route('pay.paytm.notify');

      $invoice = $data['invoice'];

      $customerEmail = $invoice->user_email_address;
      $customerPhone = $invoice->user_phone_number;
    }

    $payment = PaytmWallet::with('receive');


    $payment->prepare([
      'order' => time(),
      'user' => uniqid(),
      'mobile_number' => $customerPhone,
      'email' => $customerEmail,
      'amount' => round($data['grandTotal'], 2),
      'callback_url' => $notifyURL
    ]);


    // put some data in session before redirect to paytm url
    $request->session()->put('arrData', $data);
    $request->session()->put('paymentFor', $paymentFor);

    return $payment->receive();
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentFor = $request->session()->get('paymentFor');
    $arrData = $request->session()->get('arrData');

    if ($paymentFor == 'service') {
      $serviceSlug = $arrData['slug'];
    }

    $transaction = PaytmWallet::with('receive');

    // this response is needed to check the transaction status
    $response = $transaction->response();

    if ($transaction->isSuccessful()) {
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
          'payment_method' => 'Paytm',
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
        $request->session()->flash('error', 'Your payment has been canceled.');
        return redirect()->route('services');
      } else {
        return redirect()->route('pay.cancel');
      }
    }
  }
}
