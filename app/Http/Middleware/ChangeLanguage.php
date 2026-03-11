<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ChangeLanguage
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    if ($request->session()->has('currentLocaleCode')) {
      $localeCode = $request->session()->get('currentLocaleCode');
      $locale = Language::query()->where('code', '=', $localeCode)
        ->pluck('code')
        ->first();
    }

    if (empty($locale)) {
      // set the default language as system locale
      $languageCode = Language::query()->where('is_default', '=', 1)
        ->pluck('code')
        ->first();
      session()->put('currentLocaleCode', $languageCode);
      App::setLocale($languageCode);
    } else {
      // set the selected language as system locale
      App::setLocale($locale);
    }

    return $next($request);
  }
}
