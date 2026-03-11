<?php

namespace App\Http\Controllers\BackEnd\ClientService;

use App\Http\Controllers\Controller;
use App\Models\ClientService\Form;
use App\Models\ClientService\ServiceContent;
use App\Models\Language;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;
    $information['sellers'] = Seller::select('username', 'id')->where('id', '!=', 0)->get();
    $seller = null;
    if ($request->filled('seller')) {
      $seller = $request->seller;
    }

    $information['forms'] = $language->form()
      ->when($seller, function ($query) use ($seller) {
        if ($seller == 'admin') {
          $seller_id = null;
        } else {
          $seller_id = $seller;
        }
        return $query->where('seller_id', $seller_id);
      })
      ->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.client-service.form.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => 'required'
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

    Form::query()->create($request->all());

    $request->session()->flash('success', 'Form added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rule = [
      'name' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $form = Form::query()->find($request['id']);

    $form->update($request->all());

    $request->session()->flash('success', 'Form updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id, Request $request)
  {
    $form = Form::query()->find($id);

    $serviceContent = ServiceContent::query()->where('form_id', '=', $form->id)->first();

    if (empty($serviceContent)) {
      $inputFields = $form->input()->get();

      if (count($inputFields) > 0) {
        foreach ($inputFields as $inputField) {
          $inputField->delete();
        }
      }

      $form->delete();

      $request->session()->flash('success', 'Form deleted successfully!');

      return redirect()->back();
    } else {
      $request->session()->flash('error', 'Sorry, this form cannot be deleted right now. This form is attached with the services. Either you have to delete those services or change the form of those services.');

      return redirect()->back();
    }
  }
}
