<?php

namespace App\Http\Controllers\BackEnd\ClientService;

use App\Http\Controllers\Controller;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceFaq;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
  public function index(Request $request, $id)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $service = Service::query()->find($id);
    $information['service'] = $service;

    $information['faqs'] = $service->faq()->where('language_id', $language->id)->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.client-service.faq.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'question' => 'required',
      'answer' => 'required',
      'serial_number' => 'required|numeric'
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

    ServiceFaq::query()->create($request->all());

    $request->session()->flash('success', 'New faq added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'question' => 'required',
      'answer' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $faq = ServiceFaq::query()->find($request->id);

    $faq->update($request->all());

    $request->session()->flash('success', 'FAQ updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $faq = ServiceFaq::query()->find($id);

    $faq->delete();

    return redirect()->back()->with('success', 'FAQ deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $faq = ServiceFaq::query()->find($id);

      $faq->delete();
    }

    $request->session()->flash('success', 'FAQs deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
