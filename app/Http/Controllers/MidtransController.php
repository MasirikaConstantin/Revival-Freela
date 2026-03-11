<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Payment\MidtransController as PackageMidtransController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MidtransController as ServiceMidtransController;

class MidtransController extends Controller
{
    public function onlineBankNotify(Request $request)
    {
        $cancel_url = route('midtrans.cancel');
        $token = Session::get('token');
        $payment_type = Session::get('midtrans_payment_type');

        if ($request->status_code == 200 && $token == $request->order_id) {
            if ($payment_type == 'package') {
                $packageOrder = new PackageMidtransController();
                $data = $packageOrder->OnlineBackNotify($request->order_id);
                return redirect($data['url']);
            } elseif ($payment_type == 'service') {
                $serviceOrder = new ServiceMidtransController();
                $data = $serviceOrder->OnlineBackNotify($request->order_id);
                return redirect($data['url']);
            }
        } else {
            //redirect to cancel url 
            Session::flash("error", 'Payment Canceled');
            return redirect($cancel_url);
        }
    }

    public function cancel()
    {
        return redirect()->route('front.index');
    }
}
