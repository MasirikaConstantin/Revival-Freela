@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      @if (empty(request()->input('ticket_status')))
        {{ __('All Tickets') }}
      @elseif (request()->input('ticket_status') == 'pending')
        {{ __('Pending Tickets') }}
      @elseif (request()->input('ticket_status') == 'open')
        {{ __('Open Tickets') }}
      @elseif (request()->input('ticket_status') == 'closed')
        {{ __('Closed Tickets') }}
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
        <a href="#">{{ __('Support Tickets') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">
          @if (empty(request()->input('ticket_status')))
            {{ __('All Tickets') }}
          @elseif (request()->input('ticket_status') == 'pending')
            {{ __('Pending Tickets') }}
          @elseif (request()->input('ticket_status') == 'open')
            {{ __('Open Tickets') }}
          @elseif (request()->input('ticket_status') == 'closed')
            {{ __('Closed Tickets') }}
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
              <form id="searchForm" action="{{ route('admin.support_tickets') }}" method="GET">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Ticket ID') }}</label>
                      <input name="ticket_no" type="text" class="form-control" placeholder="Search by Ticket ID"
                        value="{{ !empty(request()->input('ticket_no')) ? request()->input('ticket_no') : '' }}">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Ticket Status') }}</label>
                      <select class="form-control " name="ticket_status"
                        onchange="document.getElementById('searchForm').submit()">
                        <option value="" {{ empty(request()->input('ticket_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="pending" {{ request()->input('ticket_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="open" {{ request()->input('ticket_status') == 'open' ? 'selected' : '' }}>
                          {{ __('Open') }}
                        </option>
                        <option value="closed" {{ request()->input('ticket_status') == 'closed' ? 'selected' : '' }}>
                          {{ __('Closed') }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right card-header-button"
                data-href="{{ route('admin.support_tickets.bulk_delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($tickets) == 0)
                <h3 class="text-center mt-3">{{ __('NO TICKET FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Ticket ID') }}</th>
                        <th scope="col">{{ __('User Type') }}</th>
                        <th scope="col">{{ __('User') }}</th>
                        <th scope="col">{{ __('Customer Email') }}</th>
                        <th scope="col">{{ __('Subject') }}</th>
                        <th scope="col">{{ __('Ticket Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tickets as $ticket)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $ticket->id }}">
                          </td>
                          <td>{{ '#' . $ticket->id }}</td>
                          @if ($ticket->user_type == 'user')
                            @php
                              $customer = $ticket->user()->first();
                            @endphp
                            <td><span class="badge badge-success">{{ __('Customer') }}</span></td>
                            <td><a target="_blank"
                                href="{{ route('admin.user_management.user.details', ['id' => $customer->id]) }}">{{ $customer->username }}</a>
                            </td>
                            <td>{{ $customer->email_address }}</td>
                          @else
                            @php
                              $seller = $ticket->seller()->first();
                            @endphp
                            <td><span class="badge badge-success">{{ __('Seller') }}</span></td>
                            <td>
                              @if ($seller)
                                <a
                                  href="{{ route('admin.seller_management.seller_details', ['id' => $seller->id, 'language' => $defaultLang->code]) }}">{{ $seller->username }}</a>
                              @endif
                            </td>
                            <td>{{ @$seller->email }}</td>
                          @endif

                          <td>{{ $ticket->subject }}</td>
                          <td>
                            @if ($ticket->status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($ticket->status == 'open')
                              <span class="badge badge-success">{{ __('Open') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Closed') }}</span>
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ '#assignModal-' . $ticket->id }}" data-toggle="modal" class="dropdown-item">
                                  {{ __('Assign To') }}
                                </a>

                                <a href="{{ route('admin.support_ticket.conversation', ['id' => $ticket->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Conversation') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.support_ticket.delete', ['id' => $ticket->id]) }}"
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

                        <!-- Assign-Admin Modal -->
                        @includeIf('backend.support-ticket.assign-admin')
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
              {{ $tickets->appends([
                      'order_no' => request()->input('order_no'),
                      'payment_status' => request()->input('payment_status'),
                      'order_status' => request()->input('order_status'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
