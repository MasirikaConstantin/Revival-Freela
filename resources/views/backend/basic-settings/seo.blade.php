@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('SEO Informations') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('SEO Informations') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.update_seo', ['language' => request()->input('language')]) }}"
          method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update SEO Informations') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Home Page') }}</label>
                  <input class="form-control" name="meta_keyword_home"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_home }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Home Page') }}</label>
                  <textarea class="form-control" name="meta_description_home" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_home }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Services Page') }}</label>
                  <input class="form-control" name="meta_keyword_services"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_services }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Services Page') }}</label>
                  <textarea class="form-control" name="meta_description_services" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_services }}</textarea>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Seller Page') }}</label>
                  <input class="form-control" name="seller_page_meta_keywords"
                    value="{{ is_null($data) ? '' : $data->seller_page_meta_keywords }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Seller Page') }}</label>
                  <textarea class="form-control" name="seller_page_meta_description" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->seller_page_meta_description }}</textarea>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Pricing Page') }}</label>
                  <input class="form-control" name="pricing_page_meta_keywords"
                    value="{{ is_null($data) ? '' : $data->pricing_page_meta_keywords }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Pricing Page') }}</label>
                  <textarea class="form-control" name="pricing_page_meta_description" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->pricing_page_meta_description }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For About Us Page') }}</label>
                  <input class="form-control" name="meta_keyword_aboutus"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_aboutus }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For About Us Page') }}</label>
                  <textarea class="form-control" name="meta_description_aboutus" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_aboutus }}</textarea>
                </div>
              </div>



              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Blog Page') }}</label>
                  <input class="form-control" name="meta_keyword_blog"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_blog }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Blog Page') }}</label>
                  <textarea class="form-control" name="meta_description_blog" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_blog }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For FAQ Page') }}</label>
                  <input class="form-control" name="meta_keyword_faq"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_faq }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For FAQ Page') }}</label>
                  <textarea class="form-control" name="meta_description_faq" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_faq }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Contact Page') }}</label>
                  <input class="form-control" name="meta_keyword_contact"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_contact }}" placeholder="Enter Meta Keywords"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Contact Page') }}</label>
                  <textarea class="form-control" name="meta_description_contact" rows="5" placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_contact }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Customer Login Page') }}</label>
                  <input class="form-control" name="meta_keyword_customer_login"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_customer_login }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Customer Login Page') }}</label>
                  <textarea class="form-control" name="meta_description_customer_login" rows="5"
                    placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_customer_login }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Customer Signup Page') }}</label>
                  <input class="form-control" name="meta_keyword_customer_signup"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_customer_signup }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Customer Signup Page') }}</label>
                  <textarea class="form-control" name="meta_description_customer_signup" rows="5"
                    placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_customer_signup }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Customer Forget Password Page') }}</label>
                  <input class="form-control" name="meta_keyword_customer_forget_password"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_customer_forget_password }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Customer Forget Password Page') }}</label>
                  <textarea class="form-control" name="meta_description_customer_forget_password" rows="5"
                    placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_customer_forget_password }}</textarea>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Seller Login Page') }}</label>
                  <input class="form-control" name="meta_keyword_seller_login"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_seller_login }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Seller Login Page') }}</label>
                  <textarea class="form-control" name="meta_description_seller_login" rows="5"
                    placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_seller_login }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Seller Signup Page') }}</label>
                  <input class="form-control" name="meta_keyword_seller_signup"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_seller_signup }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Seller Signup Page') }}</label>
                  <textarea class="form-control" name="meta_description_seller_signup" rows="5"
                    placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_seller_signup }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>{{ __('Meta Keywords For Seller Forget Password Page') }}</label>
                  <input class="form-control" name="meta_keyword_seller_forget_password"
                    value="{{ is_null($data) ? '' : $data->meta_keyword_seller_forget_password }}"
                    placeholder="Enter Meta Keywords" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label>{{ __('Meta Description For Seller Forget Password Page') }}</label>
                  <textarea class="form-control" name="meta_description_seller_forget_password" rows="5"
                    placeholder="Enter Meta Description">{{ is_null($data) ? '' : $data->meta_description_seller_forget_password }}</textarea>
                </div>
              </div>

            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
