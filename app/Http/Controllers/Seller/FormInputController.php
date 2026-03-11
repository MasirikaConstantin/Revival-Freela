<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ClientService\Form;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Requests\ClientService\FormInput\StoreRequest;
use App\Http\Requests\ClientService\FormInput\UpdateRequest;
use App\Models\ClientService\FormInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class FormInputController extends Controller
{
    public function manageInput($id, Request $request)
    {

        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;

        // set the selected language as locale
        App::setLocale($language->code);

        $form = Form::query()->where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();
        $information['form'] = $form;

        $information['inputFields'] = $form->input()->orderBy('order_no', 'asc')->get();

        return view('seller.form-input.index', $information);
    }

    public function storeInput(StoreRequest $request, $id)
    {
        // get the input 'name' attribute
        $inputName = createInputName($request['label']);

        $orderNo = FormInput::query()->where('form_id', '=', $id)->max('order_no');

        FormInput::query()->create($request->except('form_id', 'name', 'options', 'order_no') + [
            'form_id' => $id,
            'name' => $inputName,
            'options' => $request->filled('options') ? json_encode($request['options']) : NULL,
            'order_no' => is_null($orderNo) ? 1 : ($orderNo + 1)
        ]);

        $request->session()->flash('success', 'Input field added successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function editInput(Request $request, $form_id, $input_id)
    {
        $information['language'] = Language::query()->where('code', '=', $request->language)->firstOrFail();

        $inputField = FormInput::query()->find($input_id);
        $information['inputField'] = $inputField;

        $information['options'] = !is_null($inputField->options) ? json_decode($inputField->options) : [];

        return view('seller.form-input.edit', $information);
    }

    public function updateInput(UpdateRequest $request, $id)
    {
        // get the input field
        $formInput = FormInput::query()->find($id);

        // get the input 'name' attribute
        $inputName = createInputName($request['label']);

        $formInput->update($request->except('name', 'options') + [
            'name' => $inputName,
            'options' => $request->filled('options') ? json_encode($request['options']) : NULL
        ]);

        $request->session()->flash('success', 'Input field updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function destroyInput($id)
    {
        // get the input field
        $formInput = FormInput::query()->find($id);

        $formInput->delete();

        return redirect()->back()->with('success', 'Input Field deleted successfully!');
    }

    public function sortInput(Request $request)
    {
        $ids = $request['ids'];
        $orders = $request['orders'];

        for ($i = 0; $i < sizeof($ids); $i++) {
            // get the input field
            $inputField = FormInput::query()->find($ids[$i]);

            $inputField->update([
                'order_no' => $orders[$i]
            ]);
        }

        return Response::json([
            'status' => 'Input fields sorted successfully.'
        ], 200);
    }
}
