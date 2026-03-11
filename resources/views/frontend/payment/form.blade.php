@extends('frontend.layout')

@section('metaKeywords')
  {{ $seoInfo->meta_keyword_invoice_payment ?? '' }}
@endsection

@section('metaDescription')
  {{ $seoInfo->meta_description_invoice_payment ?? '' }}
@endsection
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@php
  $pageTitle = __('Invoice Payment');
  $serviceTitle = 'Invoice #' . $invoice->id;
@endphp


@section('pageHeading')
  {{ $pageTitle }}
@endsection
@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $pageTitle])

  <section class="service-checkout-area pt-120 pb-120">
    <div class="container">
      {{-- show error message for attachment (Offline) --}}
      @error('attachment')
        <div class="row mb-3">
          <div class="col">
            <div class="alert alert-danger alert-block">
              <strong>{{ $message }}</strong>
              <button type="button" class="close" data-dismiss="alert">×</button>
            </div>
          </div>
        </div>
      @enderror



      <div class="row mb-2">
        <div class="col-12">
          @php
            $items = json_decode($invoice->items);
            $position = $invoice->base_currency_symbol_position;
            $symbol = $invoice->base_currency_symbol;
          @endphp

          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">{{ __('ITEM') }}</th>
                <th scope="col">{{ __('QUANTITY') }}</th>
                <th scope="col">{{ __('UNIT PRICE') }}</th>
                <th scope="col">{{ __('AMOUNT') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
                @php
                  $quantity = intval($item->quantity);
                  $unitPrice = floatval($item->unit_price);
                  $eachItemTotal = $quantity * $unitPrice;
                @endphp

                <tr>
                  <td>{{ $item->title }}</td>
                  <td>{{ $quantity }}</td>
                  <td>
                    {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($unitPrice, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                  </td>
                  <td>
                    {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($eachItemTotal, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row align-items-center">

        <div class="col-lg-6 ">
          @php
            $position = $invoice->base_currency_symbol_position;
            $symbol = $invoice->base_currency_symbol;
          @endphp
          <table class="table table-bordered table-striped mb-0">
            <tr>
              <td>
                <p>{{ __('Total') }}</p>
              </td>
              <td>
                <p>
                  {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($invoice->total, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                </p>
              </td>
            </tr>
            <tr>
              <td>
                <p>{{ __('Discount') }}</p>
              </td>
              <td>
                <p>
                  @if (is_null($invoice->discount))
                    {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format(0, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                  @else
                    {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($invoice->discount, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                  @endif
                </p>
              </td>
            </tr>
            <tr>
              <td>
                <p>{{ __('Subtotal') }}</p>
              </td>
              <td>
                <p>
                  {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($invoice->subtotal, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                </p>
              </td>
            </tr>
            <tr>
              <td>
                <p>
                  @if (is_null($invoice->tax))
                    {{ __('Tax') }}
                  @else
                    {{ __('Tax') . ' (' . formatPrice($invoice->tax) . '%)' }}
                  @endif
                </p>
              </td>
              <td>
                <p>
                  @if (is_null($invoice->tax))
                    {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format(0, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                  @else
                    @php
                      $subtotal = floatval($invoice->subtotal);
                      $taxPercentage = floatval($invoice->tax);
                      $tax = $subtotal * ($taxPercentage / 100);
                    @endphp

                    {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($tax, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                  @endif
                </p>
              </td>
            </tr>
            <tr>
              <td>
                <p>{{ __('Grand Total') }}</p>
              </td>
              <td>
                <p>
                  {{ $position == 'left' ? $symbol : '' }}{{ formatPrice(number_format($invoice->grand_total, 2)) }}{{ $position == 'right' ? $symbol : '' }}
                </p>
              </td>
            </tr>
          </table>

        </div>
        <div class=" col-lg-6  ">
          <form action="{{ route('pay') }}" method="POST" enctype="multipart/form-data" id="payment-form">
            @csrf
            <div class="row d-flex align-items-end">
              <div class="col-lg-8  ">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Pay via') . '*' }}</label>
                  <select class="form-control" name="gateway">
                    <option selected disabled>{{ __('Select a Payment Gateway') }}</option>

                    @if (count($onlineGateways) > 0)
                      @foreach ($onlineGateways as $onlineGateway)
                        <option value="{{ $onlineGateway->keyword }}" data-gateway_type="online">
                          {{ __($onlineGateway->name) }}
                        </option>
                      @endforeach
                    @endif

                    @if (count($offlineGateways) > 0)
                      @foreach ($offlineGateways as $offlineGateway)
                        <option value="{{ $offlineGateway->id }}" data-gateway_type="offline"
                          data-has_attachment="{{ $offlineGateway->has_attachment }}">
                          {{ __($offlineGateway->name) }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                </div>

              </div>
              <div class="col-lg-4 ">
                <button class="main-btn w-100" id="payment-form-btn">
                  {{ __('Pay') }}
                </button>
              </div>

            </div>

            <div style="@if (
                $errors->has('card_number') ||
                    $errors->has('cvc_number') ||
                    $errors->has('expiry_month') ||
                    $errors->has('expiry_year')) display: block; @else display: none; @endif" id="stripe-form">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Card Number') . '*' }}</label>
                    <input type="text" class="form-control" name="card_number" autocomplete="off"
                      oninput="checkCard(this.value)" placeholder="Enter Card Number">
                    <p class="mt-1 text-danger" id="card-error"></p>
                    @error('card_number')
                      <p class="mt-1 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('CVC Number') . '*' }}</label>
                    <input type="text" class="form-control" name="cvc_number" autocomplete="off"
                      oninput="checkCVC(this.value)" placeholder="Enter CVC Number">
                    <p class="mt-1 text-danger" id="cvc-error"></p>
                    @error('cvc_number')
                      <p class="mt-1 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Expiry Month') . '*' }}</label>
                    <input type="text" class="form-control" name="expiry_month" placeholder="Enter Expiry Month">
                    @error('expiry_month')
                      <p class="mt-1 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Expiry Year') . '*' }}</label>
                    <input type="text" class="form-control" name="expiry_year" placeholder="Enter Expiry Year">
                    @error('expiry_year')
                      <p class="mt-1 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="mdf_display_none" id="authorizenet-form">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Card Number') . '*' }}</label>
                    <input type="text" class="form-control" id="cardNumber" autocomplete="off"
                      placeholder="Enter Card Number">
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Card Code') . '*' }}</label>
                    <input type="text" class="form-control" id="cardCode" autocomplete="off"
                      placeholder="Enter Card Code">
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Expiry Month') . '*' }}</label>
                    <input type="text" class="form-control" id="expMonth" placeholder="Enter Expiry Month">
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-group">
                    <label>{{ __('Expiry Year') . '*' }}</label>
                    <input type="text" class="form-control" id="expYear" placeholder="Enter Expiry Year">
                  </div>
                </div>

                <input type="hidden" name="opaqueDataValue" id="opaqueDataValue">
                <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor">

                <ul id="anetErrors"></ul>
              </div>
            </div>

            @if (count($offlineGateways) > 0)
              <div class="row">
                <div class="col-12">
                  @foreach ($offlineGateways as $offlineGateway)
                    @if ($offlineGateway->has_attachment == 1)
                      <div class="form-group mb-3 mdf_display_none"
                        id="{{ 'gateway-attachment-' . $offlineGateway->id }}">
                        <label>{{ __('Attachment') . '*' }}</label>
                        <input type="file" name="attachment">
                        <span class="text-warning">
                          {{ __('Note: File type only jpg, jpeg, png and svg') }}.
                        </span>
                      </div>
                    @endif

                    @if (!is_null($offlineGateway->short_description))
                      <div class="form-group mb-3 mdf_display_none"
                        id="{{ 'gateway-description-' . $offlineGateway->id }}">
                        <label><strong>{{ __('Description') }}</strong></label>
                        <br>
                        <p>{{ $offlineGateway->short_description }}</p>
                      </div>
                    @endif

                    @if (!is_null($offlineGateway->instructions))
                      <div class="form-group mb-3 mdf_display_none"
                        id="{{ 'gateway-instructions-' . $offlineGateway->id }}">
                        <label><strong>{{ __('Instructions') }}</strong></label>
                        <br>
                        <p class="summernote-content m-0">
                          {!! replaceBaseUrl($offlineGateway->instructions, 'summernote') !!}
                        </p>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            @endif


          </form>
        </div>
      </div>


      <div class="row mt-4">

      </div>

    </div>
  </section>
@endsection

@section('script')
  <script type="text/javascript" src="https://js.stripe.com/v3/"></script>

  <script type="text/javascript">
    const clientKey = '{{ $anetClientKey }}';
    const loginId = '{{ $anetLoginId }}';
  </script>

  <script type="text/javascript" src="{{ $anetSource }}" charset="utf-8"></script>

  <script type="text/javascript" src="{{ asset('assets/js/service.js') }}"></script>
@endsection
