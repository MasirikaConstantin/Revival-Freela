<?php

namespace App\Http\Requests\User;

use App\Models\BasicSettings\Basic;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
    $recaptchaStatus = Basic::query()->pluck('google_recaptcha_status')->first();

    return [
      'username' => 'required',
      'password' => 'required',
      'g-recaptcha-response' => ($recaptchaStatus == 1) ? 'required|captcha' : ''
    ];
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    $recaptchaStatus = Basic::query()->pluck('google_recaptcha_status')->first();

    if ($recaptchaStatus == 1) {
      return [
        'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.'
      ];
    } else {
      return [];
    }
  }
}
