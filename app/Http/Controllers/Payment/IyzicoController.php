<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Seller\SellerCheckoutController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Http\Helpers\SellerPermissionHelper;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Http\Helpers\MegaMailer;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Package;
use App\Models\SellerInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Config\Iyzipay;

class IyzicoController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {
        $lang = Language::where('is_default', 1)->first();

        $seller_info = SellerInfo::where([['seller_id', Auth::guard('seller')->user()->id], ['language_id', $lang->id]])->first();

        $fname = Auth::guard('seller')->user()->username;
        $lname = $seller_info->name;
        $email = Auth::guard('seller')->user()->email;
        $phone = Auth::guard('seller')->user()->phone;
        $city = $seller_info->city;
        $country = $seller_info->country;
        $address = $seller_info->address;
        $zip_code = $seller_info->zip_code;
        $id_number = $request->identity_number;
        $basket_id = 'B' . uniqid(999, 99999);

        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;

        Session::put("request", $request->all());
        $conversion_id = uniqid(9999, 999999);
        Session::put('conversation_id', $conversion_id);

        $options = Iyzipay::options();
        # create request class
        $request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId($conversion_id);
        $request->setPrice($_amount);
        $request->setPaidPrice($_amount);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setBasketId($basket_id);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl($notify_url);
        $request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(uniqid());
        $buyer->setName($fname);
        $buyer->setSurname($lname);
        $buyer->setGsmNumber($phone);
        $buyer->setEmail($email);
        $buyer->setIdentityNumber($id_number);
        $buyer->setLastLoginDate("");
        $buyer->setRegistrationDate("");
        $buyer->setRegistrationAddress($address);
        $buyer->setIp("");
        $buyer->setCity($city);
        $buyer->setCountry($country);
        $buyer->setZipCode($zip_code);
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($fname);
        $shippingAddress->setCity($city);
        $shippingAddress->setCountry($country);
        $shippingAddress->setAddress($address);
        $shippingAddress->setZipCode($zip_code);
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($fname);
        $billingAddress->setCity($city);
        $billingAddress->setCountry($country);
        $billingAddress->setAddress($address);
        $billingAddress->setZipCode($zip_code);
        $request->setBillingAddress($billingAddress);

        $q_id = uniqid(999, 99999);
        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId($q_id);
        $firstBasketItem->setName("Purchase Id " . $q_id);
        $firstBasketItem->setCategory1("Purchase or Extend");
        $firstBasketItem->setCategory2("");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $firstBasketItem->setPrice($_amount);
        $basketItems[0] = $firstBasketItem;
        $request->setBasketItems($basketItems);

        # make request
        $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, $options);

        $paymentResponse = (array)$payWithIyzicoInitialize;
        foreach ($paymentResponse as $key => $data) {
            $paymentInfo = json_decode($data, true);
            if ($paymentInfo['status'] == 'success') {
                if (!empty($paymentInfo['payWithIyzicoPageUrl'])) {
                    Session::put('cancel_url', $cancel_url);
                    return redirect($paymentInfo['payWithIyzicoPageUrl']);
                } else {
                    return redirect($cancel_url)->with('error', 'Payment Canceled');
                }
            } else {
                return redirect($cancel_url)->with('error', 'Payment Canceled');
            }
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        $bs = Basic::first();
        $cancel_url = Session::get('cancel_url');
        /** Get the payment ID before session clear **/
        $requestData['conversation_id'] = Session::get('conversation_id');
        $requestData['status'] = 0;

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
            return redirect()->route('success.page');
        } elseif ($paymentFor == "extend") {
            $amount = $requestData['price'];
            $password = uniqid('qrcode');
            $checkout = new SellerCheckoutController();
            $seller = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

            // $lastMemb = Membership::where('seller_id', $seller->id)->orderBy('id', 'DESC')->first();
            // $activation = Carbon::parse($lastMemb->start_date);
            // $expire = Carbon::parse($lastMemb->expire_date);

            // $file_name = $this->makeInvoice($requestData, "extend", $seller, $password, $amount, $requestData["payment_method"], $seller->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

            // $mailer = new MegaMailer();
            // $data = [
            //     'toMail' => $seller->email,
            //     'toName' => $seller->fname,
            //     'username' => $seller->username,
            //     'package_title' => $package->title,
            //     'package_price' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
            //     'activation_date' => $activation->toFormattedDateString(),
            //     'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
            //     'membership_invoice' => $file_name,
            //     'website_title' => $bs->website_title,
            //     'templateType' => 'membership_extend',
            //     'type' => 'membershipExtend'
            // ];
            // $mailer->mailFromAdmin($data);
            // @unlink(public_path('assets/front/invoices/' . $file_name));

            // //store data to transaction and earnings table
            // $transaction_data = [];
            // $transaction_data['order_id'] = $lastMemb->id;
            // $transaction_data['transcation_type'] = 5;
            // $transaction_data['user_id'] = null;
            // $transaction_data['seller_id'] = $lastMemb->seller_id;
            // $transaction_data['payment_status'] = 'completed';
            // $transaction_data['payment_method'] = $lastMemb->payment_method;
            // $transaction_data['grand_total'] = $lastMemb->price;
            // $transaction_data['pre_balance'] = null;
            // $transaction_data['tax'] = null;
            // $transaction_data['after_balance'] = null;
            // $transaction_data['gateway_type'] = 'online';
            // $transaction_data['currency_symbol'] = $lastMemb->currency_symbol;
            // $transaction_data['currency_symbol_position'] = $bs->base_currency_symbol_position;
            // storeTransaction($transaction_data);
            // $data = [
            //     'life_time_earning' => $lastMemb->price,
            //     'total_profit' => $lastMemb->price,
            // ];
            // storeEarnings($data);

            Session::forget('request');
            Session::forget('paymentFor');
            return redirect()->route('success.page');
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
