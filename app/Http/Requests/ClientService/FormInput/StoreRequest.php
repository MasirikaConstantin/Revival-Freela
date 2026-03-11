<?php

namespace App\Http\Requests\ClientService\FormInput;

use App\Models\ClientService\Form;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
    // get the input 'name' attribute
    $inputName = createInputName($this['label']);

    // get the service-form & it's all input fields
    $form = Form::query()->find($this->id);
    $inputFields = $form->input()->get();

    return [
      'type' => 'required|numeric',
      'is_required' => 'required|numeric',
      'label' => [
        'required',
        function ($attribute, $value, $fail) use ($inputName, $inputFields) {
          foreach ($inputFields as $input) {
            if ($input->name === $inputName) {
              $fail('The input field is already exist.');
              break;
            }
          }
        }
      ],
      'placeholder' => 'required_unless:type,4,8',
      'options' => 'required_if:type,3,4',
      'file_size' => 'required_if:type,8|numeric'
    ];
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'is_required.required' => 'The required status field is required.',
      'placeholder.required_unless' => 'The placeholder field is required unless input type is checkbox or file.',
      'options.required_if' => 'The options are required when input type is select or checkbox.',
      'file_size.required_if' => 'The file size field is required when input type is file.'
    ];
  }
}
