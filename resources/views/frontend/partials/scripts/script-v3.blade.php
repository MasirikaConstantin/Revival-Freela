<script>
  let baseURL = "{{ url('/') }}";
  let vapid_public_key = "{{ env('VAPID_PUBLIC_KEY') }}";
  let langDir = {{ $currentLanguageInfo->direction }};
  let whatsappStatus = {{ $basicInfo->whatsapp_status }};
  let whatsappNumber = '{{ $basicInfo->whatsapp_number }}';
  let whatsappPopupMessage = `{!! $basicInfo->whatsapp_popup_message !!}`;
  let whatsappPopupStatus = {{ $basicInfo->whatsapp_popup_status }};
  let whatsappHeaderTitle = '{{ $basicInfo->whatsapp_header_title }}';
  let readMore = "{{ __('Read More') }}";
  let readLess = "{{ __('Read Less') }}";
  let showMore = "{{ __('Show More') }}";
  let showLess = "{{ __('Show Less') }}";
  let selectSkills = "{{ __('Select Skills') }}";
  let addBtnTxt = "{{ __('Add To Wishlist') }}";
  let rmvBtnTxt = "{{ __('Remove From Wishlist') }}";
  let save_to_wishlist = "{{ __('Save to Wishlist') }}";
  let remove_from_wishlist = "{{ __('Remove from wishlist') }}";
  let demo_mode = "{{ env('DEMO_MODE') }}";
</script>

<!-- jQuery JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/jquery.min.js') }}"></script>
<!-- Bootstrap JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/bootstrap.min.js') }}"></script>
<!-- Nice Select JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/jquery.nice-select.min.js') }}"></script>
<!-- Magnific Popup JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/jquery.magnific-popup.min.js') }}"></script>
<!-- Swiper Slider JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/swiper-bundle.min.js') }}"></script>
<!-- Lazysizes -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/lazysizes.min.js') }}"></script>
<!-- Mouse Hover JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/mouse-hover-move.js') }}"></script>
<!-- AOS JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/aos.min.js') }}"></script>
<!-- Data Tables JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/datatables.min.js') }}"></script>
<!-- SVG Loader JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/vendors/svg-loader.min.js') }}"></script>
{{-- tinymce js --}}
<script type="text/javascript" src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-syotimer.min.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.min.js') }}"></script>

@if (session()->has('success'))
  <script>
    toastr['success']("{{ __(session('success')) }}");
  </script>
@endif

@if (session()->has('error'))
  <script>
    toastr['error']("{{ __(session('error')) }}");
  </script>
@endif

@if (session()->has('warning'))
  <script>
    toastr['warning']("{{ __(session('warning')) }}");
  </script>
@endif

{{-- setup csrf-token for ajax request --}}
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
</script>
@yield('script')
<script type="text/javascript" src="{{ asset('assets/js/push-notification.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/floating-whatsapp.js') }}"></script>
<!-- Main script JS -->
<script type="text/javascript" src="{{ asset('assets/front/js/script.js') }}"></script>
