<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\Invoice;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FreshpayController extends Controller
{
    private $apiUrl = 'https://paydrc.gofreshbakery.net/api/v5/';

    private $gatewayInfo = [];

    public function __construct()
    {
        $gateway = OnlineGateway::where('keyword', 'freshpay')->first();
        $this->gatewayInfo = !empty($gateway) ? json_decode($gateway->information, true) : [];
    }

    public function index(Request $request, $data, $paymentFor)
    {
        if (
            empty($this->gatewayInfo['merchant_id']) || empty($this->gatewayInfo['merchant_secrete']) ||
            empty($this->gatewayInfo['firstname']) || empty($this->gatewayInfo['lastname']) || empty($this->gatewayInfo['email'])
        ) {
            return redirect()->back()->with('error', 'Freshpay is not configured yet. Please contact the administrator.')->withInput();
        }

        if ($paymentFor != 'invoice') {
            $currencyInfo = $this->getCurrencyInfo();

            $data['currencyText'] = $currencyInfo->base_currency_text;
            $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
            $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
            $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
            $data['paymentMethod'] = 'Freshpay';
            $data['gatewayType'] = 'online';
            $data['paymentStatus'] = 'completed';
            $data['orderStatus'] = 'pending';
        }

        if ($paymentFor == 'service') {
            $serviceSlug = $data['slug'];
            $callbackUrl = route('service.place_order.freshpay.notify', ['slug' => $serviceSlug]);
            $currency = $data['currencyText'];
        } else {
            $callbackUrl = route('pay.freshpay.notify');
            $currency = $data['invoice']->base_currency_text;
        }

        $reference = 'fp_' . $paymentFor . '_' . strtoupper(uniqid());

        $payload = [
            'merchant_id' => $this->gatewayInfo['merchant_id'],
            'merchant_secrete' => $this->gatewayInfo['merchant_secrete'],
            'amount' => (string) number_format((float) $data['grandTotal'], 2, '.', ''),
            'currency' => $currency,
            'action' => 'debit',
            'customer_number' => $request->input('customer_number'),
            'firstname' => $this->gatewayInfo['firstname'],
            'lastname' => $this->gatewayInfo['lastname'],
            'email' => $this->gatewayInfo['email'],
            'reference' => $reference,
            'method' => $request->input('method'),
            'callback_url' => $callbackUrl
        ];

        $response = Http::acceptJson()->timeout(30)->post($this->apiUrl, $payload);
        $responseData = $response->json();

        if (!$response->successful() || $this->hasExplicitFailure($responseData)) {
            $errorMessage = $this->extractErrorMessage($responseData);

            return redirect()->back()
                ->with('error', !empty($errorMessage) ? $errorMessage : 'Freshpay payment request failed.')
                ->withInput();
        }

        Cache::put($this->pendingKey($reference), $this->preparePendingData($paymentFor, $data), now()->addHours(6));

        Session::put('paymentFor', $paymentFor);
        Session::put('arrData', $paymentFor == 'invoice' ? ['invoice_id' => $data['invoice']->id] : $data);
        Session::put('freshpay_reference', $reference);

        $redirectUrl = $this->extractRedirectUrl($responseData);
        if (!empty($redirectUrl)) {
            return redirect()->away($redirectUrl);
        }

        Session::flash('success', 'Freshpay push sent. Please approve the payment on your phone and wait for confirmation.');

        return redirect()->back()->withInput($request->all());
    }

    public function notify(Request $request)
    {
        $payload = $request->all();
        $reference = $this->extractReference($payload);
        if (empty($reference)) {
            $reference = Session::get('freshpay_reference');
        }

        $hasSessionContext = !empty(Session::get('paymentFor')) && !empty(Session::get('arrData'));
        $paymentFor = Session::get('paymentFor');
        $arrData = Session::get('arrData');

        $pending = !empty($reference) ? Cache::get($this->pendingKey($reference)) : null;
        if ((empty($paymentFor) || empty($arrData)) && !empty($pending)) {
            $paymentFor = $pending['payment_for'] ?? null;
            $arrData = $pending['data'] ?? null;
        }

        if ($this->hasExplicitFailure($payload)) {
            if (!empty($reference)) {
                Cache::forget($this->pendingKey($reference));
                Cache::forget($this->processedKey($reference));
            }

            $this->clearFreshpaySession();

            return $hasSessionContext ? $this->cancelResponse($paymentFor, $arrData) : response()->json(['message' => 'Payment failed.'], 200);
        }

        if (!$this->isConfirmedSuccess($payload)) {
            if ($hasSessionContext) {
                Session::flash('warning', 'Payment is pending confirmation.');

                return redirect()->back();
            }

            return response()->json(['message' => 'Payment is pending confirmation.'], 200);
        }

        if (!empty($reference) && Cache::has($this->processedKey($reference))) {
            $this->clearFreshpaySession();

            return $hasSessionContext ? $this->successResponse($paymentFor, $arrData) : response()->json(['message' => 'Payment already processed.'], 200);
        }

        if (empty($paymentFor) || empty($arrData)) {
            return response()->json(['message' => 'Payment context not found.'], 200);
        }

        if ($paymentFor == 'service') {
            $this->completeServicePayment($arrData);
        } else {
            $invoiceId = is_array($arrData) ? ($arrData['invoice_id'] ?? null) : $arrData;
            $this->completeInvoicePayment($invoiceId);
        }

        if (!empty($reference)) {
            Cache::put($this->processedKey($reference), true, now()->addDay());
            Cache::forget($this->pendingKey($reference));
        }

        $this->clearFreshpaySession();

        return $hasSessionContext ? $this->successResponse($paymentFor, $arrData) : response()->json(['message' => 'Payment confirmed and processed.'], 200);
    }

    private function completeServicePayment($arrData)
    {
        $orderProcess = new OrderProcessController();

        $selectedService = Service::where('id', $arrData['serviceId'])->select('seller_id')->first();
        if (!empty($selectedService) && $selectedService->seller_id != 0) {
            $arrData['seller_id'] = $selectedService->seller_id;
        } else {
            $arrData['seller_id'] = null;
        }

        $orderInfo = $orderProcess->storeData($arrData);

        $invoice = $orderProcess->generateInvoice($orderInfo);
        $orderInfo->update(['invoice' => $invoice]);

        $orderProcess->prepareMail($orderInfo);
    }

    private function completeInvoicePayment($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        $invoice->update([
            'payment_status' => 'paid',
            'payment_method' => 'Freshpay',
            'gateway_type' => 'online'
        ]);

        $pay = new PayController();
        $pay->generateInvoice($invoice);
        $pay->prepareMail($invoice);
    }

    private function successResponse($paymentFor, $arrData)
    {
        if ($paymentFor == 'service') {
            return redirect()->route('service.place_order.complete', ['slug' => $arrData['slug'], 'via' => 'online']);
        }

        return redirect()->route('pay.complete', ['via' => 'online']);
    }

    private function cancelResponse($paymentFor, $arrData)
    {
        if ($paymentFor == 'service' && !empty($arrData['slug'])) {
            return redirect()->route('service.place_order.cancel', ['slug' => $arrData['slug']]);
        }

        return redirect()->route('pay.cancel');
    }

    private function clearFreshpaySession()
    {
        Session::forget('paymentFor');
        Session::forget('arrData');
        Session::forget('freshpay_reference');
    }

    private function preparePendingData($paymentFor, $data)
    {
        if ($paymentFor == 'invoice') {
            return [
                'payment_for' => 'invoice',
                'data' => ['invoice_id' => $data['invoice']->id]
            ];
        }

        return [
            'payment_for' => $paymentFor,
            'data' => $data
        ];
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
