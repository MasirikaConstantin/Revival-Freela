@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->seller_forget_password_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_seller_forget_password }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_seller_forget_password }}
  @endif
@endsection
@php
  $title = $pageHeading->seller_forget_password_page_title ?? __('No Page Title Found');
@endphp
@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Forget Password Area Section ======-->
  <div class="user-area-section ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="user-form">
            <form action="{{ route('seller.forget.mail') }}" method="POST">
              @csrf
              <div class="form-group mb-4">
                <label>{{ __('Email Address') . '*' }}</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                @error('email')
                  <p class="text-danger mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-lg btn-primary radius-sm">{{ __('Proceed') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Forget Password Area Section ======-->
@endsection
