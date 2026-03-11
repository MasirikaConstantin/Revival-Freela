<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;


class PaytabsController extends Controller
{
    public function index(Request $request, $data, $paymentFor)
    {
        $currencyInfo = $this->getCurrencyInfo();
        $paytabInfo = paytabInfo();
        if ($currencyInfo->base_currency_text != $paytabInfo['currency']) {
            return redirect()->back()->with('error', 'Invalid currency for paytabs payment.')->withInput();
        }
        $data['currencyText'] = $currencyInfo->base_currency_text;
        $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $data['paymentMethod'] = 'Paytabs';
        $data['gatewayType'] = 'online';
        $data['paymentStatus'] = 'completed';
        $data['orderStatus'] = 'pending';

        $serviceSlug = $data['slug'];

        $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);
        $notifyURL = route('service.place_order.paytabs.notify', ['slug' => $serviceSlug]);

        $paytabInfo = paytabInfo();
        $description = 'Service booking via paytabs';
        try {
            $response = Http::withHeaders([
                'Authorization' => $paytabInfo['server_key'], // Server Key
                'Content-Type' => 'application/json',
            ])->post($paytabInfo['url'], [
                'profile_id' => $paytabInfo['profile_id'], // Profile ID
                'tran_type' => 'sale',
                'tran_class' => 'ecom',
                'cart_id' => uniqid(),
                'cart_description' => $description,
                'cart_currency' => $paytabInfo['currency'], // set currency by region
                'cart_amount' => $data['grandTotal'],
                'return' => $notifyURL,
            ]);

            $responseData = $response->json();
            // put some data in session before redirect
            Session::put('arrData', $data);
            Session::put('cancel_url', $cancel_url);
            return redirect()->to($responseData['redirect_url']);
        } catch (\Exception $e) {
            return redirect($cancel_url);
        }
    }

    public function notify(Request $request)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

        $resp = $request->all();
        if ($resp['respStatus'] == "A" && $resp['respMessage'] == 'Authorised') {
            // remove this session datas
            Session::forget('paymentFor');
            Session::forget('arrData');

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
            Session::forget('paymentFor');
            Session::forget('arrData');
            Session::flash('error', 'Your payment has been canceled.');
            return redirect()->route('services');
        }
    }
}
