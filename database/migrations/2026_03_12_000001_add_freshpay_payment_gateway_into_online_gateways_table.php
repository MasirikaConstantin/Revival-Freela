<?php

use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = OnlineGateway::where('keyword', 'freshpay')->first();

        if (empty($data)) {
            $information = [
                'merchant_id' => null,
                'merchant_secrete' => null,
                'firstname' => null,
                'lastname' => null,
                'email' => null
            ];

            OnlineGateway::create([
                'name' => 'Freshpay',
                'keyword' => 'freshpay',
                'information' => json_encode($information, true),
                'status' => 0
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $data = OnlineGateway::where('keyword', 'freshpay')->first();

        if ($data) {
            $data->delete();
        }
    }
};
