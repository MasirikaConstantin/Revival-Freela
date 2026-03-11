@extends('frontend.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@section('pageHeading')
  {{ $seller->username }}
@endsection

@section('metaKeywords')
  {{ $seller->username }}
@endsection

@section('metaDescription')
  {{ @$sellerInfo->details }}
@endsection

@section('content')
  {{-- breadcrumb --}}
  <section class="breadcrumbs-area bg_cover lazyload bg-img header-next" data-bg-img="{{ asset('assets/img/' . $breadcrumb) }}">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="breadcrumbs-title text-center">
            <h3>
              {{ $seller->username }}
            </h3>
            <ul class="breadcumb-link justify-content-center">
              <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
              <li class="active">{{ __('Seller Details') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- breadcrumb end --}}

  <!--====== Start Seller Section ======-->
  <div class="seller-area pt-100 pb-60">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <aside class="sidebar-widget-area">
            <div class="widget-seller-details border mb-40">
              <div class="author mb-20">
                @if (request()->input('admin') != true)
                  <figure class="author-img">
                    @if (!is_null($seller->photo))
                      <img class="radius-sm lazyload" data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                        alt="image">
                    @else
                      <img class="radius-sm lazyload" data-src="{{ asset('assets/img/seller-blank-user.jpg') }}" alt="image">
                    @endif
                  </figure>
                  <div class="author-info">
                    <h5 class="mb-0">{{ @$sellerInfo->name }}</h5>

                    <p class="mb-0 mt-1">{{ $seller->username }}</p>

                    <div class="ratings mt-2 mx-auto">
                      <div class="rate bg-img"
                        data-bg-img="{{ asset('assets/front/images/rate-star.png') }}">
                        <div class="rating-icon bg-img" style="width: {{ SellerAvgRating($seller->id) * 20 }}%;"
                          data-bg-img="{{ asset('assets/front/images/rate-star.png') }}"></div>
                      </div>
                      <span class="ratings-total">({{ SellerAvgRating($seller->id) }})</span>
                    </div>
                  </div>
                @else
                  <figure class="author-img">
                    @if (!empty($seller->image))
                      <img class="rounded-lg" src="{{ asset('assets/img/admins/' . $seller->image) }}" alt="image">
                    @else
                      <img class="rounded-lg" src="{{ asset('assets/img/blank-user.jpg') }}" alt="image">
                    @endif
                  </figure>
                  <div class="author-info">
                    <h6 class="mb-0">{{ @$seller->first_name . ' ' . $seller->last_name }}</h6>
                    <p class="mb-0 mt-1">{{ $seller->username }}</p>
                  </div>
                @endif
              </div>
              @if (request()->input('admin') != true)
                @if (!empty($sellerInfo) && !is_null($sellerInfo->details))
                  <div class="click-show">
                    <p class="text font-sm">
                      <b>{{ __('About') . ':' }} </b>{!! nl2br($sellerInfo->details) !!}
                    </p>
                  </div>
                  @if (strlen($sellerInfo->details) > 150)
                    <div class="read-more-btn font-sm">{{ __('Read More') }}</div>
                  @endif
                @endif
              @endif

              <ul class="toggle-list list-unstyled mt-15 font-sm">
                <li>
                  <span class="first">{{ __('Total Services') . ' : ' }}</span>
                  <span class="last font-sm">
                    {{ count($all_services) }}
                  </span>
                </li>
                <li>
                  <span class="first">{{ __('Orders Completed') . ':' }}</span>
                  <span class="last font-sm">{{ $order_completed }}</span>
                </li>
                @if (request()->input('admin') != true)
                  <li>
                    <span class="first">{{ __('Skills') . ':' }}</span>
                    <span class="last font-sm">
                      @php
                        if ($sellerInfo) {
                            if (!is_null($sellerInfo->skills)) {
                                $selected_skills = json_decode($sellerInfo->skills);
                            } else {
                                $selected_skills = [];
                            }
                        } else {
                            $selected_skills = [];
                        }
                      @endphp
                      @foreach ($skills as $skill)
                        @if (in_array($skill->id, $selected_skills))
                          <span class="badge bg-secondary">{{ $skill->name }}</span>
                          @if (!$loop->last)
                          @endif
                        @endif
                      @endforeach
                    </span>
                  </li>
                  @if (@$seller->show_email_addresss == 1)
                    <li>
                      <span class="first">{{ __('Email') . ':' }}</span>
                      <span class="last font-sm email">
                        <span class="text-to-copy" id="textToCopy">{{ @$seller->email }}</span>
                        <button type="button" id="copyBtn" class="btn-text" data-bs-placement="top" data-tooltip="tooltip" aria-label="List View" data-bs-original-title="{{ @$seller->email }}">
                          {{ __('Copy') }}
                        </button>
                      </span>
                    </li>
                  @endif
                  @if (@$seller->show_phone_number == 1)
                    <li>
                      <span class="first">{{ __('Phone') . ':' }}</span>
                      <span class="last font-sm">{{ @$seller->phone }}</span>
                    </li>
                  @endif

                  @if (!empty($sellerInfo) && !is_null($sellerInfo->city))
                    <li>
                      <span class="first">{{ __('City') . ':' }}</span>
                      <span class="last font-sm">{{ $sellerInfo->city }}</span>
                    </li>
                  @endif
                  @if (!empty($sellerInfo) && !is_null($sellerInfo->state))
                    <li>
                      <span class="first">{{ __('State') . ':' }}</span>
                      <span class="last font-sm">{{ $sellerInfo->state }}</span>
                    </li>
                  @endif

                  @if (!empty($sellerInfo) && !is_null($sellerInfo->zip_code))
                    <li>
                      <span class="first">{{ __('Zip Code') . ':' }}</span>
                      <span class="last font-sm">{{ $sellerInfo->zip_code }}</span>
                    </li>
                  @endif
                  @if (!empty($sellerInfo) && !is_null($sellerInfo->country))
                    <li>
                      <span class="first">{{ __('Country') . ':' }}</span>
                      <span class="last font-sm">{{ $sellerInfo->country }}</span>
                    </li>
                  @endif
                  @if (!empty($sellerInfo) && !is_null($sellerInfo->address))
                    <li>
                      <span class="first">{{ __('Address') . ':' }}</span>
                      <span class="last font-sm">{{ $sellerInfo->address }}</span>
                    </li>
                  @endif

                  <li>
                    <span class="first">{{ __('Member Since') . ':' }}</span>
                    <span class="last font-sm">{{ \Carbon\Carbon::parse($seller->created_at)->format('dS M Y') }}</span>
                  </li>
                @endif
              </ul>

              <div class="btn-groups text-center mt-20">
                @if ($seller->show_contact_form == 1)
                  <button class="btn btn-lg btn-primary radius-sm w-100 mb-10" data-bs-toggle="modal" data-bs-target="#contactModal" type="button"
                    aria-label="button">{{ __('Contact Now') }}</button>
                @endif
                @php
                  if (Auth::guard('web')->check()) {
                      $user_id = Auth::guard('web')->user()->id;
                      $type = 'user';
                  } elseif (Auth::guard('seller')->check()) {
                      $user_id = Auth::guard('seller')->user()->id;
                      $type = 'seller';
                  } else {
                      $user_id = null;
                      $type = null;
                  }
                @endphp
                @if (followingCheck($user_id, $type, $seller->id) == false)
                  <a href="{{ route('frontend.seller.follow-seller', ['user_id' => $user_id, 'type' => $type, 'following_id' => $seller->id]) }}"
                    class="btn btn-lg btn-outline radius-sm w-100" title="Title">{{ __('Follow') }}</a>
                @else
                  <a href="{{ route('frontend.seller.unfollow-seller', ['user_id' => $user_id, 'type' => $type, 'following_id' => $seller->id]) }}"
                    class="btn btn-lg btn-outline radius-sm w-100" title="Title">{{ __('Unfollow') }}</a>
                @endif

              </div>
            </div>
            <div class="widget-shared-author border mb-40">
              <div class="tabs-navigation tabs-navigation-3 text-center">
                <ul class="nav nav-tabs justify-content-center border-0">
                  <li class="nav-item">
                    <button class="nav-link active" type="button" data-bs-toggle="tab"
                      data-bs-target="#followers">{{ __('Followers') }}</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#following"
                      tabindex="-1">{{ __('Following') }}</button>
                  </li>
                </ul>
              </div>

              <div class="tab-content mt-20">
                <div class="tab-pane fade show active" id="followers" role="tabpanel">
                  @foreach ($followers as $follower)
                    @if ($follower->type == 'seller')
                      @if ($follower->follower_seller)
                        <div class="shared-author mb-20">
                          <figure class="shared-author-img">
                            <a href="{{ route('frontend.seller.details', ['username' => $follower->follower_seller->username]) }}">
                              @if (!empty($follower->follower_seller->photo))
                                <img class="rounded-lg lazyload"
                                  data-src="{{ asset('assets/admin/img/seller-photo/' . $follower->follower_seller->photo) }}"
                                  alt="image">
                              @else
                                <img class="rounded-lg lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="image">
                              @endif
                            </a>
                          </figure>
                          <div class="shared-author-info flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                              <div>
                                <h6 class="mb-0"><a
                                    href="{{ route('frontend.seller.details', ['username' => $follower->follower_seller->username]) }}">{{ $follower->follower_seller->username }}</a>
                                </h6>
                                <span class="font-xsm">{{ @$follower->follower_seller->seller_info->name }}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endif
                    @elseif ($follower->type == 'user')
                      @if ($follower->follower_user)
                        <div class="shared-author mb-20">
                          <figure class="shared-author-img">
                            <img class="rounded-lg lazyload"
                              data-src="{{ is_null($follower->follower_user->image) ? asset('assets/img/blank-user.jpg') : asset('assets/img/users/' . $follower->follower_user->image) }}"
                              alt="Author">
                          </figure>
                          <div class="shared-author-info flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                              <div>
                                <h6 class="mb-0">{{ $follower->follower_user->username }}</h6>
                                <span
                                  class="font-xsm">{{ @$follower->follower_user->first_name . ' ' . @$follower->follower_user->last_name }}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endif
                    @endif
                  @endforeach
                  @if (count($followers) > 0)
                    <div class="text-center"><a href="{{ route('frontend.seller.followers', $seller->username) }}"
                        class="btn btn-md btn-primary radius-sm">{{ __('View All') }}</a></div>
                  @else
                  <div class="not-found-area p-30 bg-light radius-md text-center">
                    <h6 class="mb-0">{{ __('No Followers are found') }}</h6>
                  </div>
                  @endif
                </div>
                <div class="tab-pane fade" id="following" role="tabpanel">
                  @foreach ($followings as $following)
                    @if ($following->following_seller)
                      <div class="shared-author mb-20">
                        <figure class="shared-author-img">
                          <a
                            href="{{ route('frontend.seller.details', ['username' => $following->following_seller->username]) }}">
                            @if (!empty($following->following_seller->photo))
                              <img class="rounded-lg lazyload"
                                data-src="{{ asset('assets/admin/img/seller-photo/' . $following->following_seller->photo) }}"
                                alt="image">
                            @else
                              <img class="rounded-lg lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="image">
                            @endif
                          </a>
                        </figure>
                        <div class="shared-author-info flex-grow-1">
                          <div class="d-flex align-items-center justify-content-between">
                            <div>
                              <h6 class="mb-0"><a
                                  href="{{ route('frontend.seller.details', ['username' => $following->following_seller->username]) }}">{{ $following->following_seller->username }}</a>
                              </h6>
                              <span class="font-xsm">{{ @$following->following_seller->seller_info->name }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                  @if (count($followings) > 0)
                    <div class="text-center"><a href="{{ route('frontend.seller.followings', $seller->username) }}"
                        class="btn btn-md btn-primary radius-sm">{{ __('View All') }}</a></div>
                  @else
                  <div class="not-found-area p-30 bg-light radius-md text-center">
                    <h6 class="mb-0">{{ __('No following are found') }}</h6>
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="mt-30 mb-4 text-center advertise">
              {!! showAd(2) !!}
            </div>
          </aside>
        </div>
        <div class="col-lg-8 order-lg-first">
          <h3 class="mb-20">{{ __('Services') }}</h3>
          <div class="tabs-navigation mb-30">
            <ul class="nav nav-tabs" data-hover="fancyHover">
              <li class="nav-item active">
                <button class="nav-link hover-effect border btn-md radius-sm active" type="button" data-bs-toggle="tab"
                  data-bs-target="#all">{{ __('All') }}
                </button>
              </li>
              @foreach ($categories as $category)
                <li class="nav-item mb-10">
                  <button class="nav-link hover-effect border btn-md radius-sm " type="button" data-bs-toggle="tab" data-bs-target="#tab{{ $category->id }}" >{{ $category->name }}</button>
                </li>
              @endforeach
            </ul>
          </div>
          @php
            $position = $currencyInfo->base_currency_symbol_position;
            $symbol = $currencyInfo->base_currency_symbol;
          @endphp
          <div class="tab-content pb-10">
            <div class="tab-pane fade show active" id="all">
              @if (count($all_services) > 0)
                <div class="row">
                  @foreach ($all_services as $service)
                  <div class="col-lg-4 col-md-6">
                    <div class="service_default p-15 radius-md border mb-25">
                      <figure class="service_img">
                        <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}"
                          title="Image" target="_self" class="lazy-container ratio ratio-2-3">
                          <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/img/services/thumbnail-images/' . $service->thumbnail_image) }}"
                            alt="service">
                        </a>
                      </figure>
                      <div class="service_details mt-20">
                        <div class="authors mb-15">
                          <div class="ratings size-md">
                            <div class="rate bg-img" data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}">
                              <div class="rating-icon bg-img" style="width: {{ $service->average_rating * 20 }}%"
                                data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}"></div>
                            </div>
                            <span class="ratings-total">{{ $service->average_rating }} ({{ @$service->reviewCount }})</span>
                          </div>
                          <a href="{{ route('service.update_wishlist', ['slug' => $service->slug]) }}"
                            class="btn btn-icon radius-sm wishlist-link" data-tooltip="tooltip" data-bs-placement="top"
                            title="{{ @$wishlisted == true ? __('Saved') : __('Save to Wishlist') }}">
                            @auth('web')
                              <i class="fas fa-heart @if (@$service->wishlisted == true) added-in-wishlist @endif"></i>
                            @endauth

                            @guest('web')
                              <i class="fas fa-heart"></i>
                            @endguest
                          </a>
                        </div>

                        <h6 class="service_title lc-2 mb-0">
                          <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}"
                            target="_self" title="Link">
                            {{ strlen($service->title) > 70 ? mb_substr($service->title, 0, 70, 'UTF-8') . '...' : $service->title }}
                          </a>
                        </h6>
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
                                    ->where('language_id', $languageId)
                                    ->min('current_price');
                                $previousPackagePrice = $service
                                    ->package()
                                    ->where('language_id', $languageId)
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
              @else
                <p class="text-center">{{ __('No Service Found') }}</p>
              @endif
            </div>

            @foreach ($categories as $category)
              <div class="tab-pane fade" id="tab{{ $category->id }}">
                @php
                  if (request()->input('admin') == true) {
                      $seller_id = 0;
                  } else {
                      $seller_id = $seller->id;
                  }
                  $all_services = App\Models\ClientService\Service::join('service_contents', 'services.id', '=', 'service_contents.service_id')
                      ->where([['services.service_status', '=', 1], ['service_contents.language_id', '=', $language->id], ['service_contents.service_category_id', $category->id], ['services.seller_id', $seller_id]])
                      ->select('services.id', 'services.thumbnail_image', 'service_contents.title', 'service_contents.slug', 'services.average_rating', 'services.package_lowest_price', 'services.quote_btn_status')
                      ->orderByDesc('services.id')
                      ->get();
                  // review
                  $all_services->map(function ($service) {
                      $service['reviewCount'] = $service->review()->count();
                  });
                  // wishlist
                  if (Auth::guard('web')->check() == true) {
                      $all_services->map(function ($service) {
                          $authUser = Auth::guard('web')->user();

                          $listedService = $service
                              ->wishlist()
                              ->where('user_id', $authUser->id)
                              ->first();

                          if (empty($listedService)) {
                              $service['wishlisted'] = false;
                          } else {
                              $service['wishlisted'] = true;
                          }
                      });
                  }
                @endphp
                @if (count($all_services) > 0)
                  <div class="row">
                    @foreach ($all_services as $service)
                    <div class="col-lg-4 col-md-6">
                      <div class="service_default p-15 radius-md border mb-25">
                        <figure class="service_img">
                          <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}"
                            title="Image" target="_self" class="lazy-container ratio ratio-2-3">
                            <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/img/services/thumbnail-images/' . $service->thumbnail_image) }}"
                              alt="service">
                          </a>
                        </figure>
                        <div class="service_details mt-20">
                          <div class="authors mb-15">
                            <div class="ratings size-md">
                              <div class="rate bg-img" data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}">
                                <div class="rating-icon bg-img" style="width: {{ $service->average_rating * 20 }}%"
                                  data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}"></div>
                              </div>
                              <span class="ratings-total">{{ $service->average_rating }} ({{ @$service->reviewCount }})</span>
                            </div>
                            <a href="{{ route('service.update_wishlist', ['slug' => $service->slug]) }}"
                              class="btn btn-icon radius-sm wishlist-link" data-tooltip="tooltip" data-bs-placement="top"
                              title="{{ @$wishlisted == true ? __('Saved') : __('Save to Wishlist') }}">
                              @auth('web')
                                <i class="fas fa-heart @if (@$service->wishlisted == true) added-in-wishlist @endif"></i>
                              @endauth

                              @guest('web')
                                <i class="fas fa-heart"></i>
                              @endguest
                            </a>
                          </div>

                          <h6 class="service_title lc-2 mb-0">
                            <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}"
                              target="_self" title="Link">
                              {{ strlen($service->title) > 70 ? mb_substr($service->title, 0, 70, 'UTF-8') . '...' : $service->title }}
                            </a>
                          </h6>
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
                                      ->where('language_id', $languageId)
                                      ->min('current_price');
                                  $previousPackagePrice = $service
                                      ->package()
                                      ->where('language_id', $languageId)
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
                @else
                <div class="not-found-area p-30 bg-light radius-md text-center">
                  <h6 class="mb-0">{{ __('No Service Found') }}</h6>
                </div>
                @endif
              </div>
            @endforeach
          </div>

          <div class="mt-30 mb-4 text-center advertise">
            {!! showAd(3) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Seller Section ======-->

  <!-- Contact Modal -->
  <div class="modal contact-modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header align-item-center">
          <h4 class="modal-title mb-0" id="contactModalLabel">{{ __('Contact Now') }}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('seller.contact.message') }}" method="POST" id="sellerContactForm">
            @csrf
            <input type="hidden" name="seller_email"
              value="{{ request()->input('admin') == true ? $bs->to_mail : $seller->recipient_mail }}">
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
                  aria-label="button">{{ __('Send Message') }}</button>
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
  <script src="{{ asset('assets/js/service.js') }}"></script>
@endsection
