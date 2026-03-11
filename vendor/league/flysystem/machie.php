<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::get('invoice', function () {
  return view('frontend.service.invoice');
});



Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');
// cron job for sending expiry mail
Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');
Route::get('/check-payment', 'CronJobController@check_payment')->name('cron.check_payment');

Route::get('myfatoorah/callback', 'MyFatoorahController@callback')->name('myfatoorah_callback');

Route::get('myfatoorah/cancel', 'MyFatoorahController@cancel')->name('myfatoorah_cancel');

Route::get('midtrans/bank/notify', 'MidtransController@onlineBankNotify')->name('midtrans.bank_notify');
Route::get('midtrans/cancel', 'MidtransController@cancel')->name('midtrans.cancel');

Route::get('/change-language', 'FrontEnd\MiscellaneousController@changeLanguage')->name('change_language');

Route::post('/store-subscriber', 'FrontEnd\MiscellaneousController@storeSubscriber')->name('store_subscriber');
Route::middleware('change.lang')->group(function () {
  Route::get('/', 'FrontEnd\HomeController@index')->name('index');
  Route::get('/pricing', 'FrontEnd\HomeController@pricing')->name('pricing');
  Route::get('/services', 'FrontEnd\ClientService\ServiceController@index')->name('services')->middleware('isServices');

  Route::get('/search-service', 'FrontEnd\ClientService\ServiceController@search_service')->name('search-service')->middleware('isServices');

  Route::get('/midtrans/notify/{id}', 'FrontEnd\PaymentGateway\MidtransController@cardNotify')->name('service.place_order.midtrans.notify');

  Route::middleware('isServices')->prefix('/service/{slug}')->group(function () {
    Route::post('/update-wishlist', 'FrontEnd\ClientService\ServiceController@updateWishlist')->name('service.update_wishlist');

    Route::get('/{id}', 'FrontEnd\ClientService\ServiceController@show')->name('service_details');

    Route::post('{id}/payment-form', 'FrontEnd\ClientService\ServiceController@paymentFormCheck')->name('service.payment_form.check');
    Route::get('{id}/payment-form', 'FrontEnd\ClientService\ServiceController@paymentForm')->name('service.payment_form');

    Route::prefix('/place-order')->middleware('Demo')->group(function () {
      Route::post('', 'FrontEnd\ClientService\OrderProcessController@index')->name('service.place_order');

      Route::get('/paypal/notify', 'FrontEnd\PaymentGateway\PayPalController@notify')->name('service.place_order.paypal.notify');

      Route::get('/instamojo/notify', 'FrontEnd\PaymentGateway\InstamojoController@notify')->name('service.place_order.instamojo.notify');

      Route::get('/paystack/notify', 'FrontEnd\PaymentGateway\PaystackController@notify')->name('service.place_order.paystack.notify');

      Route::get('/flutterwave/notify', 'FrontEnd\PaymentGateway\FlutterwaveController@notify')->name('service.place_order.flutterwave.notify');

      Route::post('/razorpay/notify', 'FrontEnd\PaymentGateway\RazorpayController@notify')->name('service.place_order.razorpay.notify');

      Route::get('/mercadopago/notify', 'FrontEnd\PaymentGateway\MercadoPagoController@notify')->name('service.place_order.mercadopago.notify');

      Route::get('/mollie/notify', 'FrontEnd\PaymentGateway\MollieController@notify')->name('service.place_order.mollie.notify');

      Route::post('/paytm/notify', 'FrontEnd\PaymentGateway\PaytmController@notify')->name('service.place_order.paytm.notify');

      Route::any('/phonepe/notify', 'FrontEnd\PaymentGateway\PhonePeController@notify')->name('service.place_order.phonepe.notify');

      Route::get('/yoco/notify', 'FrontEnd\PaymentGateway\YocoController@notify')->name('service.place_order.yoco.notify');

      Route::get('/perfect_money/notify', 'FrontEnd\PaymentGateway\PerfectMoneyController@notify')->name('service.place_order.perfect_money.notify');

      Route::get('/toyyibpay/notify', 'FrontEnd\PaymentGateway\ToyyibpayController@notify')->name('service.place_order.toyyibpay.notify');

      Route::post('/paytabs/notify', 'FrontEnd\PaymentGateway\PaytabsController@notify')->name('service.place_order.paytabs.notify');

      Route::post('/iyzico/notify', 'FrontEnd\PaymentGateway\IyzicoController@notify')->name('service.place_order.iyzico.notify');
      Route::get('/xendit/notify', 'FrontEnd\PaymentGateway\XenditController@notify')->name('service.place_order.xendit.notify');

      Route::get('/complete', 'FrontEnd\ClientService\OrderProcessController@complete')->name('service.place_order.complete');

      Route::get('/cancel', 'FrontEnd\ClientService\OrderProcessController@cancel')->name('service.place_order.cancel');
    });
  });

  Route::post('/service/{id}/store-review', 'FrontEnd\ClientService\ServiceController@storeReview')->name('service.store_review')->middleware('Demo');

  Route::get('/payment-form', 'FrontEnd\PayController@index')->name('payment_form');

  Route::prefix('/pay')->middleware('Demo')->group(function () {
    Route::post('', 'FrontEnd\PayController@pay')->name('pay');

    Route::get('/paypal/notify', 'FrontEnd\PaymentGateway\PayPalController@notify')->name('pay.paypal.notify');

    Route::get('/instamojo/notify', 'FrontEnd\PaymentGateway\InstamojoController@notify')->name('pay.instamojo.notify');

    Route::get('/paystack/notify', 'FrontEnd\PaymentGateway\PaystackController@notify')->name('pay.paystack.notify');

    Route::get('/flutterwave/notify', 'FrontEnd\PaymentGateway\FlutterwaveController@notify')->name('pay.flutterwave.notify');

    Route::post('/razorpay/notify', 'FrontEnd\PaymentGateway\RazorpayController@notify')->name('pay.razorpay.notify');

    Route::get('/mercadopago/notify', 'FrontEnd\PaymentGateway\MercadoPagoController@notify')->name('pay.mercadopago.notify');

    Route::get('/mollie/notify', 'FrontEnd\PaymentGateway\MollieController@notify')->name('pay.mollie.notify');

    Route::post('/paytm/notify', 'FrontEnd\PaymentGateway\PaytmController@notify')->name('pay.paytm.notify');

    Route::get('/complete', 'FrontEnd\PayController@complete')->name('pay.complete');

    Route::get('/cancel', 'FrontEnd\PayController@cancel')->name('pay.cancel');
  });

  Route::prefix('sellers')->group(function () {
    Route::get('/', 'FrontEnd\SellerController@index')->name('frontend.sellers');
    Route::post('contact/message', 'FrontEnd\SellerController@contact')->name('seller.contact.message')->middleware('Demo');
  });
  Route::get('seller/{username}', 'FrontEnd\SellerController@details')->name('frontend.seller.details');
  Route::get('followers/{username}', 'FrontEnd\SellerController@followers')->name('frontend.seller.followers');
  Route::get('followings/{username}', 'FrontEnd\SellerController@following')->name('frontend.seller.followings');
  Route::get('follow-seller/', 'FrontEnd\SellerController@follow_seller')->name('frontend.seller.follow-seller');
  Route::get('unfollow-seller/', 'FrontEnd\SellerController@unfollow_seller')->name('frontend.seller.unfollow-seller');


  Route::prefix('/blog')->group(function () {
    Route::get('', 'FrontEnd\BlogController@index')->name('blog');

    Route::get('/post/{slug}/{id}', 'FrontEnd\BlogController@show')->name('blog.post_details');
  });

  Route::get('/about', 'FrontEnd\AboutUsController@index')->name('aboutus');
  Route::get('/faq', 'FrontEnd\FaqController@faq')->name('faq');

  Route::prefix('/contact')->group(function () {
    Route::get('', 'FrontEnd\ContactController@contact')->name('contact');

    Route::post('/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail')->withoutMiddleware('change.lang')->middleware('Demo');
  });
});


