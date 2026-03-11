<?php

namespace App\Http\Controllers\Payment;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Seller\SellerCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Illuminate\Support\Facades\Session;

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        $data = OnlineGateway::where('keyword', 'paypal')->first();
        $paydata = $data->convertAutoData();
        $paypal_conf = Config::get('paypal');
        $paypal_conf['client_id'] = $paydata['client_id'];
        $paypal_conf['secret'] = $paydata['client_secret'];
        $paypal_conf['settings']['mode'] = $paydata['sandbox_status'] == 1 ? 'sandbox' : 'live';
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function paymentProcess(Request $request, $_amount, $_title, $_success_url, $_cancel_url)
    {
        $title = $_title;
        $price = $_amount;
        $price = round($price, 2);
        $cancel_url = $_cancel_url;
        $success_url = $_success_url;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($title)
            /** item name **/
            ->setCurrency("USD")
            ->setQuantity(1)
            ->setPrice($price);
        /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($price);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($title . ' Via Paypal');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($success_url)
            /** Specify return URL **/
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        Session::put('request', $request->all());
        Session::put('amount', $_amount);
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        return redirect()->back()->with('error', 'Unknown error occurred');
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('request');
        $bs = Basic::first();

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        /** clear the session payment ID **/
        $cancel_url = route('membership.paypal.cancel');
        if (empty($request['PayerID']) || empty($request['token'])) {
            return redirect($cancel_url);
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            $paymentFor = Session::get('paymentFor');
            $response = json_decode($payment, true);
            $package = Package::find($requestData['package_id']);

            $transaction_id = SellerPermissionHelper::uniqidReal(8);
            $transaction_details = $payment;
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
                //store data to transaction and earnings table
                return redirect()->route('success.page');
            }
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
