@extends('frontend.layout')

@section('pageHeading')
  @if ($payVia == 'online')
    {{ __('Payment Success') }}
  @else
    {{ __('Success') }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => __('Success')])

  <!-- Start Purchase Success Section -->
  <div class="purchase-message ptb-100">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="purchase-success">
            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
            <h2>{{ __('Success') . '!' }}</h2>
            @if ($payVia == 'online')
              <p>{{ __('Your order has been placed successfully') . '.' }}</p>
              <p>{{ __('We have sent you a mail with an invoice') . '.' }}</p>
            @elseif ($payVia == 'offline')
              <p>{{ __('Your transaction request was received and sent for review') . '.' }}</p>
              <p>{{ __('We answer every request as quickly as we can, usually within 24–48 hours') . '.' }}</p>
            @else
              <p>{{ __('Thank you for writing to us') . '.' }}</p>
              <p>{{ __('We have received your order and, will get back to you as soon as possible') . '.' }}</p>
            @endif

            <p class="mt-4">{{ __('Thank you') . '.' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Purchase Success Section -->
@endsection
