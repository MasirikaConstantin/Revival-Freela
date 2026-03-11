<?php

namespace App\Http\Controllers\FrontEnd\ClientService;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\ClientService\Form;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServicePackage;
use App\Models\ClientService\ServiceReview;
use App\Models\ClientService\ServiceSubcategory;
use App\Models\Language;
use App\Models\ClientService\WishlistService;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();
    $contentLanguageId = $language->id;
    if (!ServiceContent::where('language_id', $language->id)->exists() && !empty($defaultLanguage)) {
      $contentLanguageId = $defaultLanguage->id;
    }
    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_services', 'meta_description_services')->first();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);
    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $service_setings = Basic::select('is_service', 'theme_version')->first();
    if ($service_setings->is_service == 1) {

      $categories = ServiceCategory::query()->where('language_id', $contentLanguageId)->where('status', 1)->orderBy('serial_number', 'asc')->get();
      $categories->map(function ($serviceCategory) {
        $serviceCategory['subcategories'] = $serviceCategory->subcategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();
      });

      $queryResult['categories'] = $categories;

      // declare variable for searching
      $keyword = $categorySlug = $skills = $subcategorySlug =  $rating = $min = $max = $sort = $pricing = null;

      $s_serviceIds = [];
      if ($request->filled('keyword')) {
        $keyword = $request['keyword'];
        $s_service_contents = ServiceContent::where('language_id', $contentLanguageId)
          ->where('service_contents.title', 'like', '%' . $keyword . '%')
          ->orWhere('service_contents.tags', 'like', '%' . $keyword . '%')->get();

        foreach ($s_service_contents as $s_service_content) {
          if (!in_array($s_service_content->service_id, $s_serviceIds)) {
            array_push($s_serviceIds, $s_service_content->service_id);
          }
        }
      }

      if ($request->filled('category')) {
        $categorySlug = $request['category'];
      }
      if ($request->filled('skills')) {
        $skills = $request['skills'];
      }
      if ($request->filled('subcategory')) {
        $subcategorySlug = $request['subcategory'];
      }
      if ($request->filled('rating')) {
        $rating = floatval($request['rating']);
      }
      if ($request->filled('pricing')) {
        $pricing =  $request['pricing'];
      }
      if ($request->filled('min') && $request->filled('max')) {
        $min = $request['min'];
        $max = $request['max'];
      }
      if ($request->filled('sort')) {
        $sort = $request['sort'];
      }
      $paginate_count = 12;

      $services = Service::join('service_contents', 'services.id', '=', 'service_contents.service_id')
        ->join('memberships', 'services.seller_id', '=', 'memberships.seller_id')
        ->join('sellers', 'services.seller_id', '=', 'sellers.id')
        ->where([
          ['memberships.status', '=', 1],
          ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
          ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])
        ->where([['services.service_status', '=', 1], ['sellers.status', '=', 1]])
        ->when($keyword, function (Builder $query) use ($s_serviceIds) {
          return $query->whereIn('services.id', $s_serviceIds);
        })
        ->when($categorySlug, function (Builder $query, $categorySlug) {
          $category = ServiceCategory::query()->where('slug', '=', $categorySlug)->first();

          return $query->where('service_contents.service_category_id', '=', $category->id);
        })
        ->when($skills, function (Builder $query, $skills) {
          return $query->whereJsonContains('service_contents.skills', $skills);
        })
        ->when($subcategorySlug, function (Builder $query, $subcategorySlug) {
          $subcategory = ServiceSubcategory::query()->where('slug', '=', $subcategorySlug)->first();

          return $query->where('service_contents.service_subcategory_id', '=', $subcategory->id);
        })
        ->when($pricing, function (Builder $query, $pricing) {
          if ($pricing == 'fixed price') {
            return $query->where('services.quote_btn_status', 0);
          } elseif ($pricing == 'negotiable') {
            return $query->where('services.quote_btn_status', 1);
          }
        })
        ->when($rating, function (Builder $query, $rating) {
          $ratingUpperLimit = $rating + 1.00;

          return $query->where('services.average_rating', '>=', $rating);
        })
        ->when(($min && $max), function (Builder $query) use ($min, $max) {
          return $query->where('services.package_lowest_price', '>=', $min)
            ->where('services.package_lowest_price', '<=', $max);
        })
        ->where('service_contents.language_id', '=', $contentLanguageId)
        ->select('services.id', 'services.seller_id', 'services.thumbnail_image', 'service_contents.title', 'service_contents.slug', 'services.average_rating', 'services.package_lowest_price', 'services.quote_btn_status')
        ->when($sort, function (Builder $query, $sort) {
          if ($sort == 'new') {
            return $query->orderBy('services.created_at', 'desc');
          } else if ($sort == 'old') {
            return $query->orderBy('services.created_at', 'asc');
          } else if ($sort == 'ascending') {
            return $query->orderBy('services.package_lowest_price', 'asc');
          } else if ($sort == 'descending') {
            return $query->orderBy('services.package_lowest_price', 'desc');
          }
        }, function (Builder $query) {
          return $query->orderByDesc('services.id');
        })
        ->paginate($paginate_count);
      // review
      $services->map(function ($service) {
        $service['reviewCount'] = $service->review()->count();
      });

      // wishlist
      if (Auth::guard('web')->check() == true) {
        $services->map(function ($service) {
          $authUser = Auth::guard('web')->user();

          $listedService = $service->wishlist()->where('user_id', $authUser->id)->first();
          if (empty($listedService)) {
            $service['wishlisted'] = false;
          } else {
            $service['wishlisted'] = true;
          }
        });
      }

      $queryResult['services'] = $services;

      $queryResult['minPrice'] = Service::query()->where('service_status', '=', 1)->min('package_lowest_price');
      $queryResult['maxPrice'] = Service::query()->where('service_status', '=', 1)->max('package_lowest_price');
    } else {
      $queryResult['categories'] = [];
      $queryResult['services'] = [];
      $queryResult['minPrice'] = 0;
      $queryResult['maxPrice'] = 0;
    }
    $queryResult['skills'] = Skill::where([['language_id', $contentLanguageId], ['status', 1]])->get();

    $queryResult['languageId'] = $language->id;

    return view('frontend.service.index', $queryResult);
  }

  public function search_service(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();
    $contentLanguageId = $language->id;
    if (!ServiceContent::where('language_id', $language->id)->exists() && !empty($defaultLanguage)) {
      $contentLanguageId = $defaultLanguage->id;
    }
    // declare variable for searching
    $keyword = $categorySlug = $delivery_time = $skills = $subcategorySlug = $tag = $rating = $min = $max = $sort = $pricing = null;

    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }
    if ($request->filled('category')) {
      $categorySlug = $request['category'];
    }
    $d_serviceIds = [];
    if ($request->filled('delivery_time')) {
      $delivery_time = $request['delivery_time'];
      $d_services = ServicePackage::where(function ($query) use ($delivery_time) {
        if ($delivery_time == 1) {
          return $query->where('delivery_time', $delivery_time);
        } elseif ($delivery_time == 3) {
          return $query->where('delivery_time', '>=', $delivery_time);
        } elseif ($delivery_time == 7) {
          return $query->where('delivery_time', '>=', $delivery_time);
        }
      })
        ->where('language_id', $contentLanguageId)
        ->select('service_id')->get();
      foreach ($d_services as $d_service) {
        if (!in_array($d_service->service_id, $d_serviceIds)) {
          array_push($d_serviceIds, $d_service->service_id);
        }
      }
    }
    $s_serviceIds = [];
    if ($request->filled('skills')) {
      $skills = json_decode($request['skills']);
      foreach ($skills as $skill) {
        $s_service_contents = ServiceContent::where('language_id', $contentLanguageId)->whereJsonContains('skills', $skill)->select('service_id')->get();
        foreach ($s_service_contents as $s_service_content) {
          if (!in_array($s_service_content->service_id, $s_serviceIds)) {
            array_push($s_serviceIds, $s_service_content->service_id);
          }
        }
      }
    }

    if ($request->filled('keyword')) {
      $s_service_contents = ServiceContent::where('language_id', $contentLanguageId)
        ->where('service_contents.title', 'like', '%' . $keyword . '%')
        ->orWhere('service_contents.tags', 'like', '%' . $keyword . '%')->get();

      foreach ($s_service_contents as $s_service_content) {
        if (!in_array($s_service_content->service_id, $s_serviceIds)) {
          array_push($s_serviceIds, $s_service_content->service_id);
        }
      }
    }

    if ($request->filled('subcategory')) {
      $subcategorySlug = $request['subcategory'];
    }
    if ($request->filled('rating')) {
      $rating = floatval($request['rating']);
    }
    if ($request->filled('pricing')) {
      $pricing =  $request['pricing'];
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('sort')) {
      $sort = $request['sort'];
    }
    $service_setings = Basic::select('theme_version')->first();
    $paginate_count = 12;

    $services = Service::join('service_contents', 'services.id', '=', 'service_contents.service_id')
      ->join('memberships', 'services.seller_id', '=', 'memberships.seller_id')
      ->join('sellers', 'services.seller_id', '=', 'sellers.id')
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->where([['services.service_status', '=', 1], ['sellers.status', '=', 1]])
      ->when($keyword, function (Builder $query) use ($s_serviceIds) {
        return $query->whereIn('services.id', $s_serviceIds);
      })
      ->when($categorySlug, function (Builder $query, $categorySlug) {
        $category = ServiceCategory::query()->where('slug', '=', $categorySlug)->first();

        return $query->where('service_contents.service_category_id', '=', $category->id);
      })
      ->when($skills, function (Builder $query) use ($s_serviceIds) {
        return $query->whereIn('services.id', $s_serviceIds);
      })
      ->when($subcategorySlug, function (Builder $query, $subcategorySlug) {
        $subcategory = ServiceSubcategory::query()->where('slug', '=', $subcategorySlug)->first();

        return $query->where('service_contents.service_subcategory_id', '=', $subcategory->id);
      })
      ->when($pricing, function (Builder $query, $pricing) {
        if ($pricing == 'fixed price') {
          return $query->where('services.quote_btn_status', 0);
        } elseif ($pricing == 'negotiable') {
          return $query->where('services.quote_btn_status', 1);
        }
      })
      ->when($rating, function (Builder $query, $rating) {
        $ratingUpperLimit = $rating + 1.00;

        return $query->where('services.average_rating', '>=', $rating);
      })
      ->when(($min && $max), function (Builder $query) use ($min, $max) {
        return $query->where('services.package_lowest_price', '>=', $min)
          ->where('services.package_lowest_price', '<=', $max);
      })
      ->when($delivery_time, function (Builder $query) use ($d_serviceIds) {
        return $query->whereIn('services.id', $d_serviceIds);
      })
      ->where('service_contents.language_id', '=', $contentLanguageId)
      ->select('services.id', 'services.seller_id', 'services.thumbnail_image', 'service_contents.title', 'service_contents.slug', 'services.average_rating', 'services.package_lowest_price', 'services.quote_btn_status')
      ->when($sort, function (Builder $query, $sort) {
        if ($sort == 'new') {
          return $query->orderBy('services.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('services.created_at', 'asc');
        } else if ($sort == 'ascending') {
          return $query->orderBy('services.package_lowest_price', 'asc');
        } else if ($sort == 'descending') {
          return $query->orderBy('services.package_lowest_price', 'desc');
        }
      }, function (Builder $query) {
        return $query->orderByDesc('services.id');
      })
      ->paginate($paginate_count);
    // review
    $services->map(function ($service) {
      $service['reviewCount'] = $service->review()->count();
    });

    // wishlist
    if (Auth::guard('web')->check() == true) {
      $services->map(function ($service) {
        $authUser = Auth::guard('web')->user();

        $listedService = $service->wishlist()->where('user_id', $authUser->id)->first();

        if (empty($listedService)) {
          $service['wishlisted'] = false;
        } else {
          $service['wishlisted'] = true;
        }
      });
    }

    $queryResult['services'] = $services;
    $queryResult['currencyInfo'] = $this->getCurrencyInfo();
    $queryResult['languageId'] = $language->id;


    return view('frontend.service.service-section', $queryResult)->render();
  }

  public function updateWishlist(Request $request, $slug)
  {
    if (Auth::guard('web')->check() == false) {
      $request->session()->put('redirectTo', url()->previous());

      return response()->json([
        'login_route' => route('user.login')
      ]);
    } else {
      $user = Auth::guard('web')->user();

      $serviceId = ServiceContent::query()->where('slug', '=', $slug)->pluck('service_id')->first();

      $data = WishlistService::query()->where('user_id', '=', $user->id)
        ->where('service_id', '=', $serviceId)
        ->first();

      if (empty($data)) {
        WishlistService::query()->create([
          'user_id' => $user->id,
          'service_id' => $serviceId
        ]);

        return response()->json([
          'message' => 'Service added to wishlist.',
          'status' => 'Added'
        ]);
      } else {
        $data->delete();

        return response()->json([
          'message' => 'Service removed from wishlist.',
          'status' => 'Removed'
        ]);
      }
    }
  }

  public function show(Request $request, $slug, $serviceId)
  {
    if (!$serviceId) {
      abort(404);
    }

    $service_setings = Basic::select('is_service')->first();
    if ($service_setings->is_service != 1) {
      return redirect()->back();
    }
    if (Auth::guard('web')->check() == false) {
      $request->session()->put('redirectTo', url()->current());
    }

    if ($request->session()->has('package_id')) {
      $request->session()->forget('package_id');
    }

    if ($request->session()->has('addons')) {
      $request->session()->forget('addons');
    }

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $serviceInfo = Service::join('service_contents', 'services.id', '=', 'service_contents.service_id')
      ->join('service_categories', 'service_categories.id', '=', 'service_contents.service_category_id')
      ->join('service_subcategories', 'service_subcategories.id', '=', 'service_contents.service_subcategory_id')
      ->join('memberships', 'services.seller_id', '=', 'memberships.seller_id')
      ->join('sellers', 'services.seller_id', '=', 'sellers.id')
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->where([['service_contents.language_id', '=', $language->id], ['services.id', '=', $serviceId], ['services.service_status', 1], ['sellers.status', '=', 1]])
      ->select('services.id', 'services.seller_id', 'services.slider_images', 'services.video_preview_link', 'services.average_rating', 'services.live_demo_link', 'services.quote_btn_status', 'service_contents.form_id', 'service_contents.title', 'service_contents.slug', 'service_contents.description', 'service_contents.skills', 'service_contents.meta_keywords', 'service_contents.meta_description', 'service_categories.slug as category_name', 'service_subcategories.slug as sub_category_name')
      ->firstOrFail();


    $queryResult['details'] = $serviceInfo;

    $queryResult['faqs'] = $serviceInfo->faq()->where('language_id', $language->id)->orderBy('serial_number', 'asc')->get();

    if (Auth::guard('web')->check() == true) {
      $user = Auth::guard('web')->user();

      $listedService = $serviceInfo->wishlist()->where('user_id', $user->id)->first();

      $queryResult['wishlisted'] = empty($listedService) ? false : true;
    }

    if ($serviceInfo->quote_btn_status == 0) {
      $queryResult['packages'] = $serviceInfo->package()->where('language_id', $language->id)->get();

      $queryResult['currencyInfo'] = $this->getCurrencyInfo();

      $queryResult['addons'] = $serviceInfo->addon()->where('language_id', $language->id)->get();
    }

    $reviews = $serviceInfo->review()->orderByDesc('id')->get();

    $reviews->map(function ($review) {
      $review['user'] = $review->user()->first();
    });

    $queryResult['reviews'] = $reviews;
    $queryResult['bs'] = Basic::select('google_recaptcha_status', 'to_mail')->first();

    return view('frontend.service.details', $queryResult);
  }
  public function paymentFormCheck(Request $request, $slug, $serviceId)
  {
    $service = Service::findOrFail($serviceId);
    if ($service->seller_id != 0) {
      $data = sellerPermission($service->seller_id, 'service-order');
      if ($data['status'] == 'false') {
        Session::flash('error', 'The seller maximum order limit exceeded.');
        return back();
      }
    }

    if ($request->filled('package_id')) {
      $request->session()->put('package_id', $request['package_id']);
    }
    if ($request->filled('addons')) {
      $request->session()->put('addons', $request['addons']);
    }
    if ($request->filled('form_id')) {
      $request->session()->put('form_id', $request['form_id']);
    }

    // check for 'user authentication'
    if (Auth::guard('web')->check() == false) {
      $request->session()->put('redirectTo', route('service.payment_form', ['slug' => $slug, 'id' => $serviceId]));
      return redirect()->route('user.login');
    } else {
      return redirect()->route('service.payment_form.check', ['slug' => $slug, 'id' => $serviceId]);
    }
  }
  public function paymentForm(Request $request, $slug, $serviceId)
  {
    if (!$serviceId) {
      abort(404);
    }
    if (!Auth::check()) {
      return redirect()->route('user.login');
    }
    $selected_service = Service::where('id', $serviceId)->select('seller_id')->first();
    if ($selected_service->seller_id != 0) {
      $data = sellerPermission($selected_service->seller_id, 'service-order');
      if ($data['status'] == 'false') {
        Session::flash('error', 'The seller maximum order limit exceeded.');
        return back();
      }
    }


    if (session()->has('package_id')) {
      $packageId = session()->get('package_id');
      $queryResult['package'] = ServicePackage::find($packageId);
    }

    $queryResult['authUser'] = Auth::guard('web')->user();
    $quoteBtnStatus = $request['quote_btn_status'];
    $queryResult['quoteBtnStatus'] = $quoteBtnStatus;

    $misc = new MiscellaneousController();

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();
    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $language = $misc->getLanguage();

    $queryResult['serviceTitle'] = ServiceContent::query()->where('language_id', $language->id)->where('service_id', '=', $serviceId)->pluck('title')->first();
    $formId = $request->session()->get('form_id');
    if (Session::has('form_id')) {
      $formId = $request->session()->get('form_id');
    } else {
      $formId = $request->form_id;
      $request->session()->put('form_id', $request->form_id);
    }

    $form = Form::query()->find($formId);
    if ($form) {
      $queryResult['inputFields'] = $form->input()->orderBy('order_no', 'asc')->get();
    } else {
      $queryResult['inputFields'] = [];
    }

    if ($quoteBtnStatus == 0) {
      $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();

      $authorizenet = OnlineGateway::query()->whereKeyword('authorize.net')->first();
      $anetInfo = json_decode($authorizenet->information);

      if ($anetInfo->sandbox_status == 1) {
        $queryResult['anetSource'] = 'https://jstest.authorize.net/v1/Accept.js';
      } else {
        $queryResult['anetSource'] = 'https://js.authorize.net/v1/Accept.js';
      }

      $queryResult['anetClientKey'] = $anetInfo->public_client_key;
      $queryResult['anetLoginId'] = $anetInfo->api_login_id;
    }
    $serviceInfo = Service::join('service_contents', 'services.id', '=', 'service_contents.service_id')
      ->join('service_categories', 'service_categories.id', '=', 'service_contents.service_category_id')
      ->join('service_subcategories', 'service_subcategories.id', '=', 'service_contents.service_subcategory_id')
      ->where('service_contents.language_id', '=', $language->id)
      ->where('services.id', '=', $serviceId)
      ->select('services.id', 'services.slider_images', 'services.video_preview_link', 'services.live_demo_link', 'services.quote_btn_status', 'service_contents.form_id', 'service_contents.title', 'service_contents.slug', 'service_contents.description', 'service_contents.tags', 'service_contents.meta_keywords', 'service_contents.meta_description', 'service_categories.slug as category_name', 'service_subcategories.slug as sub_category_name')
      ->firstOrFail();
    $queryResult['addons'] = $serviceInfo->addon()->where('language_id', $language->id)->get();
    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_service_order', 'meta_description_service_order')->first();
    $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

    $stripe = OnlineGateway::query()->whereKeyword('stripe')->first();
    $stripeInformation = json_decode($stripe->information, true);
    $queryResult['stripeKey'] = $stripeInformation['key'];

    return view('frontend.service.payment-form', $queryResult);
  }

  public function storeReview(Request $request, $id)
  {
    $rule = [
      'rating' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()
        ->with('error', 'The rating field is required for service review.')
        ->withInput();
    }

    $serviceOrdered = false;

    // get the authenticate user
    $user = Auth::guard('web')->user();

    // then, get the orders of that user
    $orders = $user->serviceOrder()->where('payment_status', 'completed')->get();

    if (count($orders) > 0) {
      foreach ($orders as $order) {
        if ($order->service_id == $id) {
          $serviceOrdered = true;
          break;
        }
      }

      if ($serviceOrdered == true) {
        // store the review of this service
        ServiceReview::query()->updateOrCreate(
          ['user_id' => $user->id, 'service_id' => $id],
          ['rating' => $request->rating, 'comment' => $request->comment]
        );

        // store the average rating of this service
        $avgRating = ServiceReview::query()->where('service_id', '=', $id)->avg('rating');

        $service = Service::query()->find($id);

        $service->update([
          'average_rating' => $avgRating
        ]);

        $request->session()->flash('success', 'Your review submitted successfully.');
      } else {
        $request->session()->flash('error', 'You have not ordered this service yet!');
      }
    } else {
      $request->session()->flash('error', 'You have not ordered any service yet!');
    }

    return redirect()->back();
  }
}
