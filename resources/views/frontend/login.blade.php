@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->login_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_customer_login }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_customer_login }}
  @endif
@endsection
@php
  $title = $pageHeading->login_page_title ?? __('No Page Title Found');
@endphp
@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Login Area Section ======-->
  <div class="user-area-section ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          @if ($bs->facebook_login_status == 1 || $bs->google_login_status == 1)
            <div class="mb-5">
              <div class="btn-group btn-group-toggle d-flex">
                @if ($bs->facebook_login_status == 1)
                  <a class="btn py-2 facebook-login-btn" href="{{ route('user.login.facebook') }}">
                    <i
                      class="fab fa-facebook-f {{ $currentLanguageInfo->direction == 0 ? 'me-2' : 'ms-2' }}"></i>{{ __('Login via Facebook') }}
                  </a>
                @endif

                @if ($bs->google_login_status == 1)
                  <a class="btn py-2 google-login-btn" href="{{ route('user.login.google') }}">
                    <i
                      class="fab fa-google {{ $currentLanguageInfo->direction == 0 ? 'me-2' : 'ms-2' }}"></i>{{ __('Login via Google') }}
                  </a>
                @endif
              </div>
            </div>
          @endif

          <div class="user-form">
            <form action="{{ route('user.login_submit') }}" method="POST">
              @csrf
              <div class="form-group mb-4">
                <label>{{ __('Username') . '*' }}</label>
                <input type="text" class="form-control" name="username" >
                @error('username')
                  <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group mb-4">
                <label>{{ __('Password') . '*' }}</label>
                <input type="password" class="form-control" name="password" >
                @error('password')
                  <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
              </div>

              @if ($bs->google_recaptcha_status == 1)
                <div class="form-group my-4">
                  {!! NoCaptcha::renderJs() !!}
                  {!! NoCaptcha::display() !!}

                  @error('g-recaptcha-response')
                    <p class="text-danger mt-1">{{ $message }}</p>
                  @enderror
                </div>
              @endif

              <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary radius-sm">{{ __('Login') }}</button>
              </div>



              <div class="justify-content-between d-flex mt-3">
                <p>{{ __("Don't have an account") . '?' }} <a
                    href="{{ route('user.signup') }}">{{ __('Signup Now') }}</a></p>

                <a href="{{ route('user.forget_password') }}">{{ __('Lost your password?') }}</a></a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Login Area Section ======-->
@endsection
