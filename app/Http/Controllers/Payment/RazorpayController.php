<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\SellerCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('razorpay')->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function paymentProcess(Request $request, $_amount, $_item_number, $_cancel_url, $_success_url, $_title, $_description, $bs)
    {
        $cancel_url = $_cancel_url;
        $notifyURL = $_success_url;

        $orderData = [
            'receipt' => $_title,
            'amount' => $_amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);
        Session::put('request', $request->all());
        Session::put('order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $_amount;

        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key" => $this->keyId,
            "amount" => $_amount,
            "name" => $_title,
            "description" => $_description,
            "prefill" => [
                "name" => $request->name,
                "email" => $request->address,
                "contact" => $request->razorpay_phone,
            ],
            "notes" => [
                "address" => $request->razorpay_address,
                "merchant_order_id" => $_item_number,
            ],
            "theme" => [
                "color" => "{{$bs->base_color}}"
            ],
            "order_id" => $razorpayOrder['id'],
        ];

        if ($bs->base_currency_text !== 'INR') {
            $data['display_currency'] = $bs->base_currency_text;
            $data['display_amount'] = $displayAmount;
        }

        $jsonData = json_encode($data);
        $displayCurrency = $bs->base_currency_text;

        return view('frontend.payment.razorpay', compact('data', 'displayCurrency', 'jsonData', 'notifyURL'));
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $bs = Basic::first();
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('order_payment_id');
        $success = true;
        if (empty($request['razorpay_payment_id']) === false) {

            try {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $request['razorpay_payment_id'],
                    'razorpay_signature' => $request['razorpay_signature']
                );

                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
            }
        }

        if ($success === true) {
            $package = Package::find($requestData['package_id']);
            $paymentFor = Session::get('paymentFor');
            $transaction_id = SellerPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($request);
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
                return redirect()->route('success.page');
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
                return redirect()->route('success.page');
            }
        }
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('seller.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
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
