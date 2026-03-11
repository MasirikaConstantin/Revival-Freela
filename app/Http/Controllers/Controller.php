<?php

namespace App\Http\Controllers;

use App\Models\BasicSettings\Basic;
use PDF;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function getCurrencyInfo()
  {
    $baseCurrencyInfo = Basic::select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
      ->firstOrFail();

    return $baseCurrencyInfo;
  }
  public function makeInvoice($request, $key, $member, $password, $amount, $payment_method, $phone, $base_currency_symbol_position, $base_currency_symbol, $base_currency_text, $order_id, $package_title, $membership)
  {
    $file_name = uniqid($key) . ".pdf";
    $pdf = PDF::loadView('pdf.membership', compact('request', 'member', 'password', 'amount', 'payment_method', 'phone', 'base_currency_symbol_position', 'base_currency_symbol', 'base_currency_text', 'order_id', 'package_title', 'membership'));
    $output = $pdf->output();
    @mkdir(public_path('assets/front/invoices/'), 0775, true);
    file_put_contents(public_path('assets/front/invoices/') . $file_name, $output);
    return $file_name;
  }
}
