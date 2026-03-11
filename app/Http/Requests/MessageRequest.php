<?php

namespace App\Http\Requests;

use App\Models\BasicSettings\Basic;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
    $ruleArray = [];

    if (!$this->filled('msg') && !$this->hasFile('attachment')) {
      $ruleArray['msg'] = 'required';
    }

    if ($this->hasFile('attachment')) {
      $file = $this->file('attachment');
      $fileExtension = $file->getClientOriginalExtension();

      $allowedExtensions = array('jpg', 'jpeg', 'png', 'rar', 'zip', 'txt', 'doc', 'docx', 'pdf');
      $info = Basic::select('chat_max_file')->first();
      $ruleArray['attachment'] = [
        "max:$info->chat_max_file",
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('The ' . $attribute . ' must be a file of type: jpg, jpeg, png, rar, zip, txt, doc, docx or pdf.');
          }
        }
      ];
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
    $messageArray = [];

    if (!$this->filled('msg') && !$this->hasFile('attachment')) {
      $messageArray['msg.required'] = 'Please enter your message.';
    }

    return $messageArray;
  }
}
