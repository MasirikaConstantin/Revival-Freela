<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class PerfectMoneyController extends Controller
{
    public function index(Request $request, $arrayData, $paymentFor)
    {
        $currencyInfo = $this->getCurrencyInfo();

        // checking whether the currency is set to 'INR' or not
        if ($currencyInfo->base_currency_text !== 'USD') {
            return redirect()->back()->with('error', 'Invalid currency for perfect money payment.')->withInput();
        }
        $arrayData['currencyText'] = $currencyInfo->base_currency_text;
        $arrayData['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $arrayData['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $arrayData['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $arrayData['paymentMethod'] = 'Perfect Money';
        $arrayData['gatewayType'] = 'online';
        $arrayData['paymentStatus'] = 'completed';
        $arrayData['orderStatus'] = 'pending';

        $serviceSlug = $arrayData['slug'];
        $info = OnlineGateway::where('keyword', 'perfect_money')->first();
        $information = json_decode($info->information, true);

        $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);
        $notifyURL = route('service.place_order.perfect_money.notify', ['slug' => $serviceSlug]);

        $randomNo = substr(uniqid(), 0, 8);
        $websiteInfo = Basic::select('website_title', 'base_currency_text')->first();
        $perfect_money = OnlineGateway::where('keyword', 'perfect_money')->first();
        $info = json_decode($perfect_money->information, true);
        $val['PAYEE_ACCOUNT'] = $info['perfect_money_wallet_id'];;
        $val['PAYEE_NAME'] = $websiteInfo->website_title;
        $val['PAYMENT_ID'] = "$randomNo"; //random id
        $val['PAYMENT_AMOUNT'] = $arrayData['grandTotal'];
        // $val['PAYMENT_AMOUNT'] = 0.01; //test amount
        $val['PAYMENT_UNITS'] = "$websiteInfo->base_currency_text";

        $val['STATUS_URL'] = $notifyURL;
        $val['PAYMENT_URL'] = $notifyURL;
        $val['PAYMENT_URL_METHOD'] = 'GET';
        $val['NOPAYMENT_URL'] = $cancel_url;
        $val['NOPAYMENT_URL_METHOD'] = 'GET';
        $val['SUGGESTED_MEMO'] = $request->email_address;
        $val['BAGGAGE_FIELDS'] = 'IDENT';

        $data['val'] = $val;
        $data['method'] = 'post';
        $data['url'] = 'https://perfectmoney.com/api/step1.asp';

        Session::put('payment_id', $randomNo);
        Session::put('arrData', $arrayData);
        return view('frontend.payment.perfect-money', compact('data'));
    }

    public function notify(Request $request)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

        $perfect_money = OnlineGateway::where('keyword', 'perfect_money')->first();
        $perfectMoneyInfo = json_decode($perfect_money->information, true);
        $currencyInfo = Basic::select('base_currency_text')->first();

        $amo = $request['PAYMENT_AMOUNT'];
        $unit = $request['PAYMENT_UNITS'];
        $track = $request['PAYMENT_ID'];
        $id = Session::get('payment_id');
        $final_amount = $arrData['grandTotal']; //live amount
        // $final_amount = 0.01; //testing  amount

        if ($request->PAYEE_ACCOUNT == $perfectMoneyInfo['perfect_money_wallet_id'] && $unit == $currencyInfo->base_currency_text && $track == $id && $amo == round($final_amount, 2)) {
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
