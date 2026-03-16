@extends('frontend.layout')

@section('pageHeading')
  {{ __('Home') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection
@section('content')

  <!-- Home-area start-->
  <section class="hero-banner hero-banner_v1 bg-img header-next"
    @if (!empty($heroBgImg)) data-bg-img="{{ asset('assets/img/' . $heroBgImg) }}" @endif>
    <div class="container">
      <div class="row align-items-center gx-xl-5">
        <div class="col-lg-6">
          <div class="banner-content mb-40" data-aos="fade-up">
            @if (!empty($heroInfo->title))
              <h1 class="title mb-25">
                {{ $heroInfo->title }}
              </h1>
            @else
              <h1>{{ __('Discover, Hire, and Excel with Top Freelancers Globally') }}</h1>
            @endif
            @if (!empty($heroInfo->text))
              <p class="text">{{ $heroInfo->text }}</p>
            @else
              <p class="text">
                {{ __('Explore a Diverse World of Skills and Services Offered by Expert Freelancers, Connecting You to the Perfect Match for Your Project Needs.') }}
              </p>
            @endif

            <div class="banner-form mt-40">
              <div class="form-wrapper shadow-md">
                <form id="newsletterForm" action="{{ route('services') }}">
                  <div class="input-inline">
                    <input class="form-control bg-white" placeholder="{{ __('e. g. Mobile application') }}"
                      type="text" name="keyword">
                    <button class="btn btn-lg btn-primary" type="submit"
                      aria-label="button">{{ __('Search Now') }}</button>
                  </div>
                </form>
              </div>
              @if (!empty($BasicExtends))
                @if (!is_null($BasicExtends->popular_tags))
                  <div class="banner-tags mt-15">
                    <span class="color-dark">{{ __('Popular Tags') . ' :' }}</span>
                    @php
                      $tags = explode(',', $BasicExtends->popular_tags);
                    @endphp
                    @foreach ($tags as $tag)
                      <a href="{{ route('services', ['keyword' => $tag]) }}">{{ $tag }}</a>
                      @if (!$loop->last)
                        ,
                      @endif
                    @endforeach
                  </div>
                @endif
              @endif
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="banner-img img-right mb-40" data-aos="fade-up">
            @if (!empty($heroImg))
              <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $heroImg) }}"
                alt="{{ __('Banner Image') }}">
            @else
              <img class="lazyload blur-up" data-src="{{ asset('assets/img/hero-static.png') }}"
                alt="{{ __('Banner Image') }}">
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Home-area end -->

  <!-- Sponsor-area start  -->
  @if ($secInfo->partners_section_status == 1)
    @if (count($partners) > 0)
      <section class="sponsor-area pt-100" data-aos="fade-up">
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
                              alt="{{ __('Sponsor') }}">
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

  <!-- Category-area start -->
  @if ($secInfo->service_category_section_status == 1)
    <section class="category-area category-area_v1 pt-100 pb-75">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-50" data-aos="fade-up">
              @if (!empty($secTitle->category_section_title))
                <h2 class="title">
                  {{ $secTitle->category_section_title }}
                </h2>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="row" data-aos="fade-up">
              @foreach ($categories as $category)
                <div class="col-xxl-3 col-lg-4 col-sm-6 item">
                  <div class="card mb-25 p-25 radius-md">
                    <div class="card_icon radius-md mb-25">
                      <img src="{{ asset('assets/img/service-categories/' . $category->image) }}"
                        alt="{{ $category->name }}">
                      <!-- If use image as icon uncomment below line -->
                    </div>
                    <div class="card_details">
                      <h4 class="card_title lc-2 mb-15">
                        <a href="{{ route('services', ['category' => $category->slug]) }}" target="_self">
                          {{ $category->name }}
                        </a>
                      </h4>
                      <div class="card_action">
                        <a href="{{ route('services', ['category' => $category->slug]) }}" class="btn-text icon-end"
                          title="{{ __('Show Service Gigs') }}" target="_self">
                          {{ __('Show Service Gigs') }}
                          @if ($currentLanguageInfo->direction == 1)
                            <i class="far fa-long-arrow-alt-left"></i>
                          @else
                            <i class="far fa-long-arrow-alt-right"></i>
                          @endif
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Category-area end -->

  <!-- Service-area start -->
  @php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;
  @endphp
  @if ($secInfo->featured_services_section_status == 1 && $service_setings->is_service == 1)
    <section class="service-area bg-primary-light latest pt-100 pb-75">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-30" data-aos="fade-up">
              @if (!empty($secTitle->featured_services_section_title))
                <h2 class="title mb-20">
                  {{ $secTitle->featured_services_section_title }}
                </h2>
              @endif
              @if (count($featuredCategories) == 0)
                <div class="row text-center">
                  <div class="col">
                    <h3 class="mt-3">{{ __('No Information Found') . '!' }}</h3>
                  </div>
                </div>
              @else
                <div class="tabs-navigation mb-20">
                  <ul class="nav nav-tabs" data-hover="fancyHover">
                    @foreach ($featuredCategories as $featuredCategory)
                      <li class="nav-item {{ $loop->first ? 'active' : '' }}">
                        <button class="nav-link hover-effect btn-md radius-sm {{ $loop->first ? 'active' : '' }}"
                          data-bs-toggle="tab" data-bs-target="#tab{{ $featuredCategory->slug }}"
                          type="button">{{ $featuredCategory->name }}</button>
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="tab-content" data-aos="fade-up">
              @foreach ($featuredCategories as $serviceCategory)
                <div class="tab-pane slide {{ $loop->first ? 'show active' : '' }}"
                  id="tab{{ $serviceCategory->slug }}">
                  @php $allServiceContents = $serviceCategory->serviceContent; @endphp

                  @if (count($allServiceContents) == 0)
                    <div class="row text-center">
                      <div class="col-lg-12">
                        <h4>{{ __('No Service Found') . '!' }}</h4>
                      </div>
                    </div>
                  @else
                    <div class="row">
                      @foreach ($allServiceContents as $serviceContent)
                        @php
                          $service = $serviceContent->service;
                          // review
                          $reviewCount = $service->review()->count();
                          // wishlist
                          if (auth('web')->check() == true) {
                              $authUser = auth('web')->user();

                              $listedService = $service
                                  ->wishlist()
                                  ->where('user_id', $authUser->id)
                                  ->first();
                              $wishlisted = empty($listedService) ? false : true;
                          } else {
                              $wishlisted = false;
                          }
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                          <div class="service_default p-15 radius-md mb-25">
                            <figure class="service_img">
                              <a href="{{ route('service_details', ['slug' => $serviceContent->slug, 'id' => $serviceContent->service_id]) }}"
                                title="{{ $serviceContent->title }}" target="_self"
                                class="lazy-container ratio ratio-2-3">
                                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                  data-src="{{ asset('assets/img/services/thumbnail-images/' . $service->thumbnail_image) }}"
                                  alt="{{ $serviceContent->title }}">
                              </a>
                            </figure>
                            <div class="service_details mt-20">
                              <div class="authors mb-15">
                                @if ($service->seller_id != 0)
                                  @php
                                    $seller = App\Models\Seller::where('id', $service->seller_id)->first();
                                  @endphp
                                  <div class="author">
                                    <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}"
                                      target="_self" title="{{ $seller->username }}">
                                      @if (!is_null($seller->photo))
                                        <img class="lazyload"
                                          data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                                          alt="{{ __('Image') }}">
                                      @else
                                        <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                          alt="{{ __('Image') }}">
                                      @endif
                                    </a>
                                    <div>
                                      <span class="h6 font-sm mb-0">
                                        <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}"
                                          target="_self">
                                          {{ strlen($seller->username) > 20 ? mb_substr($seller->username, 0, 20, 'UTF-8') . '..' : $seller->username }}
                                        </a>
                                      </span>
                                      <span class="font-sm">
                                        <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}"
                                          target="_self" title="{{ $seller->username }}">
                                          {{ strlen(@$seller->seller_info->name) > 20 ? mb_substr(@$seller->seller_info->name, 0, 20, 'UTF-8') . '..' : @$seller->seller_info->name }}
                                        </a>
                                      </span>
                                    </div>
                                  </div>
                                @else
                                  @php
                                    $admin = App\Models\Admin::first();
                                  @endphp
                                  <div class="author">
                                    <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                      target="_self" title="{{ $admin->username }}">
                                      @if (!empty($admin->image))
                                        <img class="lazyload"
                                          data-src="{{ asset('assets/img/admins/' . $admin->image) }}"
                                          alt="{{ __('Image') }}">
                                      @else
                                        <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                          alt="{{ __('Image') }}">
                                      @endif
                                    </a>
                                    <div>
                                      <span class="h6 font-sm mb-0">
                                        <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                          target="_self">
                                          {{ strlen($admin->username) > 20 ? mb_substr($admin->username, 0, 20, 'UTF-8') . '..' : $admin->username }}
                                        </a>
                                      </span>
                                      <span class="font-sm">
                                        <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                          target="_self"
                                          title="{{ $admin->first_name . ' ' . $admin->last_name }}">
                                          {{ strlen($admin->first_name . ' ' . $admin->last_name) > 20 ? mb_substr($admin->first_name . ' ' . $admin->last_name, 0, 20, 'UTF-8') . '..' : $admin->first_name . ' ' . $admin->last_name }}
                                        </a>
                                      </span>
                                    </div>
                                  </div>
                                @endif
                                <a href="{{ route('service.update_wishlist', ['slug' => $serviceContent->slug]) }}"
                                  class="btn btn-icon radius-sm wishlist-link" data-tooltip="tooltip"
                                  data-bs-placement="top"
                                  title="{{ $wishlisted == true ? __('Remove from wishlist') : __('Save to Wishlist') }}">
                                  @auth('web')
                                    <i class="fas fa-heart {{ $wishlisted == true ? 'added-in-wishlist' : '' }}"></i>
                                  @endauth
                                  @guest('web')
                                    <i class="fas fa-heart"></i>
                                  @endguest
                                </a>
                              </div>

                              <h6 class="service_title lc-2 mb-15">
                                <a href="{{ route('service_details', ['slug' => $serviceContent->slug, 'id' => $serviceContent->service_id]) }}"
                                  target="_self">
                                  {{ strlen($serviceContent->title) > 45 ? mb_substr($serviceContent->title, 0, 45, 'UTF-8') . '...' : $serviceContent->title }}
                                </a>
                              </h6>

                              <div class="ratings size-md">
                                <div class="rate bg-img"
                                  data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}">
                                  <div class="rating-icon bg-img" style="width: {{ $service->average_rating * 20 }}%"
                                    data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}"></div>
                                </div>
                                <span class="ratings-total">{{ $service->average_rating }} ({{ $reviewCount }})</span>
                              </div>
                              @php
                                $position = $currencyInfo->base_currency_symbol_position;
                                $symbol = $currencyInfo->base_currency_symbol;
                              @endphp
                              <div class="service_bottom-info mt-20 pt-15">
                                @if ($service->quote_btn_status == 1)
                                  <span>{{ __('Request Quote') }}</span>
                                @else
                                  <span>{{ __('Starting At') }}</span>
                                  <span class="font-medium">
                                    @php
                                      $currentMinPackagePrice = $service
                                          ->package()
                                          ->where('language_id', $languageId->id)
                                          ->min('current_price');
                                      $previousPackagePrice = $service
                                          ->package()
                                          ->where('language_id', $languageId->id)
                                          ->min('previous_price');
                                    @endphp
                                    {{ $position == 'left' ? $symbol : '' }}{{ is_null($currentMinPackagePrice) ? formatPrice('0.00') : formatPrice($currentMinPackagePrice) }}{{ $position == 'right' ? $symbol : '' }}
                                    <!--- previous price --->

                                    @if ($previousPackagePrice)
                                      <del>{{ $position == 'left' ? $symbol : '' }}{{ is_null($previousPackagePrice) ? formatPrice(0.0) : formatPrice($previousPackagePrice) }}{{ $position == 'right' ? $symbol : '' }}
                                      </del>
                                    @endif
                                  </span>
                                  <!--- previous price --->
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <div class="cta-btn text-center mt-15 mb-25">
                      <a href="{{ route('services') }}" class="btn btn-lg btn-primary radius-sm"
                        title="{{ __('View More') }}" target="_self">{{ __('View More') }}</a>
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Service-area end -->

  <!-- Testimonial-area start -->
  @if ($secInfo->testimonials_section_status == 1)
    <section class="testimonial-area testimonial-area_v1 pt-100 pb-60">
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
                    data-src="{{ asset('assets/img/' . $testimonialBgImg) }}" alt="{{ __('Image') }}">
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
                                    alt="{{ $testimonial->name }}">
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

  <!-- Blog-area start -->
  @if ($secInfo->blog_section_status == 1)
    <section class="blog-area blog-area_v1 pb-70">
      <div class="container">
        <div class="section-title title-inline mb-50" data-aos="fade-up">
          @if (!empty($secTitle->blog_section_title))
            <h2 class="title">
              {{ $secTitle->blog_section_title }}
            </h2>
          @endif
          <a href="{{ route('blog') }}" class="btn btn-lg btn-primary radius-sm" title="{{ __('Read All Post') }}"
            target="_self">{{ __('Read All Post') }}</a>
        </div>
        <div class="row">
          @foreach ($posts as $post)
            <div class="col-md-6 col-xl-4" data-aos="fade-up">
              <article class="card mb-30">
                <div class="card_img">
                  <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}" target="_self"
                    class="lazy-container ratio ratio-5-4">
                    <img class="lazyload" data-src="{{ asset('assets/img/posts/' . $post->image) }}"
                      alt="{{ $post->title }}">
                  </a>
                  <ul class="card_list list-unstyled">
                    <li class="icon-start">
                      <a href="#" target="_self"><i class="fal fa-user"></i>{{ $post->author }}</a>
                    </li>
                    <li class="icon-start">
                      <a href="{{ route('blog', ['category' => $post->categorySlug]) }}" target="_self"><i
                          class="fal fa-th-large"></i>{{ $post->categoryName }} </a>
                    </li>
                    <li class="icon-start">
                      <a href="#" target="_self"><i
                          class="fal fa-calendar-check"></i>{{ $post->created_at->toFormattedDateString() }}</a>
                    </li>
                  </ul>
                </div>
                <div class="card_content p-25 border">
                  <h4 class="card_title lc-2 mb-15">
                    <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                      target="_self">
                      {{ strlen($post->title) > 45 ? mb_substr($post->title, 0, 45, 'UTF-8') . '...' : $post->title }}
                    </a>
                  </h4>
                  <p class="card_text lc-2">
                    {!! strlen(strip_tags($post->content)) > 100
                        ? mb_substr(strip_tags($post->content), 0, 100, 'UTF-8') . '...'
                        : strip_tags($post->content) !!}
                  </p>
                  <div class="cta-btn mt-15">
                    <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                      class="btn-text color-primary" target="_self"
                      title="{{ __('READ MORE') }}">{{ __('READ MORE') }}</a>
                  </div>
                </div>
              </article>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
  <!-- Blog-area end -->

  <!-- Newsletter-area start -->
  @if ($secInfo->cta_section_status == 1)
    <section class="newsletter-area newsletter-area_v1 pb-100" data-aos="fade-up">
      <div class="container">
        <div class="newsletter-inner position-relative z-1 px-60">
          <div class="overlay opacity-1 radius-md bg-img"
            @if (!empty($ctaBgImg)) data-bg-img="{{ asset('assets/img/' . $ctaBgImg) }}" @endif>
          </div>
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="content-title">
                <h2 class="title mb-25">
                  {{ @$ctaSectionInfo->title }}
                </h2>
                @if (!empty(@$ctaSectionInfo->button_text) || !empty(@$ctaSectionInfo->button_url))
                  <a href="{{ @$ctaSectionInfo->button_url }}" class="btn btn-lg btn-primary radius-sm"
                    title="{{ @$ctaSectionInfo->button_text }}"
                    target="_self">{{ @$ctaSectionInfo->button_text }}</a>
                @endif
              </div>
            </div>
            <div class="col-lg-6 align-self-end">
              <div class="image mt-2 text-end d-none d-lg-block">
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/img/' . @$ctaSectionInfo->image) }}"
                  alt="{{ @$ctaSectionInfo->title ?: __('Image') }}"
                  width="300" height="300" style="width: 300px; height: 300px; object-fit: cover;">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Newsletter-area end -->
  <div class="mb-50 text-center advertise">
    {!! showAd(3) !!}
  </div>
@endsection
