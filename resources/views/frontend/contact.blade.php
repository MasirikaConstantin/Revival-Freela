@extends('frontend.layout')

@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->contact_page_title }}
    @endif
@endsection

@section('metaKeywords')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_keyword_contact }}
    @endif
@endsection

@section('metaDescription')
    @if (!empty($seoInfo))
        {{ $seoInfo->meta_description_contact }}
    @endif
@endsection
@php
    $title = $pageHeading->contact_page_title ?? __('No Page Title Found');
@endphp
@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!--====== Start Contact Information Section ======-->
    <div class="contact-informatoin pt-100 pb-60">
        <div class="container">
          <div class="row gx-xl-5 justify-content-between">
            <div class="col-lg-7">
              <div class="contact-wrapper mb-40">
                  <div class="section-title mb-30">
                      <h2 class="title">{{ __('Get In Touch') }}</h2>
                  </div>

                  <div class="contact-form">
                    <form action="{{ route('contact.send_mail') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-20">
                                    <input name="name" type="text" class="form-control"
                                        placeholder="{{ __('Enter Your Full Name') }}">
                                </div>
                                @error('name')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group mb-20">
                                    <input name="email" type="email" class="form-control"
                                        placeholder="{{ __('Enter Your Email Address') }}">
                                </div>
                                @error('email')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group mb-20">
                                    <input name="subject" type="text" class="form-control"
                                        placeholder="{{ __('Enter Email Subject') }}">
                                </div>
                                @error('subject')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group mb-20">
                                    <textarea name="message" class="form-control" placeholder="{{ __('Write Your Message') }}"></textarea>
                                </div>
                                @error('message')
                                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($info->google_recaptcha_status == 1)
                                <div class="col-lg-12">
                                    <div class="form-group mb-20 mb-20">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}
                                    </div>
                                    @error('g-recaptcha-response')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div class="col">
                                <button class="btn btn-lg btn-primary radius-sm">{{ __('Send Message') }}</button>
                            </div>
                        </div>
                    </form>
                  </div>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="information-wrapper pb-10">
                <div class="section-title mb-20">
                  <h3 class="title">{{ __('Contact Info') }}</h3>
                </div>
                @if (!empty($info->address))
                <div class="information-item mb-30">
                    <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>

                    <div class="info">
                        <p>{{ $info->address }}</p>
                    </div>
                </div>
                @endif

                @if (!empty($info->contact_number))
                <div class="information-item mb-30">
                    <div class="icon">
                        <i class="fas fa-phone"></i>
                    </div>

                    <div class="info">
                        <p><a href="tel:+{{ $info->contact_number }}">{{ $info->contact_number }}</a></p>
                    </div>
                </div>
                @endif

                @if (!empty($info->email_address))
                <div class="information-item mb-30">
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>

                    <div class="info">
                        <p><a href="mailto:{{ $info->email_address }}">{{ $info->email_address }}</a></p>
                    </div>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
    </div>
    <!--====== End Contact Information Section ======-->

    <!--====== Start Contact Section ======-->
    <div class="contact-area pb-100">
        <div class="container">
          @if (!empty($info->latitude) && !empty($info->longitude))
              <div class="map-box">
                  <iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0"
                      marginwidth="0"
                      src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $info->latitude }},%20{{ $info->longitude }}+({{ $websiteInfo->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
              </div>
          @endif
        </div>
    </div>
    <!--====== End Contact Section ======-->
@endsection
