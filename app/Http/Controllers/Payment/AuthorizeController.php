<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\SellerCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class AuthorizeController extends Controller
{
    private $gateway;
    public function __construct()
    {
        $data = OnlineGateway::query()->whereKeyword('authorize.net')->first();
        $authorizeNetData = json_decode($data->information, true);
        $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
        $this->gateway->setAuthName($authorizeNetData['api_login_id']);
        $this->gateway->setTransactionKey($authorizeNetData['transaction_key']);
        if ($authorizeNetData['sandbox_status'] == 1) {
            $this->gateway->setTestMode(true);
        }
    }
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bs)
    {
        try {
            $allowedCurrencies = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            $currencyInfo = $this->getCurrencyInfo();
            // checking whether the base currency is allowed or not
            if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
                return redirect()->back()->with('error', 'Invalid currency for authorize.net payment.')->withInput();
            }
            Session::put('request', $request->all());
            $requestData = $request->all();
            $bs = Basic::first();

            if ($request->filled('opaqueDataValue') && $request->filled('opaqueDataDescriptor')) {
                // generate a unique merchant site transaction ID
                $transactionId = rand(100000000, 999999999);  
                $response = $this->gateway->authorize([
                    'amount' => sprintf('%0.2f', $_amount),
                    'currency' => $currencyInfo->base_currency_text,
                    'transactionId' => $transactionId,
                    'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
                    'opaqueDataValue' => $request->opaqueDataValue
                ])->send();

                if ($response->isSuccessful()) {
                    //success process will be go here
                    $paymentFor = Session::get('paymentFor');
                    $response = json_encode($response, true);
                    $package = Package::find($requestData['package_id']);

                    $transaction_id = SellerPermissionHelper::uniqidReal(8);
                    $transaction_details = $response;

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
                } else {
                    //cancel payment
                    return redirect($_cancel_url);
                }
            } else {
                //return cancel url 
                return redirect($_cancel_url);
            }
        } catch (\Exception $th) {
            return redirect($_cancel_url);
        }
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', 'Your payment has been cancel.');
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('seller.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
