<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\BasicExtends;
use App\Models\Blog\Post;
use App\Models\ClientService\ServiceContent;
use App\Models\HomePage\CtaSectionInfo;
use App\Models\HomePage\Partner;
use App\Models\HomePage\Section;
use App\Models\Language;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $secInfo = Section::query()->first();
    $queryResult['secInfo'] = $secInfo;

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();
    $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();
    $queryResult['languageId'] = $language;
    $seoInfo = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();
    if (empty($seoInfo) && !empty($defaultLanguage)) {
      $seoInfo = $defaultLanguage->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();
    }
    $queryResult['seoInfo'] = $seoInfo;

    $heroInfo = $language->heroStatic()->first();
    $defaultHeroInfo = !empty($defaultLanguage) ? $defaultLanguage->heroStatic()->first() : null;
    if (empty($heroInfo) && !empty($defaultHeroInfo)) {
      $heroInfo = $defaultHeroInfo;
    } elseif (!empty($heroInfo) && !empty($defaultHeroInfo)) {
      if (empty($heroInfo->title)) {
        $heroInfo->title = $defaultHeroInfo->title;
      }
      if (empty($heroInfo->text)) {
        $heroInfo->text = $defaultHeroInfo->text;
      }
    }

    if ($themeVersion == 1 || $themeVersion == 2 || $themeVersion == 3) {
      $queryResult['heroImg'] = Basic::query()->pluck('hero_static_img', 'hero_video_url')->first();
      $queryResult['heroVideoUrl'] = Basic::query()->pluck('hero_video_url')->first();
      $queryResult['heroInfo'] = $heroInfo;
      $queryResult['heroBgImg'] = Basic::query()->pluck('hero_bg_img')->first();
    } else {
      $queryResult['heroInfo'] = $heroInfo;
    }

    $categories = $language->serviceCategory()->where('status', 1)->orderBy('serial_number', 'asc')->limit(8)->get();
    if ($categories->isEmpty() && !empty($defaultLanguage)) {
      $categories = $defaultLanguage->serviceCategory()->where('status', 1)->orderBy('serial_number', 'asc')->limit(8)->get();
    }
    $queryResult['categories'] = $categories;

    if ($secInfo->about_section_status == 1) {
      $queryResult['aboutInfo'] = DB::table('basic_settings')->select('about_section_image', 'about_section_video_link')->first();

      $aboutData = $language->aboutSection()->first();
      if (empty($aboutData) && !empty($defaultLanguage)) {
        $aboutData = $defaultLanguage->aboutSection()->first();
      }
      $queryResult['aboutData'] = $aboutData;
    }

    $secTitle = $language->sectionTitle()->first();
    if (empty($secTitle) && !empty($defaultLanguage)) {
      $secTitle = $defaultLanguage->sectionTitle()->first();
    }
    $queryResult['secTitle'] = $secTitle;

    if ($secInfo->features_section_status == 1) {
      $queryResult['featureBgImg'] = Basic::query()->pluck('feature_bg_img')->first();
      $allFeature = $language->feature()->orderByDesc('id')->get();
      if ($allFeature->isEmpty() && !empty($defaultLanguage)) {
        $allFeature = $defaultLanguage->feature()->orderByDesc('id')->get();
      }
      $queryResult['allFeature'] = $allFeature;
    }
    $service_setings = Basic::select('is_service')->first();
    $queryResult['service_setings'] = $service_setings;
    if ($secInfo->featured_services_section_status == 1 && $service_setings->is_service == 1) {
      $categories = $language->serviceCategory()->where('status', 1)->where('is_featured', 'yes')->orderBy('serial_number', 'asc')->get();
      if ($categories->isEmpty() && !empty($defaultLanguage)) {
        $categories = $defaultLanguage->serviceCategory()->where('status', 1)->where('is_featured', 'yes')->orderBy('serial_number', 'asc')->get();
      }

      $categories->map(function ($category) {
        $category['serviceContent'] = ServiceContent::query()->whereHas('service', function (Builder $query) {

          $query->where('service_status', '=', 1)
            ->where('is_featured', '=', 'yes')
            ->join('memberships', 'services.seller_id', '=', 'memberships.seller_id')
            ->join('sellers', 'services.seller_id', '=', 'sellers.id')
            ->where([
              ['memberships.status', '=', 1],
              ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
              ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
              ['sellers.status', '=', 1],
            ]);
        })
          ->where('service_category_id', '=', $category->id)
          ->get();
      });

      $queryResult['featuredCategories'] = $categories;
    }

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    if ($secInfo->testimonials_section_status == 1) {
      $queryResult['testimonialBgImg'] = Basic::query()->pluck('testimonial_bg_img')->first();
    }
    $testimonials = $language->testimonial()->orderByDesc('id')->get();
    if ($testimonials->isEmpty() && !empty($defaultLanguage)) {
      $testimonials = $defaultLanguage->testimonial()->orderByDesc('id')->get();
    }
    $queryResult['testimonials'] = $testimonials;

    if ($secInfo->blog_section_status == 1) {
      $posts = Post::query()->join('post_informations', 'posts.id', '=', 'post_informations.post_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
        ->where('post_informations.language_id', '=', $language->id)
        ->select('posts.id', 'posts.image', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug', 'post_informations.title', 'post_informations.slug', 'post_informations.author', 'post_informations.content', 'posts.created_at')
        ->orderBy('posts.created_at', 'desc')
        ->limit(3)
        ->get();
      if ($posts->isEmpty() && !empty($defaultLanguage)) {
        $posts = Post::query()->join('post_informations', 'posts.id', '=', 'post_informations.post_id')
          ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
          ->where('post_informations.language_id', '=', $defaultLanguage->id)
          ->select('posts.id', 'posts.image', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug', 'post_informations.title', 'post_informations.slug', 'post_informations.author', 'post_informations.content', 'posts.created_at')
          ->orderBy('posts.created_at', 'desc')
          ->limit(3)
          ->get();
      }
      $queryResult['posts'] = $posts;
    }

    if ($secInfo->partners_section_status == 1) {
      $queryResult['partners'] = Partner::query()->orderByDesc('id')->get();
    }

    if ($secInfo->cta_section_status == 1) {
      $ctaSectionInfo = CtaSectionInfo::where('language_id', $language->id)->first();
      if (empty($ctaSectionInfo) && !empty($defaultLanguage)) {
        $ctaSectionInfo = CtaSectionInfo::where('language_id', $defaultLanguage->id)->first();
      }
      $queryResult['ctaSectionInfo'] = $ctaSectionInfo;
      $queryResult['ctaBgImg'] = Basic::query()->pluck('cta_bg_img')->first();
    }
    $basicExtends = BasicExtends::where('language_id', $language->id)->first();
    if (empty($basicExtends) && !empty($defaultLanguage)) {
      $basicExtends = BasicExtends::where('language_id', $defaultLanguage->id)->first();
    }
    $queryResult['BasicExtends'] = $basicExtends;

    if ($themeVersion == 1) {
      return view('frontend.home.index-v1', $queryResult);
    } else if ($themeVersion == 2) {
      return view('frontend.home.index-v2', $queryResult);
    } else if ($themeVersion == 3) {
      return view('frontend.home.index-v3', $queryResult);
    }
  }

  public function pricing()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('pricing_page_meta_keywords', 'pricing_page_meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $queryResult['monthly_packages'] = Package::where([['status', '1'], ['term', 'monthly']])->get();
    $queryResult['yearly_packages'] = Package::where([['status', '1'], ['term', 'yearly']])->get();
    $queryResult['lifetime_packages'] = Package::where([['status', '1'], ['term', 'lifetime']])->where('id', '<>', 999999)->get();
    return view('frontend.pricing', $queryResult);
  }
}
