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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FreshpayController extends Controller
{
    private $apiUrl = 'https://paydrc.gofreshbakery.net/api/v5/';

    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {
        $gateway = OnlineGateway::where('keyword', 'freshpay')->first();
        $gatewayInfo = !empty($gateway) ? json_decode($gateway->information, true) : [];

        if (
            empty($gatewayInfo['merchant_id']) || empty($gatewayInfo['merchant_secrete']) ||
            empty($gatewayInfo['firstname']) || empty($gatewayInfo['lastname']) || empty($gatewayInfo['email'])
        ) {
            Session::flash('warning', 'Freshpay is not configured yet. Please contact admin.');

            return redirect($_cancel_url)->withInput($request->all());
        }

        $paymentFor = Session::get('paymentFor', 'extend');
        $reference = 'fp_' . $paymentFor . '_' . strtoupper(uniqid());

        $payload = [
            'merchant_id' => $gatewayInfo['merchant_id'],
            'merchant_secrete' => $gatewayInfo['merchant_secrete'],
            'amount' => (string) number_format((float) $_amount, 2, '.', ''),
            'currency' => $bex->base_currency_text,
            'action' => 'debit',
            'customer_number' => $request->input('customer_number'),
            'firstname' => $gatewayInfo['firstname'],
            'lastname' => $gatewayInfo['lastname'],
            'email' => $gatewayInfo['email'],
            'reference' => $reference,
            'method' => $request->input('method'),
            'callback_url' => route('freshpay.membership.callback')
        ];

        $response = Http::acceptJson()->timeout(30)->post($this->apiUrl, $payload);
        $responseData = $response->json();

        if (!$response->successful() || $this->hasExplicitFailure($responseData)) {
            $errorMessage = $this->extractErrorMessage($responseData);
            Session::flash('warning', !empty($errorMessage) ? $errorMessage : 'Freshpay payment request failed.');

            return redirect($_cancel_url)->withInput($request->all());
        }

        Session::put('request', $request->all());
        Session::put('paymentFor', $paymentFor);
        Session::put('cancel_url', $_cancel_url);
        Session::put('freshpay_reference', $reference);

        Cache::put($this->pendingKey($reference), [
            'payment_for' => $paymentFor,
            'request_data' => $request->all(),
            'cancel_url' => $_cancel_url
        ], now()->addHours(6));

        $redirectUrl = $this->extractRedirectUrl($responseData);
        if (!empty($redirectUrl)) {
            return redirect()->away($redirectUrl);
        }

        Session::flash('success', 'Freshpay push sent. Please approve the payment on your phone and wait for confirmation.');

        return redirect()->back()->withInput($request->all());
    }

    /**
     * Public callback endpoint for Freshpay server notifications.
     */
    public function callback(Request $request)
    {
        $payload = $request->all();
        $reference = $this->extractReference($payload);

        if (empty($reference)) {
            return response()->json(['message' => 'Reference not found.'], 200);
        }

        if ($this->hasExplicitFailure($payload)) {
            Cache::forget($this->pendingKey($reference));
            Cache::forget($this->processedKey($reference));

            return response()->json(['message' => 'Payment failed.'], 200);
        }

        if (!$this->isConfirmedSuccess($payload)) {
            return response()->json(['message' => 'Payment pending confirmation.'], 200);
        }

        if (Cache::has($this->processedKey($reference))) {
            return response()->json(['message' => 'Payment already processed.'], 200);
        }

        $pending = Cache::get($this->pendingKey($reference));
        if (empty($pending) || empty($pending['request_data'])) {
            return response()->json(['message' => 'Payment context not found.'], 200);
        }

        $this->processMembershipPayment(
            $pending['request_data'],
            $pending['payment_for'] ?? 'extend',
            $reference,
            $payload
        );

        Cache::put($this->processedKey($reference), true, now()->addDay());
        Cache::forget($this->pendingKey($reference));

        return response()->json(['message' => 'Payment confirmed and processed.'], 200);
    }

    /**
     * Route used when Freshpay redirects the seller browser back.
     */
    public function successPayment(Request $request)
    {
        $payload = $request->all();

        $reference = $this->extractReference($payload);
        if (empty($reference)) {
            $reference = Session::get('freshpay_reference');
        }

        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        $cancel_url = Session::get('cancel_url');

        $pending = !empty($reference) ? Cache::get($this->pendingKey($reference)) : null;
        if (empty($requestData) && !empty($pending['request_data'])) {
            $requestData = $pending['request_data'];
        }
        if (empty($paymentFor) && !empty($pending['payment_for'])) {
            $paymentFor = $pending['payment_for'];
        }
        if (empty($cancel_url) && !empty($pending['cancel_url'])) {
            $cancel_url = $pending['cancel_url'];
        }

        if (empty($requestData) || empty($paymentFor)) {
            return redirect(!empty($cancel_url) ? $cancel_url : route('seller.plan.extend.index'));
        }

        if ($this->hasExplicitFailure($payload)) {
            $this->clearFreshpaySession();
            if (!empty($reference)) {
                Cache::forget($this->pendingKey($reference));
                Cache::forget($this->processedKey($reference));
            }

            return redirect(!empty($cancel_url) ? $cancel_url : route('seller.plan.extend.index'));
        }

        if (!empty($reference) && Cache::has($this->processedKey($reference))) {
            $this->clearFreshpaySession();

            return redirect()->route('success.page');
        }

        Session::flash('warning', 'Payment is pending confirmation.');
        if ($paymentFor == "membership" && !empty($requestData['package_type'])) {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        }

        return redirect()->route('seller.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');

        session()->flash('warning', __('cancel_payment'));
        $this->clearFreshpaySession();

        if (empty($requestData) || empty($paymentFor)) {
            return redirect()->route('seller.plan.extend.index');
        }

        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('seller.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }

    private function processMembershipPayment($requestData, $paymentFor, $reference, $callbackPayload = [])
    {
        $bs = Basic::first();
        $package = Package::find($requestData['package_id']);
        $transaction_id = SellerPermissionHelper::uniqidReal(8);
        $transaction_details = json_encode([
            'reference' => $reference,
            'callback_payload' => $callbackPayload
        ]);

        if ($paymentFor == "membership") {
            $amount = $requestData['price'];
            $password = $requestData['password'];
            $checkout = new SellerCheckoutController();

            $seller = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);
            $lastMemb = $seller->memberships()->orderBy('id', 'DESC')->first();

            $activation = Carbon::parse($lastMemb->start_date);
            $expire = Carbon::parse($lastMemb->expire_date);
            $file_name = $this->makeInvoice($requestData, "membership", $seller, $password, $amount, "Freshpay", $requestData['phone'], $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

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
        }
    }

    private function clearFreshpaySession()
    {
        Session::forget('request');
        Session::forget('paymentFor');
        Session::forget('cancel_url');
        Session::forget('freshpay_reference');
    }

    private function pendingKey($reference)
    {
        return 'freshpay:pending:' . $reference;
    }

    private function processedKey($reference)
    {
        return 'freshpay:processed:' . $reference;
    }

    private function extractReference($response)
    {
        if (!is_array($response)) {
            return null;
        }

        $keys = ['reference', 'merchant_transaction_id', 'merchantTransactionId', 'transaction_id', 'trxref'];
        foreach ($keys as $key) {
            if (!empty($response[$key])) {
                return $response[$key];
            }
        }

        if (!empty($response['data']) && is_array($response['data'])) {
            foreach ($keys as $key) {
                if (!empty($response['data'][$key])) {
                    return $response['data'][$key];
                }
            }
        }

        return null;
    }

    private function hasExplicitFailure($response)
    {
        if (!is_array($response)) {
            return false;
        }

        if (array_key_exists('success', $response) && ($response['success'] === false || $response['success'] === 'false' || $response['success'] === 0 || $response['success'] === '0')) {
            return true;
        }

        $keys = ['status', 'payment_status', 'transaction_status', 'state', 'result'];
        foreach ($keys as $key) {
            if (array_key_exists($key, $response)) {
                $status = strtolower((string) $response[$key]);
                if (in_array($status, ['failed', 'error', 'cancelled', 'canceled', 'declined'])) {
                    return true;
                }
            }
        }

        if (!empty($response['error']) || !empty($response['errors'])) {
            return true;
        }

        if (!empty($response['data']) && is_array($response['data'])) {
            return $this->hasExplicitFailure($response['data']);
        }

        return false;
    }

    private function isConfirmedSuccess($response)
    {
        if (!is_array($response)) {
            return false;
        }

        if ($this->hasExplicitFailure($response)) {
            return false;
        }

        $status = $this->extractTerminalStatus($response);
        if (!empty($status)) {
            if (in_array($status, ['pending', 'processing', 'initiated', 'in_progress', 'waiting'])) {
                return false;
            }

            if (in_array($status, ['success', 'successful', 'completed', 'paid', 'approved', 'payment_success', 'ok'])) {
                return true;
            }
        }

        if ($this->hasPositiveSuccessFlag($response) && $this->hasSuccessMessage($response)) {
            return true;
        }

        if (!empty($response['data']) && is_array($response['data'])) {
            return $this->isConfirmedSuccess($response['data']);
        }

        return false;
    }

    private function extractTerminalStatus($response)
    {
        if (!is_array($response)) {
            return null;
        }

        $keys = ['status', 'payment_status', 'transaction_status', 'state', 'result'];

        foreach ($keys as $key) {
            if (array_key_exists($key, $response) && $response[$key] !== null && $response[$key] !== '') {
                return strtolower((string) $response[$key]);
            }
        }

        return null;
    }

    private function hasPositiveSuccessFlag($response)
    {
        if (!is_array($response) || !array_key_exists('success', $response)) {
            return false;
        }

        return $response['success'] === true || $response['success'] === 'true' || $response['success'] === 1 || $response['success'] === '1';
    }

    private function hasSuccessMessage($response)
    {
        if (!is_array($response) || empty($response['message'])) {
            return false;
        }

        $message = strtolower((string) $response['message']);
        $positiveTokens = ['success', 'successful', 'completed', 'paid', 'approved'];
        $pendingTokens = ['pending', 'processing', 'initiated', 'sent', 'waiting', 'request'];

        $hasPositiveToken = false;
        foreach ($positiveTokens as $token) {
            if (strpos($message, $token) !== false) {
                $hasPositiveToken = true;
                break;
            }
        }

        if (!$hasPositiveToken) {
            return false;
        }

        foreach ($pendingTokens as $token) {
            if (strpos($message, $token) !== false) {
                return false;
            }
        }

        return true;
    }

    private function extractErrorMessage($response)
    {
        if (!is_array($response)) {
            return null;
        }

        if (!empty($response['message'])) {
            return $response['message'];
        }

        if (!empty($response['error'])) {
            return is_array($response['error']) ? implode(', ', $response['error']) : $response['error'];
        }

        return null;
    }

    private function extractRedirectUrl($response)
    {
        if (!is_array($response)) {
            return null;
        }

        $keys = ['payment_url', 'redirect_url', 'redirectUrl', 'checkout_url', 'url'];

        foreach ($keys as $key) {
            if (!empty($response[$key]) && filter_var($response[$key], FILTER_VALIDATE_URL)) {
                return $response[$key];
            }
        }

        if (!empty($response['data']) && is_array($response['data'])) {
            foreach ($keys as $key) {
                if (!empty($response['data'][$key]) && filter_var($response['data'][$key], FILTER_VALIDATE_URL)) {
                    return $response['data'][$key];
                }
            }
        }

        return null;
    }
}