Route::post('/advertisement/{id}/count-view', 'FrontEnd\MiscellaneousController@countAdView');

Route::get('login/facebook/callback', 'FrontEnd\UserController@handleFacebookCallback');
Route::get('login/google/callback', 'FrontEnd\UserController@handleGoogleCallback');
Route::prefix('/user')->middleware(['guest:web', 'change.lang'])->group(function () {
  Route::prefix('/login')->group(function () {
    // user redirect to login page route
    Route::get('', 'FrontEnd\UserController@login')->name('user.login');

    // user login via facebook route
    Route::prefix('/facebook')->group(function () {
      Route::get('', 'FrontEnd\UserController@redirectToFacebook')->name('user.login.facebook');
    });

    // user login via google route
    Route::prefix('/google')->group(function () {
      Route::get('', 'FrontEnd\UserController@redirectToGoogle')->name('user.login.google');
    });
  });

  // user login submit route
  Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit')->withoutMiddleware('change.lang');

  // user forget password route
  Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');

  // send mail to user for forget password route
  Route::post('/send-forget-password-mail', 'FrontEnd\UserController@forgetPasswordMail')->name('user.send_forget_password_mail')->withoutMiddleware('change.lang')->middleware('Demo');

  // reset password route
  Route::get('/reset-password', 'FrontEnd\UserController@resetPassword');

  // user reset password submit route
  Route::post('/reset-password-submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit')->withoutMiddleware('change.lang')->middleware('Demo');

  // user redirect to signup page route
  Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup');

  // user signup submit route
  Route::post('/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit')->withoutMiddleware('change.lang')->middleware('Demo');

  // signup verify route
  Route::get('/signup-verify/{token}', 'FrontEnd\UserController@signupVerify')->withoutMiddleware('change.lang');
});

