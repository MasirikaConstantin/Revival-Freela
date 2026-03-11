<?php

use App\Http\Helpers\SellerPermissionHelper;
use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\Form;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceOrder;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

if (!function_exists('createSlug')) {
  function createSlug($string)
  {
    $slug = preg_replace('/\s+/u', '-', trim($string));
    $slug = str_replace('/', '', $slug);
    $slug = str_replace('?', '', $slug);
    $slug = str_replace(',', '', $slug);
    $slug = str_replace('&', '', $slug);

    return mb_strtolower($slug);
  }
}

if (!function_exists('replaceBaseUrl')) {
  function replaceBaseUrl($html, $type)
  {
    $startDelimiter = 'src=""';

    if ($type == 'summernote') {
      $endDelimiter = '/assets/img/summernote';
    } elseif ($type == 'pagebuilder') {
      $endDelimiter = '/assets/img';
    }

    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;

    while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
      $contentStart += $startDelimiterLength;
      $contentEnd = strpos($html, $endDelimiter, $contentStart);

      if (false === $contentEnd) {
        break;
      }

      $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
      $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $html;
  }
}

if (!function_exists('setEnvironmentValue')) {
  function setEnvironmentValue(array $values)
  {
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
      foreach ($values as $envKey => $envValue) {
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, "\n", $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

        // If key does not exist, add it
        if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
          $str .= "{$envKey}={$envValue}\n";
        } else {
          $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        }
      }
    }

    $str = substr($str, 0, -1);

    if (!file_put_contents($envFile, $str)) return false;

    return true;
  }
}

if (!function_exists('showAd')) {
  function showAd($resolutionType)
  {
    $ad = Advertisement::query()->where('resolution_type', '=', $resolutionType)->inRandomOrder()->first();
    $googleAdsensePublisherId = Basic::query()->pluck('google_adsense_publisher_id')->first();

    if (!is_null($ad)) {
      if ($resolutionType == 1) {
        $maxWidth = '300px';
        $maxHeight = '250px';
      } else if ($resolutionType == 2) {
        $maxWidth = '300px';
        $maxHeight = '600px';
      } elseif ($resolutionType == 3) {
        $maxWidth = '728px';
        $maxHeight = '90px';
      } elseif ($resolutionType == 4) {
        $maxWidth = '370px';
        $maxHeight = '250px';
      } else {
        $maxWidth = '370px';
        $maxHeight = '600px';
      }

      if ($ad->ad_type == 'banner') {
        $markUp = '<a href="' . url($ad->url) . '" target="_blank" onclick="adView(' . $ad->id . ')">
          <img  data-src="' . asset('assets/img/advertisements/' . $ad->image) . '" alt="advertisement" style="width: ' . $maxWidth . '; max-height: ' . $maxHeight . ';" class="lazyload">
        </a>';

        return $markUp;
      } else {
        $markUp = '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . $googleAdsensePublisherId . '" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display: block;" data-ad-client="' . $googleAdsensePublisherId . '" data-ad-slot="' . $ad->slot . '" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';

        return $markUp;
      }
    } else {
      return;
    }
  }
}

if (!function_exists('get_href')) {
  function get_href($data)
  {

    $link_href = '';

    if ($data->type == 'home') {
      $link_href = route('index');
    } else if ($data->type == 'services') {
      $link_href = route('services');
    } else if ($data->type == 'sellers') {
      $link_href = route('frontend.sellers');
    } else if ($data->type == 'blog') {
      $link_href = route('blog');
    } else if ($data->type == 'faq') {
      $link_href = route('faq');
    } else if ($data->type == 'contact') {
      $link_href = route('contact');
    } else if ($data->type == 'about') {
      $link_href = route('aboutus');
    } else if ($data->type == 'pricing') {
      $link_href = route('pricing');
    } else if ($data->type == 'custom') {
      /**
       * this menu has created using menu-builder from the admin panel.
       * this menu will be used as drop-down or to link any outside url to this system.
       */
      if ($data->href == '') {
        $link_href = '#';
      } else {
        $link_href = $data->href;
      }
    } else {
      // this menu is for the custom page which has been created from the admin panel.
      $link_href = route('dynamic_page', ['slug' => $data->type]);
    }

    return $link_href;
  }
}

