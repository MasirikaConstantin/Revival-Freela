<?php

namespace App\Http\Controllers\BackEnd\Footer;

use App\Http\Controllers\Controller;
use App\Models\Footer\FooterContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ContentController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the footer content info of that language from db
    $information['data'] = $language->footerContent()->first();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.footer.content', $information);
  }

  public function update(Request $request)
  {
    $rules = [
      'footer_background_color' => 'required',
      'about_company' => 'required',
      'copyright_text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    FooterContent::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'footer_background_color' => $request['footer_background_color'],
        'about_company' => $request['about_company'],
        'copyright_text' => Purifier::clean($request['copyright_text'])
      ]
    );

    $request->session()->flash('success', 'Information updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
