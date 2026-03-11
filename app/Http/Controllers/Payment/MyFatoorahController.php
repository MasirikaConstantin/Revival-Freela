<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Seller\SellerCheckoutController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Http\Helpers\SellerPermissionHelper;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Http\Helpers\MegaMailer;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Basel\MyFatoorah\MyFatoorah;
use Illuminate\Support\Facades\Auth;

class MyFatoorahController extends Controller
{
    public $myfatoorah;

    public function __construct()
    {
        $info = OnlineGateway::where('keyword', 'myfatoorah')->first();
        $information = json_decode($info->information, true);
        $this->myfatoorah = MyFatoorah::getInstance($information['sandbox_status'] == 1 ? true : false);
    }

    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {
        $cancel_url = $_cancel_url;
        /********************************************************
         * send payment request to yoco for create a payment url
         ********************************************************/

        $info = OnlineGateway::where('keyword', 'myfatoorah')->first();
        $information = json_decode($info->information, true);
        $random_1 = rand(999, 9999);
        $random_2 = rand(9999, 99999);
        $result = $this->myfatoorah->sendPayment(
            Auth::guard('seller')->user()->username,
            intval($_amount),
            [
                'CustomerMobile' => $information['sandbox_status'] == 1 ? '56562123544' : Auth::guard('seller')->user()->phone,
                'CustomerReference' => "$random_1",  //orderID
                'UserDefinedField' => "$random_2", //clientID
                "InvoiceItems" => [
                    [
                        "ItemName" => "Package Purchase or Extends",
                        "Quantity" => 1,
                        "UnitPrice" => intval($_amount)
                    ]
                ]
            ]
        );
        if ($result && $result['IsSuccess'] == true) {
            Session::put('myfatoorah_payment_type', 'package');
            Session::put("request", $request->all());
            return redirect($result['Data']['InvoiceURL']);
        } else {
            return redirect($cancel_url);
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        $bs = Basic::first();
        /** Get the payment ID before session clear **/
        if (!empty($request->paymentId)) {
            $result = $this->myfatoorah->getPaymentStatus('paymentId', $request->paymentId);
            if ($result && $result['IsSuccess'] == true && $result['Data']['InvoiceStatus'] == "Paid") {
                $paymentFor = Session::get('paymentFor');
                $package = Package::find($requestData['package_id']);
                $transaction_id = SellerPermissionHelper::uniqidReal(8);
                $transaction_details = json_encode($request['payment_request_id']);
                if ($paymentFor == "membership") {
                    $amount = $requestData['price'];
                    $password = $requestData['password'];
                    $checkout = new SellerCheckoutController();

                    $seller = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                    $lastMemb = $seller->memberships()->orderBy('id', 'DESC')->first();

                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);
                    $file_name = $this->makeInvoice($requestData, "membership", $seller, $password, $amount, "Paypal", $requestData['phone'], $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

                    $mailer = new MegaMailer();
                    $data = [
                        'toMail' => $seller->email,
                        'toName' => $seller->fname,
                        'username' => $seller->username,
                        'package_title' => $package->title,
                        'package_price' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'discount' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $lastMemb->discount . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'total' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $lastMemb->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'activation_date' => $activation->toFormattedDateString(),
                        'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                        'membership_invoice' => $file_name,
                        'website_title' => $bs->website_title,
                        'templateType' => 'registration_with_premium_package',
                        'type' => 'registrationWithPremiumPackage'
                    ];
                    $mailer->mailFromAdmin($data);
                    @unlink(public_path('assets/front/invoices/' . $file_name));

                    session()->flash('success', 'Your payment has been completed.');
                    Session::forget('request');
                    Session::forget('paymentFor');
                    return [
                        'url' => route('success.page')
                    ];
                } elseif ($paymentFor == "extend") {
                    $amount = $requestData['price'];
                    $password = uniqid('qrcode');
                    $checkout = new SellerCheckoutController();
                    $seller = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                    $lastMemb = Membership::where('seller_id', $seller->id)->orderBy('id', 'DESC')->first();
                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);

                    $file_name = $this->makeInvoice($requestData, "extend", $seller, $password, $amount, $requestData["payment_method"], $seller->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

                    $mailer = new MegaMailer();
                    $data = [
                        'toMail' => $seller->email,
                        'toName' => $seller->fname,
                        'username' => $seller->username,
                        'package_title' => $package->title,
                        'package_price' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'activation_date' => $activation->toFormattedDateString(),
                        'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                        'membership_invoice' => $file_name,
                        'website_title' => $bs->website_title,
                        'templateType' => 'membership_extend',
                        'type' => 'membershipExtend'
                    ];
                    $mailer->mailFromAdmin($data);
                    @unlink(public_path('assets/front/invoices/' . $file_name));

                    //store data to transaction and earnings table
                    $transaction_data = [];
                    $transaction_data['order_id'] = $lastMemb->id;
                    $transaction_data['transcation_type'] = 5;
                    $transaction_data['user_id'] = null;
                    $transaction_data['seller_id'] = $lastMemb->seller_id;
                    $transaction_data['payment_status'] = 'completed';
                    $transaction_data['payment_method'] = $lastMemb->payment_method;
                    $transaction_data['grand_total'] = $lastMemb->price;
                    $transaction_data['pre_balance'] = null;
                    $transaction_data['tax'] = null;
                    $transaction_data['after_balance'] = null;
                    $transaction_data['gateway_type'] = 'online';
                    $transaction_data['currency_symbol'] = $lastMemb->currency_symbol;
                    $transaction_data['currency_symbol_position'] = $bs->base_currency_symbol_position;
                    storeTransaction($transaction_data);
                    $data = [
                        'life_time_earning' => $lastMemb->price,
                        'total_profit' => $lastMemb->price,
                    ];
                    storeEarnings($data);

                    Session::forget('request');
                    Session::forget('paymentFor');
                    return [
                        'url' => route('success.page')
                    ];
                }
            }
        }
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('seller.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
