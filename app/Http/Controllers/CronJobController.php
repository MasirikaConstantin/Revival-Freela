<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FrontEnd\ClientService\OrderProcessController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\SellerPermissionHelper;
use App\Jobs\SubscriptionExpiredMail;
use App\Jobs\SubscriptionReminderMail;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\ServiceOrder;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Seller;
use Carbon\Carbon;

class CronJobController extends Controller
{
    public function expired()
    {
        try {
            $bs = Basic::first();

            $expired_members = Membership::whereDate('expire_date', Carbon::now()->subDays(1))->get();
            foreach ($expired_members as $key => $expired_member) {
                if (!empty($expired_member->seller)) {
                    $seller = $expired_member->seller;
                    $current_package = SellerPermissionHelper::userPackage($seller->id);
                    if (is_null($current_package)) {
                        SubscriptionExpiredMail::dispatch($seller, $bs);
                    }
                }
            }

            $remind_members = Membership::whereDate('expire_date', Carbon::now()->addDays($bs->expiration_reminder))->get();
            foreach ($remind_members as $key => $remind_member) {
                if (!empty($remind_member->seller)) {
                    $seller = $remind_member->seller;

                    $nextPacakgeCount = Membership::where([
                        ['seller_id', $seller->id],
                        ['start_date', '>', Carbon::now()->toDateString()]
                    ])->where('status', '<>', 2)->count();

                    if ($nextPacakgeCount == 0) {
                        SubscriptionReminderMail::dispatch($seller, $bs, $remind_member->expire_date);
                    }
                }
                \Artisan::call("queue:work --stop-when-empty");
            }
        } catch (\Exception $e) {
        }
    }

    public function check_payment()
    {
        // check memberships
        $memberships = Membership::where([['payment_method', 'Iyzico'], ['status', 0]])->get();
        foreach ($memberships as $membership) {
            if (!is_null($membership->conversation_id)) {
                $result = $this->IyzicoPaymentStatus($membership->conversation_id);
                if ($result == 'success') {
                    $seller = Seller::where('id', $membership->seller_id)->first();
                    $lastMemb = Membership::where('seller_id', $membership->seller_id)->orderBy('id', 'DESC')->first();
                    $lastMemb->status = 1;
                    $lastMemb->save();
                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);
                    $requestData = [
                        'payment_method' => $membership->payment_method,
                        'start_date' => $membership->start_date,
                        'expire_date' => $membership->expire_date,
                    ];
                    $password = null;
                    $amount = $membership->price;
                    $transaction_id = $membership->transaction_id;
                    $bs = Basic::first();
                    $package = Package::where('id', $membership->package_id)->first();

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
                }
            }
        }
        //check service orders 
        $service_orders = ServiceOrder::where([['payment_method', 'Iyzico'], ['payment_status', 'pending']])->get();
        foreach ($service_orders as $orderInfo) {
            if (!is_null($orderInfo->conversation_id)) {
                $result = $this->IyzicoPaymentStatus($orderInfo->conversation_id);
                if ($result == 'success') {
                    $orderProcess = new OrderProcessController();
                    // generate an invoice in pdf format
                    $invoice = $orderProcess->generateInvoice($orderInfo);

                    // then, update the invoice field info in database
                    $orderInfo->invoice = $invoice;
                    $orderInfo->payment_status = 'completed';
                    $orderInfo->save();

                    // send a mail to the customer with the invoice
                    $orderProcess->prepareMail($orderInfo);
                }
            }
        }
    }

    /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    ----------- Get iyzico payment status from iyzico server ---------
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
    private function IyzicoPaymentStatus($conversation_id)
    {
        $paymentMethod = OnlineGateway::where('keyword', 'iyzico')->first();
        $paydata = $paymentMethod->convertAutoData();

        $options = new \Iyzipay\Options();
        $options->setApiKey($paydata['api_key']);
        $options->setSecretKey($paydata['secrect_key']);
        if ($paydata['sandbox_status'] == 1) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com"); // production mode
        }

        $request = new \Iyzipay\Request\ReportingPaymentDetailRequest();
        $request->setPaymentConversationId($conversation_id);

        $paymentResponse = \Iyzipay\Model\ReportingPaymentDetail::create($request, $options);
        $result = (array) $paymentResponse;

        foreach ($result as $key => $data) {
            $data = json_decode($data, true);
            if ($data['status'] == 'success' && !empty($data['payments'])) {
                if (is_array($data['payments'])) {
                    if ($data['payments'][0]['paymentStatus'] == 1) {
                        return 'success';
                    } else {
                        return 'not found';
                    }
                } else {
                    return 'not found';
                }
            } else {
                return 'not found';
            }
        }
        return 'not found';
    }
}
