@extends('frontend.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@php
  $title = $pageHeading->service_details_page_title ?? __('Service Details');
@endphp
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ @$details->title }}
  @endif
@endsection

@section('metaKeywords')
  {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $details->meta_description }}
@endsection

@section('content')

  {{-- breadcrumb --}}
  <section class="breadcrumbs-area bg_cover lazyload bg-img header-next"
    data-bg-img="{{ asset('assets/img/' . $breadcrumb) }}">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="breadcrumbs-title text-center">
            <h3>
              {{ $details->title }}
            </h3>
            <ul class="breadcumb-link justify-content-center">
              <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
              <li class="active">{{ @$title }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- breadcrumb end --}}

  <!--====== Start Service Details Section ======-->
  <section class="gig-details-section pt-120 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-lg-8">
          <div class="gig-details-wrapper">

            <div class="gig-slider-wrap mb-30">
              <div class="gigs-big-slider">
                @php $sldImgs = json_decode($details->slider_images); @endphp
                @foreach ($sldImgs as $img)
                  <div class="single-item">
                    <a href="{{ asset('assets/img/services/slider-images/' . $img) }}" class="service-slider-image">
                      <img data-src="{{ asset('assets/img/services/slider-images/' . $img) }}" alt="image"
                        class="lazyload">
                    </a>
                  </div>
                @endforeach
              </div>
              <div class="gigs-thumbs-slider">
                @foreach ($sldImgs as $img)
                  <div class="single-item">
                    <img data-src="{{ asset('assets/img/services/slider-images/' . $img) }}" alt="image"
                      class="lazyload">
                  </div>
                @endforeach
              </div>
            </div>

            <!---===== Button========= -------->
            <div class="group-btn mb-40 justify-content-center">
              <a href="{{ route('service.update_wishlist', ['slug' => $details->slug]) }}"
                class="btn btn-md btn-outline radius-sm wishlist-link text-center" data-element_type="button">
                <i class="fas fa-heart"></i>
                <span>
                  @auth('web')
                    @if ($wishlisted == true)
                      {{ __('Remove From Wishlist') }}
                    @else
                      {{ __('Add To Wishlist') }}
                    @endif
                  @endauth

                  @guest('web')
                    {{ __('Add To Wishlist') }}
                  @endguest
                </span>
              </a>
              @if (!is_null($details->video_preview_link))
                <a href="{{ $details->video_preview_link }}"
                  class="btn btn-md btn-outline radius-sm video-popup text-center">
                  <i class="fas fa-video-plus"></i> {{ __('Video Preview') }}
                </a>
              @endif
              @if (!is_null($details->live_demo_link))
                <a href="{{ $details->live_demo_link }}" class="btn btn-md btn-outline radius-sm text-center "
                  target="_blank">
                  <i class="fas fa-eye"></i> {{ __('Live Demo') }}
                </a>
              @endif
            </div>
            <!---===== Button========= -------->

            <div class="service-title-wrap mb-40">
              <div class="service-title">
                <h3 class="mb-15">{{ $details->title }}</h3>
              </div>

              <div class="service-category pt-15 border-top justify-content-lg-between">
                @if ($details->category_name)
                  <div>
                    <span>{{ __('Category') }} {{ __(':') }}</span>
                    <div class="categories">
                      <a class="category-tag" href="{{ route('services', ['category' => $details->category_name]) }}">
                        {{ $details->category_name }}
                      </a>
                      @if ($details->category_name && $details->sub_category_name)
                        @if ($currentLanguageInfo->direction == 1)
                          <span><i class="far fa-long-arrow-alt-left"></i></span>
                        @else
                          <span><i class="far fa-long-arrow-alt-right"></i></span>
                        @endif
                        <a class="category-tag"
                          href="{{ route('services', ['category' => $details->category_name, 'subcategory' => $details->sub_category_name]) }}">
                          {{ $details->sub_category_name }}
                        </a>
                      @endif
                    </div>
                  </div>
                @endif

                <div class="ratings size-md">
                  <span>{{ __('Rating') . ':' }}</span>
                  <div class="rate bg-img" data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}">
                    <div class="rating-icon bg-img" style="width: {{ $details->average_rating * 20 }}%;"
                      data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}"></div>
                  </div>
                  @php
                    $reviewCount = $details->review()->count();
                  @endphp
                  <span class="ratings-total">({{ $reviewCount }})</span>
                </div>
              </div>
            </div>

            <div class="description-wrap">
              <div class="description-tabs">
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab"
                      data-bs-target="#description">{{ __('Description') }}</button>
                  </li>
                  <li class="nav-item">
                    <button type="button"class="nav-link" data-bs-toggle="tab"
                      data-bs-target="#reviews">{{ __('Reviews') }}</button>
                  </li>
                  <li class="nav-item">
                    <button type="button"class="nav-link" data-bs-toggle="tab"
                      data-bs-target="#faq">{{ __('FAQ') }}</button>
                  </li>
                </ul>
              </div>

              <div class="tab-content">
                <div class="tab-pane show active" id="description">
                  <div class="content-box summernote-content">
                    {!! replaceBaseUrl($details->description, 'summernote') !!}
                  </div>
                </div>

                <div class="tab-pane fade" id="reviews">
                  <div class="gigs-review-area">
                    @if (count($reviews) == 0)
                      <h5 class="mb-25">{{ __('This service has no review yet') . '!' }}</h5>
                    @else
                      @foreach ($reviews as $review)
                        <div class="review_user">
                          <div class="image">
                            @if (empty($review->user->image))
                              <img data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="image" class="lazyload">
                            @else
                              <img data-src="{{ asset('assets/img/users/' . $review->user->image) }}" alt="image"
                                class="lazyload">
                            @endif
                          </div>

                          <div class="content">
                            <ul class="rating">
                              @for ($i = 0; $i < $review->rating; $i++)
                                <li><i class="fas fa-star"></i></li>
                              @endfor
                            </ul>

                            @php
                              $username = $review->user->username;
                              $date = date_format($review->created_at, 'F d, Y');
                            @endphp

                            <span><span>{{ $username == '' ? __('User') : $username }}</span>{{ ' – ' . $date }}</span>
                            <p>{{ $review->comment }}</p>
                          </div>
                        </div>
                      @endforeach
                    @endif

                    @guest('web')
                      <a href="{{ route('user.login') }}" class="btn btn-lg btn-primary radius-sm">{{ __('Login') }}</a>
                    @endguest

                    @auth('web')
                      <div class="review_form">
                        <form action="{{ route('service.store_review', ['id' => $details->id]) }}" method="POST">
                          @csrf
                          <div class="form-group">
                            <label>{{ __('Rating') . '*' }}</label>
                            <ul class="rating">
                              <li class="review-value review-1" data-ratingVal="1">
                                <span class="fas fa-star"></span>
                              </li>

                              <li class="review-value review-2" data-ratingVal="2">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                              </li>

                              <li class="review-value review-3" data-ratingVal="3">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                              </li>

                              <li class="review-value review-4" data-ratingVal="4">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                              </li>

                              <li class="review-value review-5" data-ratingVal="5">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                              </li>
                            </ul>
                          </div>

                          <input type="hidden" id="rating-id" name="rating">

                          <div class="form-group">
                            <label>{{ __('Comment') }}</label>
                            <textarea class="form-control" name="comment" placeholder="{{ __('Write your comment here') . '...' }}">{{ old('comment') }}</textarea>
                          </div>

                          <div class="form_button">
                            <button type="submit" class="btn btn-lg btn-primary radius-sm">
                              {{ __('Submit') }}
                            </button>
                          </div>
                        </form>
                      </div>
                    @endauth
                  </div>
                </div>

                <div class="tab-pane fade" id="faq">
                  @if (count($faqs) == 0)
                    <h4 class="text-center mt-5">{{ __('No FAQ Found') . '!' }}</h4>
                  @else
                    <div class="faq-wrapper">
                      <div class="accordion" id="accordionExample">
                        @foreach ($faqs as $faq)
                          <div class="accordion-item border radius-md mb-20">
                            <h6 class="accordion-header" id="{{ 'heading-' . $faq->id }}">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="{{ '#collapse-' . $faq->id }}"
                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="{{ 'collapse-' . $faq->id }}">
                                {{ $faq->question }}
                              </button>
                            </h6>
                            <div id="{{ 'collapse-' . $faq->id }}"
                              class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                              aria-labelledby="{{ 'heading-' . $faq->id }}" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                <p>{{ $faq->answer }}</p>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endif
                </div>
                <div class="mt-50 text-center advertise">
                  {!! showAd(3) !!}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          @if ($details->quote_btn_status == 1)
            <form action="{{ route('service.payment_form.check', ['slug' => $details->slug, 'id' => $details->id]) }}"
              method="GET">
              <input type="hidden" name="form_id" value="{{ $details->form_id }}">
              <input type="hidden" name="quote_btn_status" value="{{ $details->quote_btn_status }}">
              <button type="submit" class="btn btn-lg btn-primary radius-sm mb-4 w-100">
                <i class="fas fa-calendar-plus"></i> {{ __('Request A Quote') }}
              </button>
            </form>
          @else
            <div class="gigs-sidebar pb-10">
              @if (count($packages) == 0)
                <div class="alert alert-danger text-center" role="alert">
                  {{ __('No Package Available') . '!' }}
                </div>
              @else
                <div class="packages-widgets mb-40">
                  <div class="packages-tabs">
                    <ul class="nav nav-tabs">
                      @foreach ($packages as $package)
                        <li class="nav-item">
                          <button type="button" class="nav-link {{ $loop->first ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="{{ '#package-' . $package->id }}">
                            {{ $package->name }}
                          </button>
                        </li>
                      @endforeach
                    </ul>
                  </div>

                  <div class="tab-content">
                    @php
                      $position = $currencyInfo->base_currency_symbol_position;
                      $symbol = $currencyInfo->base_currency_symbol;
                    @endphp

                    @foreach ($packages as $package)
                      <div class="tab-pane {{ $loop->first ? 'active show' : '' }} fade"
                        id="{{ 'package-' . $package->id }}">
                        <div class="packages-content-wrap">

                          <form id="{{ 'package-form-' . $package->id }}"
                            action="{{ route('service.payment_form', ['slug' => $details->slug, 'id' => $details->id]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="form_id" value="{{ $details->form_id }}">
                            <input type="hidden" name="quote_btn_status" value="{{ $details->quote_btn_status }}">
                            <div class="packages-content">
                              <h3>
                                <span class="title">{{ __('Price') }}</span>
                                <span class="price">{{ $position == 'left' ? $symbol : '' }}
                                  <span id="{{ 'package-' . $package->id . '-price' }}">
                                    {{ formatPrice($package->current_price) }}
                                  </span>{{ $position == 'right' ? $symbol : '' }}
                                  @if (!empty($package->previous_price))
                                    <span class="pre-price">
                                      {{ $position == 'left' ? $symbol : '' }}
                                      <span id="{{ 'package-' . $package->id . '-prev_price' }}">
                                        {{ formatPrice($package->previous_price) }}
                                      </span>{{ $position == 'right' ? $symbol : '' }}
                                    </span>
                                  @endif
                                </span>
                              </h3>

                              @if (!empty($package->delivery_time) || !empty($package->number_of_revision))
                                <span class="additional-info">
                                  @if (!empty($package->delivery_time))
                                    <span class="delivery"><i class="far fa-clock"></i>{{ $package->delivery_time }}
                                      {{ $package->delivery_time > 1 ? __('Days Delivery') : __('Day Delivery') }}</span>
                                  @endif
                                  @if (!empty($package->number_of_revision))
                                    <span class="revisions"><i
                                        class="far fa-sync-alt"></i>{{ $package->number_of_revision }}
                                      {{ $package->number_of_revision > 1 ? __('Revisions') : __('Revision') }}</span>
                                  @endif
                                </span>
                              @endif
                              @php $features = explode(PHP_EOL, $package->features); @endphp
                              <ul class="features list-unstyled mt-10">
                                @foreach ($features as $feature)
                                  <li class="feature check-icon">{{ $feature }}
                                  </li>
                                @endforeach
                              </ul>
                              @if (count($addons) > 0)
                                <h3 class="title mb-2"><span class="title">{{ __('Addons') }}</span></h3>
                                <ul class="addons list-unstyled">
                                  @foreach ($addons as $addon)
                                    <div class="d-flex flex-row">
                                      <input type="checkbox"
                                        class="{{ $currentLanguageInfo->direction == 0 ? 'me-3' : 'ms-3' }} service-addon"
                                        name="addons[]" value="{{ $addon->id }}"
                                        data-addon_price="{{ $addon->price }}" data-package_id="{{ $package->id }}">
                                      <li class="addon">{{ $addon->name }}
                                        <span>(<span class="text-danger">+</span>
                                          {{ $position == 'left' ? $symbol : '' }}{{ formatPrice($addon->price) }}{{ $position == 'right' ? $symbol : '' }})</span>
                                      </li>
                                    </div>
                                  @endforeach
                                </ul>
                              @endif
                            </div>
                          </form>

                          <div class="packages-footer mt-20">
                            <button type="submit" class="btn btn-lg btn-primary radius-sm"
                              form="{{ 'package-form-' . $package->id }}">
                              {{ __('Checkout') }}
                            </button>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          @endif

          <div class="gigs-sidebar pb-10">
            @if ($details->seller_id != 0)
              @php
                $seller = App\Models\Seller::where('id', $details->seller_id)->first();
              @endphp
              <div class="seller-widgets mb-40">
                <h4 class="title mb-20">{{ __('About The Seller') }}</h4>
                <div class="seller mb-20">
                  <div class="seller-img">
                    <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}">
                      @if (!is_null($seller->photo))
                        <img data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}" alt="image"
                          class="lazyload">
                      @else
                        <img data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="image" class="lazyload">
                      @endif
                    </a>
                  </div>
                  @php
                    $sellerInfo = $seller
                        ->seller_info()
                        ->where('language_id', $currentLanguageInfo->id)
                        ->first();
                  @endphp
                  <div class="seller-info">
                    <h6 class="mb-0"><a
                        href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}">{{ $seller->username }}</a>
                    </h6>
                    <span class="font-xsm">{{ @$sellerInfo->name }}</span>
                    <div class="ratings mt-1">
                      <div class="rate bg-img" data-bg-img="{{ asset('assets/front/images/rate-star.png') }}">
                        <div class="rating-icon bg-img" style="width: {{ SellerAvgRating($seller->id) * 20 }}%;"
                          data-bg-img="{{ asset('assets/front/images/rate-star.png') }}"></div>
                      </div>
                      <span class="ratings-total font-sm">({{ SellerAvgRating($seller->id) }})</span>
                    </div>
                  </div>
                </div>
                <ul class="toggle-list list-unstyled mb-20">
                  <li>
                    <span class="first">{{ __('Total Services') . ' :' }}</span>
                    <span class="last h6">
                      @php
                        $serviceCount = App\Models\ClientService\Service::where([['seller_id', $seller->id], ['service_status', 1]])->count();
                      @endphp
                      {{ $serviceCount }}
                    </span>
                  </li>
                  <li>
                    <span class="first">{{ __('Orders Completed') . ' :' }}</span>
                    <span class="last h6">
                      @php
                        $orderCount = App\Models\ClientService\ServiceOrder::where([['seller_id', $seller->id], ['order_status', 'completed']])->count();
                      @endphp
                      {{ $orderCount }}
                    </span>
                  </li>
                  <li>
                    <span class="first">{{ __('Member since') . ' : ' }}</span>
                    <span class="last h6">{{ Carbon\Carbon::parse($seller->created_at)->format('dS M Y') }}</span>
                  </li>
                </ul>
                <a href="javaScript:void(0)" class="btn btn-lg btn-primary radius-sm w-100" data-bs-toggle="modal"
                  data-bs-target="#contactModal" type="button" aria-label="button">{{ __('Contact Now') }}</a>


              </div>
            @else
              @php
                $seller = App\Models\Admin::first();
              @endphp
              <div class="seller-widgets mb-40">
                <h4 class="title mb-20">{{ __('About The Seller') }}</h4>
                <div class="seller mb-20">
                  <div class="seller-img">
                    <a
                      href="{{ route('frontend.seller.details', ['username' => $seller->username, 'admin' => true]) }}">
                      @if (!is_null($seller->image))
                        <img data-src="{{ asset('assets/img/admins/' . $seller->image) }}" alt="image"
                          class="lazyload">
                      @else
                        <img data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="image" class="lazyload">
                      @endif
                    </a>
                  </div>
                  <div class="seller-info">
                    <h6 class="mb-0">{{ $seller->username }}</h6>
                    <span class="font-xsm">{{ @$seller->first_name . ' ' . $seller->last_name }}</span>
                  </div>
                </div>
                <ul class="toggle-list list-unstyled mb-20">
                  <li>
                    <span class="first">{{ __('Total Services') . ' :' }}</span>
                    <span class="last h6">
                      @php
                        $serviceCount = App\Models\ClientService\Service::where([['seller_id', 0], ['service_status', 1]])->count();
                      @endphp
                      {{ $serviceCount }}
                    </span>
                  </li>
                  <li>
                    <span class="first">{{ __('Orders Completed') . ' :' }}</span>
                    <span class="last h6">
                      @php
                        $orderCount = App\Models\ClientService\ServiceOrder::where([['seller_id', null], ['order_status', 'completed']])->count();
                      @endphp
                      {{ $orderCount }}
                    </span>
                  </li>
                </ul>
                <a href="javaScript:void(0)" class="btn btn-lg btn-primary radius-sm w-100" data-bs-toggle="modal"
                  data-bs-target="#contactModal" type="button" aria-label="button">{{ __('Contact Now') }}</a>
              </div>
            @endif
            @if (!is_null($details->skills))
              <div class="seller-widgets mb-40">
                <div class="skills">

                  @php
                    $selected_skills = json_decode($details->skills);
                  @endphp
                  @if (!is_null($selected_skills))
                    <h6>{{ __('Skills') }} {{ __(':') }}</h6>
                    <div class="skill">
                      @foreach ($selected_skills as $selected_skills)
                        @php
                          $skill = App\Models\Skill::where('id', $selected_skills)->first();
                        @endphp
                        @if ($skill)
                          <a href="{{ route('services', ['skills' => $selected_skills]) }}">
                            {{ $skill->name }}
                          </a>
                        @endif
                      @endforeach
                    </div>
                  @endif

                </div>
              </div>
            @endif
          </div>
          <div class="mb-30 text-center advertise">
            {!! showAd(4) !!}
          </div>
          <div class="mb-30 text-center advertise">
            {!! showAd(5) !!}
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Service Details Section ======-->

  <form class="d-none" action="{{ route('services') }}" method="GET">
    <input type="hidden" id="tag-id" name="tag"
      value="{{ !empty(request()->input('tag')) ? request()->input('tag') : '' }}">
    <button type="submit" id="submitBtn"></button>
  </form>

  <!-- Contact Modal -->
  <div class="modal contact-modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header align-item-center">
          <h4 class="modal-title mb-0" id="contactModalLabel">{{ __('Contact Now') }}</h4>
          <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('seller.contact.message') }}" method="POST" id="sellerContactForm">
            @csrf
            <input type="hidden" name="seller_email"
              value="{{ $details->seller_id != 0 ? $seller->recipient_mail : $bs->to_mail }}">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group mb-20">
                  <input type="text" class="form-control" placeholder="{{ __('Enter Your Full Name') }}"
                    name="name">
                  <p class="text-danger em" id="err_name"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group mb-20">
                  <input type="email" class="form-control" placeholder="{{ __('Enter Your Email Address') }}"
                    name="email">
                  <p class="text-danger em" id="err_email"></p>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group mb-20">
                  <input type="text" class="form-control" placeholder="{{ __('Enter Subject') }}" name="subject">
                  <p class="text-danger em" id="err_subject"></p>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group mb-20">
                  <textarea name="message" class="form-control" placeholder="{{ __('Message') }}"></textarea>
                  <p class="text-danger em" id="err_message"></p>
                </div>
              </div>
              @if ($bs->google_recaptcha_status == 1)
                <div class="col-md-12">
                  <div class="form-group mb-20">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    <p class="text-danger em" id="err_g-recaptcha-response"></p>
                  </div>
                </div>
              @endif
              <div class="col-lg-12 text-center">
                <button class="btn btn-lg btn-primary radius-sm" id="sellerSubmitBtn" type="submit"
                  aria-label="button">{{ __('Send message') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('script')
  <script src="{{ asset('assets/js/seller-contact.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/service.js') }}"></script>
@endsection
