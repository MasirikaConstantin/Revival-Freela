@extends('frontend.layout')

@section('pageHeading')
  {{ __('Following') }}
@endsection

@section('metaKeywords')
  {{ __('Following') }}
@endsection

@section('metaDescription')
  {{ __('Following') }}
@endsection

@section('content')
  <section class="breadcrumbs-area bg_cover lazyload bg-img header-next" data-bg-img="{{ asset('assets/img/' . $breadcrumb) }}">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="breadcrumbs-title text-center">
            <h3>
                {{ $username }}
            </h3>
            <ul class="breadcumb-link justify-content-center">
              <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
              <li class="active">{{ __('Following') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--====== Start Followers Section ======-->
  <div class="follower-area pt-100 pb-70">
    <div class="container">
      @if (count($followings) < 1)
        <h2 class="text-center">{{ __('No following are found') }}</h2>
      @endif
      <div class="row">
        @foreach ($followings as $following)
          @if ($following->following_seller)
            <div class="col-xl-4 col-md-6">
              <div class="card border mb-30">
                <figure class="card-img">
                  <a href="{{ route('frontend.seller.details', ['username' => $following->follower_seller->username]) }}"
                    target="_self" title="{{ __('Seller') }}">
                    @if (!empty($following->follower_seller->photo))
                      <img class="rounded-lg"
                        src="{{ asset('assets/admin/img/seller-photo/' . $following->follower_seller->photo) }}"
                        alt="image">
                    @else
                      <img class="rounded-lg" src="{{ asset('assets/img/seller-blank-user.jpg') }}" alt="image">
                    @endif
                  </a>
                </figure>
                <div class="card-content">
                  <h5 class="card-title mb-1">
                    <a
                      href="{{ route('frontend.seller.details', ['username' => $following->follower_seller->username]) }}">{{ $following->follower_seller->username }}</a>
                  </h5>
                  <div class="font-sm">
                    <span>{{ @$following->follower_seller->seller_info->name }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
      {{ $followings->links() }}
      <div class="mt-30 mb-4 text-center advertise">
        {!! showAd(3) !!}
      </div>
    </div>
  </div>
  <!--====== End Followers Section ======-->
@endsection
