<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\Partner;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
  public function index()
  {
    $partners = Partner::query()->orderByDesc('id')->get();

    return view('backend.home-page.partners.index', compact('partners'));
  }

  public function store(Request $request)
  {
    $rules = [
      'image' => [
        'required',
        $request->hasFile('image') ? new ImageMimeTypeRule() : ''
      ],
      'url' => 'required|url',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $imageName = UploadFile::store('./assets/img/partners/', $request->file('image'));

    Partner::query()->create($request->except('image') + [
      'image' => $imageName
    ]);

    $request->session()->flash('success', 'New partner added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'image' => $request->hasFile('image') ? new ImageMimeTypeRule() : '',
      'url' => 'required|url',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $partner = Partner::query()->findOrFail($request->id);

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $partner->image;
      $imageName = UploadFile::update('./assets/img/partners/', $newImage, $oldImage);
    }

    $partner->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $partner->image
    ]);

    $request->session()->flash('success', 'Partner updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy(Request $request, $id)
  {
    $partner = Partner::query()->findOrFail($id);

    @unlink(public_path('assets/img/partners/' . $partner->image));

    $partner->delete();

    $request->session()->flash('success', 'Partner deleted successfully!');

    return redirect()->back();
  }
}
