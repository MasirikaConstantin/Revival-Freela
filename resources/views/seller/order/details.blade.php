@extends('seller.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Order Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('seller.dashboard') }}">
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
        <a href="{{ route('seller.service_orders') }}">{{ __('All Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Order Details') }}</a>
      </li>
    </ul>
    <a href="{{ route('seller.service_orders') }}" class="btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>

  <div class="row">
    @php
      $position = $orderInfo->currency_text_position;
      $currency = $orderInfo->currency_text;
    @endphp

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Order Information') }}</div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Order No.') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">{{ '#' . $orderInfo->order_number }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Order Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">{{ date_format($orderInfo->created_at, 'M d, Y') }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Service') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                @if (!empty($serviceTitle->slug))
                  <a target="_blank"
                    href="{{ route('service_details', ['slug' => $serviceTitle->slug, 'id' => $orderInfo->service_id]) }}">
                    {{ $serviceTitle->title }}
                  </a>
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Package') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                @if (is_null($packageTitle))
                  -
                @else
                  {{ $packageTitle }}
                  ({{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->package_price }}{{ $position == 'right' ? ' ' . $currency : '' }})
                @endif
              </div>
            </div>

            @if (!is_null($orderInfo->addons))
              @php $addons = json_decode($orderInfo->addons); @endphp

              <div class="row mb-2">
                <div class="col-lg-3">
                  <strong>{{ __('Addons') . ' :' }}</strong>
                </div>

                <div class="col-lg-9">
                  @php
                    $totalAdonPrice = 0;
                  @endphp
                  @foreach ($addons as $addon)
                    @php
                      $addonId = $addon->id;

                      $serviceAddon = \App\Models\ClientService\ServiceAddon::query()->find($addonId);
                    @endphp

                    {{ $loop->iteration . '.' }} {{ $serviceAddon->name }}
                    ({{ $position == 'left' ? $currency . ' ' : '' }}{{ $addon->price }}{{ $position == 'right' ? ' ' . $currency : '' }})
                    @php
                      $totalAdonPrice = $totalAdonPrice + $addon->price;
                    @endphp
                    <br>
                  @endforeach
                  <hr class="mb-1 mt-1">
                  <p class="mt-0">{{ __('Total') . ':' }}
                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $totalAdonPrice }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  </p>
                </div>
              </div>
            @endif
            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Recived Amount') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                @if (is_null($orderInfo->grand_total))
                  {{ __('Requested') }}
                @else
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->grand_total - $orderInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @endif
              </div>
            </div>

            @if (!is_null($orderInfo->tax))
              <div class="row mb-2">
                <div class="col-lg-3">
                  <strong>{{ __('Tax') }} ({{ $orderInfo->tax_percentage . '%' }}) <i
                      class="fas fa-plus text-danger text-small"></i> : </strong>
                </div>

                <div class="col-lg-9">
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  {{ __('(Received by Admin)') }}
                </div>
              </div>
            @endif

            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Total') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                @if (is_null($orderInfo->grand_total))
                  {{ __('Requested') }}
                @else
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->grand_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @endif
              </div>
            </div>


            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Paid via') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                @if (is_null($orderInfo->payment_method))
                  -
                @else
                  {{ $orderInfo->payment_method }}
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Payment Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                @if ($orderInfo->gateway_type == 'online')
                  @if ($orderInfo->payment_status == 'completed')
                    <span class="badge badge-success">{{ __('Completed') }}</span>
                  @else
                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                  @endif
                @else
                  @if ($orderInfo->payment_status == 'pending' && is_null($orderInfo->grand_total))
                    <form id="paymentStatusForm" class="d-inline-block"
                      action="{{ route('seller.service_order.update_payment_status', ['id' => $orderInfo->id]) }}"
                      method="post">
                      @csrf
                      <select
                        class="form-control form-control-sm @if ($orderInfo->payment_status == 'completed') bg-success @elseif ($orderInfo->payment_status == 'pending') bg-warning text-dark @else bg-danger @endif"
                        name="payment_status" onchange="document.getElementById('paymentStatusForm').submit()">
                        <option value="completed" {{ $orderInfo->payment_status == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ $orderInfo->payment_status == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="rejected" {{ $orderInfo->payment_status == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </form>
                  @else
                    @if ($orderInfo->payment_status == 'completed')
                      <span class="badge badge-success">{{ __('Completed') }}</span>
                    @elseif ($orderInfo->payment_status == 'pending')
                      <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @else
                      <span class="badge badge-danger">{{ __('Rejected') }}</span>
                    @endif
                  @endif
                @endif
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-3">
                <strong>{{ __('Order Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">
                <span
                  class="badge @if ($orderInfo->order_status == 'pending') badge-primary @elseif ($orderInfo->order_status == 'completed') badge-success @elseif ($orderInfo->order_status == 'rejected') badge-danger @endif">{{ ucfirst($orderInfo->order_status) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Customer Information') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">{{ $orderInfo->name }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-3">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-9">{{ $orderInfo->email_address }}</div>
            </div>

            @php $informations = json_decode($orderInfo->informations); @endphp

            @if (!is_null($informations))
              @foreach ($informations as $key => $information)
                @php
                  $length = count((array) $informations);
                  $str = preg_replace('/_/', ' ', $key);
                  $label = mb_convert_case($str, MB_CASE_TITLE);
                @endphp

                @if ($information->type == 8)
                  <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                    <div class="col-lg-3">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-9">
                      <a href="{{ asset('assets/file/zip-files/' . $information->value) }}"
                        download="{{ $information->originalName }}" class="btn btn-sm btn-primary">
                        {{ __('Download') }}
                      </a>
                    </div>
                  </div>
                @elseif ($information->type == 5)
                  <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                    <div class="col-lg-3">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-9">
                      <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                        data-target="#textModal-{{ $loop->iteration }}">
                        {{ __('Show') }}
                      </a>
                    </div>
                  </div>

                  @include('seller.order.show-text')
                @elseif ($information->type == 4)
                  <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                    <div class="col-lg-3">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-9">
                      @php
                        $checkboxValues = $information->value;
                        $allCheckboxOptions = '';
                        $lastElement = end($checkboxValues);

                        foreach ($checkboxValues as $value) {
                            if ($value == $lastElement) {
                                $allCheckboxOptions .= $value;
                            } else {
                                $allCheckboxOptions .= $value . ', ';
                            }
                        }
                      @endphp

                      {{ $allCheckboxOptions }}
                    </div>
                  </div>
                @else
                  <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                    <div class="col-lg-3">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-9">{{ $information->value }}</div>
                  </div>
                @endif
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
