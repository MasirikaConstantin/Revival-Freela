@extends('backend.layout')


@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Transactions') }}</h4>
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
        <a href="#">{{ __('Transactions ') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-9">
              <div class="card-title d-inline-block">{{ __('Transactions ') }}</div>
            </div>
            <div class="col-lg-3">
              <form action="" method="get">
                <input type="text" value="{{ request()->input('transcation_id') }}" name="transcation_id"
                  placeholder="Enter Transaction Id" class="form-control w-100">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($transcations) == 0)
                <h3 class="text-center mt-3">{{ __('NO TRANSACTION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Transcation Id') }}</th>
                        <th scope="col">{{ __('Seller') }}</th>
                        <th scope="col">{{ __('Transcation Type') }}</th>
                        <th scope="col">{{ __('Payment Method') }}</th>
                        <th scope="col">{{ __('Pre Balance') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Tax') }}</th>
                        <th scope="col">{{ __('After Balance') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($transcations as $transcation)
                        <tr>
                          <td>#{{ $transcation->transcation_id }}</td>
                          <td>
                            @php
                              $seller = $transcation->seller()->first();
                            @endphp
                            @if ($seller)
                              <a
                                href="{{ route('admin.seller_management.seller_details', ['id' => $seller->id, 'language' => $defaultLang->code]) }}">{{ $seller->username }}</a>
                            @else
                              <a href="javascript:void(0)">{{ __('Admin') }}</a>
                            @endif
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 1)
                              {{ __('Service Booking') }}
                            @elseif ($transcation->transcation_type == 2)
                              {{ __('Withdraw') }}
                            @elseif ($transcation->transcation_type == 3)
                              {{ __('Balance Added') }}
                            @elseif ($transcation->transcation_type == 4)
                              {{ __('Balance Subtracted') }}
                            @elseif ($transcation->transcation_type == 5)
                              {{ __('Package Purchase') }}
                            @endif
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 2)
                              @php
                                $method = $transcation->method()->first();
                              @endphp
                              @if ($method)
                                {{ $method->name }}
                              @else
                                {{ '-' }}
                              @endif
                            @else
                              {{ $transcation->payment_method != null ? $transcation->payment_method : '-' }}
                            @endif
                          </td>

                          <td>
                            @if ($transcation->pre_balance != null)
                              {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                              {{ $transcation->pre_balance }}
                              {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                            @else
                              {{ '-' }}
                            @endif
                          </td>

                          <td>
                            @if ($transcation->transcation_type == 2 || $transcation->transcation_type == 4)
                              <span class="text-danger">{{ '(-)' }}</span>
                            @else
                              <span class="text-success">{{ '(+)' }}</span>
                            @endif

                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->grand_total - $transcation->tax }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>
                          <td>
                            @if (!is_null($transcation->tax))
                              {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                              {{ $transcation->tax }}
                              {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                            @else
                              {{ '-' }}
                            @endif
                          </td>

                          <td>
                            @if ($transcation->after_balance != null)
                              {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                              {{ $transcation->after_balance }}
                              {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                            @else
                              {{ '-' }}
                            @endif

                          </td>
                          <td>
                            @if ($transcation->payment_status == 'completed')
                              <span class="badge badge-success">{{ __('Paid') }}</span>
                            @elseif($transcation->payment_status == 'declined')
                              <span class="badge badge-warning">{{ __('Declined') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                            @endif
                          </td>

                          <td>
                            @if ($transcation->transcation_type == 1)
                              @php
                                $booking = $transcation->order()->first();
                              @endphp
                              @if ($booking)
                                <a target="_blank" class="btn btn-secondary btn-sm mr-1"
                                  href="{{ asset('assets/file/invoices/service/' . $booking->invoice) }}">
                                  <i class="fas fa-eye"></i>
                                </a>
                              @endif
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer ">
          {{ $transcations->appends([
                  'transcation_id' => request()->input('transcation_id'),
              ])->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
