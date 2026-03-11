<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class MiscellaneousController extends Controller
{
  public function getLanguage()
  {
    // get the current locale of this system
    if (Session::has('currentLocaleCode')) {
      $locale = Session::get('currentLocaleCode');
    }

    if (empty($locale)) {
      $language = Language::query()->where('is_default', '=', 1)->firstOrFail();
    } else {
      $language = Language::query()->where('code', '=', $locale)->firstOrFail();
    }

    return $language;
  }


  public function storeSubscriber(Request $request)
  {
    $rules = [
      'email_id' => 'required|email:rfc,dns|unique:subscribers'
    ];

    $messages = [
      'email_id.required' => __('Please enter your email address.'),
      'email_id.unique' => __('This email address already exists!')
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'error' => $validator->getMessageBag()
      ], 400);
    }

    Subscriber::query()->create($request->all());

    return Response::json([
      'success' => __('You have successfully subscribed to our newsletter.')
    ], 200);
  }


  public function changeLanguage(Request $request)
  {
    // put the selected language in session
    $langCode = $request['lang_code'];

    $request->session()->put('currentLocaleCode', $langCode);

    return redirect()->back();
  }


  public function getPageHeading($language)
  {
    $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();

    if (URL::current() == Route::is('blog')) {
      $pageHeading = $language->pageName()->select('blog_page_title')->first();
    } else if (URL::current() == Route::is('aboutus')) {
      $pageHeading = $language->pageName()->select('about_us_page_title')->first();
    } else if (URL::current() == Route::is('blog.post_details')) {
      $pageHeading = $language->pageName()->select('post_details_page_title')->first();
    } else if (URL::current() == Route::is('faq')) {
      $pageHeading = $language->pageName()->select('faq_page_title')->first();
    } else if (URL::current() == Route::is('contact')) {
      $pageHeading = $language->pageName()->select('contact_page_title')->first();
    } else if (URL::current() == Route::is('user.login')) {
      $pageHeading = $language->pageName()->select('login_page_title')->first();
    } else if (URL::current() == Route::is('user.signup')) {
      $pageHeading = $language->pageName()->select('signup_page_title')->first();
    } else if (URL::current() == Route::is('user.forget_password')) {
      $pageHeading = $language->pageName()->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('services')) {
      $pageHeading = $language->pageName()->select('services_page_title')->first();
    } else if (URL::current() == Route::is('service_details')) {
      $pageHeading = $language->pageName()->select('service_details_page_title')->first();
    } else if (URL::current() == Route::is('seller.signup')) {
      $pageHeading = $language->pageName()->select('seller_signup_page_title')->first();
    } else if (URL::current() == Route::is('seller.login')) {
      $pageHeading = $language->pageName()->select('seller_login_page_title')->first();
    } else if (URL::current() == Route::is('seller.forget.password')) {
      $pageHeading = $language->pageName()->select('seller_forget_password_page_title')->first();
    } else if (URL::current() == Route::is('seller.reset.password')) {
      $pageHeading = $language->pageName()->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('seller.reset.password')) {
      $pageHeading = $language->pageName()->select('forget_password_page_title')->first();
    } else if (URL::current() == Route::is('frontend.sellers')) {
      $pageHeading = $language->pageName()->select('seller_page_title')->first();
    } else if (URL::current() == Route::is('pricing')) {
      $pageHeading = $language->pageName()->select('pricing_page_title')->first();
    }

    if (empty($pageHeading) && !empty($defaultLanguage) && $defaultLanguage->id !== $language->id) {
      if (URL::current() == Route::is('blog')) {
        $pageHeading = $defaultLanguage->pageName()->select('blog_page_title')->first();
      } else if (URL::current() == Route::is('aboutus')) {
        $pageHeading = $defaultLanguage->pageName()->select('about_us_page_title')->first();
      } else if (URL::current() == Route::is('blog.post_details')) {
        $pageHeading = $defaultLanguage->pageName()->select('post_details_page_title')->first();
      } else if (URL::current() == Route::is('faq')) {
        $pageHeading = $defaultLanguage->pageName()->select('faq_page_title')->first();
      } else if (URL::current() == Route::is('contact')) {
        $pageHeading = $defaultLanguage->pageName()->select('contact_page_title')->first();
      } else if (URL::current() == Route::is('user.login')) {
        $pageHeading = $defaultLanguage->pageName()->select('login_page_title')->first();
      } else if (URL::current() == Route::is('user.signup')) {
        $pageHeading = $defaultLanguage->pageName()->select('signup_page_title')->first();
      } else if (URL::current() == Route::is('user.forget_password')) {
        $pageHeading = $defaultLanguage->pageName()->select('forget_password_page_title')->first();
      } else if (URL::current() == Route::is('services')) {
        $pageHeading = $defaultLanguage->pageName()->select('services_page_title')->first();
      } else if (URL::current() == Route::is('service_details')) {
        $pageHeading = $defaultLanguage->pageName()->select('service_details_page_title')->first();
      } else if (URL::current() == Route::is('seller.signup')) {
        $pageHeading = $defaultLanguage->pageName()->select('seller_signup_page_title')->first();
      } else if (URL::current() == Route::is('seller.login')) {
        $pageHeading = $defaultLanguage->pageName()->select('seller_login_page_title')->first();
      } else if (URL::current() == Route::is('seller.forget.password')) {
        $pageHeading = $defaultLanguage->pageName()->select('seller_forget_password_page_title')->first();
      } else if (URL::current() == Route::is('seller.reset.password')) {
        $pageHeading = $defaultLanguage->pageName()->select('forget_password_page_title')->first();
      } else if (URL::current() == Route::is('seller.reset.password')) {
        $pageHeading = $defaultLanguage->pageName()->select('forget_password_page_title')->first();
      } else if (URL::current() == Route::is('frontend.sellers')) {
        $pageHeading = $defaultLanguage->pageName()->select('seller_page_title')->first();
      } else if (URL::current() == Route::is('pricing')) {
        $pageHeading = $defaultLanguage->pageName()->select('pricing_page_title')->first();
      }
    }

    return $pageHeading;
  }


  public static function getBreadcrumb()
  {
    $breadcrumb = Basic::query()->pluck('breadcrumb')->first();

    return $breadcrumb;
  }


  public function countAdView($id)
  {
    try {
      $ad = Advertisement::query()->findOrFail($id);

      $ad->update([
        'views' => $ad->views + 1
      ]);

      return response()->json(['success' => 'Advertisement view counted successfully.']);
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'Sorry, something went wrong!']);
    }
  }


  public function serviceUnavailable()
  {
    $info = Basic::query()->select('maintenance_img', 'maintenance_msg')->first();

    return view('errors.503', compact('info'));
  }
}
