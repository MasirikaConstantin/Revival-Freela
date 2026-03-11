@extends('frontend.layout')

@section('pageHeading')
  {{ $pageHeading->seller_page_title ?? __('Sellers') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->seller_page_meta_keywords ?? '' }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->seller_page_meta_description ?? '' }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $breadcrumb ?? '',
      'title' => $pageHeading->seller_page_title ?? __('Sellers'),
  ])

  <!--====== Start Seller Section ======-->
  <div class="seller-area pt-100 pb-70">
    <div class="container">
      <form action="{{ route('frontend.sellers') }}" method="get">
        <div class="row justify-content-left mb-30">
          <div class="col-lg-3 col-md-6">
            <div class="seller-search mb-10">
              <div class="form-group">
                <input type="text" placeholder="{{ __('Seller Name/Username') }}" class="form-control rounded"
                  name="name" value="{{ request()->input('name') }}">
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="seller-search mb-10">
              <div class="form-group">
                <input type="text" placeholder="{{ __('Seller Location') }}" class="form-control rounded"
                  name="location" value="{{ request()->input('location') }}">
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="seller-search mb-10">
              <div class="form-group">
                <button class="btn btn-lg btn-primary radius-sm">{{ __('Search') }}</button>
              </div>
            </div>
          </div>

        </div>
      </form>
      <div class="row">
        @if (count($sellers) > 0)
          @foreach ($sellers as $seller)
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="card card-center p-3 border mb-30">
                <figure class="card-img mb-15">
                  <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}" target="_self"
                    title="Seller">
                    @if (!is_null($seller->photo))
                      <img class="rounded-circle" src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                        alt="image">
                    @else
                      <img class="rounded-circle" src="{{ asset('assets/img/seller-blank-user.jpg') }}" alt="image">
                    @endif
                  </a>
                </figure>
                <div class="card-content">
                  <div class="content-top">
                    <div class="ratings mx-auto">
                      <div class="rate bg-img"
                        data-bg-img="{{ asset('assets/front/images/rate-star.png') }}">
                        <div class="rating-icon bg-img" style="width: {{ SellerAvgRating($seller->sellerId) * 20 }}%;"
                          data-bg-img="{{ asset('assets/front/images/rate-star.png') }}"></div>
                      </div>
                      <span class="ratings-total">({{ SellerAvgRating($seller->sellerId) }})</span>
                    </div>
                  </div>
                  <h5 class="card-title mb-10"><a
                      href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}">{{ strlen($seller->username) > 20 ? mb_substr($seller->username, 0, 20, 'UTF-8') . '..' : $seller->username }}</a>
                  </h5>
                  <ul class="info-list list-unstyled mb-15 font-sm">
                    @php
                      $service_count = App\Models\ClientService\Service::where([['seller_id', $seller->sellerId], ['service_status', 1]])->count();
                    @endphp
                    <li>{{ $service_count }} {{ $service_count > 1 ? __('Services') : __('Service') }}</li>
                    <li>
                      @php
                        $follwers_count = App\Models\Follower::where('following_id', $seller->sellerId)->count();
                      @endphp

                      {{ $follwers_count }} {{ __('Followers') }}
                    </li>
                  </ul>
                  <a href="{{ route('frontend.seller.details', ['username' => $seller->username]) }}" target="_self"
                    title="{{ $seller->username }}" class="main-btn text-center w-100">
                    {{ __('View Profile') }}
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12">
            <h4 class="text-center">{{ __('No Seller Found.') }}</h4>
          </div>
        @endif


        <div class="col-12">
          {{ $sellers->appends([
                  'name' => request()->input('name'),
                  'location' => request()->input('location'),
              ])->links() }}
        </div>
      </div>
      <div class="mt-30 text-center advertise">
        {!! showAd(3) !!}
      </div>
    </div>
  </div>
  <!--====== End Seller Section ======-->
@endsection
