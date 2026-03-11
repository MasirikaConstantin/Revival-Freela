@extends('seller.layout')

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="pb-2">{{ __('Welcome back,') }} {{ Auth::guard('seller')->user()->username . '!' }}</h2>
  </div>

  @php
    $data = sellerPermission(Auth::guard('seller')->user()->id, 'form', $defaultLang->id);
    $data2 = sellerPermission(Auth::guard('seller')->user()->id, 'service');
    $data3 = sellerPermission(Auth::guard('seller')->user()->id, 'service-order');
    $data4 = sellerPermission(Auth::guard('seller')->user()->id, 'service-featured');
  @endphp
  @if (
      $data['status'] == 'false' ||
          $data2['status'] == 'false' ||
          $data3['status'] == 'false' ||
          $data4['status'] == 'false')
    <div class="alert alert-warning alert-block">
      @if ($data['status'] == 'false')
        <strong
          class="text-dark">{{ __('Currently, you have added ' . $data['total_form_added'] . ' forms. ' . 'Your current package supports ' . $data['package_support'] . ' forms. Please delete ' . $data['total_form_added'] - $data['package_support'] . ' forms to enable form management.') }}</strong>
        <br>
      @endif

      @if ($data2['status'] == 'false')
        <strong
          class="text-dark">{{ __('Currently, you have added ' . $data2['total_service_added'] . ' services. ' . 'Your current package supports ' . $data2['package_support'] . ' services. Please delete ' . $data2['total_service_added'] - $data2['package_support'] . ' services to enable service management.') }}</strong>
        <br>
      @endif
      @if ($data3['status'] == 'false')
        <strong
          class="text-dark">{{ __('Currently, you have received ' . $data3['total_service_ordered'] . ' Orders. ' . 'Your current package supports ' . $data3['package_support'] . ' services orders. if you want to receive more orders from your customers, then please upgrade the package') }}
          <a href="{{ route('seller.plan.extend.index') }}">{{ __('from here') }}</a></strong>
        <br>
      @endif
      @if ($data4['status'] == 'false')
        <strong
          class="text-dark">{{ __('Currently, You have featured ' . $data4['total_service_featured'] . ' services. ' . 'Your current package supports ' . $data4['package_support'] . ' services to make featured. Please unfeatured ' . $data4['total_service_featured'] - $data4['package_support'] . ' services to enable service management') }}</strong>
        <br>
      @endif
    </div>
  @endif

  @if (Auth::guard('seller')->user()->status == 0 && $admin_setting->seller_admin_approval == 1)
    <div class="mt-2 mb-4">
      <div class="alert alert-danger text-dark">
        {{ $admin_setting->admin_approval_notice != null ? $admin_setting->admin_approval_notice : 'Your account is deactive!' }}
      </div>
    </div>
  @endif

  @php
    $seller = Auth::guard('seller')->user();
    $package = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller->id);
  @endphp

  @if (is_null($package))
    @php
      $pendingMemb = \App\Models\Membership::query()
          ->where([['seller_id', '=', $seller->id], ['status', 0]])
          ->whereYear('start_date', '<>', '9999')
          ->orderBy('id', 'DESC')
          ->first();
      $pendingPackage = isset($pendingMemb) ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id) : null;
    @endphp

    @if ($pendingPackage)
      <div class="alert alert-warning text-dark">
        {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
      </div>
      <div class="alert alert-warning text-dark">
        <strong>{{ __('Pending Package') . ':' }} </strong> {{ $pendingPackage->title }}
        <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
        <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
      </div>
    @else
      <div class="alert alert-warning text-dark">
        {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
      </div>
    @endif
  @else
    <div class="row justify-content-center align-items-center mb-1">
      <div class="col-12">
        <div class="alert border-left border-primary text-dark">
          @if ($package_count >= 2 && $next_membership)
            @if ($next_membership->status == 0)
              <strong
                class="text-danger">{{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}</strong><br>
            @elseif ($next_membership->status == 1)
              <strong
                class="text-danger">{{ __('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated') }}</strong><br>
            @endif
          @endif

          <strong>{{ __('Current Package') . ':' }} </strong> {{ $current_package->title }}
          <span class="badge badge-secondary">{{ $current_package->term }}</span>
          @if ($current_membership->is_trial == 1)
            ({{ __('Expire Date') . ':' }}
            {{ Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
            <span class="badge badge-primary">{{ __('Trial') }}</span>
          @else
            ({{ __('Expire Date') . ':' }}
            {{ $current_package->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($current_membership->expire_date)->format('M-d-Y') }})
          @endif

          @if ($package_count >= 2 && $next_package)
            <div>
              <strong>{{ __('Next Package To Activate') . ':' }} </strong> {{ $next_package->title }} <span
                class="badge badge-secondary">{{ $next_package->term }}</span>
              @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
                (
                {{ __('Activation Date') . ':' }}
                {{ Carbon\Carbon::parse($next_membership->start_date)->format('M-d-Y') }},
                {{ __('Expire Date') . ':' }}
                {{ $next_package->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($next_membership->expire_date)->format('M-d-Y') }})
              @endif
              @if ($next_membership->status == 0)
                <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif

  {{-- dashboard information start --}}
  <div class="row dashboard-items">
    <div class="col-sm-6 col-md-4">
      <a href="{{ route('seller.monthly_income') }}">
        <div class="card card-stats card-secondary card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fal fa-dollar-sign"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('My Balance') }}</p>
                  <h4 class="card-title">
                    {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                    {{ Auth::guard('seller')->user()->amount }}
                    {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-sm-6 col-md-4">
      <a href="{{ route('seller.transcation') }}">
        <div class="card card-stats card-warning card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-exchange"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Transaction') }}</p>
                  <h4 class="card-title">
                    {{ $transcations }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-sm-6 col-md-4">
      <a href="{{ route('seller.service_management.services', ['language' => $defaultLang->code]) }}">
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

    <div class="col-sm-6 col-md-4">
      <a href="{{ route('seller.service_orders') }}">
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

    @if ($current_package != '[]')
      <div class="col-sm-6 col-md-4">
        <a href="{{ route('seller.subscription_log') }}">
          <div class="card card-stats card-info card-round">
            <div class="card-body">
              <div class="row">
                <div class="col-5">
                  <div class="icon-big text-center">
                    <i class="fal fa-lightbulb-dollar"></i>
                  </div>
                </div>

                <div class="col-7 col-stats">
                  <div class="numbers">
                    <p class="card-category">{{ __('Subscription Log') }}</p>
                    <h4 class="card-title">{{ $payment_logs }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    <div class="col-sm-6 col-md-4">
      <a href="{{ route('seller.support_tickets') }}">
        <div class="card card-stats card-dark card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fal fa-headset"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Support Tickets') }}</p>
                  <h4 class="card-title">{{ $support_tickets_count }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Month Wise Total Incomes') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="serviceIncomeChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Number of Service Orders') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="serviceOrderChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  {{-- chart js --}}
  <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

  <script>
    const monthArr = {!! json_encode($months) !!};
    const serviceOrderArr = {!! json_encode($totalServiceOrders) !!};
    const serviceIncomeArr = {!! json_encode($totalServiceIncomes) !!};
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/my-chart.js') }}"></script>
@endsection
