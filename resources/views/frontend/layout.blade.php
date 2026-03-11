<!DOCTYPE html>
<html lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>

<head>
  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="@yield('metaKeywords')">
  <meta name="description" content="@yield('metaDescription')">

  {{-- title --}}
  <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}" type="image/x-icon">

  {{-- include styles --}}
  @if ($basicInfo->theme_version == 1)
    @includeIf('frontend.partials.styles.style-v1')
  @elseif ($basicInfo->theme_version == 2)
    @includeIf('frontend.partials.styles.style-v2')
  @elseif ($basicInfo->theme_version == 3)
    @includeIf('frontend.partials.styles.style-v3')
  @endif
  @php
    $primaryColor = $basicInfo->primary_color;
  @endphp
  <style>
    :root {
      --color-primary: #{{ $primaryColor }};
      --color-primary-rgb: {{ hexToRgb($primaryColor) }};
    }

    .breadcrumbs-area::after {
      background-color: #{{ $basicInfo->breadcrumb_overlay_color }};
      opacity: {{ $basicInfo->breadcrumb_overlay_opacity }};
    }
  </style>
</head>

<body>

  <!-- Preloader start -->
  <div id="preLoader">
    <div class="loader"></div>
  </div>
  <!-- Preloader end -->
  <div class="request-loader">
    <div class="loader-inner">
      <span class="loader"></span>
    </div>
  </div>

  <div class="main-wrapper">

    <!-- Header-area start -->
    @if ($basicInfo->theme_version == 1)
      @includeIf('frontend.partials.header.header-nav-v1')
    @elseif ($basicInfo->theme_version == 2)
      @includeIf('frontend.partials.header.header-nav-v2')
    @elseif ($basicInfo->theme_version == 3)
      @includeIf('frontend.partials.header.header-nav-v3')
    @endif
    <!-- Header-area end -->
    @yield('content')
  </div>

  {{-- announcement popup --}}
  @includeIf('frontend.partials.popups')

  {{-- cookie alert --}}
  @if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
    @includeIf('cookie-consent::index')
  @endif
  {{-- floating whatsapp button --}}
  @if ($basicInfo->whatsapp_status == 1)
    <div class="whatsapp-btn"></div>
  @endif

  <!-- Footer-area start -->
  @if ($basicInfo->theme_version == 1)
    @includeIf('frontend.partials.footer.footer-v1')
  @elseif ($basicInfo->theme_version == 2)
    @includeIf('frontend.partials.footer.footer-v2')
  @elseif ($basicInfo->theme_version == 3)
    @includeIf('frontend.partials.footer.footer-v3')
  @endif
  <!-- Footer-area end-->

  <!-- Jquery JS -->
  @if ($basicInfo->theme_version == 1)
    @includeIf('frontend.partials.scripts.script-v1')
  @elseif ($basicInfo->theme_version == 2)
    @includeIf('frontend.partials.scripts.script-v2')
  @elseif ($basicInfo->theme_version == 3)
    @includeIf('frontend.partials.scripts.script-v3')
  @endif
  {{-- additional script --}}
</body>

</html>
