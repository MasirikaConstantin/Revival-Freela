<?php

use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*************************************************
         ********************** PhonePe ******************
         *************************************************/
        $data = OnlineGateway::where('keyword', 'phonepe')->first();
        if (empty($data)) {
            $information = [
                'sandbox_status' => null,
                'merchant_id' => null,
                'salt_key' => null,
                'salt_index' => null
            ];
            $data = [
                'name' => 'Phonepe',
                'keyword' => 'phonepe',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($data);
        }

        /*************************************************
         ********************** Xendit ******************
         *************************************************/
        $data = OnlineGateway::where('keyword', 'xendit')->first();
        if (empty($data)) {
            $information = [
                'secret_key' => null,
            ];
            $data = [
                'name' => 'Xendit',
                'keyword' => 'xendit',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($data);
        }
        /*************************************************
         ********************** Perfect Money ******************
         *************************************************/
        $data = OnlineGateway::where('keyword', 'perfect_money')->first();
        if (empty($data)) {
            $information = [
                'perfect_money_wallet_id' => null
            ];
            $data = [
                'name' => 'Perfect Money',
                'keyword' => 'perfect_money',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($data);
        }

        /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            --------- Myfatoorah -----------------
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $myfatoorah = OnlineGateway::where('keyword', 'myfatoorah')->first();
        if (empty($myfatoorah)) {
            $information = [
                'sandbox_status' => null,
                'token' => null
            ];
            $myfatoorah = [
                'name' => 'Myfatoorah',
                'keyword' => 'myfatoorah',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($myfatoorah);
        }

        /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            --------- Yoco -----------------
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $yoco = OnlineGateway::where('keyword', 'yoco')->first();
        if (empty($yoco)) {
            $information = [
                'secret_key' => null
            ];
            $yoco = [
                'name' => 'Yoco',
                'keyword' => 'yoco',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($yoco);
        }

        /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            --------- toyyibpay -----------------
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $toyyibpay = OnlineGateway::where('keyword', 'toyyibpay')->first();
        if (empty($toyyibpay)) {
            $information = [
                'sandbox_status' => null,
                'secret_key' => null,
                'category_code' => null
            ];
            $toyyibpay = [
                'name' => 'Toyyibpay',
                'keyword' => 'toyyibpay',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($toyyibpay);
        }

        /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            --------- paytabs -----------------
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $paytabs = OnlineGateway::where('keyword', 'paytabs')->first();
        if (empty($paytabs)) {
            $information = [
                'profile_id' => null,
                'server_key' => null,
                'api_endpoint' => null,
                'country' => null
            ];
            $paytabs = [
                'name' => 'Paytabs',
                'keyword' => 'paytabs',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($paytabs);
        }

        /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            --------- iyzico -----------------
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $iyzico = OnlineGateway::where('keyword', 'iyzico')->first();
        if (empty($iyzico)) {
            $information = [
                'api_key' => null,
                'secret_key' => null,
                'sandbox_status' => null
            ];
            $iyzico = [
                'name' => 'Iyzico',
                'keyword' => 'iyzico',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($iyzico);
        }

        /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            --------- midtrans -----------------
            ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $midtrans = OnlineGateway::where('keyword', 'midtrans')->first();
        if (empty($midtrans)) {
            $information = [
                'server_key' => null,
                'is_production' => null
            ];
            $midtrans = [
                'name' => 'Midtrans',
                'keyword' => 'midtrans',
                'information' => json_encode($information, true),
                'status' => 0
            ];
            OnlineGateway::create($midtrans);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // phonepe
        $phonepe = OnlineGateway::where('keyword', 'phonepe')->first();
        if ($phonepe) {
            $phonepe->delete();
        }
        // xendit
        $xendit = OnlineGateway::where('keyword', 'xendit')->first();
        if ($xendit) {
            $xendit->delete();
        }
        // perfect_money
        $perfect_money = OnlineGateway::where('keyword', 'perfect_money')->first();
        if ($perfect_money) {
            $perfect_money->delete();
        }
        // myfatoorah
        $myfatoorah = OnlineGateway::where('keyword', 'myfatoorah')->first();
        if ($myfatoorah) {
            $myfatoorah->delete();
        }
        // yoco
        $yoco = OnlineGateway::where('keyword', 'yoco')->first();
        if ($yoco) {
            $yoco->delete();
        }
        // toyyibpay
        $toyyibpay = OnlineGateway::where('keyword', 'toyyibpay')->first();
        if ($toyyibpay) {
            $toyyibpay->delete();
        }
        // paytabs
        $paytabs = OnlineGateway::where('keyword', 'paytabs')->first();
        if ($paytabs) {
            $paytabs->delete();
        }
        // iyzico
        $iyzico = OnlineGateway::where('keyword', 'iyzico')->first();
        if ($iyzico) {
            $iyzico->delete();
        }
        // midtrans
        $midtrans = OnlineGateway::where('keyword', 'midtrans')->first();
        if ($midtrans) {
            $midtrans->delete();
        }
    }
};
