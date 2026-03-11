@extends('frontend.layout')

@php $title = __('Dashboard'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Dashboard Section ======-->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details mb-30">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Account Information') }}</h4>
                  </div>

                  <div class="main-info">
                    <ul class="list list-unstyled">

                      @if ($authUser->first_name != null && $authUser->last_name != null)
                      <li>
                        <span>{{ __('Name') . ':' }}</span>
                        <span>{{ $authUser->first_name . ' ' . $authUser->last_name }}</span>
                      </li>
                      @endif

                      @if ($authUser->username != null)
                      <li>
                        <span>{{ __('Username') . ':' }}</span>
                        <span>{{ $authUser->username }}</span>
                      </li>
                      @endif

                      <li>
                        <span>{{ __('Email Address') . ':' }}</span>
                        <span>{{ $authUser->email_address }}</span>
                      </li>

                      @if ($authUser->phone_number != null)
                      <li>
                        <span>{{ __('Phone') . ':' }}</span>
                        <span>{{ $authUser->phone_number }}</span>
                      </li>
                      @endif

                      @if ($authUser->address != null)
                      <li>
                        <span>{{ __('Address') . ':' }}</span>
                        <span>{{ $authUser->address }}</span>
                      </li>
                      @endif

                      @if ($authUser->city != null)
                      <li>
                        <span>{{ __('City') . ':' }}</span>
                        <span>{{ $authUser->city }}</span>
                      </li>
                      @endif

                      @if ($authUser->state != null)
                      <li>
                        <span>{{ __('State') . ':' }}</span>
                        <span>{{ $authUser->state }}</span>
                      </li>
                      @endif

                      @if ($authUser->country != null)
                      <li>
                        <span>{{ __('Country') . ':' }}</span>
                        <span>{{ $authUser->country }}</span>
                      </li>
                      @endif
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row pb-10">
            @if ($basicInfo->is_service)
              <div class="col-md-4">
                <div class="mb-30">
                  <a href="{{ route('user.service_orders') }}" class="d-block">
                    <div class="card card-box radius-md box-1">
                      <div class="card-info">
                        <h5>{{ __('Service Orders') }}</h5>
                        <p>{{ $numOfServiceOrders }}</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

              <div class="col-md-4">
                <div class="mb-30">
                  <a href="{{ route('user.service_wishlist') }}" class="d-block">
                    <div class="card card-box radius-md box-2">
                      <div class="card-info">
                        <h5>{{ __('Wishlisted Services') }}</h5>
                        <p>{{ $numOfWishlistedServices }}</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            @endif
            @if ($basicInfo->support_ticket_status == 1)
              <div class="col-md-4">
                <div class="mb-30">
                  <a href="{{ route('user.support_tickets') }}" class="d-block">
                    <div class="card card-box radius-md box-5">
                      <div class="card-info">
                        <h5>{{ __('Support Tickets') }}</h5>
                        <p>{{ $numOfsupportTicket }}</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Dashboard Section ======-->
@endsection
