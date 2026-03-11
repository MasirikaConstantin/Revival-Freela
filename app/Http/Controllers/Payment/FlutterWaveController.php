<?php

namespace App\Http\Controllers\Payment;

use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\SellerCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use Carbon\Carbon;

class FlutterWaveController extends Controller
{
    public $public_key;
    private $secret_key;

    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('flutterwave')->first();
        $paydata = $data->convertAutoData();
        $this->public_key = $paydata['public_key'];
        $this->secret_key = $paydata['secret_key'];
    }

    public function paymentProcess(Request $request, $_amount, $_email, $_item_number, $_successUrl, $_cancelUrl, $bex)
    {
        $cancel_url = $_cancelUrl;
        $notify_url = $_successUrl;
        Session::put('request', $request->all());
        Session::put('payment_id', $_item_number);

        // SET CURL

        $curl = curl_init();
        $currency = $bex->base_currency_text;
        $txref = $_item_number; // ensure you generate unique references per transaction.
        $PBFPubKey = $this->public_key; // get your public key from the dashboard.
        $redirect_url = $notify_url;
        $payment_plan = ""; // this is only required for recurring payments.


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => $_amount,
                'customer_email' => $_email,
                'currency' => $currency,
                'txref' => $txref,
                'PBFPubKey' => $PBFPubKey,
                'redirect_url' => $redirect_url,
                'payment_plan' => $payment_plan
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            // there was an error contacting the rave API
            return redirect($cancel_url)->with('error', 'Curl returned error: ' . $err);
        }

        $transaction = json_decode($response);

        if (!$transaction->data && !$transaction->data->link) {
            // there was an error from the API
            return redirect($cancel_url)->with('error', 'API returned error: ' . $transaction->message);
        }

        return redirect()->to($transaction->data->link);
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

        $success_url = route('membership.flutterwave.cancel');
        $cancel_url = route('membership.flutterwave.cancel');
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('payment_id');
        if (isset($request['txref'])) {
            $ref = $payment_id;
            $query = array(
                "SECKEY" => $this->secret_key,
                "txref" => $ref
            );
            $data_string = json_encode($query);
            $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            curl_close($ch);
            $resp = json_decode($response, true);

            if ($resp['status'] == 'error') {
                return redirect($cancel_url);
            }
            if ($resp['status'] = "success") {
                $paymentStatus = $resp['data']['status'];
                $paymentFor = Session::get('paymentFor');
                if ($resp['status'] = "success") {
                    $package = Package::find($requestData['package_id']);
                    $transaction_id = SellerPermissionHelper::uniqidReal(8);
                    $transaction_details = json_encode($resp['data']);

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

                        session()->flash(
                            'success',
                            'Your payment has been completed.'
                        );
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
            }
            return redirect($cancel_url);
        }
        return redirect($cancel_url);
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