Route::prefix('/user')->middleware(['auth:web', 'account.status', 'change.lang'])->group(function () {
  // user redirect to dashboard route
  Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');
  Route::get('/followings', 'FrontEnd\UserController@followings')->name('user.followings');

  // edit profile route
  Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');

  // update profile route
  Route::post('/update-profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile')->withoutMiddleware('change.lang')->middleware('Demo');

  Route::middleware('exists.password')->group(function () {
    // change password route
    Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');

    // update password route
    Route::post('/update-password', 'FrontEnd\UserController@updatePassword')->name('user.update_password')->withoutMiddleware('change.lang')->middleware('Demo');
  });

  // service orders route
  Route::get('/service-orders', 'FrontEnd\UserController@serviceOrders')->name('user.service_orders')->middleware('isServices');
  Route::get('service-orders/raise-request/{id}/{status}', 'FrontEnd\UserController@raise_request')->name('user.service_order.raise_request');

  Route::prefix('/service-order/{id}')->middleware(['has.access', 'isServices'])->group(function () {
    // service order details route
    Route::get('/details', 'FrontEnd\UserController@serviceOrderDetails')->name('user.service_order.details');

    // message of service order route
    Route::get('/message', 'FrontEnd\UserController@message')->name('user.service_order.message');

    Route::post('/store-message', 'FrontEnd\UserController@storeMessage')->name('user.service_order.store_message')->withoutMiddleware('has.access')->middleware('Demo');

    Route::post('/confirm-order', 'FrontEnd\UserController@confirm_order')->name('user.service_order.confirm_order')->withoutMiddleware('has.access')->middleware('Demo');
  });

  Route::middleware('isServices')->prefix('/service-wishlist')->group(function () {
    // service wishlist route
    Route::get('', 'FrontEnd\UserController@serviceWishlist')->name('user.service_wishlist');

    // remove service from wishlist route
    Route::post('/remove-service/{service_id}', 'FrontEnd\UserController@removeService')->name('user.service_wishlist.remove_service')->middleware('Demo');
  });

  // support tickets route
  Route::middleware('isSupportTicket', 'Demo')->prefix('/support-tickets')->group(function () {
    Route::get('', 'FrontEnd\UserController@tickets')->name('user.support_tickets');

    Route::get('/create-ticket', 'FrontEnd\UserController@createTicket')->name('user.support_tickets.create');

    Route::post('/store-temp-file', 'FrontEnd\UserController@storeTempFile')->name('user.support_tickets.store_temp_file');

    Route::post('/store-ticket', 'FrontEnd\UserController@storeTicket')->name('user.support_tickets.store');
  });

  Route::get('/support-ticket/{id}/conversation', 'FrontEnd\UserController@ticketConversation')->name('user.support_ticket.conversation')->middleware('isSupportTicket');

  Route::post('/support-ticket/{id}/reply', 'FrontEnd\UserController@ticketReply')->name('user.support_ticket.reply')->middleware('isSupportTicket')->middleware('Demo');

  // user logout attempt route
  Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout')->withoutMiddleware('change.lang');
});

// service unavailable route
Route::get('/service-unavailable', 'FrontEnd\MiscellaneousController@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');


/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/admin')->middleware('guest:admin')->group(function () {
  // admin redirect to login page route
  Route::get('/', 'BackEnd\AdminController@login')->name('admin.login');

  // admin login attempt route
  Route::post('/auth', 'BackEnd\AdminController@authentication')->name('admin.auth');

  // admin forget password route
  Route::get('/forget-password', 'BackEnd\AdminController@forgetPassword')->name('admin.forget_password');

  // send mail to admin for forget password route
  Route::post('/mail-for-forget-password', 'BackEnd\AdminController@forgetPasswordMail')->name('admin.mail_for_forget_password');
});



/*
|--------------------------------------------------------------------------
| Custom Page Route For UI
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', 'FrontEnd\PageController@page')->name('dynamic_page')->middleware('change.lang');

// // fallback route
// Route::fallback(function () {
//   //
// })->middleware('change.lang');
