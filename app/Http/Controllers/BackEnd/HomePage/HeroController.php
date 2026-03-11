<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\HeroSlider;
use App\Models\HomePage\HeroStatic;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class HeroController extends Controller
{
  public function index(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['langs'] = Language::all();

    if (
      $themeVersion == 1 ||
      $themeVersion == 2
      ||
      $themeVersion == 3
    ) {
      $information['heroImgs'] = Basic::query()->select('hero_bg_img', 'hero_static_img', 'hero_video_url')->first();
      $information['heroInfo'] = $language->heroStatic()->first();
      return view('backend.home-page.hero-section.static.index', $information);
    } else {
      $information['bgImg'] = Basic::query()->pluck('hero_bg_img')->first();
      $information['sliders'] = $language->heroSlider()->orderByDesc('id')->get();

      return view('backend.home-page.hero-section.static.index', $information);
    }
  }

  public function updateBgImg(Request $request)
  {
    $bgImg = Basic::query()->pluck('hero_bg_img')->first();

    $rules = [];

    if (empty($bgImg)) {
      $rules['hero_bg_img'] = 'required';
    }
    if ($request->hasFile('hero_bg_img')) {
      $rules['hero_bg_img'] = new ImageMimeTypeRule();
    }

    $message = [
      'hero_bg_img.required' => 'The background image field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('hero_bg_img')) {
      $newImage = $request->file('hero_bg_img');
      $oldImage = $bgImg;

      $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);

      Basic::query()->updateOrCreate(
        ['uniqid' => 12345],
        ['hero_bg_img' => $imgName]
      );

      $request->session()->flash('success', 'Image updated successfully!');
    }

    return redirect()->back();
  }


  public function storeSlider(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'image' => [
        'required',
        new ImageMimeTypeRule()
      ]
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // store image in storage
    $imgName = UploadFile::store('./assets/img/hero-sliders/', $request->file('image'));

    HeroSlider::query()->create($request->except('image') + [
      'image' => $imgName
    ]);

    $request->session()->flash('success', 'New slider added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateSlider(Request $request)
  {
    $rule = [
      'image' => $request->hasFile('image') ? new ImageMimeTypeRule() : ''
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $slider = HeroSlider::query()->find($request['id']);

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $slider->image;
      $imgName = UploadFile::update('./assets/img/hero-sliders/', $newImage, $oldImage);
    }

    $slider->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imgName : $slider->image
    ]);

    $request->session()->flash('success', 'Slider updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroySlider($id)
  {

    $slider = HeroSlider::query()->find($id);

    @unlink(public_path('assets/img/hero-sliders/' . $slider->image));

    $slider->delete();

    return redirect()->back()->with('success', 'Slider deleted successfully!');
  }


  public function updateImg(Request $request)
  {
    $bgImg = Basic::query()->select('hero_bg_img', 'hero_static_img', 'hero_video_url')->first();

    $rules = [];

    if (empty($bgImg->hero_bg_img)) {
      $rules['hero_bg_img'] = 'required';
    }
    if (empty($bgImg->hero_static_img)) {
      $rules['image'] = 'required';
    }
    if ($request->hasFile('hero_bg_img')) {
      $rules['hero_bg_img'] = new ImageMimeTypeRule();
    }
    if ($request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $message = [
      'hero_bg_img.required' => 'The background image field is required.',
      'image.required' => 'The Hero Static image field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('hero_bg_img')) {
      $newImage = $request->file('hero_bg_img');
      $oldImage = $bgImg->hero_bg_img;

      $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);
      $request->session()->flash('success', 'Image updated successfully!');
    } else {
      $imgName = $bgImg->hero_bg_img;
    }

    if ($request->hasFile('image')) {
      $newImage2 = $request->file('image');
      $oldImage2 = $bgImg->hero_static_img;
      $imgName2 = UploadFile::update('./assets/img/', $newImage2, $oldImage2);
    } else {
      $imgName2 = $bgImg->hero_static_img;
    }
    $basic = Basic::where('uniqid', 12345)->first();
    $basic->hero_bg_img = $imgName;
    $basic->hero_static_img = $imgName2;
    $basic->hero_video_url = $request->hero_video_url;
    $basic->save();
    $request->session()->flash('success', 'updated successfully!');
    return back();
  }

  public function updateHeroInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    HeroStatic::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'title' => $request->title,
        'text' => $request->text
      ]
    );

    $request->session()->flash('success', 'Information updated successfully!');

    return redirect()->back();
  }
}
