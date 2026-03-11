<?php

namespace App\Http\Controllers\BackEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OnlineGatewayController extends Controller
{
  public function index()
  {
    $gatewayInfo['paypal'] = OnlineGateway::query()->whereKeyword('paypal')->first();
    $gatewayInfo['instamojo'] = OnlineGateway::query()->whereKeyword('instamojo')->first();
    $gatewayInfo['paystack'] = OnlineGateway::query()->whereKeyword('paystack')->first();
    $gatewayInfo['flutterwave'] = OnlineGateway::query()->whereKeyword('flutterwave')->first();
    $gatewayInfo['razorpay'] = OnlineGateway::query()->whereKeyword('razorpay')->first();
    $gatewayInfo['mercadopago'] = OnlineGateway::query()->whereKeyword('mercadopago')->first();
    $gatewayInfo['mollie'] = OnlineGateway::query()->whereKeyword('mollie')->first();
    $gatewayInfo['stripe'] = OnlineGateway::query()->whereKeyword('stripe')->first();
    $gatewayInfo['paytm'] = OnlineGateway::query()->whereKeyword('paytm')->first();
    $gatewayInfo['authorizenet'] = OnlineGateway::query()->whereKeyword('authorize.net')->first();
    $gatewayInfo['midtrans'] = OnlineGateway::where('keyword', 'midtrans')->first();
    $gatewayInfo['iyzico'] = OnlineGateway::where('keyword', 'iyzico')->first();
    $gatewayInfo['paytabs'] = OnlineGateway::where('keyword', 'paytabs')->first();
    $gatewayInfo['toyyibpay'] = OnlineGateway::where('keyword', 'toyyibpay')->first();
    $gatewayInfo['phonepe'] = OnlineGateway::where('keyword', 'phonepe')->first();
    $gatewayInfo['yoco'] = OnlineGateway::where('keyword', 'yoco')->first();
    $gatewayInfo['myfatoorah'] = OnlineGateway::where('keyword', 'myfatoorah')->first();
    $gatewayInfo['xendit'] = OnlineGateway::where('keyword', 'xendit')->first();
    $gatewayInfo['perfect_money'] = OnlineGateway::where('keyword', 'perfect_money')->first();

    return view('backend.payment-gateways.online-gateways', $gatewayInfo);
  }

