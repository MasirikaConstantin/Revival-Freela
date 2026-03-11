<?php

namespace App\Http\Controllers;

use App\Models\PaymentInvoice;
use Basel\MyFatoorah\MyFatoorah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Payment\MyFatoorahController as PackageMyFatoorahController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MyFatoorahController as ServiceMyFatoorahController;

class MyFatoorahController extends Controller
{
    public function callback(Request $request)
    {
        $type = Session::get('myfatoorah_payment_type');
        if ($type == 'package') {
            $data = new PackageMyFatoorahController();
            $data = $data->successPayment($request);
            Session::forget('myfatoorah_payment_type');
            return redirect($data['url']);
        } elseif ($type == 'service') {
            $data = new ServiceMyFatoorahController();
            $data = $data->notify($request);
            Session::forget('myfatoorah_payment_type');
            return redirect($data['url']);
        }
    }

    public function  cancel(Request $request)
    {
        return redirect()->route('index')->with(['alert-type' => 'error', 'message' => 'Payment failed']);
    }
}
