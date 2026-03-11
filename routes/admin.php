<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/admin')->middleware('auth:admin', 'Demo')->group(function () {
  // admin redirect to dashboard route
  Route::get('/dashboard', 'BackEnd\AdminController@redirectToDashboard')->name('admin.dashboard');
  Route::get('/transcation', 'BackEnd\AdminController@transcation')->name('admin.transcation')->middleware('permission:Transactions');

  // change admin-panel theme (dark/light) route
  Route::get('/change-theme', 'BackEnd\AdminController@changeTheme')->name('admin.change_theme');

  Route::get('/monthly-profit', 'BackEnd\AdminController@monthly_profit')->name('admin.monthly_profit');
  Route::get('/monthly-earning', 'BackEnd\AdminController@monthly_earning')->name('admin.monthly_earning');

  // admin profile settings route start
  Route::get('/edit-profile', 'BackEnd\AdminController@editProfile')->name('admin.edit_profile');

  Route::post('/update-profile', 'BackEnd\AdminController@updateProfile')->name('admin.update_profile');

  Route::get('/change-password', 'BackEnd\AdminController@changePassword')->name('admin.change_password');

  Route::post('/update-password', 'BackEnd\AdminController@updatePassword')->name('admin.update_password');
  // admin profile settings route end

  // admin logout attempt route
  Route::get('/logout', 'BackEnd\AdminController@logout')->name('admin.logout');

  // Payment Log
  Route::get('/subscription-log', 'BackEnd\PaymentLogController@index')->name('admin.payment-log.index')->middleware('permission:Subscription Log');
  Route::post('/payment-log/update', 'BackEnd\PaymentLogController@update')->name('admin.payment-log.update');

  Route::prefix('package')->group(function () {
    // Package Settings routes
    Route::get('/settings', 'BackEnd\PackageController@settings')->name('admin.package.settings');
    Route::post('/settings', 'BackEnd\PackageController@updateSettings')->name('admin.package.settings');
    // Package routes
    Route::get('packages', 'BackEnd\PackageController@index')->name('admin.package.index');
    Route::post('package/upload', 'BackEnd\PackageController@upload')->name('admin.package.upload');
    Route::post('package/store', 'BackEnd\PackageController@store')->name('admin.package.store');
    Route::get('package/{id}/edit', 'BackEnd\PackageController@edit')->name('admin.package.edit');
    Route::post('package/update', 'BackEnd\PackageController@update')->name('admin.package.update');
    Route::post('package/{id}/uploadUpdate', 'BackEnd\PackageController@uploadUpdate')->name('admin.package.uploadUpdate');
    Route::post('package/delete', 'BackEnd\PackageController@delete')->name('admin.package.delete');
    Route::post('package/bulk-delete', 'BackEnd\PackageController@bulkDelete')->name('admin.package.bulk.delete');
  });


  // admin management route start
  Route::prefix('/admin-management')->middleware('permission:Admin Management')->group(function () {
    // role-permission route
    Route::get('/role-permissions', 'BackEnd\Administrator\RolePermissionController@index')->name('admin.admin_management.role_permissions');

    Route::post('/store-role', 'BackEnd\Administrator\RolePermissionController@store')->name('admin.admin_management.store_role');

    Route::get('/role/{id}/permissions', 'BackEnd\Administrator\RolePermissionController@permissions')->name('admin.admin_management.role.permissions');

    Route::post('/role/{id}/update-permissions', 'BackEnd\Administrator\RolePermissionController@updatePermissions')->name('admin.admin_management.role.update_permissions');

    Route::post('/update-role', 'BackEnd\Administrator\RolePermissionController@update')->name('admin.admin_management.update_role');

    Route::post('/delete-role/{id}', 'BackEnd\Administrator\RolePermissionController@destroy')->name('admin.admin_management.delete_role');

    // registered admin route
    Route::get('/registered-admins', 'BackEnd\Administrator\SiteAdminController@index')->name('admin.admin_management.registered_admins');

    Route::post('/store-admin', 'BackEnd\Administrator\SiteAdminController@store')->name('admin.admin_management.store_admin');

    Route::post('/update-status/{id}', 'BackEnd\Administrator\SiteAdminController@updateStatus')->name('admin.admin_management.update_status');

    Route::post('/update-admin', 'BackEnd\Administrator\SiteAdminController@update')->name('admin.admin_management.update_admin');

    Route::post('/delete-admin/{id}', 'BackEnd\Administrator\SiteAdminController@destroy')->name('admin.admin_management.delete_admin');
  });
  // admin management route end


  // language management route start
  Route::prefix('/language-management')->middleware('permission:Language Management')->group(function () {
    Route::get('', 'BackEnd\LanguageController@index')->name('admin.language_management');
    Route::get('settings', 'BackEnd\LanguageController@settings')->name('admin.language_management.settings');
    Route::post('settings/update', 'BackEnd\LanguageController@settingsUpdate')->name('admin.language_management.settings.update');
    Route::post('add-keyword', 'BackEnd\LanguageController@addKeyword')->name('admin.language_management.add_keyword');
    Route::post('/store', 'BackEnd\LanguageController@store')->name('admin.language_management.store');

    Route::post('/{id}/make-default-language', 'BackEnd\LanguageController@makeDefault')->name('admin.language_management.make_default_language');

    Route::post('/update', 'BackEnd\LanguageController@update')->name('admin.language_management.update');

    Route::get('/{id}/edit-keyword', 'BackEnd\LanguageController@editKeyword')->name('admin.language_management.edit_keyword');

    Route::post('/{id}/update-keyword', 'BackEnd\LanguageController@updateKeyword')->name('admin.language_management.update_keyword');

    Route::post('/{id}/delete', 'BackEnd\LanguageController@destroy')->name('admin.language_management.delete');
  });
  // language management route end


  Route::prefix('/basic-settings')->middleware('permission:Basic Settings')->group(function () {
    // basic settings favicon route
    Route::get('/favicon', 'BackEnd\BasicSettings\BasicController@favicon')->name('admin.basic_settings.favicon');

    Route::post(
      '/update-favicon',
      'BackEnd\BasicSettings\BasicController@updateFavicon'
    )->name('admin.basic_settings.update_favicon');

    // basic settings logo route
    Route::get('/logo', 'BackEnd\BasicSettings\BasicController@logo')->name('admin.basic_settings.logo');

    Route::post('/update-logo', 'BackEnd\BasicSettings\BasicController@updateLogo')->name('admin.basic_settings.update_logo');

    // basic settings information route
    Route::get('/information', 'BackEnd\BasicSettings\BasicController@information')->name('admin.basic_settings.information');

    Route::post('/update-info', 'BackEnd\BasicSettings\BasicController@updateInfo')->name('admin.basic_settings.update_info');

    // basic settings timezone route
    Route::get('/timezone', 'BackEnd\BasicSettings\BasicController@timezone')->name('admin.basic_settings.timezone');

    Route::post('/update-timezone', 'BackEnd\BasicSettings\BasicController@updateTimezone')->name('admin.basic_settings.update_timezone');

    // basic settings (theme & home) route
    Route::get('/theme-and-home', 'BackEnd\BasicSettings\BasicController@themeAndHome')->name('admin.basic_settings.theme_and_home');

    Route::post('/update-theme-and-home', 'BackEnd\BasicSettings\BasicController@updateThemeAndHome')->name('admin.basic_settings.update_theme_and_home');

    // basic settings currency route
    Route::get('/currency', 'BackEnd\BasicSettings\BasicController@currency')->name('admin.basic_settings.currency');

    Route::post('/update-currency', 'BackEnd\BasicSettings\BasicController@updateCurrency')->name('admin.basic_settings.update_currency');

    // basic settings appearance route
    Route::get('/appearance', 'BackEnd\BasicSettings\BasicController@appearance')->name('admin.basic_settings.appearance');

    Route::post('/update-appearance', 'BackEnd\BasicSettings\BasicController@updateAppearance')->name('admin.basic_settings.update_appearance');

    // basic settings mail route start
    Route::get('/mail-from-admin', 'BackEnd\BasicSettings\BasicController@mailFromAdmin')->name('admin.basic_settings.mail_from_admin');

    Route::post('/update-mail-from-admin', 'BackEnd\BasicSettings\BasicController@updateMailFromAdmin')->name('admin.basic_settings.update_mail_from_admin');

    Route::get('/mail-to-admin', 'BackEnd\BasicSettings\BasicController@mailToAdmin')->name('admin.basic_settings.mail_to_admin');

    Route::post('/update-mail-to-admin', 'BackEnd\BasicSettings\BasicController@updateMailToAdmin')->name('admin.basic_settings.update_mail_to_admin');

    Route::get('/mail-templates', 'BackEnd\BasicSettings\MailTemplateController@index')->name('admin.basic_settings.mail_templates');

    Route::get('/edit-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@edit')->name('admin.basic_settings.edit_mail_template');

    Route::post('/update-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@update')->name('admin.basic_settings.update_mail_template');
    // basic settings mail route end

    // basic settings breadcrumb route
    Route::get('/breadcrumb', 'BackEnd\BasicSettings\BasicController@breadcrumb')->name('admin.basic_settings.breadcrumb');

    Route::post('/update-breadcrumb', 'BackEnd\BasicSettings\BasicController@updateBreadcrumb')->name('admin.basic_settings.update_breadcrumb');

    // basic settings page-headings route
    Route::get('/page-headings', 'BackEnd\BasicSettings\PageHeadingController@pageHeadings')->name('admin.basic_settings.page_headings');

    Route::post('/update-page-headings', 'BackEnd\BasicSettings\PageHeadingController@updatePageHeadings')->name('admin.basic_settings.update_page_headings');

    // basic settings plugins route start
    Route::get('/plugins', 'BackEnd\BasicSettings\BasicController@plugins')->name('admin.basic_settings.plugins');

    Route::post('/update-recaptcha', 'BackEnd\BasicSettings\BasicController@updateRecaptcha')->name('admin.basic_settings.update_recaptcha');

    Route::post('/update-disqus', 'BackEnd\BasicSettings\BasicController@updateDisqus')->name('admin.basic_settings.update_disqus');

    Route::post('/update-whatsapp', 'BackEnd\BasicSettings\BasicController@updateWhatsApp')->name('admin.basic_settings.update_whatsapp');

    Route::post('/update-facebook', 'BackEnd\BasicSettings\BasicController@updateFacebook')->name('admin.basic_settings.update_facebook');

    Route::post('/update-google', 'BackEnd\BasicSettings\BasicController@updateGoogle')->name('admin.basic_settings.update_google');

    Route::post('/update-pusher', 'BackEnd\BasicSettings\BasicController@updatePusher')->name('admin.basic_settings.update_pusher');
    // basic settings plugins route end

    // basic settings seo route
    Route::get('/seo', 'BackEnd\BasicSettings\SEOController@index')->name('admin.basic_settings.seo');

    Route::post('/update-seo', 'BackEnd\BasicSettings\SEOController@update')->name('admin.basic_settings.update_seo');

    // basic settings maintenance-mode route
    Route::get('/maintenance-mode', 'BackEnd\BasicSettings\BasicController@maintenance')->name('admin.basic_settings.maintenance_mode');

    Route::post('/update-maintenance-mode', 'BackEnd\BasicSettings\BasicController@updateMaintenance')->name('admin.basic_settings.update_maintenance_mode');

    // basic settings cookie-alert route
    Route::get('/cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@cookieAlert')->name('admin.basic_settings.cookie_alert');

    Route::post('/update-cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@updateCookieAlert')->name('admin.basic_settings.update_cookie_alert');

    // basic-settings social-media route
    Route::get('/social-medias', 'BackEnd\BasicSettings\SocialMediaController@index')->name('admin.basic_settings.social_medias');

    Route::post('/store-social-media', 'BackEnd\BasicSettings\SocialMediaController@store')->name('admin.basic_settings.store_social_media');

    Route::post('/update-social-media', 'BackEnd\BasicSettings\SocialMediaController@update')->name('admin.basic_settings.update_social_media');

    Route::post('/delete-social-media/{id}', 'BackEnd\BasicSettings\SocialMediaController@destroy')->name('admin.basic_settings.delete_social_media');
  });


  // announcement-popup route start
  Route::prefix('/announcement-popups')->middleware('permission:Announcement Popups')->group(function () {
    Route::get('', 'BackEnd\PopupController@index')->name('admin.announcement_popups');

    Route::get('/select-popup-type', 'BackEnd\PopupController@popupType')->name('admin.announcement_popups.select_popup_type');

    Route::get('/create-popup/{type}', 'BackEnd\PopupController@create')->name('admin.announcement_popups.create_popup');

    Route::post('/store-popup', 'BackEnd\PopupController@store')->name('admin.announcement_popups.store_popup');

    Route::post('/popup/{id}/update-status', 'BackEnd\PopupController@updateStatus')->name('admin.announcement_popups.update_popup_status');

    Route::get('/edit-popup/{id}', 'BackEnd\PopupController@edit')->name('admin.announcement_popups.edit_popup');

    Route::post('/update-popup/{id}', 'BackEnd\PopupController@update')->name('admin.announcement_popups.update_popup');

    Route::post('/delete-popup/{id}', 'BackEnd\PopupController@destroy')->name('admin.announcement_popups.delete_popup');

    Route::post('/bulk-delete-popup', 'BackEnd\PopupController@bulkDestroy')->name('admin.announcement_popups.bulk_delete_popup');
  });
  // announcement-popup route end


  // menu-builder route start
  Route::prefix('/menu-builder')->middleware('permission:Menu Builder')->group(function () {
    Route::get(
      '',
      'BackEnd\MenuBuilderController@index'
    )->name('admin.menu_builder');

    Route::post('/update-menus', 'BackEnd\MenuBuilderController@update')->name('admin.menu_builder.update_menus');
  });
  // menu-builder route end


  // home-page route start
  Route::prefix('/home-page')->middleware('permission:Home Page')->group(function () {
    // hero section
    Route::prefix('/hero-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\HeroController@index')->name('admin.home_page.hero_section');

      Route::post('/update-background-image', 'BackEnd\HomePage\HeroController@updateBgImg')->name('admin.home_page.update_hero_bg');

      Route::post('/store-slider', 'BackEnd\HomePage\HeroController@storeSlider')->name('admin.home_page.store_slider');

      Route::post('/update-slider', 'BackEnd\HomePage\HeroController@updateSlider')->name('admin.home_page.update_slider');

      Route::post('/delete-slider/{id}', 'BackEnd\HomePage\HeroController@destroySlider')->name('admin.home_page.delete_slider');

      Route::post('/update-image', 'BackEnd\HomePage\HeroController@updateImg')->name('admin.home_page.update_hero_img');

      Route::post('/update-info', 'BackEnd\HomePage\HeroController@updateHeroInfo')->name('admin.home_page.update_hero_info');
    });

    // section titles
    Route::get('/section-titles', 'BackEnd\HomePage\SectionTitleController@index')->name('admin.home_page.section_titles');

    Route::post('/update-section-titles', 'BackEnd\HomePage\SectionTitleController@update')->name('admin.home_page.update_section_titles');

    // about section
    Route::prefix('/about-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\AboutController@index')->name('admin.home_page.about_section');

      Route::post('/update-image', 'BackEnd\HomePage\AboutController@updateImage')->name('admin.home_page.update_about_img');

      Route::post('/update-info', 'BackEnd\HomePage\AboutController@updateInfo')->name('admin.home_page.update_about_info');
    });

    // features section
    Route::prefix('/features-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\FeatureController@index')->name('admin.home_page.features_section');

      Route::post('/update-background-image', 'BackEnd\HomePage\FeatureController@updateBgImg')->name('admin.home_page.update_features_bg');

      Route::post('/store-feature', 'BackEnd\HomePage\FeatureController@storeFeature')->name('admin.home_page.store_feature');

      Route::post('/update-feature', 'BackEnd\HomePage\FeatureController@updateFeature')->name('admin.home_page.update_feature');

      Route::post('/delete-feature/{id}', 'BackEnd\HomePage\FeatureController@destroyFeature')->name('admin.home_page.delete_feature');

      Route::post('/bulk-delete-feature', 'BackEnd\HomePage\FeatureController@bulkDestroyFeature')->name('admin.home_page.bulk_delete_feature');
    });

    // testimonials section
    Route::prefix('/testimonials-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\TestimonialController@index')->name('admin.home_page.testimonials_section');

      Route::post('/update-background-image', 'BackEnd\HomePage\TestimonialController@updateBgImg')->name('admin.home_page.update_testimonials_bg');

      Route::post('/store-testimonial', 'BackEnd\HomePage\TestimonialController@storeTestimonial')->name('admin.home_page.store_testimonial');

      Route::post('/update-testimonial', 'BackEnd\HomePage\TestimonialController@updateTestimonial')->name('admin.home_page.update_testimonial');

      Route::post('/delete-testimonial/{id}', 'BackEnd\HomePage\TestimonialController@destroyTestimonial')->name('admin.home_page.delete_testimonial');

      Route::post('/bulk-delete-testimonial', 'BackEnd\HomePage\TestimonialController@bulkDestroyTestimonial')->name('admin.home_page.bulk_delete_testimonial');
    });

    // newsletter section
    Route::prefix('/newsletter-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\NewsletterController@index')->name('admin.home_page.newsletter_section');

      Route::post('/update-text/{language_id}', 'BackEnd\HomePage\NewsletterController@updateText')->name('admin.home_page.update_newsletter_text');
    });

    Route::prefix('/call-to-action-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\CalltoActionSectionController@index')->name('admin.home_page.calltoactionsection');

      Route::post('/update-image', 'BackEnd\HomePage\CalltoActionSectionController@updateBgImg')->name('admin.home_page.update_calltoactionsection');
      Route::post('/update-info/{language}', 'BackEnd\HomePage\CalltoActionSectionController@updateInfo')->name('admin.home_page.update_calltoactionsection_info');
    });

    // partners section
    Route::prefix('/partners-section')->group(function () {
      Route::get('', 'BackEnd\HomePage\PartnerController@index')->name('admin.home_page.partners_section');

      Route::post('/store-partner', 'BackEnd\HomePage\PartnerController@store')->name('admin.home_page.store_partner');

      Route::post('/update-partner', 'BackEnd\HomePage\PartnerController@update')->name('admin.home_page.update_partner');

      Route::post('/delete-partner/{id}', 'BackEnd\HomePage\PartnerController@destroy')->name('admin.home_page.delete_partner');
    });

    // section customization
    Route::get('/section-customization', 'BackEnd\HomePage\SectionController@index')->name('admin.home_page.section_customization');

    Route::post('/update-section-status', 'BackEnd\HomePage\SectionController@update')->name('admin.home_page.update_section_status');
  });
  // home-page route end


  // payment-gateway route start
  Route::prefix('/payment-gateways')->middleware('permission:Payment Gateways')->group(function () {
    Route::get('/online-gateways', 'BackEnd\PaymentGateway\OnlineGatewayController@index')->name('admin.payment_gateways.online_gateways');

    Route::post('/update-paypal-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePayPalInfo')->name('admin.payment_gateways.update_paypal_info');

    Route::post('/update-instamojo-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateInstamojoInfo')->name('admin.payment_gateways.update_instamojo_info');

    Route::post('/update-paystack-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaystackInfo')->name('admin.payment_gateways.update_paystack_info');

    Route::post('/update-flutterwave-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateFlutterwaveInfo')->name('admin.payment_gateways.update_flutterwave_info');

    Route::post('/update-razorpay-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateRazorpayInfo')->name('admin.payment_gateways.update_razorpay_info');

    Route::post('/update-mercadopago-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMercadoPagoInfo')->name('admin.payment_gateways.update_mercadopago_info');

    Route::post('/update-mollie-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMollieInfo')->name('admin.payment_gateways.update_mollie_info');

    Route::post('/update-stripe-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateStripeInfo')->name('admin.payment_gateways.update_stripe_info');

    Route::post('/update-paytm-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaytmInfo')->name('admin.payment_gateways.update_paytm_info');

    Route::post('/update-authorizenet-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateAuthorizeNetInfo')->name('admin.payment_gateways.update_authorizenet_info');

    Route::post('/update-midtrans-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMidtransInfo')->name('admin.payment_gateways.update_midtrans_info');

    Route::post('/update-iyzico-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateIyzicoInfo')->name('admin.payment_gateways.update_iyzico_info');

    Route::post('/update-paytabs-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaytabsInfo')->name('admin.payment_gateways.update_paytabs_info');

    Route::post('/update-toyyibpay-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateToyyibpayInfo')->name('admin.payment_gateways.update_toyyibpay_info');

    Route::post('/update-phonepe-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePhonepeInfo')->name('admin.payment_gateways.update_phonepe_info');
    Route::post('/update-yoco-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateYocoInfo')->name('admin.payment_gateways.update_yoco_info');

    Route::post('/update-myfatoorah-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMyFatoorahInfo')->name('admin.payment_gateways.update_myfatoorah_info');

    Route::post('/update-zendit-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateXenditInfo')->name('admin.payment_gateways.update_xendit_info');
    Route::post('/update-perfect_money-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePerfectMoneyInfo')->name('admin.payment_gateways.update_perfect_money_info');

    Route::get('/offline-gateways', 'BackEnd\PaymentGateway\OfflineGatewayController@index')->name('admin.payment_gateways.offline_gateways');

    Route::post(
      '/store-offline-gateway',
      'BackEnd\PaymentGateway\OfflineGatewayController@store'
    )->name('admin.payment_gateways.store_offline_gateway');

    Route::post('/update-status/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@updateStatus')->name('admin.payment_gateways.update_status');

    Route::post('/update-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@update')->name('admin.payment_gateways.update_offline_gateway');

    Route::post('/delete-offline-gateway/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@destroy')->name('admin.payment_gateways.delete_offline_gateway');
  });
  // payment-gateway route end

  // service route start
  Route::prefix('/service-management')->middleware('permission:Service Management')->group(function () {
    // category route

    Route::get('/categories', 'BackEnd\ClientService\CategoryController@index')->name('admin.service_management.categories');
    Route::post('/store-category', 'BackEnd\ClientService\CategoryController@store')->name('admin.service_management.store_category');
    Route::post('/category/{id}/update-featured-status', 'BackEnd\ClientService\CategoryController@updateFeaturedStatus')->name('admin.service_management.category.update_featured_status');
    Route::post('/category/{id}/update-add-to-menu-status', 'BackEnd\ClientService\CategoryController@updateAddToMenuStatus')->name('admin.service_management.category.update_add_to_menu');
    Route::post('/update-category', 'BackEnd\ClientService\CategoryController@update')->name('admin.service_management.update_category');
    Route::post('/delete-category/{id}', 'BackEnd\ClientService\CategoryController@destroy')->name('admin.service_management.delete_category');
    Route::post('/bulk-delete-category', 'BackEnd\ClientService\CategoryController@bulkDestroy')->name('admin.service_management.bulk_delete_category');

    Route::prefix('skill')->group(function () {
      Route::get('/', 'BackEnd\ClientService\SkillController@index')->name('admin.service_management.skills');
      Route::post('/store', 'BackEnd\ClientService\SkillController@store')->name('admin.service_management.store_skill');
      Route::post('/{id}/update-featured-status', 'BackEnd\ClientService\SkillController@updateFeaturedStatus')->name('admin.service_management.skill.update_featured_status');
      Route::post('/update', 'BackEnd\ClientService\SkillController@update')->name('admin.service_management.update_skill');
      Route::post('/delete/{id}', 'BackEnd\ClientService\SkillController@destroy')->name('admin.service_management.delete_skill');
      Route::post('/bulk-delete', 'BackEnd\ClientService\SkillController@bulkDestroy')->name('admin.service_management.bulk_delete_skill');
    });

    // subcategory route
    Route::get('/subcategories', 'BackEnd\ClientService\SubcategoryController@index')->name('admin.service_management.subcategories');

    Route::post('/store-subcategory', 'BackEnd\ClientService\SubcategoryController@store')->name('admin.service_management.store_subcategory');

    Route::post('/update-subcategory', 'BackEnd\ClientService\SubcategoryController@update')->name('admin.service_management.update_subcategory');

    Route::post('/delete-subcategory/{id}', 'BackEnd\ClientService\SubcategoryController@destroy')->name('admin.service_management.delete_subcategory');

    Route::post('/bulk-delete-subcategory', 'BackEnd\ClientService\SubcategoryController@bulkDestroy')->name('admin.service_management.bulk_delete_subcategory');

    // form route
    Route::get('/forms', 'BackEnd\ClientService\FormController@index')->name('admin.service_management.forms');

    Route::post('/store-form', 'BackEnd\ClientService\FormController@store')->name('admin.service_management.store_form');

    Route::prefix('/form')->group(function () {
      Route::get('/{id}/input', 'BackEnd\ClientService\FormInputController@manageInput')->name('admin.service_management.form.input');

      Route::post('/{id}/store-input', 'BackEnd\ClientService\FormInputController@storeInput')->name('admin.service_management.form.store_input');

      Route::get('/{form_id}/edit-input/{input_id}', 'BackEnd\ClientService\FormInputController@editInput')->name('admin.service_management.form.edit_input');

      Route::post('/update-input/{id}', 'BackEnd\ClientService\FormInputController@updateInput')->name('admin.service_management.form.update_input');

      Route::post('/delete-input/{id}', 'BackEnd\ClientService\FormInputController@destroyInput')->name('admin.service_management.form.delete_input');

      Route::post('/sort-input', 'BackEnd\ClientService\FormInputController@sortInput')->name('admin.service_management.form.sort_input');
    });

    Route::post('/update-form', 'BackEnd\ClientService\FormController@update')->name('admin.service_management.update_form');

    Route::post('/delete-form/{id}', 'BackEnd\ClientService\FormController@destroy')->name('admin.service_management.delete_form');
    // popular tags
    Route::get('/populer/tags', 'BackEnd\ClientService\ServiceController@popularTags')->name('admin.service_management.popular_tags');
    Route::post('/populer/tags/update', 'BackEnd\ClientService\ServiceController@populerTagupdate')->name('admin.service_management.popular_tags.update');

    // service route
    Route::get('/settings', 'BackEnd\ClientService\ServiceController@settings')->name('admin.service_management.settings');
    Route::post('/settings/update', 'BackEnd\ClientService\ServiceController@settingsUpdate')->name('admin.service_management.settings.update');
    Route::get('/services', 'BackEnd\ClientService\ServiceController@index')->name('admin.service_management.services');

    Route::get('/create-service', 'BackEnd\ClientService\ServiceController@create')->name('admin.service_management.create_service');

    Route::get('/category/{id}/get-subcategory', 'BackEnd\ClientService\ServiceController@getSubcategory');

    Route::post('/upload-slider-image', 'BackEnd\ClientService\ServiceController@uploadImage')->name('admin.service_management.upload_slider_image');

    Route::post('/remove-slider-image', 'BackEnd\ClientService\ServiceController@removeImage')->name('admin.service_management.remove_slider_image');

    Route::post('/store-service', 'BackEnd\ClientService\ServiceController@store')->name('admin.service_management.store_service');

    Route::get('/get-form-by-vendor', 'BackEnd\ClientService\ServiceController@get_form')->name('admin.service_management.get-form-by-vendor');

    Route::post(
      '/service/{id}/update-featured-status',
      'BackEnd\ClientService\ServiceController@updateFeaturedStatus'
    )->name('admin.service_management.service.update_featured_status');

    Route::get('/edit-service/{id}', 'BackEnd\ClientService\ServiceController@edit')->name('admin.service_management.edit_service');

    Route::post('/detach-slider-image', 'BackEnd\ClientService\ServiceController@detachImage')->name('admin.service_management.detach_slider_image');

    Route::post('/update-service/{id}', 'BackEnd\ClientService\ServiceController@update')->name('admin.service_management.update_service');

    Route::post('/delete-service/{id}', 'BackEnd\ClientService\ServiceController@destroy')->name('admin.service_management.delete_service');

    Route::post('/bulk-delete-service', 'BackEnd\ClientService\ServiceController@bulkDestroy')->name('admin.service_management.bulk_delete_service');

    // package route
    Route::prefix('/service')->group(function () {
      Route::get('/{id}/packages', 'BackEnd\ClientService\PackageController@index')->name('admin.service_management.service.packages');

      Route::post('/store-package', 'BackEnd\ClientService\PackageController@store')->name('admin.service_management.service.store_package');

      Route::post('/update-package', 'BackEnd\ClientService\PackageController@update')->name('admin.service_management.service.update_package');

      Route::post(
        '/delete-package/{id}',
        'BackEnd\ClientService\PackageController@destroy'
      )->name('admin.service_management.service.delete_package');

      Route::post('/bulk-delete-package', 'BackEnd\ClientService\PackageController@bulkDestroy')->name('admin.service_management.service.bulk_delete_package');
    });

    // addon route
    Route::prefix('/service')->group(function () {
      Route::get('/{id}/addons', 'BackEnd\ClientService\AddonController@index')->name('admin.service_management.service.addons');

      Route::post('/store-addon', 'BackEnd\ClientService\AddonController@store')->name('admin.service_management.service.store_addon');

      Route::post('/update-addon', 'BackEnd\ClientService\AddonController@update')->name('admin.service_management.service.update_addon');

      Route::post('/delete-addon/{id}', 'BackEnd\ClientService\AddonController@destroy')->name('admin.service_management.service.delete_addon');

      Route::post(
        '/bulk-delete-addon',
        'BackEnd\ClientService\AddonController@bulkDestroy'
      )->name('admin.service_management.service.bulk_delete_addon');
    });

    // faq route
    Route::prefix('/service')->group(function () {
      Route::get('/{id}/faqs', 'BackEnd\ClientService\FaqController@index')->name('admin.service_management.service.faqs');

      Route::post('/store-faq', 'BackEnd\ClientService\FaqController@store')->name('admin.service_management.service.store_faq');

      Route::post('/update-faq', 'BackEnd\ClientService\FaqController@update')->name('admin.service_management.service.update_faq');

      Route::post('/delete-faq/{id}', 'BackEnd\ClientService\FaqController@destroy')->name('admin.service_management.service.delete_faq');

      Route::post('/bulk-delete-faq', 'BackEnd\ClientService\FaqController@bulkDestroy')->name('admin.service_management.service.bulk_delete_faq');
    });
  });
  // service route end


  // service order route start
  Route::middleware('permission:Service Orders')->group(function () {
    Route::prefix('/service-orders')->group(function () {
      Route::get('', 'BackEnd\ClientService\OrderController@orders')->name('admin.service_orders');

      Route::prefix('/order/{id}')->group(function () {
        Route::post('/update-payment-status', 'BackEnd\ClientService\OrderController@updatePaymentStatus')->name('admin.service_order.update_payment_status');

        Route::post('/update-order-status', 'BackEnd\ClientService\OrderController@updateOrderStatus')->name('admin.service_order.update_order_status');

        Route::get('/details', 'BackEnd\ClientService\OrderController@show')->name('admin.service_order.details');

        Route::get('/message', 'BackEnd\ClientService\OrderController@message')->name('admin.service_order.message');
        Route::post('/send-mail', 'BackEnd\ClientService\OrderController@sendMail')->name('admin.service_order.sendmail');

        Route::post('/store-message', 'BackEnd\ClientService\OrderController@storeMessage')->name('admin.service_order.store_message');

        Route::post('/delete', 'BackEnd\ClientService\OrderController@destroy')->name('admin.service_order.delete');
      });

      Route::post('/bulk-delete', 'BackEnd\ClientService\OrderController@bulkDestroy')->name('admin.service_orders.bulk_delete');

      // service orders report route
      Route::get('/report', 'BackEnd\ClientService\OrderController@report')->name('admin.service_orders.report');

      Route::get(
        '/export-report',
        'BackEnd\ClientService\OrderController@exportReport'
      )->name('admin.service_orders.export_report');
    });
  });
  Route::prefix('disputs')->group(function () {
    Route::get('/', 'BackEnd\ClientService\OrderController@disputs')->name('admin.service_order.disputs');
    Route::post('/update/{id}', 'BackEnd\ClientService\OrderController@disput_update')->name('admin.service_order.disput.update');
  })->middleware('permission:Raise Disputs');
  // service order route end

  Route::prefix('withdraw')->middleware('permission:Withdrawals Management')->group(function () {
    Route::get('/payment-methods', 'BackEnd\WithdrawPaymentMethodController@index')->name('admin.withdraw.payment_method');
    Route::post('/payment-methods/store', 'BackEnd\WithdrawPaymentMethodController@store')->name('admin.withdraw_payment_method.store');
    Route::put('/payment-methods/update', 'BackEnd\WithdrawPaymentMethodController@update')->name('admin.withdraw_payment_method.update');
    Route::post('/payment-methods/delete/{id}', 'BackEnd\WithdrawPaymentMethodController@destroy')->name('admin.withdraw_payment_method.delete');

    Route::get('/payment-method/input', 'BackEnd\WithdrawPaymentMethodInputController@index')->name('admin.withdraw_payment_method.mange_input');
    Route::post('/payment-method/input-store', 'BackEnd\WithdrawPaymentMethodInputController@store')->name('admin.withdraw_payment_method.store_input');
    Route::get('/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
    Route::get('/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
    Route::post('/payment-method/input-update', 'BackEnd\WithdrawPaymentMethodInputController@update')->name('admin.withdraw_payment_method.update_input');
    Route::post('/payment-method/order-update', 'BackEnd\WithdrawPaymentMethodInputController@order_update')->name('admin.withdraw_payment_method.order_update');
    Route::get('/payment-method/input-option/{id}', 'BackEnd\WithdrawPaymentMethodInputController@get_options')->name('admin.withdraw_payment_method.options');
    Route::post('/payment-method/input-delete', 'BackEnd\WithdrawPaymentMethodInputController@delete')->name('admin.withdraw_payment_method.options_delete');

    Route::get('/withdraw-request', 'BackEnd\WithdrawController@index')->name('admin.withdraw.withdraw_request');
    Route::post('/withdraw-request/delete', 'BackEnd\WithdrawController@delete')->name('admin.witdraw.delete_withdraw');
    Route::get('/withdraw-request/approve/{id}', 'BackEnd\WithdrawController@approve')->name('admin.witdraw.approve_withdraw');
    Route::get('/withdraw-request/decline/{id}', 'BackEnd\WithdrawController@decline')->name('admin.witdraw.decline_withdraw');
  });


  // blog route start
  Route::prefix('/blog-management')->middleware('permission:Blog Management')->group(function () {
    // blog category route
    Route::get('/categories', 'BackEnd\Blog\CategoryController@index')->name('admin.blog_management.categories');

    Route::post('/store-category', 'BackEnd\Blog\CategoryController@store')->name('admin.blog_management.store_category');

    Route::post('/update-category', 'BackEnd\Blog\CategoryController@update')->name('admin.blog_management.update_category');

    Route::post('/delete-category/{id}', 'BackEnd\Blog\CategoryController@destroy')->name('admin.blog_management.delete_category');

    Route::post('/bulk-delete-category', 'BackEnd\Blog\CategoryController@bulkDestroy')->name('admin.blog_management.bulk_delete_category');

    // post route
    Route::get(
      '/posts',
      'BackEnd\Blog\PostController@index'
    )->name('admin.blog_management.posts');

    Route::get('/create-post', 'BackEnd\Blog\PostController@create')->name('admin.blog_management.create_post');

    Route::post('/store-post', 'BackEnd\Blog\PostController@store')->name('admin.blog_management.store_post');

    Route::get('/edit-post/{id}', 'BackEnd\Blog\PostController@edit')->name('admin.blog_management.edit_post');

    Route::post('/update-post/{id}', 'BackEnd\Blog\PostController@update')->name('admin.blog_management.update_post');

    Route::post('/delete-post/{id}', 'BackEnd\Blog\PostController@destroy')->name('admin.blog_management.delete_post');

    Route::post('/bulk-delete-post', 'BackEnd\Blog\PostController@bulkDestroy')->name('admin.blog_management.bulk_delete_post');
  });
  // blog route end


  // faq route start
  Route::prefix('/faq-management')->middleware('permission:FAQ Management')->group(function () {
    Route::get('', 'BackEnd\FaqController@index')->name('admin.faq_management');

    Route::post('/store-faq', 'BackEnd\FaqController@store')->name('admin.faq_management.store_faq');

    Route::post('/update-faq', 'BackEnd\FaqController@update')->name('admin.faq_management.update_faq');

    Route::post('/delete-faq/{id}', 'BackEnd\FaqController@destroy')->name('admin.faq_management.delete_faq');

    Route::post('/bulk-delete-faq', 'BackEnd\FaqController@bulkDestroy')->name('admin.faq_management.bulk_delete_faq');
  });
  // faq route end


  // custom-pages route start
  Route::prefix('/custom-pages')->middleware('permission:Custom Pages')->group(function () {
    Route::get('', 'BackEnd\CustomPageController@index')->name('admin.custom_pages');

    Route::get('/create-page', 'BackEnd\CustomPageController@create')->name('admin.custom_pages.create_page');

    Route::post('/store-page', 'BackEnd\CustomPageController@store')->name('admin.custom_pages.store_page');

    Route::get('/edit-page/{id}', 'BackEnd\CustomPageController@edit')->name('admin.custom_pages.edit_page');

    Route::post('/update-page/{id}', 'BackEnd\CustomPageController@update')->name('admin.custom_pages.update_page');

    Route::post('/delete-page/{id}', 'BackEnd\CustomPageController@destroy')->name('admin.custom_pages.delete_page');

    Route::post('/bulk-delete-page', 'BackEnd\CustomPageController@bulkDestroy')->name('admin.custom_pages.bulk_delete_page');
  });
  // custom-pages route end


  // advertise route start
  Route::prefix('/advertisement')->middleware('permission:Advertise')->group(function () {
    Route::get('/settings', 'BackEnd\AdvertisementController@advertiseSettings')->name('admin.advertise.settings');

    Route::post('/update-settings', 'BackEnd\AdvertisementController@updateAdvertiseSettings')->name('admin.advertise.update_settings');

    Route::get('/all-advertisement', 'BackEnd\AdvertisementController@index')->name('admin.advertise.all_advertisement');

    Route::post('/store-advertisement', 'BackEnd\AdvertisementController@store')->name('admin.advertise.store_advertisement');

    Route::post('/update-advertisement', 'BackEnd\AdvertisementController@update')->name('admin.advertise.update_advertisement');

    Route::post('/delete-advertisement/{id}', 'BackEnd\AdvertisementController@destroy')->name('admin.advertise.delete_advertisement');

    Route::post('/bulk-delete-advertisement', 'BackEnd\AdvertisementController@bulkDestroy')->name('admin.advertise.bulk_delete_advertisement');
  });
  // advertise route end


  // footer route start
  Route::prefix('/footer')->middleware('permission:Footer')->group(function () {
    // logo route
    Route::get('/logo', 'BackEnd\Footer\LogoController@index')->name('admin.footer.logo');

    Route::post('/update-logo', 'BackEnd\Footer\LogoController@updateLogo')->name('admin.footer.update_logo');

    // content route
    Route::get('/content', 'BackEnd\Footer\ContentController@index')->name('admin.footer.content');

    Route::post('/update-content', 'BackEnd\Footer\ContentController@update')->name('admin.footer.update_content');

    // quick link route
    Route::get('/quick-links', 'BackEnd\Footer\QuickLinkController@index')->name('admin.footer.quick_links');

    Route::post('/store-quick-link', 'BackEnd\Footer\QuickLinkController@store')->name('admin.footer.store_quick_link');

    Route::post('/update-quick-link', 'BackEnd\Footer\QuickLinkController@update')->name('admin.footer.update_quick_link');

    Route::post('/delete-quick-link/{id}', 'BackEnd\Footer\QuickLinkController@destroy')->name('admin.footer.delete_quick_link');
  });
  // footer route end


  // user management route start
  Route::prefix('/user-management')->middleware('permission:User Management')->group(function () {
    // registered user route
    Route::get('/registered-users', 'BackEnd\User\UserController@index')->name('admin.user_management.registered_users');
    Route::post('/register-user', 'BackEnd\User\UserController@registerUser')->name('admin.user_management.register_user');

    Route::prefix('/user/{id}')->group(function () {
      Route::post('/update-email-status', 'BackEnd\User\UserController@updateEmailStatus')->name('admin.user_management.user.update_email_status');

      Route::post('/update-account-status', 'BackEnd\User\UserController@updateAccountStatus')->name('admin.user_management.user.update_account_status');

      Route::get('/details', 'BackEnd\User\UserController@show')->name('admin.user_management.user.details');
      Route::get('/edit', 'BackEnd\User\UserController@edit')->name('admin.user_management.user.edit');
      Route::post('/update', 'BackEnd\User\UserController@update')->name('admin.user_management.user.update');

      Route::get('/change-password', 'BackEnd\User\UserController@changePassword')->name('admin.user_management.user.change_password');

      Route::post('/update-password', 'BackEnd\User\UserController@updatePassword')->name('admin.user_management.user.update_password');

      Route::post('/secretLogin', 'BackEnd\User\UserController@secretLogin')->name('admin.user_management.user.secretLogin');
      Route::post('/delete', 'BackEnd\User\UserController@destroy')->name('admin.user_management.user.delete');
    });

    Route::post('/bulk-delete-user', 'BackEnd\User\UserController@bulkDestroy')->name('admin.user_management.bulk_delete_user');

    // subscriber route
    Route::get(
      '/subscribers',
      'BackEnd\User\SubscriberController@index'
    )->name('admin.user_management.subscribers');

    Route::post(
      '/subscriber/{id}/delete',
      'BackEnd\User\SubscriberController@destroy'
    )->name('admin.user_management.subscriber.delete');

    Route::post('/bulk-delete-subscriber', 'BackEnd\User\SubscriberController@bulkDestroy')->name('admin.user_management.bulk_delete_subscriber');

    Route::get(
      '/mail-for-subscribers',
      'BackEnd\User\SubscriberController@writeEmail'
    )->name('admin.user_management.mail_for_subscribers');

    Route::post('/subscribers/send-email', 'BackEnd\User\SubscriberController@prepareEmail')->name('admin.user_management.subscribers.send_email');

    // push notification route
    Route::prefix('/push-notification')->group(function () {
      Route::get('/settings', 'BackEnd\User\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

      Route::post('/update-settings', 'BackEnd\User\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

      Route::get('/notification-for-visitors', 'BackEnd\User\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

      Route::post('/send', 'BackEnd\User\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send');
    });
  });
  // user management route end

  // seller management route start
  Route::prefix('/seller-management')->middleware('permission:Sellers Management')->group(function () {
    Route::get('/settings', 'BackEnd\SellerManagementController@settings')->name('admin.seller_management.settings');
    Route::post('/settings/update', 'BackEnd\SellerManagementController@update_setting')->name('admin.seller_management.setting.update');

    Route::get('/add-seller', 'BackEnd\SellerManagementController@add')->name('admin.seller_management.add_seller');
    Route::post('/save-seller', 'BackEnd\SellerManagementController@create')->name('admin.seller_management.save-seller');

    Route::get('/registered-sellers', 'BackEnd\SellerManagementController@index')->name('admin.seller_management.registered_seller');

    Route::prefix('/seller/{id}')->group(function () {
      Route::post('/update-account-status', 'BackEnd\SellerManagementController@updateAccountStatus')->name('admin.seller_management.seller.update_account_status');

      Route::post('/update-email-status', 'BackEnd\SellerManagementController@updateEmailStatus')->name('admin.seller_management.seller.update_email_status');

      Route::get('/details', 'BackEnd\SellerManagementController@show')->name('admin.seller_management.seller_details');

      Route::get('/edit', 'BackEnd\SellerManagementController@edit')->name('admin.edit_management.seller_edit');

      Route::post('/update', 'BackEnd\SellerManagementController@update')->name('admin.seller_management.seller.update_seller');

      Route::post('/update/seller/balance', 'BackEnd\SellerManagementController@update_seller_balance')->name('admin.seller_management.update_seller_balance');

      Route::get('/change-password', 'BackEnd\SellerManagementController@changePassword')->name('admin.seller_management.seller.change_password');

      Route::post('/update-password', 'BackEnd\SellerManagementController@updatePassword')->name('admin.seller_management.seller.update_password');

      Route::post('/delete', 'BackEnd\SellerManagementController@destroy')->name('admin.seller_management.seller.delete');

      Route::post('/update/seller/balance', 'BackEnd\SellerManagementController@update_seller_balance')->name('admin.seller_management.seller.update_seller_balance');
    });

    Route::post('/seller/current-package/remove', 'BackEnd\SellerManagementController@removeCurrPackage')->name('seller.currPackage.remove');

    Route::post('/seller/current-package/change', 'BackEnd\SellerManagementController@changeCurrPackage')->name('seller.currPackage.change');

    Route::post('/seller/current-package/add', 'BackEnd\SellerManagementController@addCurrPackage')->name('seller.currPackage.add');

    Route::post('/seller/next-package/remove', 'BackEnd\SellerManagementController@removeNextPackage')->name('seller.nextPackage.remove');

    Route::post('/seller/next-package/change', 'BackEnd\SellerManagementController@changeNextPackage')->name('seller.nextPackage.change');

    Route::post('/seller/next-package/add', 'BackEnd\SellerManagementController@addNextPackage')->name('seller.nextPackage.add');

    Route::post('/bulk-delete-seller', 'BackEnd\SellerManagementController@bulkDestroy')->name('admin.seller_management.bulk_delete_seller');

    Route::get('/secret-login/{id}', 'BackEnd\SellerManagementController@secret_login')->name('admin.seller_management.seller.secret_login');
  });
  // seller management route start


  // qr-code route start
  Route::prefix('/qr-codes')->middleware('permission:QR Codes')->group(function () {
    Route::get('/generate-code', 'BackEnd\QrCodeController@generate')->name('admin.qr_codes.generate_code');

    Route::post('/regenerate-code', 'BackEnd\QrCodeController@regenerate')->name('admin.qr_codes.regenerate_code');

    Route::post('/clear', 'BackEnd\QrCodeController@clearFilters')->name('admin.qr_codes.clear');

    Route::post('/save-qr', 'BackEnd\QrCodeController@saveQrCode')->name('admin.qr_codes.save_qr');

    Route::get('/saved-codes', 'BackEnd\QrCodeController@savedCodes')->name('admin.qr_codes.saved_codes');

    Route::post('/delete-qr/{id}', 'BackEnd\QrCodeController@deleteQrCode')->name('admin.qr_codes.delete_qr');

    Route::post('/bulk-delete-qr', 'BackEnd\QrCodeController@bulkDeleteQrCode')->name('admin.qr_codes.bulk_delete_qr');
  });
  // qr-code route end


  // support-ticket route start
  Route::prefix('/support-tickets')->middleware('permission:Support Tickets')->group(function () {
    Route::get('/settings', 'BackEnd\SupportTicketController@settings')->name('admin.support_tickets.settings');

    Route::post('/update-settings', 'BackEnd\SupportTicketController@updateSettings')->name('admin.support_tickets.update_settings');

    Route::get('', 'BackEnd\SupportTicketController@tickets')->name('admin.support_tickets');

    Route::prefix('/ticket/{id}')->group(function () {
      Route::post(
        '/assign-admin',
        'BackEnd\SupportTicketController@assignAdmin'
      )->name('admin.support_ticket.assign_admin');

      Route::get('support-ticket/unassign-stuff/', 'BackEnd\SupportTicketController@unassign_stuff')->name('admin.support_tickets.unassign');

      Route::get('/conversation', 'BackEnd\SupportTicketController@conversation')->name('admin.support_ticket.conversation');

      Route::post('/close', 'BackEnd\SupportTicketController@close')->name('admin.support_ticket.close');

      Route::post('/reply', 'BackEnd\SupportTicketController@reply')->name('admin.support_ticket.reply');

      Route::post('/delete', 'BackEnd\SupportTicketController@destroy')->name('admin.support_ticket.delete');
    });

    Route::post('/bulk-delete', 'BackEnd\SupportTicketController@bulkDestroy')->name('admin.support_tickets.bulk_delete');

    Route::post('/store-temp-file', 'BackEnd\SupportTicketController@storeTempFile')->name('admin.support_tickets.store_temp_file');
  });
  // support-ticket route end


  // upload image in summernote route
  Route::prefix('/summernote')->group(function () {
    Route::post('/upload-image', 'BackEnd\SummernoteController@upload');

    Route::post('/remove-image', 'BackEnd\SummernoteController@remove');
  });
});

Route::get('/language-management/{id}/check-rtl', 'BackEnd\LanguageController@checkRTL');
Route::get('/service-management/language/{id}/service-categories', 'BackEnd\ClientService\SubcategoryController@getCategories');
