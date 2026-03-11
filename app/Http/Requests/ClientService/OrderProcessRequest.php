<?php

namespace App\Http\Requests\ClientService;

use App\Models\ClientService\Form;
use Illuminate\Foundation\Http\FormRequest;

class OrderProcessRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $formId = $this->session()->get('form_id');

    $form = Form::query()->find($formId);

    $inputFields = $form->input()->orderBy('order_no', 'asc')->get();

    $ruleArray = [
      'name' => 'required',
      'email_address' => 'required|email:rfc,dns',
      'phone_number' => $this->gateway == 'iyzico' ? 'required' : '',
      'identity_number' => $this->gateway == 'iyzico' ? 'required' : '',
      'city' => $this->gateway == 'iyzico' ? 'required' : '',
      'country' => $this->gateway == 'iyzico' ? 'required' : '',
      'address' => $this->gateway == 'iyzico' ? 'required' : '',
      'zip_code' => $this->gateway == 'iyzico' ? 'required' : '',
    ];

    foreach ($inputFields as $inputField) {
      if ($inputField->is_required == 1) {
        if ($inputField->type == 8) {
          $ruleArray['form_builder_' . $inputField->name] = 'required';
        } else {
          $ruleArray[$inputField->name] = 'required';
        }
      }

      if (($inputField->type == 8) && $this->hasFile('form_builder_' . $inputField->name)) {
        $file = $this->file('form_builder_' . $inputField->name);
        $fileExtension = $file->getClientOriginalExtension();

        $maxSize = intval($inputField->file_size);
        // convert mb to kb
        $convertedSize = $maxSize * 1024;

        $ruleArray['form_builder_' . $inputField->name] = [
          function ($attribute, $value, $fail) use ($fileExtension) {
            if (strcmp('zip', $fileExtension) != 0) {
              $fail('Only .zip file is allowed.');
            }
          },
          'max:' . $convertedSize
        ];
      }
    }

    if ($this->quote_btn_status == null) {
      $ruleArray['gateway'] = 'required';
    }

    if ($this->gateway == 'stripe') {
      $ruleArray['stripeToken'] = 'required';
    }

    return $ruleArray;
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    $formId = $this->session()->get('form_id');

    $form = Form::query()->find($formId);

    $inputFields = $form->input()->orderBy('order_no', 'asc')->get();

    $messageArray = [];

    foreach ($inputFields as $inputField) {
      if ($inputField->is_required == 1) {
        if ($inputField->type == 8) {
          $messageArray['form_builder_' . $inputField->name . '.required'] = 'The ' . strtolower($inputField->label) . ' field is required.';
        } else {
          $ruleArray[$inputField->name] = 'required';
        }
      }

      if (($inputField->type == 8) && $this->hasFile('form_builder_' . $inputField->name)) {
        $maxSize = intval($inputField->file_size);

        $messageArray['form_builder_' . $inputField->name . '.max'] = 'The file must not be greater than ' . $maxSize . ' megabytes.';

        $messageArray['form_builder_' . $inputField->name . '.required'] = 'The ' . strtolower($inputField->label) . ' is required.';
      }
    }
    return $messageArray;
  }
}
