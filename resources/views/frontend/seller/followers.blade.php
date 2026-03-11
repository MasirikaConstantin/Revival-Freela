@extends('frontend.layout')

@section('pageHeading')
  {{ __('Followers') }}
@endsection

@section('metaKeywords')
  {{ __('Followers') }}
@endsection

@section('metaDescription')
  {{ __('Followers') }}
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
              <li class="active">{{ __('Followers') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--====== Start Followers Section ======-->
  <div class="follower-area pt-100 pb-70">
    <div class="container">
      @if (count($followers) < 1)
        <h2 class="text-center">{{ __('No Followers are found') }}</h2>
      @endif
      <div class="row">
        @foreach ($followers as $follower)
          @if ($follower->type == 'seller')
            @if ($follower->follower_seller)
              <div class="col-xl-4 col-md-6">
                <div class="card border mb-30">
                  <figure class="card-img">
                    <a href="{{ route('frontend.seller.details', ['username' => $follower->follower_seller->username]) }}"
                      target="_self" title="{{ __('Seller') }}">
                      @if (!empty($follower->follower_seller->photo))
                        <img class="rounded-lg"
                          src="{{ asset('assets/admin/img/seller-photo/' . $follower->follower_seller->photo) }}"
                          alt="image">
                      @else
                        <img class="rounded-lg" src="{{ asset('assets/img/seller-blank-user.jpg') }}" alt="image">
                      @endif
                    </a>
                  </figure>
                  <div class="card-content">
                    <h5 class="card-title mb-1">
                      <a
                        href="{{ route('frontend.seller.details', ['username' => $follower->follower_seller->username]) }}">{{ $follower->follower_seller->username }}</a>
                    </h5>
                    <div class="font-sm">
                      <span>{{ @$follower->follower_seller->seller_info->name }}</span>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @elseif ($follower->type == 'user')
            @if ($follower->follower_user)
              <div class="col-xl-4 col-md-6">
                <div class="card border mb-30">
                  <figure class="card-img">
                    <a href="#" target="_self" title="Seller">
                      <img class="rounded-lg"
                        src="{{ is_null($follower->follower_user->image) ? asset('assets/img/blank-user.jpg') : asset('assets/img/users/' . $follower->follower_user->image) }}"
                        alt="Author">
                    </a>
                  </figure>
                  <div class="card-content">
                    <h5 class="card-title mb-1"><a href="#">{{ $follower->follower_user->username }}</a></h5>
                    <div class="font-sm">
                      <span>{{ @$follower->follower_user->first_name . ' ' . @$follower->follower_user->last_name }}</span>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @endif
        @endforeach
      </div>
      {{ $followers->links() }}

      <div class="mt-30 mb-4 text-center advertise">
        {!! showAd(3) !!}
      </div>
    </div>
  </div>
  <!--====== End Followers Section ======-->
@endsection
