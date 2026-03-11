<?php

namespace App\Http\Requests\User;

use App\Models\BasicSettings\Basic;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
      'username' => 'required|unique:users|max:255',
      'email_address' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required',
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

    return [
      'password_confirmation.required' => 'The confirm password field is required.',
      'g-recaptcha-response.required' => ($recaptchaStatus == 1) ? 'Please verify that you are not a robot.' : '',
      'g-recaptcha-response.captcha' => ($recaptchaStatus == 1) ? 'Captcha error! try again later or contact site admin.' : ''
    ];
  }
}
