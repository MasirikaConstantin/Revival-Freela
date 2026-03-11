<!DOCTYPE html>
<html>

<head lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>
  {{-- required meta tags --}}
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- title --}}
  <title>{{ __('Service Invoice') . ' | ' . config('app.name') }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- css files --}}
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <style>
    body {
      font-family: DejaVu Sans, serif;
    }
  </style>
</head>

<body>
  <div class="service-order-invoice my-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="logo text-center mdf_3432">
            <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="website logo">
          </div>

          <div class="mb-3">
            <h2 class="text-center">
              {{ __('SERVICE ORDER INVOICE') }}
            </h2>
          </div>

          @php
            $position = $orderInfo->currency_text_position;
            $currency = $orderInfo->currency_text;
          @endphp

          <div class="row">
            <div class="col">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th>{{ __('Order No') }}</th>
                    <td>{{ '#' . $orderInfo->order_number }}</td>
                  </tr>

                  <tr>
                    <th>{{ __('Order Date') }}</th>
                    <td>{{ date_format($orderInfo->created_at, 'M d, Y') }}</td>
                  </tr>

                  <tr>
                    <th>{{ __('Customer Name') }}</th>
                    <td>{{ $orderInfo->name }}</td>
                  </tr>
                  <tr>
                    <th>{{ __('Seller') }}</th>
                    <td>
                      @if ($orderInfo->seller_id != 0)
                        @if ($orderInfo->seller)
                          <a target="_blank"
                            href="{{ route('frontend.seller.details', @$orderInfo->seller->username) }}">{{ @$orderInfo->seller->username }}</a>
                        @endif
                      @else
                        <span class="badge badge-success">{{ __('Admin') }}</span>
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <th>{{ __('Customer Email') }}</th>
                    <td>{{ $orderInfo->email_address }}</td>
                  </tr>

                  <tr>
                    <th>{{ __('Service') }}</th>
                    <td>{{ $serviceTitle }}</td>
                  </tr>

                  @if (!is_null($packageTitle))
                    <tr>
                      <th>{{ __('Package') }}</th>
                      <td>{{ $packageTitle }}
                        ({{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice(number_format($orderInfo->package_price, 2)) }}{{ $position == 'right' ? ' ' . $currency : '' }})
                      </td>
                    </tr>
                  @endif

                  @if (!is_null($orderInfo->addons))
                    <tr>
                      <th>{{ __('Addons') }}</th>
                      <td>
                        @php
                          $addons = json_decode($orderInfo->addons);
                          $adonTotal = 0;
                        @endphp

                        @foreach ($addons as $addon)
                          @php
                            $addonId = $addon->id;

                            $serviceAddon = \App\Models\ClientService\ServiceAddon::query()->find($addonId);
                          @endphp

                          {{ $loop->iteration . '.' }} {{ $serviceAddon->name }}
                          ({{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice($addon->price) }}{{ $position == 'right' ? ' ' . $currency : '' }})
                          @php
                            $adonTotal = $adonTotal + $addon->price;
                          @endphp
                          <br>
                        @endforeach
                        <hr>
                        <p>{{ __('Total') . ':' }}
                          {{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice($adonTotal) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                        </p>
                      </td>
                    </tr>
                  @endif
                  @if (!is_null($orderInfo->tax))
                    <tr>
                      <th>{{ __('Tax') }} ({{ $orderInfo->tax_percentage . '%' }}) <i
                          class="fas fa-plus text-danger text-small"></i></th>
                      <td>
                        @if (is_null($orderInfo->tax))
                          {{ __('Price Requested') }}
                        @else
                          {{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice(number_format($orderInfo->tax, 2)) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                        @endif
                      </td>
                    </tr>
                  @endif

                  <tr>
                    <th>{{ __('Total') }}</th>
                    <td>
                      @if (is_null($orderInfo->grand_total))
                        {{ __('Price Requested') }}
                      @else
                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ formatPrice(number_format($orderInfo->grand_total, 2)) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <th>{{ __('Payment Method') }}</th>
                    <td>
                      @if (is_null($orderInfo->payment_method))
                        {{ __('None') }}
                      @else
                        {{ $orderInfo->payment_method }}
                      @endif
                    </td>
                  </tr>

                  <tr>
                    <th>{{ __('Payment Status') }}</th>
                    <td>{{ ucfirst($orderInfo->payment_status) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