  // paypal
  public function updatePayPalInfo(Request $request)
  {
    $rules = [
      'paypal_status' => 'required',
      'paypal_sandbox_status' => 'required',
      'paypal_client_id' => 'required',
      'paypal_client_secret' => 'required'
    ];

    $messages = [
      'paypal_status.required' => 'The status field is required.',
      'paypal_sandbox_status.required' => 'The test mode field is required.',
      'paypal_client_id.required' => 'The client id field is required.',
      'paypal_client_secret.required' => 'The client secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->paypal_sandbox_status;
    $information['client_id'] = $request->paypal_client_id;
    $information['client_secret'] = $request->paypal_client_secret;

    $paypalInfo = OnlineGateway::query()->whereKeyword('paypal')->first();

    $paypalInfo->update([
      'information' => json_encode($information),
      'status' => $request->paypal_status
    ]);

    $request->session()->flash('success', 'PayPal\'s information updated successfully!');

    return redirect()->back();
  }

  // instamojo
  public function updateInstamojoInfo(Request $request)
  {
    $rules = [
      'instamojo_status' => 'required',
      'instamojo_sandbox_status' => 'required',
      'instamojo_key' => 'required',
      'instamojo_token' => 'required'
    ];

    $messages = [
      'instamojo_status.required' => 'The status field is required.',
      'instamojo_sandbox_status.required' => 'The test mode field is required.',
      'instamojo_key.required' => 'The api key field is required.',
      'instamojo_token.required' => 'The auth token field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->instamojo_sandbox_status;
    $information['key'] = $request->instamojo_key;
    $information['token'] = $request->instamojo_token;

    $instamojoInfo = OnlineGateway::query()->whereKeyword('instamojo')->first();

    $instamojoInfo->update([
      'information' => json_encode($information),
      'status' => $request->instamojo_status
    ]);

    $request->session()->flash('success', 'Instamojo\'s information updated successfully!');

    return redirect()->back();
  }

  // paystack
  public function updatePaystackInfo(Request $request)
  {
    $rules = [
      'paystack_status' => 'required',
      'paystack_key' => 'required'
    ];

    $messages = [
      'paystack_status.required' => 'The status field is required.',
      'paystack_key.required' => 'The key field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->paystack_key;

    $paystackInfo = OnlineGateway::query()->whereKeyword('paystack')->first();

    $paystackInfo->update([
      'information' => json_encode($information),
      'status' => $request->paystack_status
    ]);

    $request->session()->flash('success', 'Paystack\'s information updated successfully!');

    return redirect()->back();
  }

  // flutterwave
  public function updateFlutterwaveInfo(Request $request)
  {
    $rules = [
      'flutterwave_status' => 'required',
      'flutterwave_public_key' => 'required',
      'flutterwave_secret_key' => 'required'
    ];

    $messages = [
      'flutterwave_status.required' => 'The status field is required.',
      'flutterwave_public_key.required' => 'The public key field is required.',
      'flutterwave_secret_key.required' => 'The secret key field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['public_key'] = $request->flutterwave_public_key;
    $information['secret_key'] = $request->flutterwave_secret_key;

    $flutterwaveInfo = OnlineGateway::query()->whereKeyword('flutterwave')->first();

    $flutterwaveInfo->update([
      'information' => json_encode($information),
      'status' => $request->flutterwave_status
    ]);

    $array = [
      'FLW_PUBLIC_KEY' => $request->flutterwave_public_key,
      'FLW_SECRET_KEY' => $request->flutterwave_secret_key
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Flutterwave\'s information updated successfully!');

    return redirect()->back();
  }

  // razorpay
  public function updateRazorpayInfo(Request $request)
  {
    $rules = [
      'razorpay_status' => 'required',
      'razorpay_key' => 'required',
      'razorpay_secret' => 'required'
    ];

    $messages = [
      'razorpay_status.required' => 'The status field is required.',
      'razorpay_key.required' => 'The key field is required.',
      'razorpay_secret.required' => 'The secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->razorpay_key;
    $information['secret'] = $request->razorpay_secret;

    $razorpayInfo = OnlineGateway::query()->whereKeyword('razorpay')->first();

    $razorpayInfo->update([
      'information' => json_encode($information),
      'status' => $request->razorpay_status
    ]);

    $request->session()->flash('success', 'Razorpay\'s information updated successfully!');

    return redirect()->back();
  }

  // mercadopago
  public function updateMercadoPagoInfo(Request $request)
  {
    $rules = [
      'mercadopago_status' => 'required',
      'mercadopago_sandbox_status' => 'required',
      'mercadopago_token' => 'required'
    ];

    $messages = [
      'mercadopago_status.required' => 'The status field is required.',
      'mercadopago_sandbox_status.required' => 'The test mode field is required.',
      'mercadopago_token.required' => 'The token field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->mercadopago_sandbox_status;
    $information['token'] = $request->mercadopago_token;

    $mercadopagoInfo = OnlineGateway::query()->whereKeyword('mercadopago')->first();

    $mercadopagoInfo->update([
      'information' => json_encode($information),
      'status' => $request->mercadopago_status
    ]);

    $request->session()->flash('success', 'MercadoPago\'s information updated successfully!');

    return redirect()->back();
  }

  // mollie
  public function updateMollieInfo(Request $request)
  {
    $rules = [
      'mollie_status' => 'required',
      'mollie_key' => 'required'
    ];

    $messages = [
      'mollie_status.required' => 'The status field is required.',
      'mollie_key.required' => 'The api key field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->mollie_key;

    $mollieInfo = OnlineGateway::query()->whereKeyword('mollie')->first();

    $mollieInfo->update([
      'information' => json_encode($information),
      'status' => $request->mollie_status
    ]);

    $array = ['MOLLIE_KEY' => $request->mollie_key];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Mollie\'s information updated successfully!');

    return redirect()->back();
  }

  // stripe
  public function updateStripeInfo(Request $request)
  {
    $rules = [
      'stripe_status' => 'required',
      'stripe_key' => 'required',
      'stripe_secret' => 'required'
    ];

    $messages = [
      'stripe_status.required' => 'The status field is required.',
      'stripe_key.required' => 'The key field is required.',
      'stripe_secret.required' => 'The secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->stripe_key;
    $information['secret'] = $request->stripe_secret;

    $stripeInfo = OnlineGateway::query()->whereKeyword('stripe')->first();

    $stripeInfo->update([
      'information' => json_encode($information),
      'status' => $request->stripe_status
    ]);

    $array = [
      'STRIPE_KEY' => $request->stripe_key,
      'STRIPE_SECRET' => $request->stripe_secret
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Stripe\'s information updated successfully!');

    return redirect()->back();
  }

  // paytm
  public function updatePaytmInfo(Request $request)
  {
    $rules = [
      'paytm_status' => 'required',
      'paytm_environment' => 'required',
      'paytm_merchant_key' => 'required',
      'paytm_merchant_mid' => 'required',
      'paytm_merchant_website' => 'required',
      'paytm_industry_type' => 'required'
    ];

    $messages = [
      'paytm_status.required' => 'The status field is required.',
      'paytm_environment.required' => 'The environment field is required.',
      'paytm_merchant_key.required' => 'The merchant key field is required.',
      'paytm_merchant_mid.required' => 'The merchant mid field is required.',
      'paytm_merchant_website.required' => 'The merchant website field is required.',
      'paytm_industry_type.required' => 'The industry type field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['environment'] = $request->paytm_environment;
    $information['merchant_key'] = $request->paytm_merchant_key;
    $information['merchant_mid'] = $request->paytm_merchant_mid;
    $information['merchant_website'] = $request->paytm_merchant_website;
    $information['industry_type'] = $request->paytm_industry_type;

    $paytmInfo = OnlineGateway::query()->whereKeyword('paytm')->first();

    $paytmInfo->update([
      'information' => json_encode($information),
      'status' => $request->paytm_status
    ]);

    $array = [
      'PAYTM_ENVIRONMENT' => $request->paytm_environment,
      'PAYTM_MERCHANT_KEY' => $request->paytm_merchant_key,
      'PAYTM_MERCHANT_ID' => $request->paytm_merchant_mid,
      'PAYTM_MERCHANT_WEBSITE' => $request->paytm_merchant_website,
      'PAYTM_INDUSTRY_TYPE' => $request->paytm_industry_type
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Paytm\'s information updated successfully!');

    return redirect()->back();
  }

  // authorize.net
  public function updateAuthorizeNetInfo(Request $request)
  {
    $rules = [
      'authorizenet_status' => 'required',
      'authorizenet_sandbox_status' => 'required',
      'authorizenet_api_login_id' => 'required',
      'authorizenet_transaction_key' => 'required',
      'authorizenet_public_client_key' => 'required'
    ];

    $messages = [
      'authorizenet_status.required' => 'The status field is required.',
      'authorizenet_sandbox_status.required' => 'The test mode field is required.',
      'authorizenet_api_login_id.required' => 'The api login id field is required.',
      'authorizenet_transaction_key.required' => 'The transaction key field is required.',
      'authorizenet_public_client_key.required' => 'The public client key field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->authorizenet_sandbox_status;
    $information['api_login_id'] = $request->authorizenet_api_login_id;
    $information['transaction_key'] = $request->authorizenet_transaction_key;
    $information['public_client_key'] = $request->authorizenet_public_client_key;

    $authorizenetInfo = OnlineGateway::query()->whereKeyword('authorize.net')->first();

    $authorizenetInfo->update([
      'information' => json_encode($information),
      'status' => $request->authorizenet_status
    ]);

    $request->session()->flash('success', 'Authorize.Net\'s information updated successfully!');

    return redirect()->back();
  }


  public function updateMidtransInfo(Request $request)
  {
    $midtrans = OnlineGateway::where('keyword', 'midtrans')->first();

    $information = [];
    $information['server_key'] = $request->server_key;
    $information['is_production'] = $request->is_production;

    $midtrans->information = json_encode($information);
    $midtrans->status = $request->status;
    $midtrans->save();

    $request->session()->flash('success', "Midtrans informations updated successfully!");

    return back();
  }

  public function updateIyzicoInfo(Request $request)
  {

    $iyzico = OnlineGateway::where('keyword', 'iyzico')->first();

    $information = [];
    $information['api_key'] = $request->api_key;
    $information['secrect_key'] = $request->secrect_key;
    $information['sandbox_status'] = $request->sandbox_status;

    $iyzico->information = json_encode($information);
    $iyzico->status = $request->status;
    $iyzico->save();

    $request->session()->flash('success', "Iyzico's informations updated successfully!");

    return back();
  }

  public function updatePaytabsInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'country' => 'required',
      'server_key' => 'required',
      'profile_id' => 'required',
      'api_endpoint' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['server_key'] = $request->server_key;
    $information['profile_id'] = $request->profile_id;
    $information['country'] = $request->country;
    $information['api_endpoint'] = $request->api_endpoint;

    $data = OnlineGateway::where('keyword', 'paytabs')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    Session::flash('success', "Updated Paytabs's Information Successfully");

    return redirect()->back();
  }
  public function updateToyyibpayInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'sandbox_status' => 'required',
      'secret_key' => 'required',
      'category_code' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->sandbox_status;
    $information['secret_key'] = $request->secret_key;
    $information['category_code'] = $request->category_code;

    $data = OnlineGateway::where('keyword', 'toyyibpay')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    Session::flash('success', "Updated Toyyibpay's Information Successfully");

    return redirect()->back();
  }

  public function updatePhonepeInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'sandbox_status' => 'required',
      'merchant_id' => 'required',
      'salt_key' => 'required',
      'salt_index' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['merchant_id'] = $request->merchant_id;
    $information['sandbox_status'] = $request->sandbox_status;
    $information['salt_key'] = $request->salt_key;
    $information['salt_index'] = $request->salt_index;

    $data = OnlineGateway::where('keyword', 'phonepe')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    Session::flash('success', "Updated Phonepe's Information Successfully");

    return redirect()->back();
  }

  public function updateYocoInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'secret_key' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['secret_key'] = $request->secret_key;

    $data = OnlineGateway::where('keyword', 'yoco')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    Session::flash('success', "Updated Yoco's Information Successfully");

    return redirect()->back();
  }
  public function updateMyFatoorahInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'sandbox_status' => 'required',
      'token' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information = [
      'token' => $request->token,
      'sandbox_status' => $request->sandbox_status
    ];

    $data = OnlineGateway::where('keyword', 'myfatoorah')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    $array = [
      'MYFATOORAH_TOKEN' => $request->token,
      'MYFATOORAH_CALLBACK_URL' => route('myfatoorah_callback'),
      'MYFATOORAH_ERROR_URL' => route('myfatoorah_cancel'),
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    Session::flash('success', "Updated Myfatoorah's Information Successfully");

    return redirect()->back();
  }

  public function updateXenditInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'secret_key' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['secret_key'] = $request->secret_key;

    $data = OnlineGateway::where('keyword', 'xendit')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    $array = [
      'XENDIT_SECRET_KEY' => $request->secret_key,
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    Session::flash('success', "Updated Xendit' Information Successfully");

    return redirect()->back();
  }

  public function updatePerfectMoneyInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'perfect_money_wallet_id' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information = [
      'perfect_money_wallet_id' => $request->perfect_money_wallet_id
    ];

    $data = OnlineGateway::where('keyword', 'perfect_money')->first();

    $data->update([
      'information' => json_encode($information),
      'status' => $request->status
    ]);

    Session::flash('success', "Updated Perfect Money's Information Successfully");

    return redirect()->back();
  }
}
