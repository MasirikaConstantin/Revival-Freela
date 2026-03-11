<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageHeadingRequest extends FormRequest
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
    return [
      'blog_page_title' => 'required',
      'pricing_page_title' => 'required',
      'post_details_page_title' => 'required',
      'contact_page_title' => 'required',
      'error_page_title' => 'required',
      'faq_page_title' => 'required',
      'forget_password_page_title' => 'required',
      'login_page_title' => 'required',
      'signup_page_title' => 'required',
      'services_page_title' => 'required',
      'service_details_page_title' => 'required',
      'about_us_page_title' => 'required',
      'seller_page_title' => 'required',
      'seller_login_page_title' => 'required',
      'seller_signup_page_title' => 'required',
      'seller_forget_password_page_title' => 'required',
    ];
  }
}
