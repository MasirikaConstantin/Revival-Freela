<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class YocoController extends Controller
{
    public function index(Request $request, $data, $paymentFor)
    {
        $currencyInfo = $this->getCurrencyInfo();

        // checking whether the currency is set to 'INR' or not
        if ($currencyInfo->base_currency_text !== 'ZAR') {
            return redirect()->back()->with('error', 'Invalid currency for yoco payment.')->withInput();
        }
        $data['currencyText'] = $currencyInfo->base_currency_text;
        $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $data['paymentMethod'] = 'Yoco';
        $data['gatewayType'] = 'online';
        $data['paymentStatus'] = 'completed';
        $data['orderStatus'] = 'pending';

        $serviceSlug = $data['slug'];
        $info = OnlineGateway::where('keyword', 'yoco')->first();
        $information = json_decode($info->information, true);

        $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);
        $notifyURL = route('service.place_order.yoco.notify', ['slug' => $serviceSlug]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $information['secret_key'],
        ])->post('https://payments.yoco.com/api/checkouts', [
            'amount' => $data['grandTotal'] * 100,
            'currency' => 'ZAR',
            'successUrl' => $notifyURL
        ]);

        $responseData = $response->json();
        if (array_key_exists('redirectUrl', $responseData)) {
            // put some data in session before redirect
            Session::put('arrData', $data);
            Session::put('cancel_url', $cancel_url);
            Session::put('yoco_id', $responseData['id']);
            Session::put('s_key', $information['secret_key']);
            return redirect($responseData["redirectUrl"]);
        } else {
            return redirect($cancel_url);
        }
    }

    public function notify(Request $request)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

        $id = Session::get('yoco_id');
        $s_key = Session::get('s_key');
        $info = OnlineGateway::where('keyword', 'yoco')->first();
        $information = json_decode($info->information, true);
        if ($id && $information['secret_key'] == $s_key) {
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
