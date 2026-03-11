<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\CtaSectionInfo;
use App\Models\HomePage\Partner;
use App\Models\HomePage\Section;
use App\Models\Language;
use Exception;
use Illuminate\Support\Facades\DB;

class AboutUsController extends Controller
{
    public function index()
    {
        try {
            $misc = new MiscellaneousController();
            $language = $misc->getLanguage();
            $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();
            $seoInfo = $language->seoInfo()->select('meta_keyword_aboutus', 'meta_description_aboutus')->first();
            if (empty($seoInfo) && !empty($defaultLanguage)) {
                $seoInfo = $defaultLanguage->seoInfo()->select('meta_keyword_aboutus', 'meta_description_aboutus')->first();
            }
            $queryResult['seoInfo'] = $seoInfo;
            $queryResult['secInfo'] = Section::query()->first();;
            $queryResult['pageHeading'] = $misc->getPageHeading($language);
            $queryResult['breadcrumb'] = $misc->getBreadcrumb();
            $queryResult['testimonialBgImg'] = Basic::query()->pluck('testimonial_bg_img')->first();
            $queryResult['aboutInfo'] = DB::table('basic_settings')->select('about_section_image', 'about_section_video_link')->first();
            $aboutData = $language->aboutSection()->first();
            if (empty($aboutData) && !empty($defaultLanguage)) {
                $aboutData = $defaultLanguage->aboutSection()->first();
            }
            $queryResult['aboutData'] = $aboutData;

            $testimonials = $language->testimonial()->orderByDesc('id')->get();
            if ($testimonials->isEmpty() && !empty($defaultLanguage)) {
                $testimonials = $defaultLanguage->testimonial()->orderByDesc('id')->get();
            }
            $queryResult['testimonials'] = $testimonials;
            $queryResult['partners'] = Partner::query()->orderByDesc('id')->get();
            $ctaSectionInfo = CtaSectionInfo::where('language_id', $language->id)->first();
            if (empty($ctaSectionInfo) && !empty($defaultLanguage)) {
                $ctaSectionInfo = CtaSectionInfo::where('language_id', $defaultLanguage->id)->first();
            }
            $queryResult['ctaSectionInfo'] = $ctaSectionInfo;
            $queryResult['ctaBgImg'] = Basic::query()->pluck('cta_bg_img')->first();
            return view('frontend.aboutus', $queryResult);
        } catch (Exception $e) {

            abort(404);
        }
    }
}
