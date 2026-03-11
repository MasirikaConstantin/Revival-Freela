<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::prefix('seller')->middleware('change.lang', 'guest:seller')->group(function () {
  Route::get('/signup', 'Seller\SellerController@signup')->name('seller.signup');
  Route::post('/signup/submit', 'Seller\SellerController@create')->name('seller.signup_submit')->middleware('Demo');
  Route::get('/login', 'Seller\SellerController@login')->name('seller.login');
  Route::post('/login/submit', 'Seller\SellerController@authentication')->name('seller.login_submit');

  Route::get('/email/verify', 'Seller\SellerController@confirm_email');

  Route::get('/forget-password', 'Seller\SellerController@forget_passord')->name('seller.forget.password');
  Route::post('/send-forget-mail', 'Seller\SellerController@forget_mail')->name('seller.forget.mail')->middleware('Demo');
  Route::get('/reset-password', 'Seller\SellerController@reset_password')->name('seller.reset.password');
  Route::post('/update-forget-password', 'Seller\SellerController@update_password')->name('seller.update-forget-password');
});


Route::prefix('seller')->middleware('auth:seller', 'EmailStatus:seller', 'Deactive:seller', 'Demo')->group(function () {
  Route::get('dashboard', 'Seller\SellerController@dashboard')->name('seller.dashboard');
  Route::get('monthly-income', 'Seller\SellerController@monthly_income')->name('seller.monthly_income');
  Route::get('/change-password', 'Seller\SellerController@change_password')->name('seller.change_password');
  Route::post('/update-password', 'Seller\SellerController@updated_password')->name('seller.update_password');
  Route::get('/edit-profile', 'Seller\SellerController@edit_profile')->name('seller.edit.profile');
  Route::post('/profile/update', 'Seller\SellerController@update_profile')->name('seller.update_profile');
  Route::get('/logout', 'Seller\SellerController@logout')->name('seller.logout');
  Route::get('/recipient-mail', 'Seller\SellerController@recipient_mail')->name('seller.recipient_mail');
  Route::post('/update/recipient-mail', 'Seller\SellerController@update_recipient_mail')->name('seller.update_recipient_mail');

  // change vendor-panel theme (dark/light) route
  Route::post('/change-theme', 'Seller\SellerController@changeTheme')->name('seller.change_theme');

  // form route
  Route::get('/forms', 'Seller\FormController@index')->name('seller.service_management.forms');

  Route::post('/store-form', 'Seller\FormController@store')->name('seller.service_management.store_form')->middleware('limit_check:form');

  Route::prefix('/form')->group(function () {
    Route::get('/{id}/input', 'Seller\FormInputController@manageInput')->name('seller.service_management.form.input');

    Route::post('/{id}/store-input', 'Seller\FormInputController@storeInput')->name('seller.service_management.form.store_input');

    Route::get('/{form_id}/edit-input/{input_id}', 'Seller\FormInputController@editInput')->name('seller.service_management.form.edit_input');

    Route::post('/update-input/{id}', 'Seller\FormInputController@updateInput')->name('seller.service_management.form.update_input');

    Route::post('/delete-input/{id}', 'Seller\FormInputController@destroyInput')->name('seller.service_management.form.delete_input');

    Route::post('/sort-input', 'Seller\FormInputController@sortInput')->name('seller.service_management.form.sort_input');
  });

  Route::post('/update-form', 'Seller\FormController@update')->name('seller.service_management.update_form')->middleware('limit_check:form');

  Route::post('/delete-form/{id}', 'Seller\FormController@destroy')->name('seller.service_management.delete_form');

  //service routes are goes here
  Route::prefix('service-management')->group(function () {
    Route::get('/services', 'Seller\ServiceController@index')->name('seller.service_management.services');
    Route::get('/create-service', 'Seller\ServiceController@create')->name('seller.service_management.create_service');

    Route::get('/category/{id}/get-subcategory', 'Seller\ServiceController@getSubcategory');

    Route::post('/upload-slider-image', 'Seller\ServiceController@uploadImage')->name('seller.service_management.upload_slider_image');

    Route::post('/remove-slider-image', 'Seller\ServiceController@removeImage')->name('seller.service_management.remove_slider_image');

    Route::post('/store-service', 'Seller\ServiceController@store')->name('seller.service_management.store_service')->middleware('limit_check:service');

    Route::post('/service/{id}/update-featured-status', 'Seller\ServiceController@updateFeaturedStatus')->name('seller.service_management.service.update_featured_status')->middleware('limit_check:service-featured,except-json');

    Route::get('/edit-service/{id}', 'Seller\ServiceController@edit')->name('seller.service_management.edit_service');

    Route::post('/detach-slider-image', 'Seller\ServiceController@detachImage')->name('seller.service_management.detach_slider_image');

    Route::post('/update-service/{id}', 'Seller\ServiceController@update')->name('seller.service_management.update_service');

    Route::post('/delete-service/{id}', 'Seller\ServiceController@destroy')->name('seller.service_management.delete_service');

    Route::post('/bulk-delete-service', 'Seller\ServiceController@bulkDestroy')->name('seller.service_management.bulk_delete_service');

    // package route
    Route::prefix('/service')->group(function () {
      Route::get('/{id}/packages', 'Seller\PackageController@index')->name('seller.service_management.service.packages');

      Route::post('/store-package', 'Seller\PackageController@store')->name('seller.service_management.service.store_package');

      Route::post('/update-package', 'Seller\PackageController@update')->name('seller.service_management.service.update_package');

      Route::post('/delete-package/{id}', 'Seller\PackageController@destroy')->name('seller.service_management.service.delete_package');

      Route::post('/bulk-delete-package', 'Seller\PackageController@bulkDestroy')->name('seller.service_management.service.bulk_delete_package');
    });

    // addon route
    Route::prefix('/service')->group(function () {
      Route::get('/{id}/addons', 'Seller\AddonController@index')->name('seller.service_management.service.addons');

      Route::post('/store-addon', 'Seller\AddonController@store')->name('seller.service_management.service.store_addon');

      Route::post('/update-addon', 'Seller\AddonController@update')->name('seller.service_management.service.update_addon');

      Route::post('/delete-addon/{id}', 'Seller\AddonController@destroy')->name('seller.service_management.service.delete_addon');

      Route::post('/bulk-delete-addon', 'Seller\AddonController@bulkDestroy')->name('seller.service_management.service.bulk_delete_addon');
    });

    // faq route
    Route::prefix('/service')->group(function () {
      Route::get('/{id}/faqs', 'Seller\FaqController@index')->name('seller.service_management.service.faqs');

      Route::post('/store-faq', 'Seller\FaqController@store')->name('seller.service_management.service.store_faq');

      Route::post('/update-faq', 'Seller\FaqController@update')->name('seller.service_management.service.update_faq');

      Route::post('/delete-faq/{id}', 'Seller\FaqController@destroy')->name('seller.service_management.service.delete_faq');

      Route::post('/bulk-delete-faq', 'Seller\FaqController@bulkDestroy')->name('seller.service_management.service.bulk_delete_faq');
    });
  });

  Route::prefix('/service-orders')->group(function () {
    Route::get('', 'Seller\OrderController@orders')->name('seller.service_orders');

    Route::prefix('/order/{id}')->group(function () {
      Route::post('/update-payment-status', 'Seller\OrderController@updatePaymentStatus')->name('seller.service_order.update_payment_status');

      Route::get('/details', 'Seller\OrderController@show')->name('seller.service_order.details');

      Route::get('/message', 'Seller\OrderController@message')->name('seller.service_order.message');
      Route::post('/send-mail', 'Seller\OrderController@sendMail')->name('seller.service_order.sendmail');

      Route::post('/store-message', 'Seller\OrderController@storeMessage')->name('seller.service_order.store_message');

      Route::post('/delete', 'Seller\OrderController@destroy')->name('seller.service_order.delete');
    });

    Route::post('/bulk-delete', 'Seller\OrderController@bulkDestroy')->name('seller.service_orders.bulk_delete');

    // service orders report route
    Route::get('/report', 'Seller\OrderController@report')->name('seller.service_orders.report');

    Route::get('/export-report', 'Seller\OrderController@exportReport')->name('seller.service_orders.export_report');
  });


  Route::get('/subscription-log', 'Seller\SellerController@subscription_log')->name('seller.subscription_log');

  //vendor package extend route
  Route::get('/package-list', 'Seller\BuyPlanController@index')->name('seller.plan.extend.index');
  Route::get('/package/checkout/{package_id}', 'Seller\BuyPlanController@checkout')->name('seller.plan.extend.checkout');
  Route::post('/package/checkout', 'Seller\SellerCheckoutController@checkout')->name('seller.plan.checkout');

  Route::post('/payment/instructions', 'Seller\SellerCheckoutController@paymentInstruction')->name('seller.payment.instructions');


  //checkout payment gateway routes
  Route::prefix('membership')->group(function () {
    Route::get('paypal/success', "Payment\PaypalController@successPayment")->name('membership.paypal.success');
    Route::get('paypal/cancel', "Payment\PaypalController@cancelPayment")->name('membership.paypal.cancel');
    Route::get('stripe/cancel', "Payment\StripeController@cancelPayment")->name('membership.stripe.cancel');
    Route::post('paytm/payment-status', "Payment\PaytmController@paymentStatus")->name('membership.paytm.status');
    Route::get('paystack/success', 'Payment\PaystackController@successPayment')->name('membership.paystack.success');
    Route::get('mercadopago/cancel', 'Payment\MercadopagoController@cancelPayment')->name('membership.mercadopago.cancel');
    Route::get('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('membership.mercadopago.success');
    Route::post('razorpay/success', 'Payment\RazorpayController@successPayment')->name('membership.razorpay.success');
    Route::post('razorpay/cancel', 'Payment\RazorpayController@cancelPayment')->name('membership.razorpay.cancel');
    Route::get('instamojo/success', 'Payment\InstamojoController@successPayment')->name('membership.instamojo.success');
    Route::post('instamojo/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.instamojo.cancel');
    Route::post('flutterwave/success', 'Payment\FlutterWaveController@successPayment')->name('membership.flutterwave.success');
    Route::post('flutterwave/cancel', 'Payment\FlutterWaveController@cancelPayment')->name('membership.flutterwave.cancel');

    Route::get('/mollie/success', 'Payment\MollieController@successPayment')->name('membership.mollie.success');
    Route::post('mollie/cancel', 'Payment\MollieController@cancelPayment')->name('membership.mollie.cancel');

    Route::get('anet/cancel', 'Payment\AuthorizeController@cancelPayment')->name('membership.anet.cancel');

    Route::any('/phonepe/success', 'Payment\PhonePeController@successPayment')->name('membership.phonepe.success');
    Route::get('phonepe/cancel', 'Payment\PhonePeController@cancelPayment')->name('membership.phonepe.cancel');

    Route::any('/yoco/success', 'Payment\YocoController@successPayment')->name('membership.yoco.success');
    Route::get('yoco/cancel', 'Payment\YocoController@cancelPayment')->name('membership.yoco.cancel');

    Route::get('/toyyibpay/success', 'Payment\ToyyibpayController@successPayment')->name('membership.toyyibpay.success');
    Route::get('toyyibpay/cancel', 'Payment\ToyyibpayController@cancelPayment')->name('membership.toyyibpay.cancel');

    Route::get('/perfect_money/success', 'Payment\PerfectMoneyController@successPayment')->name('membership.perfect_money.success');
    Route::get('perfect_money/cancel', 'Payment\PerfectMoneyController@cancelPayment')->name('membership.perfect_money.cancel');

    Route::post('/paytabs/success', 'Payment\PaytabsController@successPayment')->name('membership.paytabs.success');
    Route::get('paytabs/cancel', 'Payment\PaytabsController@cancelPayment')->name('membership.paytabs.cancel');

    Route::post('/iyzico/success', 'Payment\IyzicoController@successPayment')->name('membership.iyzico.success');
    Route::get('iyzico/cancel', 'Payment\IyzicoController@cancelPayment')->name('membership.iyzico.cancel');

    Route::get('/myfatoorah/success', 'Payment\MyFatoorahController@successPayment')->name('membership.myfatoorah.success');
    Route::get('myfatoorah/cancel', 'Payment\MyFatoorahController@cancelPayment')->name('membership.myfatoorah.cancel');

    Route::get('/midtrans/success/{id}', 'Payment\MidtransController@cardNotify')->name('membership.midtrans.success');
    Route::get('midtrans/cancel', 'Payment\MidtransController@cancelPayment')->name('membership.midtrans.cancel');

    Route::get('/xendit/success', 'Payment\XenditController@successPayment')->name('membership.xendit.success');
    Route::get('xendit/cancel', 'Payment\XenditController@cancelPayment')->name('membership.xendit.cancel');

    Route::get('/offline/success', 'Front\CheckoutController@offlineSuccess')->name('membership.offline.success');
    Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('membership.trial.success');

    Route::get('/online/success', 'Seller\SellerCheckoutController@onlineSuccess')->name('success.page');
    Route::get('/offline/success', 'Seller\SellerCheckoutController@offlineSuccess')->name('seller.offline-success');
  });

  Route::get('/payment-log', 'Seller\SellerController@payment_log')->name('seller.payment_log');
  Route::get('/transcation', 'Seller\SellerController@transcation')->name('seller.transcation');

  // qr-code route start
  Route::prefix('/qr-codes')->group(function () {
    Route::get('/generate-code', 'Seller\QrCodeController@generate')->name('seller.qr_codes.generate_code')->middleware('limit_check:qr_code_status');

    Route::post('/regenerate-code', 'Seller\QrCodeController@regenerate')->name('seller.qr_codes.regenerate_code');

    Route::post('/clear', 'Seller\QrCodeController@clearFilters')->name('seller.qr_codes.clear');

    Route::post('/save-qr', 'Seller\QrCodeController@saveQrCode')->name('seller.qr_codes.save_qr')->middleware('limit_check:qr_code_save');

    Route::get('/saved-codes', 'Seller\QrCodeController@savedCodes')->name('seller.qr_codes.saved_codes');

    Route::post('/delete-qr/{id}', 'Seller\QrCodeController@deleteQrCode')->name('seller.qr_codes.delete_qr');

    Route::post('/bulk-delete-qr', 'Seller\QrCodeController@bulkDeleteQrCode')->name('seller.qr_codes.bulk_delete_qr');
  });
  // qr-code route end

  Route::prefix('withdraw')->group(function () {
    Route::get('/', 'Seller\SellerWithdrawController@index')->name('seller.withdraw');
    Route::get('/create', 'Seller\SellerWithdrawController@create')->name('seller.withdraw.create');
    Route::get('/get-method/input/{id}', 'Seller\SellerWithdrawController@get_inputs');

    Route::get('/balance-calculation/{method}/{amount}', 'Seller\SellerWithdrawController@balance_calculation');

    Route::post('/send-request', 'Seller\SellerWithdrawController@send_request')->name('seller.withdraw.send-request');
    Route::post('/witdraw/bulk-delete', 'Seller\SellerWithdrawController@bulkDelete')->name('seller.witdraw.bulk_delete_withdraw');
    Route::post('/witdraw/delete', 'Seller\SellerWithdrawController@Delete')->name('seller.witdraw.delete_withdraw');
  });

  #====support tickets ============
  Route::prefix('support/ticket')->group(function () {
    Route::get('create', 'Seller\SupportTicketController@create')->name('seller.support_ticket.create');
    Route::post('store', 'Seller\SupportTicketController@store')->name('seller.support_ticket.store');
    Route::get('', 'Seller\SupportTicketController@index')->name('seller.support_tickets');

    Route::get('message/{id}', 'Seller\SupportTicketController@message')->name('seller.support_tickets.message');

    Route::post('zip-upload', 'Seller\SupportTicketController@zip_file_upload')->name('seller.support_ticket.zip_file.upload');

    Route::post('reply/{id}', 'Seller\SupportTicketController@ticketreply')->name('seller.support_ticket.reply');

    Route::post('delete/{id}', 'Seller\SupportTicketController@delete')->name('seller.support_tickets.delete');
    Route::post('bulk/delete/', 'Seller\SupportTicketController@bulk_delete')->name('seller.support_tickets.bulk_delete');
  });
});
