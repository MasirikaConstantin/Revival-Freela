@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      @if (empty(request()->input('order_status')))
        {{ __('All Orders') }}
      @elseif (request()->input('order_status') == 'pending')
        {{ __('Pending Orders') }}
      @elseif (request()->input('order_status') == 'processing')
        {{ __('Processing Orders') }}
      @elseif (request()->input('order_status') == 'completed')
        {{ __('Completed Orders') }}
      @elseif (request()->input('order_status') == 'rejected')
        {{ __('Rejected Orders') }}
      @endif
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
        <a href="#">{{ __('Service Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">
          @if (empty(request()->input('order_status')))
            {{ __('All Orders') }}
          @elseif (request()->input('order_status') == 'pending')
            {{ __('Pending Orders') }}
          @elseif (request()->input('order_status') == 'processing')
            {{ __('Processing Orders') }}
          @elseif (request()->input('order_status') == 'completed')
            {{ __('Completed Orders') }}
          @elseif (request()->input('order_status') == 'rejected')
            {{ __('Rejected Orders') }}
          @endif
        </a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form id="searchForm" action="{{ route('admin.service_orders') }}" method="GET">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Order Number') }}</label>
                      <input name="order_no" type="text" class="form-control" placeholder="Search Here..."
                        value="{{ !empty(request()->input('order_no')) ? request()->input('order_no') : '' }}">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Seller') }}</label>
                      <select class="form-control mdb_343 select2" name="seller"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('seller')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="admin" @selected(request()->input('seller') == 'admin')>
                          {{ __('Admin') }}
                        </option>
                        @foreach ($sellers as $seller)
                          <option @selected(request()->input('seller') == $seller->id) value="{{ $seller->id }}">{{ $seller->username }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Payment') }}</label>
                      <select class="form-control mdb_343" name="payment_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="rejected"
                          {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Order') }}</label>
                      <select class="form-control mdb_343" name="order_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('order_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="pending" {{ request()->input('order_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="processing"
                          {{ request()->input('order_status') == 'processing' ? 'selected' : '' }}>
                          {{ __('Processing') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('order_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="rejected" {{ request()->input('order_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right card-header-button"
                data-href="{{ route('admin.service_orders.bulk_delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) == 0)
                <h3 class="text-center mt-3">{{ __('NO ORDER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Order No.') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
                        <th scope="col">{{ __('Seller') }}</th>
                        <th scope="col">{{ __('Service') }}</th>
                        <th scope="col">{{ __('Package') }}</th>
                        <th scope="col">{{ __('Total Price') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $order->id }}">
                          </td>
                          <td>{{ '#' . $order->order_number }}</td>

                          @php $customerName = $order->name; @endphp
                          <td>{{ $customerName }}</td>
                          <td>
                            @if (!is_null($order->seller_id))
                              <a
                                href="{{ route('admin.seller_management.seller_details', ['id' => $order->seller_id, 'language' => $defaultLang->code]) }}">{{ @$order->seller->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td>
                            @if (!empty($order->serviceSlug))
                              <a
                                href="{{ route('service_details', ['slug' => $order->serviceSlug, 'id' => $order->service_id]) }}">
                                {{ strlen($order->serviceTitle) > 70 ? mb_substr($order->serviceTitle, 0, 70, 'UTF-8') . '...' : $order->serviceTitle }}
                              </a>
                            @else
                              {{ '-' }}
                            @endif
                          </td>

                          <td>
                            @if (is_null($order->packageName))
                              <span class="ml-4">-</span>
                            @else
                              {{ $order->packageName }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->grand_total))
                              {{ __('Requested') }}
                            @else
                              {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}{{ $order->grand_total }}{{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->payment_method))
                              <span class="ml-4">-</span>
                            @else
                              {{ $order->payment_method }}
                            @endif
                          </td>
                          <td>
                            @if ($order->gateway_type == 'online')
                              <h2 class="d-inline-block">
                                @if ($order->payment_status == 'completed')
                                  <span class="badge badge-success">{{ __('Completed') }}</span>
                                @else
                                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @endif
                              </h2>
                            @else
                              @if ($order->payment_status == 'pending')
                                <form id="paymentStatusForm-{{ $order->id }}" class="d-inline-block"
                                  action="{{ route('admin.service_order.update_payment_status', ['id' => $order->id]) }}"
                                  method="post">
                                  @csrf
                                  <select
                                    class="form-control form-control-sm @if ($order->payment_status == 'completed') bg-success @elseif ($order->payment_status == 'pending') bg-warning text-dark @else bg-danger @endif"
                                    name="payment_status"
                                    onchange="document.getElementById('paymentStatusForm-{{ $order->id }}').submit()">
                                    <option value="completed"
                                      {{ $order->payment_status == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="rejected"
                                      {{ $order->payment_status == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                @if ($order->payment_status == 'completed')
                                  <span class="badge badge-success">{{ __('Completed') }}</span>
                                @elseif ($order->payment_status == 'pending')
                                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @else
                                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                @endif
                              @endif
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <form class="d-inline-block completeForm"
                                action="{{ route('admin.service_order.update_order_status', ['id' => $order->id]) }}"
                                method="post">
                                @csrf
                                <select
                                  class="form-control completeBtn form-control-sm @if ($order->order_status == 'pending') bg-warning text-dark @elseif ($order->order_status == 'processing') bg-primary @elseif ($order->order_status == 'completed') bg-success @elseif ($order->order_status == 'rejected') bg-danger @endif"
                                  name="order_status">
                                  <option disabled value="pending"
                                    {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                    {{ __('Pending') }}
                                  </option>
                                  <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                    {{ __('Completed') }}
                                  </option>
                                  <option value="rejected" {{ $order->order_status == 'rejected' ? 'selected' : '' }}>
                                    {{ __('Rejected') }}
                                  </option>
                                </select>
                              </form>
                            @else
                              @if ($order->order_status == 'completed')
                                <span class="badge badge-success">{{ __('Completed') }}</span>
                              @else
                                <span class="badge badge-danger">{{ __('Rejected') }}</span>
                              @endif
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.service_order.details', ['id' => $order->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                @if (!is_null($order->receipt))
                                  <a href="#" class="dropdown-item" data-toggle="modal"
                                    data-target="#receiptModal-{{ $order->id }}">
                                    {{ __('Receipt') }}
                                  </a>
                                @endif

                                @if (!is_null($order->invoice))
                                  <a href="{{ asset('assets/file/invoices/service/' . $order->invoice) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif

                                <a href="{{ route('admin.service_order.message', ['id' => $order->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Chat with customer') }}
                                </a>
                                <a href="{{ '#emailModal-' . $order->id }}" data-toggle="modal"
                                  class="dropdown-item">
                                  {{ __('Send via Mail') }}
                                </a>
                                <form class="deleteForm d-block"
                                  action="{{ route('admin.service_order.delete', ['id' => $order->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <!-- Email Modal -->
                        @includeIf('backend.client-service.order.send-mail')

                        @includeWhen($order->receipt, 'backend.client-service.order.show-receipt')
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
              {{ $orders->appends([
                      'order_no' => request()->input('order_no'),
                      'payment_status' => request()->input('payment_status'),
                      'order_status' => request()->input('order_status'),
                      'seller' => request()->input('seller'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
