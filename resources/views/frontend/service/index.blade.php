@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->services_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_services }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_services }}
  @endif
@endsection

@section('content')
  @php
    $title = $pageHeading->services_page_title ?? __('No Page Title Found');
  @endphp

  <!--====== Start Service Area ======-->
  <div class="service-gig-area header-next pt-90 pb-90">
    <div class="container">
      <div class="row">
        <div class="col-xl-3">
          <div class="widget-offcanvas" id="widgetOffcanvas">
            <div class="offcanvas-header ps-3 pe-3">
              <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
              <button type="button" class="btn-close" data-dismiss="widgetOffcanvas">X</button>
            </div>
            <div class="offcanvas-body offcanvas-body p-3 p-xl-0">
              <div class="gigs-sidebar">
                @if (count($categories) > 0)
                  <div class="widget widget-categories mb-30">
                    <h4 class="widget-title">{{ __('Categories') }}</h4>
                    <ul class="widget-link list-unstyled toggle-list" data-toggle-list="pricingToggle"
                      data-toggle-show="5">
                      <li>
                        <a href="#"
                          class="category-search {{ empty(request()->input('category')) ? 'active' : '' }}">
                          <i
                            class="{{ $currentLanguageInfo->direction == 0 ? 'far fa-angle-right' : 'far fa-angle-left' }}"></i>
                          {{ __('All') }}
                        </a>
                      </li>
                      @foreach ($categories as $category)
                        <li class="cat-item">
                          <a href="#"
                            class="category-search {{ $category->slug == request()->input('category') ? 'active' : '' }}"
                            data-category_slug="{{ $category->slug }}">
                            <i
                              class="{{ $currentLanguageInfo->direction == 0 ? 'far fa-angle-right' : 'far fa-angle-left' }}"></i>
                            {{ $category->name }}
                          </a>
                          @php $subcategories = $category->subcategories; @endphp

                          @if (count($subcategories) > 0)
                            <ul class="widget-link list-unstyled widget-subcategories">
                              @foreach ($subcategories as $subcategory)
                                <li>
                                  <a href="#"
                                    class="subcategory-search {{ $subcategory->slug == request()->input('subcategory') ? 'active' : '' }}"
                                    data-subcategory_slug="{{ $subcategory->slug }}">
                                    {{ $subcategory->name }}
                                  </a>
                                </li>
                              @endforeach
                            </ul>
                          @endif
                        </li>
                      @endforeach
                    </ul>
                    <span class="show-more mt-15" data-toggle-btn="toggleListBtn">
                      {{ __('Show More') . ' +' }}
                    </span>
                  </div>
                @endif

                <div class="widget widget-skills mb-30">
                  <h4 class="widget-title">{{ __('Skills') }}</h4>
                  <select class="js-select2" multiple="multiple" name="skills[]">
                    @foreach ($skills as $skill)
                      <option value="{{ $skill->id }}" @selected(request()->input('skills') == $skill->id)>{{ $skill->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="widget widget-time mb-30">
                  <h4 class="widget-title">{{ __('Delivery Time') }}</h4>
                  <select class="niceselect float-none delivery_time" name="delivery_time">
                    <option value="1">{{ __('Express 24H') }}</option>
                    <option value="3">{{ __('Up to 3 days') }}</option>
                    <option value="7">{{ __('Up to 7 days') }}</option>
                    <option value="" selected>{{ __('Anytime') }}</option>
                  </select>
                </div>

                <div class="widget widget-product-filter mb-30">
                  <h4 class="widget-title">{{ __('Pricing Type') }}</h4>
                  <div class="single-filter">
                    <input type="radio" class="single_input pricing-search" id="alls" name="pricing" value=""
                      {{ empty(request()->input('pricing')) ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="alls"><span>{{ __('Show All') }}</span></label>
                  </div>
                  <div class="single-filter">
                    <input type="radio" class="single_input pricing-search" id="fixed-price" name="pricing"
                      value="fixed price" {{ request()->input('pricing') == 'fixed price' ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="fixed-price"><span>{{ __('Fixed Price') }}</span></label>
                  </div>
                  <div class="single-filter">
                    <input type="radio" class="single_input pricing-search" id="negotiable" name="pricing"
                      value="negotiable" {{ request()->input('pricing') == 'negotiable' ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="negotiable"><span>{{ __('Negotiable') }}</span></label>
                  </div>
                </div>

                <div class="widget widget-price-range mb-30">
                  <h4 class="widget-title">{{ __('Filter By Price') }}</h4>
                  <div id="range-slider"></div>
                  <span class="text">{{ __('Price') . ' :' }}</span>
                  <input type="text" id="amount" readonly>
                </div>

                <div class="widget widget-product-filter mb-30">
                  <h4 class="widget-title">{{ __('Filter By Rating') }}</h4>
                  <div class="single-filter">
                    <input type="radio" class="single_input rating-search" id="all" name="filter_rating"
                      value="" {{ empty(request()->input('rating')) ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="all"><span>{{ __('Show All') }}</span></label>
                  </div>

                  <div class="single-filter">
                    <input type="radio" class="single_input rating-search" id="five-star" name="filter_rating"
                      value="5" {{ request()->input('rating') == 5 ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="five-star"><span>{{ 5 . ' ' . __('Star') }}</span></label>
                  </div>

                  <div class="single-filter">
                    <input type="radio" class="single_input rating-search" id="four-star" name="filter_rating"
                      value="4" {{ request()->input('rating') == 4 ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="four-star"><span>{{ 4 . ' ' . __('Star & Above') }}</span></label>
                  </div>

                  <div class="single-filter">
                    <input type="radio" class="single_input rating-search" id="three-star" name="filter_rating"
                      value="3" {{ request()->input('rating') == 3 ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="three-star"><span>{{ 3 . ' ' . __('Star & Above') }}</span></label>
                  </div>

                  <div class="single-filter">
                    <input type="radio" class="single_input rating-search" id="two-star" name="filter_rating"
                      value="2" {{ request()->input('rating') == 2 ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="two-star"><span>{{ 2 . ' ' . __('Star & Above') }}</span></label>
                  </div>

                  <div class="single-filter">
                    <input type="radio" class="single_input rating-search" id="one-star" name="filter_rating"
                      value="1" {{ request()->input('rating') == 1 ? 'checked' : '' }}>
                    <label class="single_input_label single_input_check"
                      for="one-star"><span>{{ 1 . ' ' . __('Star & Above') }}</span></label>
                  </div>
                </div>

                <div class="mb-30">
                  <a href="#"
                    class="btn btn-md btn-primary radius-sm d-block text-center reset-search">{{ __('Reset Search') }}</a>
                </div>

                <div class="mb-30 text-center">
                  {!! showAd(1) !!}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-9">
          <div class="row justify-content-between pb-20">
            <div class="col-xl-4 col-lg-4 col-sm-6 col-md-5">
              <div class="gig-search mb-20">
                <form action="" id="serviceSearch">
                  <div class="form-group">
                    <input type="text" placeholder="{{ __('Search by keyword') . '...' }}"
                      class="form-control input-search"
                      value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}"
                      name="keyword">
                    <i class="far fa-search serviceSearchBtn"></i>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6 col-md-5">
              <div class="gig-select mb-20">
                <select class="niceselect wide" id="sort-search">
                  <option selected disabled>{{ __('Sort By') }}</option>
                  <option {{ request()->input('sort') == 'new' ? 'selected' : '' }} value="new">
                    {{ __('New Services') }}
                  </option>
                  <option {{ request()->input('sort') == 'old' ? 'selected' : '' }} value="old">
                    {{ __('Old Services') }}
                  </option>
                  <option {{ request()->input('sort') == 'ascending' ? 'selected' : '' }} value="ascending">
                    {{ __('Price') . ': ' . __('Ascending') }}
                  </option>
                  <option {{ request()->input('sort') == 'descending' ? 'selected' : '' }} value="descending">
                    {{ __('Price') . ': ' . __('Descending') }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-sm-4 col-md-2 d-xl-none">
              <button class="btn btn-md btn-primary radius-sm filter-btn mb-20 w-100" type="button"
                data-toggle="widgetOffcanvas">
                Filter <i class="fal fa-filter"></i>
              </button>
            </div>
          </div>
          <div class="service-container">
            @if (count($services) == 0)
              <div class="row">
                <div class="col">
                  <h3 class="text-center mt-5">{{ __('No Service Found') . '!' }}</h3>
                </div>
              </div>
            @else
              <div class="row sss">
                @foreach ($services as $service)
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
                                    alt="Image">
                                @else
                                  <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                    alt="Image">
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
                                target="_self" title="James Hobert">
                                @if (!empty($admin->image))
                                  <img class="lazyload" data-src="{{ asset('assets/img/admins/' . $admin->image) }}"
                                    alt="Image">
                                @else
                                  <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                    alt="Image">
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
                                    target="_self" title="Graphic Designer">
                                    {{ strlen($admin->first_name . ' ' . $admin->last_name) > 20 ? mb_substr($admin->first_name . ' ' . $admin->last_name, 0, 20, 'UTF-8') . '..' : $admin->first_name . ' ' . $admin->last_name }}
                                  </a>
                                </span>
                              </div>
                            </div>
                          @endif
                          <a href="{{ route('service.update_wishlist', ['slug' => $service->slug]) }}"
                            class="btn btn-icon radius-sm wishlist-link" data-tooltip="tooltip" data-bs-placement="top"
                            title="{{ @$service->wishlisted == true ? __('Remove from wishlist') : __('Save to Wishlist') }}">
                            @auth('web')
                              <i class="fas fa-heart @if (@$service->wishlisted == true) added-in-wishlist @endif"></i>
                            @endauth

                            @guest('web')
                              <i class="fas fa-heart"></i>
                            @endguest
                          </a>
                        </div>

                        <h6 class="service_title lc-2 mb-15">
                          <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}"
                            target="_self">
                            {{ strlen($service->title) > 70 ? mb_substr($service->title, 0, 70, 'UTF-8') . '...' : $service->title }}
                          </a>
                        </h6>

                        <div class="ratings size-md">
                          <div class="rate bg-img" data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}">
                            <div class="rating-icon bg-img" style="width: {{ $service->average_rating * 20 }}%"
                              data-bg-img="{{ asset('assets/front/images/rate-star-md.png') }}"></div>
                          </div>
                          <span class="ratings-total">{{ $service->average_rating }}
                            ({{ @$service->reviewCount }})
                          </span>
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
                                $currentMinPackagePrice = $service->package()->where('language_id', $languageId)->min('current_price');
                                $previousPackagePrice = $service->package()->where('language_id', $languageId)->min('previous_price');
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
              <div class="row">
                <div class="col-md-12">
                  <nav class="pagination-nav">
                    <ul class="pagination justify-content-center">
                      {{ $services->appends([
                              'keyword' => request()->input('keyword'),
                              'category' => request()->input('category'),
                              'subcategory' => request()->input('subcategory'),
                              'tag' => request()->input('tag'),
                              'rating' => request()->input('rating'),
                              'min' => request()->input('min'),
                              'max' => request()->input('max'),
                              'sort' => request()->input('sort'),
                          ])->links() }}
                    </ul>
                  </nav>
                </div>
              </div>
            @endif
          </div>
        </div>
        <div class="col-lg-12">
          <div class="mt-50 text-center advertise">
            {!! showAd(3) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Service Area ======-->

  <form class="d-none" action="{{ route('services') }}" method="GET" id="searchForm">
    <input type="hidden" id="keyword-id" name="keyword"
      value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}">

    <input type="hidden" id="category-id" name="category"
      value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">

    <input type="hidden" id="subcategory-id" name="subcategory"
      value="{{ !empty(request()->input('subcategory')) ? request()->input('subcategory') : '' }}">

    <input type="hidden" id="tag-id" name="tag"
      value="{{ !empty(request()->input('tag')) ? request()->input('tag') : '' }}">

    <input type="hidden" id="delivery_time" name="delivery_time"
      value="{{ !empty(request()->input('delivery_time')) ? request()->input('delivery_time') : '' }}">

    <input type="hidden" id="pricing-id" name="pricing"
      value="{{ !empty(request()->input('pricing')) ? request()->input('pricing') : '' }}">

    <input type="hidden" id="rating-id" name="rating"
      value="{{ !empty(request()->input('rating')) ? request()->input('rating') : '' }}">

    <input type="hidden" id="min-id" name="min"
      value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

    <input type="hidden" id="max-id" name="max"
      value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">

    <input type="hidden" id="sort-id" name="sort"
      value="{{ !empty(request()->input('sort')) ? request()->input('sort') : '' }}">
    <textarea class="d-none" name="skills" id="skills" cols="30" rows="10">
      @if (request()->filled('skills') && !empty(request()->input('skills')))
{{ json_encode([request()->input('skills')]) }}
@endif
    </textarea>

    <button type="submit" id="submitBtn"></button>
  </form>

@endsection

@section('script')
  <script>
    let currency_info = {!! json_encode($currencyInfo) !!};
    let position = currency_info.base_currency_symbol_position;
    let symbol = currency_info.base_currency_symbol;
    let min_price = {{ !empty($minPrice) ? $minPrice : 0 }};
    let max_price = {{ !empty($maxPrice) ? $maxPrice : 0 }};
    let curr_min =
      {{ (!empty(request()->input('min')) ? request()->input('min') : !empty($minPrice)) ? $minPrice : 0 }};
    let curr_max =
      {{ (!empty(request()->input('max')) ? request()->input('max') : !empty($maxPrice)) ? $maxPrice : 0 }};
    var searchUrl = "{{ route('search-service') }}";
    var serviceUrl = "{{ route('services') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/service.js') }}"></script>
@endsection
