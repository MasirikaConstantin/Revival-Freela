<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    '/service/*/place-order/razorpay/notify',
    '/service/*/place-order/mercadopago/notify',
    '/service/*/place-order/paytm/notify',
    '/shop/purchase-product/razorpay/notify',
    '/shop/purchase-product/mercadopago/notify',
    '/shop/purchase-product/paytm/notify',
    '/pay/razorpay/notify',
    '/pay/mercadopago/notify',
    '/pay/paytm/notify',
    '/seller/membership/flutterwave/success',
    '/seller/membership/razorpay/success',
    '/seller/membership/mercadopago/notify',
    '/seller/membership/paytm/payment-status',
    '*/phonepe/success',
    '*/phonepe/notify',
    '*/paytabs/success',
    '*/paytabs/notify',
    '*/iyzico/success',
    '*/iyzico/notify',
  ];
}
