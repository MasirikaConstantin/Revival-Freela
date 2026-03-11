@extends('frontend.layout')

@php
  $pageTitle = $quoteBtnStatus == 0 ? __('Service Checkout') : __('Request A Quote');
@endphp

@section('pageHeading')
  {{ $pageTitle }}
@endsection
@section('metaKeywords')
  {{ $seoInfo->meta_keyword_service_order ?? '' }}
@endsection

@section('metaDescription')
  {{ $seoInfo->meta_description_service_order ?? '' }}
@endsection
@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $breadcrumb,
      'serviceTitle' => $serviceTitle,
      'title' => $pageTitle,
  ])

  <!--====== Start Service Checkout Area ======-->
  <section class="service-checkout-area pt-100 pb-60">
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


      <form action="{{ route('service.place_order', ['slug' => request()->route('slug')]) }}" method="POST"
        enctype="multipart/form-data" id="payment-form">
        @csrf
        <div class="row">
          <div class=" @if ($quoteBtnStatus == 0) col-lg-8 @else col-12 @endif">
            <input type="hidden" name="quote_btn_status" value="{{ $quoteBtnStatus }}">
            <div class="row mb-40">
              <div class="col-md-6">
                @php
                  if (!empty($authUser->first_name) && !empty($authUser->last_name)) {
                      $authUserName = $authUser->first_name . ' ' . $authUser->last_name;
                  } else {
                      $authUserName = '';
                  }
                @endphp

                <div class="form-group mb-30">
                  <label>{{ __('Name') . '*' }}</label>
                  <input type="text" class="form-control" name="name" placeholder="{{ __('Enter Your Full Name') }}"
                    value="{{ old('name') ? old('name') : $authUserName }}">
                  @error('name')
                    <p class="mt-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-30">
                  <label>{{ __('Email Address') . '*' }}</label>
                  <input type="email" class="form-control" name="email_address"
                    placeholder="{{ __('Enter Your Email Address') }}"
                    value="{{ old('email_address') ? old('email_address') : $authUser->email_address }}">
                  @error('email_address')
                    <p class="mt-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>


              @foreach ($inputFields as $inputField)
                @if ($inputField->type == 1)
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <input type="text" class="form-control" name="{{ $inputField->name }}"
                        placeholder="{{ __($inputField->placeholder) }}" value="{{ old($inputField->name) }}">
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @elseif ($inputField->type == 2)
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <input type="number" class="form-control" name="{{ $inputField->name }}"
                        placeholder="{{ __($inputField->placeholder) }}" value="{{ old($inputField->name) }}">
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @elseif ($inputField->type == 3)
                  @php $options = json_decode($inputField->options); @endphp

                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <select class="form-control" name="{{ $inputField->name }}">
                        <option selected disabled>{{ __($inputField->placeholder) }}</option>

                        @foreach ($options as $option)
                          <option value="{{ $option }}" {{ old($inputField->name) == $option ? 'selected' : '' }}>
                            {{ __($option) }}
                          </option>
                        @endforeach
                      </select>
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @elseif ($inputField->type == 4)
                  @php $options = json_decode($inputField->options); @endphp

                  <div class="col-12">
                    <div class="form-group mb-30">
                      <label class="mb-1">
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <br>
                      @foreach ($options as $option)
                        <div class="custom-control custom-checkbox custom-control-inline">
                          <input type="checkbox" id="{{ 'option-' . $loop->iteration }}"
                            name="{{ $inputField->name . '[]' }}" class="custom-control-input"
                            value="{{ $option }}"
                            {{ is_array(old($inputField->name)) && in_array($option, old($inputField->name)) ? 'checked' : '' }}>
                          <label for="{{ 'option-' . $loop->iteration }}"
                            class="custom-control-label">{{ $option }}</label>
                        </div>
                      @endforeach
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @elseif ($inputField->type == 5)
                  <div class="col-12">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <textarea class="form-control" name="{{ $inputField->name }}" placeholder="{{ __($inputField->placeholder) }}"
                        rows="2">{{ old($inputField->name) }}</textarea>
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @elseif ($inputField->type == 6)
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <input type="text" class="form-control datepicker ltr" name="{{ $inputField->name }}"
                        placeholder="{{ __($inputField->placeholder) }}" readonly autocomplete="off"
                        value="{{ old($inputField->name) }}">
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @elseif ($inputField->type == 7)
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                      </label>
                      <input type="text" class="form-control timepicker ltr" name="{{ $inputField->name }}"
                        placeholder="{{ __($inputField->placeholder) }}" readonly autocomplete="off"
                        value="{{ old($inputField->name) }}">
                      @error($inputField->name)
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @else
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <label>
                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                        <span
                          class="text-info {{ $currentLanguageInfo->direction == 0 ? 'ms-2' : 'me-2' }}">({{ __('Only .zip file is allowed') . '.' }})</span>
                      </label>
                      <input type="file" name="{{ 'form_builder_' . $inputField->name }}">
                      @error("form_builder_$inputField->name")
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                @endif
              @endforeach
            </div>

            @if ($quoteBtnStatus != 0)
              <div class="row mb-40">
                <div class="col-12 text-center">
                  <button class="btn btn-lg btn-primary radius-sm" id="payment-form-btn">
                    {{ $quoteBtnStatus == 0 ? __('Place Order') : __('Submit') }}
                  </button>
                </div>
              </div>
            @endif
          </div>
          @if ($quoteBtnStatus == 0)
            <div class="col-lg-4">
              @if (isset($package))
                @php
                  $position = $currencyInfo->base_currency_symbol_position;
                  $symbol = $currencyInfo->base_currency_symbol;
                @endphp

                <div class="gigs-sidebar mb-40">
                  <div class="packages-widgets">
                    <div class="packages-content-wrap">
                      <div class="packages-content">
                        <div class="p-2">
                          <h3 class="text-center">{{ __('Selected Package') }}</h3>
                        </div>
                        <hr class="p-0 m-0">
                        <ul class="mt-30 list-unstyled">
                          <li class="d-flex justify-content-between">

                            <h3>{{ $package->name }}</h3>
                            <h3>
                              {{ $position == 'left' ? $symbol : '' }}{{ formatPrice($package->current_price) }}{{ $position == 'right' ? $symbol : '' }}
                              @if ($package->previous_price)
                                <del class="ms-2 mdf_34335">
                                  {{ $position == 'left' ? $symbol : '' }}{{ formatPrice($package->previous_price) }}{{ $position == 'right' ? $symbol : '' }}
                                </del>
                              @endif
                            </h3>

                          </li>
                          <li>

                          </li>
                        </ul>
                        <div class="mt-2 mb-2">
                          @if (!empty($package->delivery_time) || !empty($package->number_of_revision))
                            <span class="additional-info">
                              @if (!empty($package->delivery_time))
                                <span class="delivery">
                                  <i class="far fa-clock "></i>
                                  {{ $package->delivery_time }}
                                  {{ $package->delivery_time > 1 ? __('Days Delivery') : __('Day Delivery') }}</span>
                              @endif

                              @if (!empty($package->number_of_revision))
                                &nbsp;&nbsp;
                                <span class="revisions"><i class="far fa-sync-alt"></i>
                                  {{ $package->number_of_revision }}
                                  {{ $package->number_of_revision > 1 ? __('Revisions') : __('Revision') }}</span>
                              @endif
                            </span>
                          @endif
                        </div>
                        @php $features = explode(PHP_EOL, $package->features); @endphp
                        <ul class="features list-unstyled">
                          @foreach ($features as $feature)
                            <li class="feature check-icon">
                              {{ $feature }}
                            </li>
                          @endforeach
                        </ul>

                        @php
                          $chekedAddons = session()->get('addons');
                          $adonPrice = 0;
                        @endphp

                        @if (count($addons) > 0 && $chekedAddons)
                          <h3><span class="title">{{ __('Addons') }}</span></h3>
                          <ul class="features mt-3 list-unstyled">


                            @foreach ($addons as $addon)
                              @if (in_array($addon->id, $chekedAddons))
                                <li class="feature check-icon">

                                  {{ __($addon->name) }}
                                  <span>(<span
                                      class="text-danger">+</span>{{ $position == 'left' ? $symbol : '' }}{{ formatPrice($addon->price) }}{{ $position == 'right' ? $symbol : '' }})</span>
                                </li>
                                @php
                                  $adonPrice = $adonPrice + $addon->price;
                                @endphp
                              @endif
                            @endforeach
                          </ul>
                        @endif

                        @php
                          $totalPrice = $package->current_price + $adonPrice;
                          $tax = ($basicInfo->tax / 100) * $totalPrice;
                        @endphp

                        <hr class="pb-1 mb-1">
                        <p class="mb-0"><strong>{{ __('Subtotal') . ':' }}</strong>
                          {{ $position == 'left' ? $symbol : '' }}{{ formatPrice($totalPrice) }}{{ $position == 'right' ? $symbol : '' }}
                        </p>
                        <p class="mb-0"><strong>{{ __('Tax') . ':' }}</strong>
                          {{ $position == 'left' ? $symbol : '' }}{{ formatPrice($tax) }}{{ $position == 'right' ? $symbol : '' }}
                        </p>
                        <p><strong>{{ __('Total') . ':' }}</strong>
                          {{ $position == 'left' ? $symbol : '' }}{{ formatPrice($tax + $totalPrice) }}{{ $position == 'right' ? $symbol : '' }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
              <div class="order-payment mb-40">
                <h4 class="mb-3">{{ __('Payment Method') }}</h4>
                @error('gateway')
                  <p class="mt-2 text-danger">{{ $message }}</p>
                @enderror

                @if ($quoteBtnStatus == 0)
                  <div class="form-group mb-30">

                    <select class="niceselect form-control wide" name="gateway">
                      <option selected disabled>{{ __('Select a Payment Gateway') }}</option>

                      @if (count($onlineGateways) > 0)
                        @foreach ($onlineGateways as $onlineGateway)
                          <option value="{{ $onlineGateway->keyword }}"
                            {{ old('gateway') == $onlineGateway->keyword ? 'selected' : '' }}
                            data-gateway_type="online">
                            {{ __($onlineGateway->name) }}
                          </option>
                        @endforeach
                      @endif

                      @if (count($offlineGateways) > 0)
                        @foreach ($offlineGateways as $offlineGateway)
                          <option value="{{ $offlineGateway->id }}"
                            {{ old('gateway') == $offlineGateway->id ? 'selected' : '' }} data-gateway_type="offline"
                            data-has_attachment="{{ $offlineGateway->has_attachment }}">
                            {{ __($offlineGateway->name) }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                  </div>

                @endif

                <div class="iyzico-element {{ old('gateway') == 'iyzico' ? '' : 'd-none' }}">
                  <div class="form-group mb-30">
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="form-control"
                      placeholder="Phone Number">
                    @error('phone_number')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-30">
                    <input type="text" name="identity_number" value="{{ old('identity_number') }}"
                      class="form-control" placeholder="Identity Number">
                    @error('identity_number')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-30">
                    <input type="text" name="city" value="{{ old('city') }}" class="form-control"
                      placeholder="City">
                    @error('city')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-30">
                    <input type="text" name="country" value="{{ old('country') }}" class="form-control"
                      placeholder="Country">
                    @error('country')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-30">
                    <input type="text" name="address" value="{{ old('address') }}" class="form-control"
                      placeholder="Address">
                    @error('address')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-30">
                    <input type="text" name="zip_code" value="{{ old('zip_code') }}" class="form-control"
                      placeholder="Zip Code">
                    @error('zip_code')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <!-----------stripe------------->
                <div id="stripe-element" class="mb-2 mt-2">
                  <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors -->
                <div id="stripe-errors" role="alert" class="mb-2 text-danger"></div>
                <!-----------stripe------------->

                <div class="mt-3 mdf_display_none" id="authorizenet-form">
                  <div class="row">
                    <div class="col-md-12 mb-4">
                      <div class="form-group mb-30">
                        <label>{{ __('Card Number') . '*' }}</label>
                        <input type="text" class="form-control" id="cardNumber" autocomplete="off"
                          placeholder="Enter Card Number">
                      </div>
                    </div>

                    <div class="col-md-12 mb-4">
                      <div class="form-group mb-30">
                        <label>{{ __('Card Code') . '*' }}</label>
                        <input type="text" class="form-control" id="cardCode" autocomplete="off"
                          placeholder="Enter Card Code">
                      </div>
                    </div>

                    <div class="col-md-12 mb-4">
                      <div class="form-group mb-30">
                        <label>{{ __('Expiry Month') . '*' }}</label>
                        <input type="text" class="form-control" id="expMonth" placeholder="Enter Expiry Month">
                      </div>
                    </div>

                    <div class="col-md-12 mb-4">
                      <div class="form-group mb-30">
                        <label>{{ __('Expiry Year') . '*' }}</label>
                        <input type="text" class="form-control" id="expYear" placeholder="Enter Expiry Year">
                      </div>
                    </div>

                    <input type="hidden" name="opaqueDataValue" id="opaqueDataValue">
                    <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor">

                    <div id="anetErrors"></div>
                  </div>
                </div>

                @if ($quoteBtnStatus == 0 && count($offlineGateways) > 0)
                  <div class="row ">
                    <div class="col-12 mt-3">
                      @foreach ($offlineGateways as $offlineGateway)
                        @if ($offlineGateway->has_attachment == 1)
                          <div class="form-group mb-30 mb-3 mdf_display_none"
                            id="{{ 'gateway-attachment-' . $offlineGateway->id }}">
                            <label><strong>{{ __('Attachment') . '*' }}</strong></label>
                            <br>
                            <input type="file" name="attachment" class="form-control-file">
                            <span class="text-warning">{{ __('Note: File type only jpg, jpeg, png and svg') }}.</span>
                          </div>
                        @endif

                        @if (!is_null($offlineGateway->short_description))
                          <div class="form-group mb-30 mb-3 mdf_display_none"
                            id="{{ 'gateway-description-' . $offlineGateway->id }}">
                            <strong>{{ __('Description') }}</strong>
                            <br>
                            <p>{{ $offlineGateway->short_description }}</p>
                          </div>
                        @endif

                        @if (!is_null($offlineGateway->instructions))
                          <div class="form-group mb-30 mb-3 mdf_display_none"
                            id="{{ 'gateway-instructions-' . $offlineGateway->id }}">
                            <strong class="">{{ __('Instructions') }}</strong>
                            <br>
                            {!! replaceBaseUrl($offlineGateway->instructions, 'summernote') !!}
                          </div>
                        @endif
                      @endforeach
                    </div>
                  </div>
                @endif

                @if ($quoteBtnStatus == 0)
                  <button class="btn btn-lg btn-primary radius-sm w-100" id="payment-form-btn">
                    {{ $quoteBtnStatus == 0 ? __('Place Order') : __('Submit') }}
                  </button>
                @endif

              </div>
            </div>
          @endif
        </div>
      </form>
    </div>
  </section>
  <!--====== End Service Checkout Area ======-->
@endsection


@section('script')
  <script type="text/javascript">
    const clientKey = '{{ $quoteBtnStatus == 0 ? $anetClientKey : '' }}';
    const loginId = '{{ $quoteBtnStatus == 0 ? $anetLoginId : '' }}';
    let stripe_key = "{{ $stripeKey }}";
  </script>
  <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript" src="{{ $quoteBtnStatus == 0 ? $anetSource : '' }}" charset="utf-8"></script>

  <script type="text/javascript" src="{{ asset('assets/js/service.js') }}"></script>

  @if (old('gateway') == 'stripe')
    <script>
      $(document).ready(function() {
        $('#stripe-element').removeClass('d-none');
      });
    </script>
  @endif
@endsection
