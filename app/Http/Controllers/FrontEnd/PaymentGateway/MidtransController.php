<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;

class MidtransController extends Controller
{
    public function index(Request $request, $data, $paymentFor)
    {
        $currencyInfo = $this->getCurrencyInfo();

        // checking whether the currency is set to 'INR' or not
        if ($currencyInfo->base_currency_text != 'IDR') {
            return redirect()->back()->with('error', 'Invalid currency for midtrans payment.')->withInput();
        }
        $data['currencyText'] = $currencyInfo->base_currency_text;
        $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $data['paymentMethod'] = 'Midtrans';
        $data['gatewayType'] = 'online';
        $data['paymentStatus'] = 'completed';
        $data['orderStatus'] = 'pending';

        $serviceSlug = $data['slug'];


        $info = OnlineGateway::where('keyword', 'midtrans')->first();
        $information = json_decode($info->information, true);

        $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);



        // will come from database
        $client_key = $information['server_key'];
        MidtransConfig::$serverKey = $information['server_key'];
        MidtransConfig::$isProduction = $information['is_production'] == 0 ? true : false;
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
        $token = uniqid();
        Session::put('token', $token);
        $params = [
            'transaction_details' => [
                'order_id' => $token,
                'gross_amount' => $data['grandTotal'] * 1000, // will be multiplied by 1000
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email_address,
                'phone' => $request->phone ? $request->phone : 999999999,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        //if generate payment url then put some data into session
        Session::put('arrData', $data);
        Session::put('cancel_url', $cancel_url);
        Session::put('midtrans_payment_type', 'service');
        if ($information['is_production'] == 1) {
            $is_production = $information['is_production'];
        }
        return view('frontend.payment.service-midtrans', compact('snapToken', 'is_production', 'client_key'));
    }

    public function cardNotify($order_id)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

        if ($order_id) {
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

    public function OnlineBackNotify($order_id)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

        if ($order_id) {
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

            return [
                'url' => route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online'])
            ];
        } else {
            Session::forget('paymentFor');
            Session::forget('arrData');
            Session::flash('error', 'Your payment has been canceled.');
            return [
                'url' => route('services')
            ];
        }
    }
}
