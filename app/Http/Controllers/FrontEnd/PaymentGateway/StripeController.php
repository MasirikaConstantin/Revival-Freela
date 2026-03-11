<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\Seller;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StripeController extends Controller
{
  public function index(Request $request, $data, $paymentFor)
  {

    $currencyInfo = $this->getCurrencyInfo();

    // changing the currency before redirect to Stripe
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round(($data['grandTotal'] / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? $data['grandTotal'] : $convertedTotal;

    if ($request->billing_email_address) {
      //shop payment
      $billingEmail = $request->billing_email_address;
    } else {
      //service payment
      $billingEmail = $request->email_address;
    }
    if ($request->billing_first_name) {
      //shop payment
      $billingName = $request->billing_first_name . ' ' . $request->billing_last_name;
    } else {
      //service payment
      $billingName = $request->name ?? '';
    }


    $currencySym =  $currencyInfo->base_currency_symbol ?? '';
    $descriptions =  $data['grandTotal'] . $currencySym . ' paid for order';


    if ($paymentFor != 'invoice') {
      $data['currencyText'] = $currencyInfo->base_currency_text;
      $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
      $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
      $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
      $data['paymentMethod'] = 'Stripe';
      $data['gatewayType'] = 'online';
      $data['paymentStatus'] = 'completed';
      $data['orderStatus'] = 'pending';
    }

    try {

      // initialize stripe
      $stripe = Stripe::make(Config::get('services.stripe.secret'));

      try {
        // generate charge
        $charge = $stripe->charges()->create([
          'source' =>  $request->stripeToken,
          'currency' => 'USD',
          'amount'   => $stripeTotal,
          'description' => $descriptions,
          'receipt_email' => $billingEmail,
          'metadata' => [
            'customer_name' => $billingName,
          ]
        ]);

        if ($paymentFor == 'service') {
          $serviceSlug = $data['slug'];
        }

        if ($charge['status'] == 'succeeded') {
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
              'payment_method' => 'Stripe',
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
      } catch (CardErrorException $e) {
        $request->session()->flash('error', $e->getMessage());

        if ($paymentFor == 'service') {
          return redirect()->route('service.place_order.cancel', ['slug' => $data['slug']]);
        } else {
          return redirect()->route('pay.cancel');
        }
      }
    } catch (UnauthorizedException $e) {
      $request->session()->flash('error', $e->getMessage());

      if ($paymentFor == 'service') {
        return redirect()->route('service.place_order.cancel', ['slug' => $data['slug']]);
      } else {
        return redirect()->route('pay.cancel');
      }
    }
  }
}
