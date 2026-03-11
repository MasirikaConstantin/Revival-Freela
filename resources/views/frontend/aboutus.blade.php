@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->about_us_page_title ?? __('About') }}
  @else
    {{ __('About') }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_aboutus ?? '' }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_aboutus ?? '' }}
  @endif
@endsection
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@php
  $title = $pageHeading->about_us_page_title ?? __('About');
@endphp

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title ?? ''])

  <!-- About-area start -->
  @if ($secInfo->about_section_status == 1)
    <section class="about-area-v1 pt-100 pb-60">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            @if (!empty($aboutInfo->about_section_image))
              <div class="about-img-box mb-40">
                <div class="about-img">
                  <img data-src="{{ asset('assets/img/' . $aboutInfo->about_section_image) }}" alt="image"
                    class="lazyload">
                  @if (!empty($aboutInfo->about_section_video_link))
                    <div class="play-content text-center">
                      <a href="{{ $aboutInfo->about_section_video_link }}"
                        class="video-btn video-btn-white youtube-popup p-absolute"
                        style="background-color: #{{ $basicInfo->primary_color }} !important"><i
                          class="fas fa-play"></i></a>
                    </div>
                  @endif
                </div>
              </div>
            @endif
          </div>

          <div class="col-lg-6">
            <div class="about-content-box mb-40">
              @if (!empty($aboutData->title))
                <h2>{{ $aboutData->title }}</h2>
              @endif
              @if (!empty($aboutData->text))
                <div class="summernote-content">
                  {!! nl2br($aboutData->text) !!}
                </div>
              @endif
              @if (!empty($aboutData->button_name) && !empty($aboutData->button_url))
                <a href="{{ $aboutData->button_url }}"
                  class="btn btn-lg btn-primary radius-sm mt-30">{{ $aboutData->button_name }}</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <div class="mb-50 text-center advertise">
    {!! showAd(3) !!}
  </div>
  <!-- About-area end -->

  <!-- Testimonial-area start -->
  @if ($secInfo->testimonials_section_status == 1)
    <section class="testimonial-area testimonial-area_v1 pb-60">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          <div class="col-lg-6" data-aos="fade-up">
            <div class="content-title mb-40">
              @if (!empty($secTitle->testimonials_section_title))
                <h2 class="title">
                  {{ $secTitle->testimonials_section_title }}
                </h2>
              @endif
            </div>
            <div class="testimonial-image mb-40">
              <div class="lazy-container radius-md ratio ratio-5-3">
                @if (!empty($testimonialBgImg))
                  <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/img/' . $testimonialBgImg) }}" alt="Image">
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up">
            @if (count($testimonials) == 0)
              <div class="row">
                <div class="col">
                  <h4 class="mt-3 ms-1">{{ __('No Testimonial Found') . '!' }}</h4>
                </div>
              </div>
            @else
              <div class="slider-wrapper">
                <div class="swiper testimonial-slider mb-40" id="testimonial-slider-1">
                  <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                      <div class="swiper-slide">
                        <div class="slider-item radius-md">
                          <div class="quote">
                            <p class="text font-lg mb-0">
                              {{ $testimonial->comment }}
                            </p>
                          </div>
                          <div class="item-bottom">
                            <div class="client-info">
                              <div class="client-img">
                                <div class="lazy-container rounded-pill ratio ratio-1-1">
                                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                    alt="Person Image">
                                </div>
                              </div>
                              <div class="content">
                                <h6 class="name mb-0">{{ $testimonial->name }}</h6>
                                <span class="designation font-sm">{{ $testimonial->occupation }}</span>
                              </div>
                            </div>
                            <span class="icon"><i class="fal fa-quote-right"></i></span>
                          </div>
                        </div>
                      </div>
                    @endforeach

                  </div>
                </div>
                <div class="swiper-pagination" id="testimonial-slider-1-pagination"></div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Testimonial-area end -->

  <!-- Sponsor-area start  -->
  @if ($secInfo->partners_section_status == 1)
    @if (count($partners) > 0)
      <section class="sponsor-area pb-100" data-aos="fade-up">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="swiper sponsor-slider">
                <div class="swiper-wrapper">
                  @foreach ($partners as $partner)
                    <div class="swiper-slide">
                      <div class="item-single d-flex justify-content-center">
                        <a href="{{ $partner->url }}" target="_blank">
                          <div class="sponsor-img">
                            <img class="lazyload" data-src="{{ asset('assets/img/partners/' . $partner->image) }}"
                              alt="Sponsor">
                          </div>
                        </a>
                      </div>
                    </div>
                  @endforeach

                </div>
                <div class="swiper-pagination position-static mt-30" data-aos="fade-up"></div>
              </div>
            </div>
          </div>
        </div>
      </section>
    @endif
  @endif
  <!-- Sponsor-area end -->

  <!-- Newsletter-area start -->
  @if ($secInfo->cta_section_status == 1)
    <section class="newsletter-area newsletter-area_v1 pb-100" data-aos="fade-up">
      <div class="container">
        <div class="newsletter-inner position-relative z-1 px-60">
          <div class="overlay opacity-1 radius-md bg-img"
            @if (!empty($ctaBgImg)) data-bg-img="{{ asset('assets/img/' . $ctaBgImg) }}" @endif></div>
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="content-title">
                <h2 class="title mb-25">
                  {{ @$ctaSectionInfo->title }}
                </h2>
                @if (!empty(@$ctaSectionInfo->button_text) || !empty(@$ctaSectionInfo->button_url))
                  <a href="{{ @$ctaSectionInfo->button_url }}" class="btn btn-lg btn-primary rounded-pill" title=""
                    target="_self">{{ @$ctaSectionInfo->button_text }}</a>
                @endif
              </div>
            </div>
            <div class="col-lg-6 align-self-end">
              <div class="image mt-2 text-end d-none d-lg-block">
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/img/' . @$ctaSectionInfo->image) }}" alt="Image"
                  width="300" height="300" style="width: 300px; height: 300px; object-fit: cover;" >
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <div class="mb-50 text-center advertise">
    {!! showAd(3) !!}
  </div>
  <!-- Newsletter-area end -->
@endsection
