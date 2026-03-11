@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Order Details') }}</h4>
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
        <a href="{{ route('admin.service_orders') }}">{{ __('All Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Order Details') }}</a>
      </li>
    </ul>
    <a href="{{ route('admin.service_orders') }}" class="btn btn-primary ml-auto">{{ __('Back') }}</a>
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
                @else
                  {{ '-' }}
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

            @if (!is_null($orderInfo->seller_id))
              <div class="row mb-2">
                <div class="col-lg-3">
                  <strong>{{ __('Seller Recived') . ' :' }}</strong>
                </div>

                <div class="col-lg-9">
                  @if (is_null($orderInfo->grand_total))
                    {{ __('Requested') }}
                  @else
                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->grand_total - $orderInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  @endif
                </div>
              </div>
            @endif

            @if (!is_null($orderInfo->tax))
              <div class="row mb-2">
                <div class="col-lg-3">
                  <strong>{{ __('Tax') }} ({{ $orderInfo->tax_percentage . '%' }}) <i
                      class="fas fa-plus text-success text-small"></i> : </strong>
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
                  @if ($orderInfo->payment_status == 'pending')
                    <form id="paymentStatusForm" class="d-inline-block"
                      action="{{ route('admin.service_order.update_payment_status', ['id' => $orderInfo->id]) }}"
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
                @if ($orderInfo->order_status == 'pending')
                  <form class="d-inline-block completeForm"
                    action="{{ route('admin.service_order.update_order_status', ['id' => $orderInfo->id]) }}"
                    method="post">
                    @csrf
                    <select
                      class="form-control completeBtn form-control-sm @if ($orderInfo->order_status == 'pending') bg-warning text-dark @elseif ($orderInfo->order_status == 'processing') bg-primary @elseif ($orderInfo->order_status == 'completed') bg-success @elseif ($orderInfo->order_status == 'rejected') bg-danger @endif"
                      name="order_status">
                      <option disabled value="pending" {{ $orderInfo->order_status == 'pending' ? 'selected' : '' }}>
                        {{ __('Pending') }}
                      </option>
                      <option value="completed" {{ $orderInfo->order_status == 'completed' ? 'selected' : '' }}>
                        {{ __('Completed') }}
                      </option>
                      <option value="rejected" {{ $orderInfo->order_status == 'rejected' ? 'selected' : '' }}>
                        {{ __('Rejected') }}
                      </option>
                    </select>
                  </form>
                @else
                  @if ($orderInfo->order_status == 'completed')
                    <span class="badge badge-success">{{ __('Completed') }}</span>
                  @else
                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                  @endif
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Customer Information') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ $orderInfo->name }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">{{ @$orderInfo->email_address }}</div>
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
                    <div class="col-lg-4">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">
                      <a href="{{ asset('assets/file/zip-files/' . $information->value) }}"
                        download="{{ $information->originalName }}" class="btn btn-sm btn-primary">
                        {{ __('Download') }}
                      </a>
                    </div>
                  </div>
                @elseif ($information->type == 5)
                  <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                    <div class="col-lg-4">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">
                      <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                        data-target="#textModal-{{ $loop->iteration }}">
                        {{ __('Show') }}
                      </a>
                    </div>
                  </div>

                  @include('backend.client-service.order.show-text')
                @elseif ($information->type == 4)
                  <div class="row {{ $loop->iteration == $length ? 'mb-1' : 'mb-2' }}">
                    <div class="col-lg-4">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">
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
                    <div class="col-lg-4">
                      <strong>{{ $label . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">{{ $information->value }}</div>
                  </div>
                @endif
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
    @if ($orderInfo->seller_id != 0)
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">
            <div class="card-title d-inline-block">
              {{ __('Seller Information') }}
            </div>
          </div>
          @php
            if ($orderInfo->seller) {
                $sellerInfo = $orderInfo->seller
                    ->seller_info()
                    ->where('language_id', $defaultLang->id)
                    ->first();
            } else {
                $sellerInfo = null;
            }
          @endphp
          <div class="card-body">
            <div class="payment-information">
              <div class="row mb-2">
                <div class="col-lg-4">
                  <strong>{{ __('Username') . ' :' }}</strong>
                </div>

                <div class="col-lg-8">
                  <a
                    href="{{ route('admin.seller_management.seller_details', ['id' => $orderInfo->seller_id, 'language' => $defaultLang->code]) }}">{{ @$orderInfo->seller->username }}</a>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-lg-4">
                  <strong>{{ __('Name') . ' :' }}</strong>
                </div>

                <div class="col-lg-8">{{ @$sellerInfo->name }}</div>
              </div>

              <div class="row mb-2">
                <div class="col-lg-4">
                  <strong>{{ __('Email') . ' :' }}</strong>
                </div>

                <div class="col-lg-8">{{ @$orderInfo->seller->email }}</div>
              </div>

              <div class="row mb-2">
                <div class="col-lg-4">
                  <strong>{{ __('Phone Number') . ' :' }}</strong>
                </div>

                <div class="col-lg-8">{{ @$orderInfo->seller->phone }}</div>
              </div>

              @if (!is_null($sellerInfo))
                @if (!is_null($sellerInfo->address))
                  <div class="row mb-2">
                    <div class="col-lg-4">
                      <strong>{{ __('Address') . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">{{ @$sellerInfo->address }}</div>
                  </div>
                @endif

                @if (!is_null($sellerInfo->city))
                  <div class="row mb-2">
                    <div class="col-lg-4">
                      <strong>{{ __('City') . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">{{ @$sellerInfo->city }}</div>
                  </div>
                @endif

                @if (!is_null($sellerInfo->state))
                  <div class="row mb-2">
                    <div class="col-lg-4">
                      <strong>{{ __('State') . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">{{ @$sellerInfo->state }}</div>
                  </div>
                @endif
                @if (!is_null($sellerInfo->country))
                  <div class="row mb-2">
                    <div class="col-lg-4">
                      <strong>{{ __('Country') . ' :' }}</strong>
                    </div>

                    <div class="col-lg-8">{{ @$sellerInfo->country }}</div>
                  </div>
                @endif
              @endif

            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection
