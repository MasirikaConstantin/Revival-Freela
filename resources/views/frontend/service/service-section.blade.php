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
            <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}" title="Image"
              target="_self" class="lazy-container ratio ratio-2-3">
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
                  <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}" target="_self"
                    title="{{ $seller->username }}">
                    @if (!is_null($seller->photo))
                      <img class="lazyload" data-src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                        alt="Image">
                    @else
                      <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
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
                      <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
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
              <a href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}" target="_self"
                title="Link">
                {{ strlen($service->title) > 70 ? mb_substr($service->title, 0, 70, 'UTF-8') . '...' : $service->title }}
              </a>
            </h6>

            <div class="ratings size-md">
              <div class="rate bg-img"
                style="background-image:url('{{ asset('assets/front/images/rate-star-md.png') }}')">
                <div class="rating-icon bg-img"
                  style="width: {{ $service->average_rating * 20 }}%; background-image:url('{{ asset('assets/front/images/rate-star-md.png') }}')">
                </div>
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
