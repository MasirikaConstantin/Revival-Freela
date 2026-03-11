@extends('frontend.layout')

@php $title = __('Following'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Service Wishlist Section ======-->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details mb-40">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Following') }}</h4>
                  </div>

                  <div class="main-info seller-area">
                    @if (count($followings) == 0)
                      <div class="row text-center mt-2">
                        <div class="col">
                          <h4>{{ __('No Data Found') . '!' }}</h4>
                        </div>
                      </div>
                    @else
                      {{-- Follwing will be goes here --}}
                      <div class="row">
                        @foreach ($followings as $following)
                          @if ($following->following_seller)
                            <div class="col-xl-4 col-lg-4 col-md-6">
                              <div class="card card-center p-3 border mb-30">
                                <figure class="card-img mb-15">
                                  <a href="{{ route('frontend.seller.details', ['username' => $following->following_seller->username]) }}"
                                    target="_self" title="{{ __('Seller') }}">
                                    @if (!is_null($following->following_seller->photo))
                                      <img class="rounded-circle"
                                        src="{{ asset('assets/admin/img/seller-photo/' . $following->following_seller->photo) }}"
                                        alt="image">
                                    @else
                                      <img class="rounded-circle" src="{{ asset('assets/img/seller-blank-user.jpg') }}"
                                        alt="image">
                                    @endif
                                  </a>
                                </figure>
                                <div class="card-content">
                                  <div class="content-top">
                                    <div class="ratings mx-auto">
                                      <div class="rate bg-img"
                                        data-bg-img="{{ asset('assets/front/images/rate-star.png') }}">
                                        <div class="rating-icon bg-img"style="width: {{ SellerAvgRating(@$following->following_seller->id) * 20 }}%;"
                                          data-bg-img="{{ asset('assets/front/images/rate-star.png') }}"></div>
                                      </div>
                                      <span class="ratings-total">({{ SellerAvgRating(@$following->following_seller->id) }})</span>
                                    </div>
                                  </div>
                                  <h5 class="card-title mb-10">
                                    <a href="{{ route('frontend.seller.details', ['username' => $following->following_seller->username]) }}">{{ strlen($following->following_seller->username) > 20 ? mb_substr($following->following_seller->username, 0, 20, 'UTF-8') . '..' : $following->following_seller->username }}</a>
                                  </h5>
                                  <ul class="info-list mb-15 font-sm list-unstyled">
                                    @php
                                      $service_count = App\Models\ClientService\Service::where([['seller_id', $following->following_seller->id], ['service_status', 1]])->count();
                                    @endphp
                                    <li>{{ $service_count }} {{ $service_count > 1 ? __('Services') : __('Service') }}
                                    </li>
                                    <li>
                                      @php
                                        $follwers_count = App\Models\Follower::where('following_id', $following->following_seller->id)->count();
                                      @endphp

                                      {{ $follwers_count }} {{ __('Followers') }}
                                    </li>
                                  </ul>
                                  <a href="{{ route('frontend.seller.details', ['username' => $following->following_seller->username]) }}"
                                    target="_self" title="{{ $following->following_seller }}"
                                    class="main-btn text-center w-100">
                                    {{ __('View Profile') }}
                                  </a>
                                </div>
                              </div>
                            </div>
                          @endif
                        @endforeach
                      </div>
                      {{ $followings->links() }}
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Service Wishlist Section ======-->
@endsection
