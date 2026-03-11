<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Feature;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
  public function index(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();
    $information['themeVersion'] = $themeVersion;

    if ($themeVersion == 1) {
      $information['bgImg'] = Basic::query()->pluck('feature_bg_img')->first();
    }

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['allFeature'] = $language->feature()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.home-page.feature-section.index', $information);
  }

  public function updateBgImg(Request $request)
  {
    $bgImg = Basic::query()->pluck('feature_bg_img')->first();

    $rules = [];

    if (empty($bgImg)) {
      $rules['feature_bg_img'] = 'required';
    }
    if ($request->hasFile('feature_bg_img')) {
      $rules['feature_bg_img'] = new ImageMimeTypeRule();
    }

    $message = [
      'feature_bg_img.required' => 'The background image field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('feature_bg_img')) {
      $newImage = $request->file('feature_bg_img');
      $oldImage = $bgImg;

      $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);

      Basic::query()->updateOrCreate(
        ['uniqid' => 12345],
        ['feature_bg_img' => $imgName]
      );

      $request->session()->flash('success', 'Image updated successfully!');
    }

    return redirect()->back();
  }


  public function storeFeature(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'color' => ($themeVersion != 1) ? 'required' : '',
      'title' => 'required'
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

    Feature::query()->create($request->except('language'));

    $request->session()->flash('success', 'New feature added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateFeature(Request $request)
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $rules = [
      'icon' => 'required',
      'color' => ($themeVersion != 1) ? 'required' : '',
      'title' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $feature = Feature::query()->find($request->id);

    $feature->update($request->except('language'));

    $request->session()->flash('success', 'Feature updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyFeature($id)
  {
    $feature = Feature::query()->find($id);

    $feature->delete();

    return redirect()->back()->with('success', 'Feature deleted successfully!');
  }

  public function bulkDestroyFeature(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $feature = Feature::query()->find($id);

      $feature->delete();
    }

    $request->session()->flash('success', 'Features deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
