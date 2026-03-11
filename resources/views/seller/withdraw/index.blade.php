@extends('seller.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Withdrawals') }}</h4>
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
        <a href="#">{{ __('My Withdrawals') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">

            <div class="col-lg-6">
              <div class="card-title d-inline-block">
                {{ __('My Balance') }} :
                {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                {{ Auth::guard('seller')->user()->amount }}
                {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
              </div>
            </div>

            <div class="col-lg-6">

              <a href="{{ route('seller.withdraw.create', ['language' => $defaultLang->code]) }}"
                class="btn btn-secondary btn-sm float-lg-right float-left">
                <i class="fas fa-donate"></i> {{ __('Make a Withdrawal Request') }}
              </a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('seller.witdraw.bulk_delete_withdraw') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">

            @if (session()->has('course_status_warning'))
              <div class="alert alert-warning">
                <p class="text-dark mb-0">{{ session()->get('course_status_warning') }}</p>
              </div>
            @endif

            <div class="table-responsive">
              @if (count($collection) < 1)
                <h2 class="text-center">{{ __('NO WITHDRAWAL REQUEST FOUND') }}</h2>
              @else
                <table class="table table-striped mt-3" id="basic-datatables">
                  <thead>
                    <tr>
                      <th scope="col">
                        <input type="checkbox" class="bulk-check" data-val="all">
                      </th>
                      <th scope="col">{{ __('Withdraw Id') }}</th>
                      <th scope="col">{{ __('Method Name') }}</th>
                      <th scope="col">{{ __('Total Amount') }}</th>
                      <th scope="col">{{ __('Total Charge') }}</th>
                      <th scope="col">{{ __('Total Payable Amount') }}</th>
                      <th scope="col">{{ __('Status') }}</th>
                      <th scope="col">{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($collection as $item)
                      <tr>
                        <td>
                          <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                        </td>
                        <td>
                          {{ $item->withdraw_id }}
                        </td>
                        <td>
                          {{ optional($item->method)->name }}
                        </td>

                        <td>

                          {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                          {{ round($item->amount, 2) }}
                          {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                        </td>
                        <td>

                          {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                          {{ round($item->total_charge, 2) }}
                          {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                        </td>
                        <td>

                          {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                          {{ round($item->payable_amount, 2) }}
                          {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                        </td>
                        <td>
                          @if ($item->status == 0)
                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                          @elseif($item->status == 1)
                            <span class="badge badge-success">{{ __('Approved') }}</span>
                          @elseif($item->status == 2)
                            <span class="badge badge-danger">{{ __('Declined') }}</span>
                          @endif
                        </td>
                        <td>
                          <a href="javascript:void(0)" data-toggle="modal"
                            data-target="#withdrawModal{{ $item->id }}" class="btn btn-primary btn-sm mb-1"><span
                              class="btn-label">
                              <i class="fas fa-eye"></i>
                            </span></a>
                          <form class="deleteForm d-inline-block"
                            action="{{ route('seller.witdraw.delete_withdraw', ['id' => $item->id]) }}" method="post">

                            @csrf
                            <button type="submit" class="btn btn-danger mb-1 btn-sm deleteBtn">
                              <span class="btn-label">
                                <i class="fas fa-trash"></i>
                              </span>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer"></div>
    </div>
  </div>
  </div>
  @foreach ($collection as $item)
    <div class="modal fade" id="withdrawModal{{ $item->id }}" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLongTitle">{{ __('Withdraw Information') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="text-left">
              <p><strong>{{ __('Payable Amount :') }}
                  {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                  {{ round($item->payable_amount, 2) }}
                  {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}</strong>
              </p>
            </div>
            @php
              $d_feilds = json_decode($item->feilds, true);
            @endphp
            @foreach ($d_feilds as $key => $d_feild)
              <div class="text-left">
                <p><strong>{{ str_replace('_', ' ', $key) }} : {{ $d_feild }}</strong></p>
              </div>
            @endforeach
            <div class="text-left">
              <p><strong>{{ __('Additional Reference ') . ' : ' }}
                  {{ $item->additional_reference }}</strong>
              </p>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
              {{ __('Close') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection
