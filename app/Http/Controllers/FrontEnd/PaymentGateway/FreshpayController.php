<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Controllers\FrontEnd\PayController;
use App\Models\ClientService\Service;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
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

        $redirectUrl = $this->extractRedirectUrl($responseData);

        if (!empty($redirectUrl)) {
            Session::put('arrData', $data);
            Session::put('paymentFor', $paymentFor);
            Session::put('freshpay_reference', $reference);

            return redirect()->away($redirectUrl);
        }

        if ($paymentFor == 'service') {
            return $this->completeServicePayment($data);
        }

        return $this->completeInvoicePayment($data['invoice']);
    }

    public function notify(Request $request)
    {
        $paymentFor = Session::get('paymentFor');
        $arrData = Session::get('arrData');
        $reference = Session::get('freshpay_reference');

        // This callback may come from server to server where user session does not exist.
        if (empty($paymentFor) || empty($arrData)) {
            return response()->json(['message' => 'Freshpay callback received.'], 200);
        }

        if (!empty($reference) && $request->filled('reference') && $request->input('reference') != $reference) {
            $this->clearFreshpaySession();

            return $this->cancelResponse($paymentFor, $arrData);
        }

        if ($this->hasExplicitFailure($request->all())) {
            $this->clearFreshpaySession();

            return $this->cancelResponse($paymentFor, $arrData);
        }

        $this->clearFreshpaySession();

        if ($paymentFor == 'service') {
            return $this->completeServicePayment($arrData);
        }

        return $this->completeInvoicePayment($arrData['invoice']);
    }

    private function completeServicePayment($arrData)
    {
        $serviceSlug = $arrData['slug'];

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

        return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
    }

    private function completeInvoicePayment($invoiceData)
    {
        if ($invoiceData instanceof \App\Models\Invoice) {
            $invoice = $invoiceData;
        } else {
            $invoiceId = is_array($invoiceData) ? ($invoiceData['id'] ?? null) : $invoiceData;
            $invoice = \App\Models\Invoice::findOrFail($invoiceId);
        }

        $invoice->update([
            'payment_status' => 'paid',
            'payment_method' => 'Freshpay',
            'gateway_type' => 'online'
        ]);

        $pay = new PayController();
        $pay->generateInvoice($invoice);
        $pay->prepareMail($invoice);

        return redirect()->route('pay.complete', ['via' => 'online']);
    }

    private function cancelResponse($paymentFor, $arrData)
    {
        if ($paymentFor == 'service') {
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

    private function hasExplicitFailure($response)
    {
        if (!is_array($response)) {
            return false;
        }

        if (array_key_exists('success', $response) && $response['success'] === false) {
            return true;
        }

        if (array_key_exists('status', $response)) {
            $status = strtolower((string) $response['status']);
            if (in_array($status, ['failed', 'error', 'cancelled', 'canceled', 'declined'])) {
                return true;
            }
        }

        if (!empty($response['error']) || !empty($response['errors'])) {
            return true;
        }

        return false;
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
