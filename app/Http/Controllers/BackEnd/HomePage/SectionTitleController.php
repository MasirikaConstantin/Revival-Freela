<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\SectionTitle;
use App\Models\Language;
use Illuminate\Http\Request;

class SectionTitleController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->sectionTitle()->first();

    $information['langs'] = Language::all();

    $information['themeVersion'] = Basic::query()->pluck('theme_version')->first();

    return view('backend.home-page.section-titles', $information);
  }

  public function update(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    SectionTitle::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'category_section_title' => $request->category_section_title,
        'featured_services_section_title' => $request->featured_services_section_title,
        'testimonials_section_title' => $request->testimonials_section_title,
        'blog_section_title' => $request->blog_section_title,
        'featured_products_section_title' => $request->featured_products_section_title,
        'newsletter_section_title' => $request->newsletter_section_title
      ]
    );

    $request->session()->flash('success', 'Section titles updated successfully!');

    return redirect()->back();
  }
}
