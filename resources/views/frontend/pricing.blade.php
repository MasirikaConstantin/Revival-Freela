@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->pricing_page_title ?? __('Pricing') }}
  @else
    {{ __('Pricing') }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->pricing_page_meta_keywords ?? '' }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->pricing_page_meta_description ?? '' }}
  @endif
@endsection

@php
  $title = $pageHeading->pricing_page_title ?? __('Pricing');
@endphp

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title ?? ''])

  <!-- Pricing Start -->
  <div class="pricing-area pt-100 pb-70">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="tabs-navigation tabs-navigation-2 mb-40 text-center">
            <ul class="nav nav-tabs radius-sm" data-hover="fancyHover">
              <li class="nav-item active">
                <button class="nav-link hover-effect btn-md radius-sm active" data-bs-toggle="tab" data-bs-target="#monthly"
                  type="button">{{ __('Monthly') }}</button>
              </li>
              <li class="nav-item">
                <button class="nav-link hover-effect btn-md radius-sm" data-bs-toggle="tab" data-bs-target="#yearly"
                  type="button">{{ __('Yearly') }}</button>
              </li>
              <li class="nav-item">
                <button class="nav-link hover-effect btn-md radius-sm" data-bs-toggle="tab" data-bs-target="#lifeTime"
                  type="button">{{ __('Lifetime') }}</button>
              </li>
            </ul>
          </div>
          <div class="tab-content">
            <div class="tab-pane fade active show" id="monthly">
              <div class="row justify-content-center">
                @foreach ($monthly_packages as $package)
                  <div class="col-md-6 col-lg-4">
                    <div class="card mb-30 {{ $package->recommended == 1 ? 'active' : '' }}" data-aos-delay="100">
                      <div class="d-flex align-items-center">
                        <div class="icon"><i class="far fa-layer-group"></i></div>
                        <div class="label">
                          <h4>{{ __($package->title) }}</h4>
                        </div>
                      </div>
                      <div class="d-flex align-items-center">
                        <span
                          class="price mt-3">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
                        <span class="period">/ {{ __('Monthly') }}</span>
                      </div>
                      <h5>{{ __("What's Included") }}</h5>
                      <ul class="pricing-list list-unstyled p-0">

                        <li><i class="fal fa-check"></i>{{ __('Services') . ' :' }}
                          {{ $package->number_of_service_add }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Featured Services') . ' : ' }}
                          {{ $package->number_of_service_featured }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Custom Form') . ' : ' }}
                          {{ $package->number_of_form_add }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Service Orders') . ' : ' }}
                          {{ $package->number_of_service_order }}</li>

                        <li class="{{ $package->live_chat_status == 0 ? 'disabled' : '' }}"><i
                            class="fal fa-{{ $package->live_chat_status == 0 ? 'times' : 'check' }}"></i>{{ __('Live Chat') }}
                        </li>
                        <li class="{{ $package->qr_builder_status == 0 ? 'disabled' : '' }}"><i
                            class="fal fa-{{ $package->qr_builder_status == 0 ? 'times' : 'check' }}"></i>{{ __('QR Builder') }}
                        </li>
                        @if (!is_null($package->custom_features))
                          @php
                            $features = explode("\n", $package->custom_features);
                          @endphp
                          @if (count($features) > 0)
                            @foreach ($features as $key => $value)
                              <li><i class="fal fa-check"></i> {{ __($value) }} </li>
                            @endforeach
                          @endif
                        @endif
                      </ul>
                      <div class="btn-groups mt-3">
                        @guest('seller')
                          <a href="{{ route('seller.login', ['redirect' => 'buy_plan']) }}"
                            class="btn btn-lg btn-outline radius-sm" title="{{ __('Purchase') }}"
                            target="_self">{{ __('Purchase') }}</a>
                        @endguest
                        @auth('seller')
                          <a href="{{ route('seller.plan.extend.index') }}" class="btn btn-lg btn-outline radius-sm"
                            title="{{ __('Purchase') }}" target="_self">{{ __('Purchase') }}</a>
                        @endguest
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="tab-pane fade" id="yearly">
              <div class="row justify-content-center">
                @foreach ($yearly_packages as $package)
                  <div class="col-md-6 col-lg-4">
                    <div class="card mb-30 {{ $package->recommended == 1 ? 'active' : '' }}" data-aos-delay="100">
                      <div class="d-flex align-items-center">
                        <div class="icon"><i class="far fa-layer-group"></i></div>
                        <div class="label">
                          <h4>{{ __($package->title) }}</h4>
                        </div>
                      </div>
                      <div class="d-flex align-items-center">
                        <span
                          class="price mt-3">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
                        <span class="period">/ {{ __('Monthly') }}</span>
                      </div>
                      <h5>{{ __("What's Included") }}</h5>
                      <ul class="pricing-list list-unstyled p-0">

                        <li><i class="fal fa-check"></i>{{ __('Services') . ' :' }}
                          {{ $package->number_of_service_add }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Featured Services') . ' : ' }}
                          {{ $package->number_of_service_featured }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Custom Form') . ' : ' }}
                          {{ $package->number_of_form_add }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Service Orders') . ' : ' }}
                          {{ $package->number_of_service_order }}</li>

                        <li class="{{ $package->live_chat_status == 0 ? 'disabled' : '' }}"><i
                            class="fal fa-{{ $package->live_chat_status == 0 ? 'times' : 'check' }}"></i>{{ __('Live Chat') }}
                        </li>
                        <li class="{{ $package->qr_builder_status == 0 ? 'disabled' : '' }}"><i
                            class="fal fa-{{ $package->qr_builder_status == 0 ? 'times' : 'check' }}"></i>{{ __('QR Builder') }}
                        </li>
                        @if (!is_null($package->custom_features))
                          @php
                            $features = explode("\n", $package->custom_features);
                          @endphp
                          @if (count($features) > 0)
                            @foreach ($features as $key => $value)
                              <li><i class="fal fa-check"></i> {{ __($value) }} </li>
                            @endforeach
                          @endif
                        @endif
                      </ul>
                      <div class="btn-groups mt-3">
                        @guest('seller')
                          <a href="{{ route('seller.login', ['redirect' => 'buy_plan']) }}"
                            class="btn btn-lg btn-outline radius-sm" title="{{ __('Purchase') }}"
                            target="_self">{{ __('Purchase') }}</a>
                        @endguest
                        @auth('seller')
                          <a href="{{ route('seller.plan.extend.index') }}" class="btn btn-lg btn-outline radius-sm"
                            title="{{ __('Purchase') }}" target="_self">{{ __('Purchase') }}</a>
                        @endguest
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="tab-pane fade" id="lifeTime">
              <div class="row justify-content-center">
                @foreach ($lifetime_packages as $package)
                  <div class="col-md-6 col-lg-4">
                    <div class="card mb-30 {{ $package->recommended == 1 ? 'active' : '' }}" data-aos-delay="100">
                      <div class="d-flex align-items-center">
                        <div class="icon"><i class="far fa-layer-group"></i></div>
                        <div class="label">
                          <h4>{{ __($package->title) }}</h4>
                        </div>
                      </div>
                      <div class="d-flex align-items-center">
                        <span
                          class="price mt-3">{{ $package->price == 0 ? __('Free') : format_price($package->price) }}</span>
                        <span class="period">/ {{ __('Monthly') }}</span>
                      </div>
                      <h5>{{ __("What's Included") }}</h5>
                      <ul class="pricing-list list-unstyled p-0">

                        <li><i class="fal fa-check"></i>{{ __('Services') . ' :' }}
                          {{ $package->number_of_service_add }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Featured Services') . ' : ' }}
                          {{ $package->number_of_service_featured }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Custom Form') . ' : ' }}
                          {{ $package->number_of_form_add }}</li>

                        <li><i class="fal fa-check"></i>{{ __('Service Orders') . ' : ' }}
                          {{ $package->number_of_service_order }}</li>

                        <li class="{{ $package->live_chat_status == 0 ? 'disabled' : '' }}"><i
                            class="fal fa-{{ $package->live_chat_status == 0 ? 'times' : 'check' }}"></i>{{ __('Live Chat') }}
                        </li>
                        <li class="{{ $package->qr_builder_status == 0 ? 'disabled' : '' }}"><i
                            class="fal fa-{{ $package->qr_builder_status == 0 ? 'times' : 'check' }}"></i>{{ __('QR Builder') }}
                        </li>
                        @if (!is_null($package->custom_features))
                          @php
                            $features = explode("\n", $package->custom_features);
                          @endphp
                          @if (count($features) > 0)
                            @foreach ($features as $key => $value)
                              <li><i class="fal fa-check"></i> {{ __($value) }} </li>
                            @endforeach
                          @endif
                        @endif
                      </ul>
                      <div class="btn-groups mt-3">
                        @guest('seller')
                          <a href="{{ route('seller.login', ['redirect' => 'buy_plan']) }}"
                            class="btn btn-lg btn-outline radius-sm" title="{{ __('Purchase') }}"
                            target="_self">{{ __('Purchase') }}</a>
                        @endguest
                        @auth('seller')
                          <a href="{{ route('seller.plan.extend.index') }}" class="btn btn-lg btn-outline radius-sm"
                            title="{{ __('Purchase') }}" target="_self">{{ __('Purchase') }}</a>
                        @endguest
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="mt-50 text-center advertise">
            {!! showAd(3) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Pricing End -->
@endsection
