@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Page Headings') }}</h4>
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
        <a href="#">{{ __('Page Headings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form
          action="{{ route('admin.basic_settings.update_page_headings', ['language' => request()->input('language')]) }}"
          method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Page Headings') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-10 mx-auto">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Sellers Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="seller_page_title"
                        value="{{ !is_null($data) ? $data->seller_page_title : '' }}">
                      @error('seller_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Pricing Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="pricing_page_title"
                        value="{{ !is_null($data) ? $data->pricing_page_title : '' }}">
                      @error('pricing_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('About Us Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="about_us_page_title"
                        value="{{ !is_null($data) ? $data->about_us_page_title : '' }}">
                      @error('about_us_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Blog Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="blog_page_title"
                        value="{{ !is_null($data) ? $data->blog_page_title : '' }}">
                      @error('blog_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Post Details Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="post_details_page_title"
                        value="{{ !is_null($data) ? $data->post_details_page_title : '' }}">
                      @error('post_details_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Contact Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="contact_page_title"
                        value="{{ !is_null($data) ? $data->contact_page_title : '' }}">
                      @error('contact_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Error Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="error_page_title"
                        value="{{ !is_null($data) ? $data->error_page_title : '' }}">
                      @error('error_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('FAQ Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="faq_page_title"
                        value="{{ !is_null($data) ? $data->faq_page_title : '' }}">
                      @error('faq_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Customer Forget Password Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="forget_password_page_title"
                        value="{{ !is_null($data) ? $data->forget_password_page_title : '' }}">
                      @error('forget_password_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Customer Login Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="login_page_title"
                        value="{{ !is_null($data) ? $data->login_page_title : '' }}">
                      @error('login_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Customer Signup Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="signup_page_title"
                        value="{{ !is_null($data) ? $data->signup_page_title : '' }}">
                      @error('signup_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Services Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="services_page_title"
                        value="{{ !is_null($data) ? $data->services_page_title : '' }}">
                      @error('services_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Service Details Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="service_details_page_title"
                        value="{{ !is_null($data) ? $data->service_details_page_title : '' }}">
                      @error('service_details_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Seller Login Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="seller_login_page_title"
                        value="{{ !is_null($data) ? $data->seller_login_page_title : '' }}">
                      @error('seller_login_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Seller Signup Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="seller_signup_page_title"
                        value="{{ !is_null($data) ? $data->seller_signup_page_title : '' }}">
                      @error('seller_signup_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>{{ __('Seller Forget Password Page Title') . '*' }}</label>
                      <input type="text" class="form-control" name="seller_forget_password_page_title"
                        value="{{ !is_null($data) ? $data->seller_forget_password_page_title : '' }}">
                      @error('seller_forget_password_page_title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
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
