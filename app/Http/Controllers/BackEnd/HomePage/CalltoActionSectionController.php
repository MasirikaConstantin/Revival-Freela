<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\CtaSectionInfo;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalltoActionSectionController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['bgImg'] = Basic::query()->pluck('cta_bg_img')->first();
        $information['language'] = $language;
        $information['langs'] = Language::get();
        $information['data'] = CtaSectionInfo::where('language_id', $language->id)->first();
        return view('backend.home-page.call_to_action_section', $information);
    }

    public function updateBgImg(Request $request)
    {
        $bgImg = Basic::query()->pluck('cta_bg_img')->first();

        $rules = [];

        if (empty($bgImg)) {
            $rules['cta_bg_img'] = 'required';
        }
        if ($request->hasFile('cta_bg_img')) {
            $rules['cta_bg_img'] = new ImageMimeTypeRule();
        }

        $message = [
            'cta_bg_img.required' => 'The background image field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('cta_bg_img')) {
            $newImage = $request->file('cta_bg_img');
            $oldImage = $bgImg;

            $imgName = UploadFile::update('./assets/img/', $newImage, $oldImage);

            Basic::query()->updateOrCreate(
                ['uniqid' => 12345],
                ['cta_bg_img' => $imgName]
            );

            $request->session()->flash('success', 'Image updated successfully!');
        }

        return redirect()->back();
    }

    public function updateInfo(Request $request, $language)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $data = CtaSectionInfo::where('language_id', $language->id)->first();
        if (empty($data)) {
            $rules['image'] = 'required';
        }
        if ($request->hasFile('image')) {
            $rules['image'] = new ImageMimeTypeRule();
        }
        $rules['title'] = 'required';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        
        $info = [];

        $info['title'] = $request->title;
        $info['button_text'] = $request->button_text;
        $info['button_url'] = $request->button_url;
        if (empty($data)) {
            if ($request->hasFile('image')) {
                $newImage = $request->file('image');
                $info['image'] = UploadFile::store('./assets/img/', $newImage);
            }
        } else {
            $oldImage = $data->image;
            if ($request->hasFile('image')) {
                $newImage = $request->file('image');
                $info['image'] = UploadFile::update('./assets/img/', $newImage, $oldImage);
            } else {
                $info['image'] = $oldImage;
            }
        }

        CtaSectionInfo::query()->updateOrCreate(['language_id' => $language->id], $info);

        $request->session()->flash('success', 'Information updated successfully!');

        return redirect()->back();
    }
}
