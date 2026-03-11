@extends('seller.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Payment Success') }}</h4>
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
        <a href="#">{{ __('Payment Success') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title d-inline-block">
                {{ __('Payment Success') }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-6 mx-auto">
            <div class="card p-4 text-center">
              <div class="mb-3">
                <i class="fas fa-check color-white p-3 rounded-circle bg-success text-white"></i>
              </div>
              <h1>{{ __('Success') }}</h1>
              @if (request()->filled('type') && request()->input('type') == 'free')
                <p>{{ __('Your Package purchase successfully completed.') }}</p>
              @else
                <p>{{ __('Your Payment successfully completed.') }}</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer"></div>
    </div>
  </div>
  </div>
@endsection
