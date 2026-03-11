<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\BasicExtends;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['langs'] = Language::all();
    $information['language'] = $language;

    $information['data'] = BasicExtends::query()->select('news_letter_section_text')->where('language_id', $language->id)->first();

    return view('backend.footer.newsletter-section', $information);
  }

  public function updateText(Request $request, $language_id)
  {
    $rules = [
      'news_letter_section_text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }
    $data = BasicExtends::where('language_id', $request->language_id)->first();
    if (empty($data)) {
      $data->language_id = $language_id;
    }
    $data->news_letter_section_text = $request->news_letter_section_text;
    $data->save();
    $request->session()->flash('success', 'updated successfully!');

    return redirect()->back();
  }
}
