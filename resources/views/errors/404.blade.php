@extends('frontend.layout')

@php
  $misc = new App\Http\Controllers\FrontEnd\MiscellaneousController();

  $language = $misc->getLanguage();
  $pageHeading = $language
      ->pageName()
      ->select('error_page_title')
      ->first();
  $breadcrumb = $misc->getBreadcrumb();
@endphp

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->error_page_title }}
  @endif
@endsection

@section('content')
  @php $pageTitle = !empty($pageHeading) ? $pageHeading->error_page_title : ''; @endphp

  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $pageTitle])

  <!--====== 404 PART START ======-->
  <section class="error-area ptb-100">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-6">
          <div class="image mb-30">
            <svg class="mw-100" data-src="{{ asset('assets/img/404.svg') }}" data-unique-ids="disabled" data-cache="disabled"></svg>
          </div>
          <div class="error-text">
            <h2>{{ __('You are lost') . '.' }}</h2>
            <p>
              {{ __('The page you are looking for') . ', ' . __('might have been moved') . ',' }}<br>
              {{ __('renamed') . ', ' . __('or might never existed') . '.' }}
            </p>
            <a href="{{ route('index') }}" class="btn btn-lg btn-primary radius-sm">{{ __('Go Back Home') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== 404 PART END ======-->
@endsection
