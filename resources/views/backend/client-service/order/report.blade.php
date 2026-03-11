@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Report') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Service Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Report') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form action="" method="GET">
                <div class="row no-gutters">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('From') }}</label>
                      <input name="from" type="text" class="form-control datepicker" placeholder="Select Start Date"
                        value="{{ !empty(request()->input('from')) ? request()->input('from') : '' }}" readonly
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('To') }}</label>
                      <input name="to" type="text" class="form-control datepicker" placeholder="Select To Date"
                        value="{{ !empty(request()->input('to')) ? request()->input('to') : '' }}" readonly
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment Gateways') }}</label>
                      <select class="form-control mdb_343" name="payment_gateway">
                        <option value="" {{ empty(request()->input('payment_gateway')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>

                        @if (count($onlineGateways) > 0)
                          @foreach ($onlineGateways as $onlineGateway)
                            <option value="{{ $onlineGateway->keyword }}"
                              {{ request()->input('payment_gateway') == $onlineGateway->keyword ? 'selected' : '' }}>
                              {{ $onlineGateway->name }}
                            </option>
                          @endforeach
                        @endif

                        @if (count($offlineGateways) > 0)
                          @foreach ($offlineGateways as $offlineGateway)
                            <option value="{{ $offlineGateway->name }}"
                              {{ request()->input('payment_gateway') == $offlineGateway->name ? 'selected' : '' }}>
                              {{ $offlineGateway->name }}
                            </option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment Status') }}</label>
                      <select class="form-control mdb_343" name="payment_status">
                        <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="rejected"
                          {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Order Status') }}</label>
                      <select class="form-control mdb_343" name="order_status">
                        <option value="" {{ empty(request()->input('order_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="pending" {{ request()->input('order_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="processing"
                          {{ request()->input('order_status') == 'processing' ? 'selected' : '' }}>
                          {{ __('Processing') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('order_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="rejected" {{ request()->input('order_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary btn-sm ml-lg-3 card-header-button">
                      {{ __('Submit') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <a href="{{ route('admin.service_orders.export_report') }}"
                class="btn btn-success btn-sm float-lg-right card-header-button">
                <i class="fas fa-file-export"></i> {{ __('Export') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) == 0)
                <h3 class="text-center mt-3">{{ __('NO ORDER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Order No.') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
                        <th scope="col">{{ __('Customer Email Address') }}</th>
                        <th scope="col">{{ __('Service') }}</th>
                        <th scope="col">{{ __('Package') }}</th>
                        <th scope="col">{{ __('Package Price') }}</th>
                        <th scope="col">{{ __('Addons') }}</th>
                        <th scope="col">{{ __('Addon Price') }}</th>
                        <th scope="col">{{ __('Tax') }}</th>
                        <th scope="col">{{ __('Total Price') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Order Date') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td>{{ '#' . $order->order_number }}</td>
                          <td>{{ $order->name }}</td>
                          <td>{{ $order->email_address }}</td>
                          <td>
                            {{ strlen($order->serviceTitle) > 20 ? mb_substr($order->serviceTitle, 0, 20, 'UTF-8') . '...' : $order->serviceTitle }}
                          </td>
                          <td>
                            {{ is_null($order->packageName) ? '-' : $order->packageName }}
                          </td>
                          <td>
                            @if (is_null($order->package_price))
                              -
                            @else
                              {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{ $order->package_price }}{{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                            @endif
                          </td>
                          <td>
                            @if (count($order->addonNames) == 0)
                              -
                            @else
                              @php
                                $allAddons = '';

                                // get the array length
                                $arrLen = count($order->addonNames);

                                foreach ($order->addonNames as $key => $addonName) {
                                    // checking whether the current index is the last position of the array
                                    if ($arrLen - 1 == $key) {
                                        $allAddons .= $addonName;
                                    } else {
                                        $allAddons .= $addonName . ', ';
                                    }
                                }
                              @endphp

                              {{ $allAddons }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->addon_price))
                              -
                            @else
                              {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{ $order->addon_price }}{{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->tax))
                              {{ '-' }}
                            @else
                              {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{ $order->tax }}{{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->grand_total))
                              {{ __('Requested') }}
                            @else
                              {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{ $order->grand_total }}{{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                            @endif
                          </td>
                          <td>
                            {{ is_null($order->payment_method) ? '-' : $order->payment_method }}
                          </td>
                          <td>
                            @if ($order->payment_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @elseif ($order->payment_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($order->order_status == 'processing')
                              <span class="badge badge-primary">{{ __('Processing') }}</span>
                            @elseif ($order->order_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>{{ $order->createdAt }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              @if (count($orders) > 0)
                {{ $orders->appends([
                        'from' => request()->input('from'),
                        'to' => request()->input('to'),
                        'payment_gateway' => request()->input('payment_gateway'),
                        'payment_status' => request()->input('payment_status'),
                        'order_status' => request()->input('order_status'),
                    ])->links() }}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
