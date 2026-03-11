<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Basel\MyFatoorah\MyFatoorah;

class MyFatoorahController extends Controller
{
    public $myfatoorah;

    public function __construct()
    {
        $info = OnlineGateway::where('keyword', 'myfatoorah')->first();
        $information = json_decode($info->information, true);
        $this->myfatoorah = MyFatoorah::getInstance($information['sandbox_status'] == 1 ? true : false);
    }
    public function index(Request $request, $data, $paymentFor)
    {
        $currencyInfo = $this->getCurrencyInfo();

        $available_currency = array('KWD', 'SAR', 'BHD', 'AED', 'QAR', 'OMR', 'JOD');
        if (!in_array($currencyInfo->base_currency_text, $available_currency)) {
            return redirect()->back()->with('error', 'Invalid currency for myfatoorah payment.')->withInput();
        }
        $data['currencyText'] = $currencyInfo->base_currency_text;
        $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $data['paymentMethod'] = 'Myfatoorah';
        $data['gatewayType'] = 'online';
        $data['paymentStatus'] = 'completed';
        $data['orderStatus'] = 'pending';

        $serviceSlug = $data['slug'];
        $info = OnlineGateway::where('keyword', 'yoco')->first();
        $information = json_decode($info->information, true);

        $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);

        /********************************************************
         * send payment request to yoco for create a payment url
         ********************************************************/
        $payAmount = intval($data['grandTotal']);
        $info = OnlineGateway::where('keyword', 'myfatoorah')->first();
        $information = json_decode($info->information, true);
        $random_1 = rand(999, 9999);
        $random_2 = rand(9999, 99999);
        $result = $this->myfatoorah->sendPayment(
            $request->name,
            $payAmount,
            [
                'CustomerMobile' => $information['sandbox_status'] == 1 ? '56562123544' : $request->email_address,
                'CustomerReference' => "$random_1",  //orderID
                'UserDefinedField' => "$random_2", //clientID
                "InvoiceItems" => [
                    [
                        "ItemName" => "Service Orders",
                        "Quantity" => 1,
                        "UnitPrice" => $payAmount
                    ]
                ]
            ]
        );
        if ($result && $result['IsSuccess'] == true) {
            Session::put('myfatoorah_payment_type', 'service');
            Session::put('arrData', $data);
            Session::put('cancel_url', $cancel_url);
            return redirect($result['Data']['InvoiceURL']);
        } else {
            return redirect($cancel_url);
        }
    }

    public function notify(Request $request)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

        if (!empty($request->paymentId)) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);
            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Paid") {
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
