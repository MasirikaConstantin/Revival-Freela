<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\MailFromAdminRequest;
use App\Models\Timezone;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class BasicController extends Controller
{
  public function favicon()
  {
    $data = DB::table('basic_settings')->select('favicon')->first();

    return view('backend.basic-settings.favicon', ['data' => $data]);
  }

  public function updateFavicon(Request $request)
  {
    $data = DB::table('basic_settings')->select('favicon')->first();

    $rules = [];

    if (is_null($data->favicon)) {
      $rules['favicon'] = 'required';
    }
    if ($request->hasFile('favicon')) {
      $rules['favicon'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('favicon')) {
      $newImg = $request->file('favicon');
      $oldImg = $data->favicon;
      $iconName = UploadFile::update('./assets/img/', $newImg, $oldImg);

      // finally, store the favicon into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['favicon' => $iconName]
      );

      $request->session()->flash('success', 'Favicon updated successfully!');
    }

    return redirect()->back();
  }


  public function logo()
  {
    $data = DB::table('basic_settings')->select('logo')->first();

    return view('backend.basic-settings.logo', ['data' => $data]);
  }

  public function updateLogo(Request $request)
  {
    $data = DB::table('basic_settings')->select('logo')->first();

    $rules = [];

    if (is_null($data->logo)) {
      $rules['logo'] = 'required';
    }
    if ($request->hasFile('logo')) {
      $rules['logo'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('logo')) {
      $newImg = $request->file('logo');
      $oldImg = $data->logo;
      $logoName = UploadFile::update('./assets/img/', $newImg, $oldImg);

      // finally, store the logo into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['logo' => $logoName]
      );

      $request->session()->flash('success', 'Logo updated successfully!');
    }

    return redirect()->back();
  }


  public function information()
  {
    $data = DB::table('basic_settings')
      ->select('website_title', 'email_address', 'contact_number', 'address', 'latitude', 'longitude')
      ->first();

    return view('backend.basic-settings.information', ['data' => $data]);
  }

  public function updateInfo(Request $request)
  {
    $rules = [
      'website_title' => 'required',
      'email_address' => 'nullable|email',
      'contact_number' => 'nullable',
      'address' => 'nullable',
      'latitude' => 'nullable|numeric',
      'longitude' => 'nullable|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'website_title' => $request->website_title,
        'email_address' => $request->email_address,
        'contact_number' => $request->contact_number,
        'address' => $request->address,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude
      ]
    );

    $request->session()->flash('success', 'Information updated successfully!');

    return redirect()->back();
  }


  public function timezone()
  {
    $timezones = Timezone::all();

    return view('backend.basic-settings.timezone', compact('timezones'));
  }

  public function updateTimezone(Request $request)
  {
    $prevTimezone = Timezone::query()->where('is_set', '=', 'yes')->first();

    if (!is_null($prevTimezone)) {
      $prevTimezone->update([
        'is_set' => 'no'
      ]);
    }

    $newTimezone = Timezone::query()->find($request->timezone_id);

    $newTimezone->update([
      'is_set' => 'yes'
    ]);

    $array = [
      'TIMEZONE' => $newTimezone->timezone
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Timezone updated successfully.');

    return redirect()->back();
  }


  public function themeAndHome()
  {
    $data = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.basic-settings.theme-&-home', ['data' => $data]);
  }

  public function updateThemeAndHome(Request $request)
  {
    $rules = [
      'theme_version' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['theme_version' => $request->theme_version]
    );

    $request->session()->flash('success', 'Theme & home version updated successfully!');

    return redirect()->back();
  }


  public function currency()
  {
    $data = DB::table('basic_settings')
      ->select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
      ->first();

    return view('backend.basic-settings.currency', ['data' => $data]);
  }

  public function updateCurrency(CurrencyRequest $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'base_currency_symbol' => $request->base_currency_symbol,
        'base_currency_symbol_position' => $request->base_currency_symbol_position,
        'base_currency_text' => $request->base_currency_text,
        'base_currency_text_position' => $request->base_currency_text_position,
        'base_currency_rate' => $request->base_currency_rate
      ]
    );

    $request->session()->flash('success', 'Currency updated successfully!');

    return redirect()->back();
  }


  public function appearance()
  {
    $data = DB::table('basic_settings')
      ->select('primary_color', 'secondary_color', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity')
      ->first();

    return view('backend.basic-settings.appearance', ['data' => $data]);
  }

  public function updateAppearance(Request $request)
  {
    $rules = [
      'primary_color' => 'required',
      'secondary_color' => 'required',
      'breadcrumb_overlay_color' => 'required',
      'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'primary_color' => $request->primary_color,
        'secondary_color' => $request->secondary_color,
        'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
        'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity,
      ]
    );

    $request->session()->flash('success', 'Appearance updated successfully!');

    return redirect()->back();
  }


  public function mailFromAdmin()
  {
    $data = DB::table('basic_settings')
      ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    return view('backend.basic-settings.email.mail-from-admin', ['data' => $data]);
  }

  public function updateMailFromAdmin(MailFromAdminRequest $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'smtp_status' => $request->smtp_status,
        'smtp_host' => $request->smtp_host,
        'smtp_port' => $request->smtp_port,
        'encryption' => $request->encryption,
        'smtp_username' => $request->smtp_username,
        'smtp_password' => $request->smtp_password,
        'from_mail' => $request->from_mail,
        'from_name' => $request->from_name
      ]
    );

    $request->session()->flash('success', 'Mail info updated successfully!');

    return redirect()->back();
  }

  public function mailToAdmin()
  {
    $data = DB::table('basic_settings')->select('to_mail')->first();

    return view('backend.basic-settings.email.mail-to-admin', ['data' => $data]);
  }

  public function updateMailToAdmin(Request $request)
  {
    $rule = [
      'to_mail' => 'required'
    ];

    $message = [
      'to_mail.required' => 'The mail address field is required.'
    ];

    $validator = Validator::make($request->all(), $rule, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['to_mail' => $request->to_mail]
    );

    $request->session()->flash('success', 'Mail info updated successfully!');

    return redirect()->back();
  }


  public function breadcrumb()
  {
    $data = DB::table('basic_settings')->select('breadcrumb')->first();

    return view('backend.basic-settings.breadcrumb', ['data' => $data]);
  }

  public function updateBreadcrumb(Request $request)
  {
    $data = DB::table('basic_settings')->select('breadcrumb')->first();

    $rules = [];

    if (is_null($data->breadcrumb)) {
      $rules['breadcrumb'] = 'required';
    }
    if ($request->hasFile('breadcrumb')) {
      $rules['breadcrumb'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('breadcrumb')) {
      $newImg = $request->file('breadcrumb');
      $oldImg = $data->breadcrumb;
      $breadcrumbName = UploadFile::update('./assets/img/', $newImg, $oldImg);

      // finally, store the breadcrumb into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['breadcrumb' => $breadcrumbName]
      );

      $request->session()->flash('success', 'Breadcrumb updated successfully!');
    }

    return redirect()->back();
  }


  public function plugins()
  {
    $data = DB::table('basic_settings')
      ->select('disqus_status', 'disqus_short_name', 'google_recaptcha_status', 'google_recaptcha_site_key', 'google_recaptcha_secret_key', 'whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message', 'facebook_login_status', 'facebook_app_id', 'facebook_app_secret', 'google_login_status', 'google_client_id', 'google_client_secret', 'pusher_app_id', 'pusher_key', 'pusher_secret', 'pusher_cluster')
      ->first();

    return view('backend.basic-settings.plugins', ['data' => $data]);
  }

  public function updateDisqus(Request $request)
  {
    $rules = [
      'disqus_status' => 'required',
      'disqus_short_name' => 'required'
    ];

    $messages = [
      'disqus_status.required' => 'The status field is required.',
      'disqus_short_name.required' => 'The short name field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'disqus_status' => $request->disqus_status,
        'disqus_short_name' => $request->disqus_short_name
      ]
    );

    $request->session()->flash('success', 'Disqus info updated successfully!');

    return redirect()->back();
  }

  public function updateRecaptcha(Request $request)
  {
    $rules = [
      'google_recaptcha_status' => 'required',
      'google_recaptcha_site_key' => 'required',
      'google_recaptcha_secret_key' => 'required'
    ];

    $messages = [
      'google_recaptcha_status.required' => 'The status field is required.',
      'google_recaptcha_site_key.required' => 'The site key field is required.',
      'google_recaptcha_secret_key.required' => 'The secret key field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_recaptcha_status' => $request->google_recaptcha_status,
        'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
        'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key
      ]
    );

    $array = [
      'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret_key,
      'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_site_key
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Recaptcha info updated successfully!');

    return redirect()->back();
  }

  public function updateWhatsApp(Request $request)
  {
    $rules = [
      'whatsapp_status' => 'required',
      'whatsapp_number' => 'required',
      'whatsapp_header_title' => 'required',
      'whatsapp_popup_status' => 'required',
      'whatsapp_popup_message' => 'required'
    ];

    $messages = [
      'whatsapp_status.required' => 'The status field is required.',
      'whatsapp_number.required' => 'The number field is required.',
      'whatsapp_header_title.required' => 'The header title field is required.',
      'whatsapp_popup_status.required' => 'The popup status field is required.',
      'whatsapp_popup_message.required' => 'The popup message field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'whatsapp_status' => $request->whatsapp_status,
        'whatsapp_number' => $request->whatsapp_number,
        'whatsapp_header_title' => $request->whatsapp_header_title,
        'whatsapp_popup_status' => $request->whatsapp_popup_status,
        'whatsapp_popup_message' => Purifier::clean($request->whatsapp_popup_message)
      ]
    );

    $request->session()->flash('success', 'WhatsApp info updated successfully!');

    return redirect()->back();
  }

  public function updateFacebook(Request $request)
  {
    $rules = [
      'facebook_login_status' => 'required',
      'facebook_app_id' => 'required',
      'facebook_app_secret' => 'required'
    ];

    $messages = [
      'facebook_login_status.required' => 'The login status field is required.',
      'facebook_app_id.required' => 'The app id field is required.',
      'facebook_app_secret.required' => 'The app secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'facebook_login_status' => $request->facebook_login_status,
        'facebook_app_id' => $request->facebook_app_id,
        'facebook_app_secret' => $request->facebook_app_secret
      ]
    );

    $array = [
      'FACEBOOK_CLIENT_ID' => $request->facebook_app_id,
      'FACEBOOK_CLIENT_SECRET' => $request->facebook_app_secret,
      'FACEBOOK_CALLBACK_URL' => url('/login/facebook/callback')
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Facebook info updated successfully!');

    return redirect()->back();
  }

  public function updateGoogle(Request $request)
  {
    $rules = [
      'google_login_status' => 'required',
      'google_client_id' => 'required',
      'google_client_secret' => 'required'
    ];

    $messages = [
      'google_login_status.required' => 'The login status field is required.',
      'google_client_id.required' => 'The client id field is required.',
      'google_client_secret.required' => 'The client secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_login_status' => $request->google_login_status,
        'google_client_id' => $request->google_client_id,
        'google_client_secret' => $request->google_client_secret
      ]
    );

    $array = [
      'GOOGLE_CLIENT_ID' => $request->google_client_id,
      'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
      'GOOGLE_CALLBACK_URL' => url('/login/google/callback')
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Google info updated successfully!');

    return redirect()->back();
  }

  public function updatePusher(Request $request)
  {
    $rules = [
      'pusher_app_id' => 'required',
      'pusher_key' => 'required',
      'pusher_secret' => 'required',
      'pusher_cluster' => 'required'
    ];

    $messages = [
      'pusher_app_id.required' => 'The app id field is required.',
      'pusher_key.required' => 'The key field is required.',
      'pusher_secret.required' => 'The secret field is required.',
      'pusher_cluster.required' => 'The cluster field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'pusher_app_id' => $request->pusher_app_id,
        'pusher_key' => $request->pusher_key,
        'pusher_secret' => $request->pusher_secret,
        'pusher_cluster' => $request->pusher_cluster
      ]
    );

    $array = [
      'PUSHER_APP_ID' => $request->pusher_app_id,
      'PUSHER_APP_KEY' => $request->pusher_key,
      'PUSHER_APP_SECRET' => $request->pusher_secret,
      'PUSHER_APP_CLUSTER' => $request->pusher_cluster
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Pusher info updated successfully!');

    return redirect()->back();
  }


  public function maintenance()
  {
    $data = DB::table('basic_settings')
      ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'bypass_token')
      ->first();

    return view('backend.basic-settings.maintenance', ['data' => $data]);
  }

  public function updateMaintenance(Request $request)
  {
    $data = DB::table('basic_settings')->select('maintenance_img')->first();

    $rules = $messages = [];

    if (is_null($data->maintenance_img)) {
      $rules['maintenance_img'] = 'required';

      $messages['maintenance_img.required'] = 'The maintenance image field is required.';
    }
    if ($request->hasFile('maintenance_img')) {
      $rules['maintenance_img'] = new ImageMimeTypeRule();
    }

    $rules['maintenance_status'] = 'required';
    $rules['maintenance_msg'] = 'required';

    $messages['maintenance_msg.required'] = 'The maintenance message field is required.';

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('maintenance_img')) {
      $newImg = $request->file('maintenance_img');
      $oldImg = $data->maintenance_img;
      $imageName = UploadFile::update('./assets/img/', $newImg, $oldImg);
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'maintenance_img' => $request->hasFile('maintenance_img') ? $imageName : $data->maintenance_img,
        'maintenance_status' => $request->maintenance_status,
        'maintenance_msg' => Purifier::clean($request->maintenance_msg),
        'bypass_token' => $request->bypass_token
      ]
    );

    if ($request->maintenance_status == 1) {
      $link = route('service_unavailable');

      Artisan::call('down --redirect=' . $link . ' --secret="' . $request->bypass_token . '"');
    } else {
      Artisan::call('up');
    }

    $request->session()->flash('success', 'Maintenance Info updated successfully!');

    return redirect()->back();
  }


  public function productTaxAmount()
  {
    $data = DB::table('basic_settings')->select('product_tax_amount')->first();

    return view('backend.shop.tax', ['data' => $data]);
  }

  public function updateProductTaxAmount(Request $request)
  {
    $rules = [
      'product_tax_amount' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // store the tax amount info into db
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['product_tax_amount' => $request->product_tax_amount]
    );

    $request->session()->flash('success', 'Tax amount updated successfully!');

    return redirect()->back();
  }
}
