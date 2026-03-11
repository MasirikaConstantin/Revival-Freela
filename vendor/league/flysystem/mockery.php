<?php

namespace App\Providers;

use App\Models\BasicSettings\SEO;
use App\Models\BasicSettings\SocialMedia;
use App\Models\Footer\FooterContent;
use App\Models\HomePage\Section;
use App\Models\Language;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Paginator::useBootstrap();

    if (!app()->runningInConsole()) {
      $data = DB::table('basic_settings')->select('favicon', 'website_title', 'logo', 'base_currency_text', 'base_currency_text_position')->first();


      // send this information to only back-end view files
      View::composer('backend.*', function ($view) {
        if (Auth::guard('admin')->check() == true) {
          $authAdmin = Auth::guard('admin')->user();
          $role = null;

          if (!is_null($authAdmin->role_id)) {
            $role = $authAdmin->role()->first();
          }
        }

        $language = Language::query()->where('is_default', '=', 1)->first();

        $websiteSettings = DB::table('basic_settings')->select('website_title', 'email_address', 'address', 'contact_number', 'admin_theme_version', 'theme_version', 'base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate', 'tax', 'life_time_earning', 'total_profit')->first();

        $footerText = $language->footerContent()->first();

        if (Auth::guard('admin')->check() == true) {
          $view->with('roleInfo', $role);
        }

        $view->with('defaultLang', $language);
        $view->with('settings', $websiteSettings);
        $view->with('footerTextInfo', $footerText);
      });

      // send this information to only vendors view files
      View::composer('seller.*', function ($view) {
        $language = Language::where('is_default', 1)->first();
        $seo = SEO::where('language_id', $language->id)->first();

        $footerText = FooterContent::where('language_id', $language->id)->first();

        $websiteSettings = DB::table('basic_settings')->select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')->first();

        $view->with('defaultLang', $language);
        $view->with('settings', $websiteSettings);
        $view->with('seo', $seo);
        $view->with('footerTextInfo', $footerText);
      });


      // send this information to only front-end view files
      View::composer('frontend.*', function ($view) {
        // get basic info
        $basicData = DB::table('basic_settings')
          ->select('theme_version', 'footer_logo', 'email_address', 'contact_number', 'address', 'primary_color', 'secondary_color', 'breadcrumb_overlay_color', 'whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message', 'support_ticket_status', 'is_language', 'is_service',  'breadcrumb_overlay_opacity', 'base_currency_symbol', 'base_currency_symbol_position', 'tax')
          ->first();
        // get all the languages of this system
        $allLanguages = Language::all();

        // get the current locale of this website
        if (Session::has('currentLocaleCode')) {
          $locale = Session::get('currentLocaleCode');
        }

        if (empty($locale)) {
          $language = Language::query()->where('is_default', '=', 1)->first();
        } else {
          $language = Language::query()->where('code', '=', $locale)->first();
        }

        // get the menus of this website
        $siteMenuInfo = $language->menuInfo;

        if (is_null($siteMenuInfo)) {
          $menus = json_encode([]);
        } else {
          $menus = $siteMenuInfo->menus;
        }

        // get the announcement popups
        $popups = $language->announcementPopup()->where('status', 1)->orderBy('serial_number', 'asc')->get();

        // get the cookie alert info
        $cookieAlert = $language->cookieAlertInfo()->first();

        // get the footer info
        $footerData = $language->footerContent()->first();
        $basicExtend = $language->basicExtend()->first();

        // get all the social medias
        $socialMedias = SocialMedia::query()->orderBy('serial_number', 'asc')->get();

        // get the quick links of footer
        $quickLinks = $language->footerQuickLink()->orderBy('serial_number', 'asc')->get();

        $footerSectionStatus = Section::query()->pluck('footer_section_status')->first();
        $menu_categories = $language->serviceCategory()->where('add_to_menu', 1)->orderBy('serial_number', 'asc')->get();

        $view->with([
          'basicInfo' => $basicData,
          'allLanguageInfos' => $allLanguages,
          'currentLanguageInfo' => $language,
          'socialMediaInfos' => $socialMedias,
          'menuInfos' => $menus,
          'menu_categories' => $menu_categories,
          'popupInfos' => $popups,
          'cookieAlertInfo' => $cookieAlert,
          'footerInfo' => $footerData,
          'quickLinkInfos' => $quickLinks,
          'footerSectionStatus' => $footerSectionStatus,
          'basicExtend' => $basicExtend
        ]);
      });


      // send this information to both front-end & back-end view files
      View::share(['websiteInfo' => $data]);
    }
  }
}