if (!function_exists('createInputName')) {
  function createInputName($string)
  {
    $inputName = preg_replace('/\s+/u', '_', trim($string));

    return mb_strtolower($inputName);
  }
}
/// decimal number to integer
if (!function_exists('formatPrice')) {
  function formatPrice($price)
  {
    $priceArray = explode('.', $price);
    $re_number2 = null;
    if (isset($priceArray[1])) {
      $number_2 = ($priceArray[1]);
      if ($number_2 == "00") {
        $re_number2 = null;
      } else {
        $re_number2 = $number_2;
      }
    }
    if (is_null($re_number2)) {
      return $priceArray[0];
    }
    return $priceArray[0] . '.' . $re_number2;
  }
}


if (!function_exists('format_price')) {
  function format_price($value): string
  {
    $bs = Basic::first();
    if ($bs->base_currency_symbol_position == 'left') {
      return $bs->base_currency_symbol . $value;
    } else {
      return $value . $bs->base_currency_symbol;
    }
  }
}
if (!function_exists('sellerPermission')) {
  function sellerPermission($seller_id, $type, $language_id = null)
  {
    $seller_id = $seller_id;
    $currentPackage = SellerPermissionHelper::currentPackagePermission($seller_id);
    $currentMembership = SellerPermissionHelper::userPackage($seller_id);
    if ($currentPackage) {
      if ($type == 'form') {
        $total_form = Form::where('seller_id', $seller_id)->count();
        if ($total_form > $currentPackage->number_of_form_add) {
          return ['status' => 'false', 'total_form_added' => $total_form, 'package_support' => $currentPackage->number_of_form_add];
        } else {
          return ['status' => 'true'];
        }
      } elseif ($type == 'service') {
        $total_service = Service::where('seller_id', $seller_id)->count();
        if ($total_service > $currentPackage->number_of_service_add) {
          return ['status' => 'false', 'total_service_added' => $total_service, 'package_support' => $currentPackage->number_of_service_add];
        } else {
          return ['status' => 'true'];
        }
      } elseif ($type == 'service-order') {
        if ($currentMembership) {
          if ($seller_id != 0) {
            $total_service_ordered = ServiceOrder::where([['seller_id', $seller_id], ['seller_membership_id', $currentMembership->id]])->count();
            if ($total_service_ordered >= $currentPackage->number_of_service_order) {
              return ['status' => 'false', 'total_service_ordered' => $total_service_ordered, 'package_support' => $currentPackage->number_of_service_order];
            } else {
              return ['status' => 'true'];
            }
          } else {
            return ['status' => 'true'];
          }
        } else {
          return ['status' => 'true'];
        }
      } elseif ($type == 'service-featured') {
        if ($currentMembership) {
          if ($seller_id != 0) {
            $total_service_featured = Service::where([['seller_id', $seller_id], ['is_featured', 'yes']])->count();
            if ($total_service_featured > $currentPackage->number_of_service_featured) {
              return ['status' => 'false', 'total_service_featured' => $total_service_featured, 'package_support' => $currentPackage->number_of_service_featured];
            } else {
              return ['status' => 'true'];
            }
          } else {
            return ['status' => 'true'];
          }
        } else {
          return ['status' => 'true'];
        }
      }
    } else {
      return ['status' => 'package_false'];
    }
  }
}

if (!function_exists('storeTransaction')) {
  function storeTransaction($data)
  {
    Transaction::create([
      'transcation_id' => uniqid(),
      'order_id' => $data['order_id'],
      'transcation_type' => $data['transcation_type'],
      'user_id' => $data['user_id'],
      'seller_id' => $data['seller_id'],
      'payment_status' => $data['payment_status'],
      'payment_method' => $data['payment_method'],
      'grand_total' => $data['grand_total'],
      'tax' => $data['tax'],
      'pre_balance' => $data['pre_balance'],
      'after_balance' => $data['after_balance'],
      'gateway_type' => $data['gateway_type'],
      'currency_symbol' => $data['currency_symbol'],
      'currency_symbol_position' => $data['currency_symbol_position'],
    ]);
  }
}
if (!function_exists('storeEarnings')) {
  function storeEarnings($data)
  {
    $info = App\Models\BasicSettings\Basic::first();
    if ($info) {
      $info->update([
        'life_time_earning' => $info->life_time_earning + $data['life_time_earning'],
        'total_profit' => $info->total_profit + $data['total_profit'],
      ]);
    }
  }
}

