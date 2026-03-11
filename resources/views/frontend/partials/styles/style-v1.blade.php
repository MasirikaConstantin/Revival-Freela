<!-- Google font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@400;500;600&display=swap"
  rel="stylesheet">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/bootstrap.min.css') }}">
<!-- Fontawesome Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/fonts/fontawesome/css/all.min.css') }}">
<!-- Icomoon Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/fonts/icomoon/style.css') }}">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/magnific-popup.min.css') }}">
<!-- Swiper Slider -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/swiper-bundle.min.css') }}">
<!-- Nice Select -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/nice-select.css') }}">
<!-- AOS Animation CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/aos.min.css') }}">
<!-- Animate CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/animate.min.css') }}">
<!-- Data Tables CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/datatables.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/floating-whatsapp.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main-default-front.css') }}">

<!-- Main Style CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
<!-- Innerpages CSS -->
@if (!request()->routeIs('index'))
  <link rel="stylesheet" href="{{ asset('assets/front/css/default.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/innerpages.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/innerpages-responsive.css') }}">
  @if ($currentLanguageInfo->direction == 1)
  <link rel="stylesheet" href="{{ asset('assets/front/css/innerpages-rtl.css') }}">
  @endif
@endif
<!-- Responsive CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
<!-- RTL CSS -->
@if ($currentLanguageInfo->direction == 1)
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
@endif
