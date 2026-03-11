<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Models\ClientService\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Config\Iyzipay;

class IyzicoController extends Controller
{
    public function index(Request $request, $data, $paymentFor)
    {
        $fname = $request->name;
        $lname = $request->name;
        $email = $request->email_address;
        $city = $request->city;
        $country = $request->country;
        $address = $request->address;
        $zip_code = $request->zip_code;

        $serviceSlug = $data['slug'];

        $cancel_url = route('service.place_order.cancel', ['slug' => $serviceSlug]);
        $notifyURL = route('service.place_order.iyzico.notify', ['slug' => $serviceSlug]);

        $currencyInfo = $this->getCurrencyInfo();
        if ($currencyInfo->base_currency_text != 'TRY') {
            return redirect($cancel_url)->withInput();
        }
        $data['currencyText'] = $currencyInfo->base_currency_text;
        $data['currencyTextPosition'] = $currencyInfo->base_currency_text_position;
        $data['currencySymbol'] = $currencyInfo->base_currency_symbol;
        $data['currencySymbolPosition'] = $currencyInfo->base_currency_symbol_position;
        $data['paymentMethod'] = 'Iyzico';
        $data['gatewayType'] = 'online';
        $data['paymentStatus'] = 'pending';
        $data['orderStatus'] = 'pending';

        $conversion_id = uniqid(9999, 999999);
        $data['conversation_id'] = $conversion_id;
        $basket_id = 'B' . uniqid(999, 99999);
        $phone_number = $request->phone_number;
        $identity_number = $request->identity_number;

        Session::put('arrData', $data);

        $options = Iyzipay::options();
        # create request class
        $request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId($conversion_id);
        $request->setPrice($data['grandTotal']);
        $request->setPaidPrice($data['grandTotal']);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setBasketId($basket_id);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl($notifyURL);
        $request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(uniqid());
        $buyer->setName($fname);
        $buyer->setSurname($lname);
        $buyer->setGsmNumber($phone_number);
        $buyer->setEmail($email);
        $buyer->setIdentityNumber($identity_number);
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
        $firstBasketItem->setPrice($data['grandTotal']);
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

    public function notify(Request $request)
    {
        // get the information from session
        $arrData = Session::get('arrData');
        $serviceSlug = $arrData['slug'];

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

        return redirect()->route('service.place_order.complete', ['slug' => $serviceSlug, 'via' => 'online']);
    }
}