if (!function_exists('make_input_name')) {
  function make_input_name($string)
  {
    return preg_replace('/\s+/u', '_', trim($string));
  }
}

if (!function_exists('symbolPrice')) {
  function symbolPrice($price)
  {
    $basic = Basic::where('uniqid', 12345)->select('base_currency_symbol_position', 'base_currency_symbol')->first();
    if ($basic->base_currency_symbol_position == 'left') {
      $data = $basic->base_currency_symbol . number_format($price, 2);
      return str_replace(' ', '', $data);
    } elseif ($basic->base_currency_symbol_position == 'right') {
      $data = number_format($price, 2) . $basic->base_currency_symbol;
      return str_replace(' ', '', $data);
    }
  }
}
if (!function_exists('followingCheck')) {
  function followingCheck($user_id, $type, $following_id)
  {
    if (!$user_id && $type) {
      return false;
    } elseif (!$following_id) {
      return false;
    } else {
      if ($type == 'user') {
        $user = App\Models\User::where('id', $user_id)->first();
        if (!$user) {
          return false;
        }
      } elseif ($type == 'seller') {
        $seller = App\Models\Seller::where('id', $user_id)->first();
        if (!$seller) {
          return false;
        }
      }
      //already exist or not
      $data = App\Models\Follower::where([['follower_id', $user_id], ['type', $type], ['following_id', $following_id]])->first();
      if ($data) {
        return true;
      } else {
        return false;
      }
    }
  }
}

if (!function_exists('SellerAvgRating')) {
  function SellerAvgRating($seller_id)
  {
    $services = App\Models\ClientService\Service::where('seller_id', $seller_id)->select('id')->get();
    $serviceIds = [];
    foreach ($services as $service) {
      if (!in_array($service->id, $serviceIds)) {
        array_push($serviceIds, $service->id);
      }
    }
    $data = App\Models\ClientService\ServiceReview::whereIn('service_id', $serviceIds)->avg('rating');
    return number_format($data, 2);
  }
}
if (!function_exists('hexToRgb')) {
  function hexToRgb($hex)
  {
    // Remove '#' if present
    $hex = str_replace('#', '', $hex);

    // Make sure it's a valid hex color
    if (ctype_xdigit($hex) && (strlen($hex) == 6 || strlen($hex) == 3)) {
      // If it's a shorthand hex color, expand it
      if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
      } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
      }

      return "$r, $g, $b";
    } else {
      return "Invalid Hex Color";
    }
  }
}

if (!function_exists('paytabInfo')) {
  function paytabInfo()
  {
    // Could please connect me with a support.who can tell me about live api and test api's Payment url ? Now, I am using this https://secure-global.paytabs.com/payment/request url for testing puporse. Is it work for my live api ???
    // paytabs informations
    $paytabs = OnlineGateway::where('keyword', 'paytabs')->first();
    $paytabsInfo = json_decode($paytabs->information, true);
    if ($paytabsInfo['country'] == 'global') {
      $currency = 'USD';
    } elseif ($paytabsInfo['country'] == 'sa') {
      $currency = 'SAR';
    } elseif ($paytabsInfo['country'] == 'uae') {
      $currency = 'AED';
    } elseif ($paytabsInfo['country'] == 'egypt') {
      $currency = 'EGP';
    } elseif ($paytabsInfo['country'] == 'oman') {
      $currency = 'OMR';
    } elseif ($paytabsInfo['country'] == 'jordan') {
      $currency = 'JOD';
    } elseif ($paytabsInfo['country'] == 'iraq') {
      $currency = 'IQD';
    } else {
      $currency = 'USD';
    }
    return [
      'server_key' => $paytabsInfo['server_key'],
      'profile_id' => $paytabsInfo['profile_id'],
      'url'        => $paytabsInfo['api_endpoint'],
      'currency'   => $currency,
    ];
  }
}
