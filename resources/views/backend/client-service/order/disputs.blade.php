@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Dispute Requests') }}
    </h4>
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
        <a href="#">{{ __('Dispute Requests') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">
          {{ __('Dispute Requests') }}
        </a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-8">
              <div class="card-title d-inline-block">{{ __('Dispute Requests') }}</div>
            </div>
            <div class="col-md-4">
              <form action="" action="" method="GET">
                <div class="form-group">
                  <input name="order_no" type="text" class="form-control" placeholder="Order Number"
                    value="{{ !empty(request()->input('order_no')) ? request()->input('order_no') : '' }}">
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($collection) == 0)
                <h3 class="text-center mt-3">{{ __('NO ORDER DISPUTES FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Order No.') }}</th>
                        <th scope="col">{{ __('Customer Email') }}</th>
                        <th scope="col">{{ __('Seller') }}</th>
                        <th scope="col">{{ __('Service') }}</th>
                        <th scope="col">{{ __('Disput Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $order)
                        <tr>
                          <td>{{ '#' . $order->order_number }}</td>

                          @php $customerEmail = $order->email_address; @endphp
                          <td>{{ $customerEmail }}</td>
                          <td>
                            @if (!is_null($order->seller_id))
                              <a
                                href="{{ route('admin.seller_management.seller_details', ['id' => $order->seller_id, 'language' => $defaultLang->code]) }}">{{ @$order->seller->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td><a
                              href="{{ route('service_details', ['slug' => $order->serviceSlug, 'id' => $order->service_id]) }}">
                              {{ strlen($order->serviceTitle) > 35 ? mb_substr($order->serviceTitle, 0, 35, 'UTF-8') . '...' : $order->serviceTitle }}
                            </a>
                          </td>

                          <td>
                            <form id="orderStatusForm-{{ $order->id }}" class="d-inline-block"
                              action="{{ route('admin.service_order.disput.update', ['id' => $order->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm @if ($order->raise_status == 1) bg-warning text-dark @elseif  ($order->raise_status == 2) bg-success @elseif ($order->raise_status == 3) bg-danger @endif"
                                name="raise_status"
                                onchange="document.getElementById('orderStatusForm-{{ $order->id }}').submit()">
                                <option value="1" @selected($order->raise_status == 1)>
                                  {{ __('Pending') }}
                                </option>
                                <option value="2" @selected($order->raise_status == 2)>
                                  {{ __('Completed') }}
                                </option>
                                <option value="3" @selected($order->raise_status == 3)>
                                  {{ __('Rejected') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <a href="{{ route('admin.service_order.details', ['id' => $order->id]) }}"
                              class="btn btn-secondary btn-sm">{{ __('View Order') }}</a>
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

        <div class="card-footer">
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              {{ $collection->appends([
                      'order_no' => request()->input('order_no'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
