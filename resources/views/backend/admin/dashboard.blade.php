@extends('backend.layout')

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="pb-2">{{ __('Welcome back,') }} {{ $authAdmin->first_name . ' ' . $authAdmin->last_name . '!' }}</h2>
  </div>

  {{-- dashboard information start --}}
  @php
    if (!is_null($roleInfo)) {
        $rolePermissions = json_decode($roleInfo->permissions);
    }
  @endphp

  <div class="row dashboard-items">
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Log', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.monthly_earning') }}">
          <div class="card card-stats card-info card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-sack-dollar"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Lifetime Earnings') }}</p>
                    <h4 class="card-title">{{ symbolPrice($settings->life_time_earning) }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Log', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.monthly_profit') }}">
          <div class="card card-stats card-dark card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fas fa-usd-square"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Total Profit') }}</p>
                    <h4 class="card-title">{{ symbolPrice($settings->total_profit) }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Log', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.transcation') }}">
          <div class="card card-stats card-warning card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-exchange-alt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Total Transcation') }}</p>
                    <h4 class="card-title">{{ $total_transaction }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Log', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.payment-log.index') }}">
          <div class="card card-stats card-secondary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-exchange-alt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Subscription Log') }}</p>
                    <h4 class="card-title">{{ $memberships }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Service Management', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.service_management.services', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-success card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-headset"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Services') }}</p>
                    <h4 class="card-title">{{ $services }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Service Orders', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.service_orders') }}">
          <div class="card card-stats card-danger card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="far fa-cubes"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Service Orders') }}</p>
                    <h4 class="card-title">{{ $serviceOrders }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Tickets', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.support_tickets') }}">
          <div class="card card-stats card-info card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-ticket-alt"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Support Tickets') }}</p>
                    <h4 class="card-title">{{ $support_tickets }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.blog_management.posts', ['language' => $defaultLang->code]) }}">
          <div class="card card-stats card-dark card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-blog"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Posts') }}</p>
                    <h4 class="card-title">{{ $posts }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Sellers Management', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.seller_management.registered_seller') }}">
          <div class="card card-stats card-warning card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-users"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Sellers') }}</p>
                    <h4 class="card-title">{{ $sellers }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.user_management.registered_users') }}">
          <div class="card card-stats card-orchid card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="la flaticon-users"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Users') }}</p>
                    <h4 class="card-title">{{ $users }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
      <div class="col-sm-6 col-md-3">
        <a href="{{ route('admin.user_management.subscribers') }}">
          <div class="card card-stats card-secondary card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-bell"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Subscribers') }}</p>
                    <h4 class="card-title">{{ $subscribers }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif


  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Monthly Subscriptions') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="monthlySubscriptionChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Monthly Service Orders') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="serviceOrderChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- dashboard information end --}}
@endsection

@section('script')
  {{-- chart js --}}
  <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

  <script>
    const monthArr = {!! json_encode($months) !!};
    const serviceOrderArr = {!! json_encode($totalServiceOrders) !!};
    const subscriptionArr = {!! json_encode($subscriptionArr) !!};
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/my-chart.js') }}"></script>
@endsection
