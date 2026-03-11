@extends('seller.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Messages') }}</h4>
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
        <a href="#">{{ __('Support Tickets') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('seller.support_tickets') }}">{{ __('All Tickets') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Messages') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Ticket') . ': #' . $ticket->id }}
          </div>

          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('seller.support_tickets') }}">
            <span class="btn-label">
              <i class="fas fa-backward mdb_12"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row text-center">
            <div class="col-12">
              <h3 class="ticket-subject">{{ $ticket->subject }}</h3>
            </div>
          </div>

          <div class="row text-center mt-4">
            <div class="col-12">
              @if ($ticket->status == 'pending')
                <span class="badge badge-warning">{{ __('Pending') }}</span>
              @elseif ($ticket->status == 'open')
                <span class="badge badge-success">{{ __('Open') }}</span>
              @else
                <span class="badge badge-danger">{{ __('Closed') }}</span>
              @endif

              <span class="badge badge-secondary ml-2">{{ $ticket->created_at->format('M d, Y - h:i A') }}</span>
            </div>
          </div>

          <div class="row justify-content-center mt-4 msg">
            <div class="col-8">
              {!! $ticket->message !!}

              @if (!is_null($ticket->attachment))
                <div class="text-center mt-4">
                  <a href="{{ asset('assets/file/ticket-files/' . $ticket->attachment) }}" class="btn btn-info btn-sm"
                    download="file.zip">
                    <span class="btn-label">
                      <i class="fas fa-download mdb_12"></i>
                    </span>
                    {{ __('Attachment') }}
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="{{ $ticket->status != 'closed' ? 'col-lg-6' : 'col-12' }}">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Conversations') }}</div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @if (count($conversations) == 0)
                <h5>{{ __('No Conversation Found') . '!' }}</h5>
              @else
                <div class="messages-container">
                  @foreach ($conversations as $conversation)
                    @if ($conversation->person_type == 'admin')
                      @php $admin = $conversation->admin()->first(); @endphp

                      <div class="single-message">
                        <div class="user-details">
                          <div class="user-img">
                            <img
                              src="{{ !is_null($admin->image) ? asset('assets/img/admins/' . $admin->image) : asset('assets/img/blank-user.jpg') }}"
                              alt="{{ $admin->first_name . ' ' . $admin->last_name }}">
                          </div>

                          <div class="user-infos">
                            <h6 class="name">{{ $admin->first_name . ' ' . $admin->last_name }}</h6>
                            <span class="type"><i
                                class="fas fa-user mr-2"></i>{{ is_null($admin->role_id) ? 'Super Admin' : $admin->role->name }}</span>
                            <span
                              class="badge badge-secondary">{{ $conversation->created_at->format('M d, Y - h:i A') }}</span>
                          </div>
                        </div>

                        <div class="message">
                          {!! replaceBaseUrl($conversation->reply, 'summernote') !!}
                        </div>

                        @if (!is_null($conversation->attachment))
                          <a href="{{ asset('assets/file/ticket-files/' . $conversation->attachment) }}"
                            download="support.zip" class="btn btn-sm btn-info mt-3">
                            <span class="btn-label">
                              <i class="fas fa-download mdb_12"></i>
                            </span>
                            {{ __('Attachment') }}
                          </a>
                        @endif
                      </div>
                    @else
                      @php $seller = $conversation->seller()->first(); @endphp
                      <div class="single-message">
                        <div class="user-details">
                          <div class="user-img">
                            <img
                              src="{{ !is_null($seller->photo) ? asset('assets/admin/img/seller-photo/' . $seller->photo) : asset('assets/img/blank-user.jpg') }}"
                              alt="{{ $seller->username }}">
                          </div>

                          <div class="user-infos">
                            <h6 class="name">{{ $seller->username }}</h6>
                            <span class="type"><i class="fas fa-user mr-2"></i>{{ __('Seller') }}</span>
                            <span
                              class="badge badge-secondary">{{ $conversation->created_at->format('M d, Y - h:i A') }}</span>
                          </div>
                        </div>

                        <div class="message">
                          {!! replaceBaseUrl($conversation->reply, 'summernote') !!}
                        </div>

                        @if (!is_null($conversation->attachment))
                          <a href="{{ asset('assets/file/ticket-files/' . $conversation->attachment) }}"
                            download="support.zip" class="btn btn-sm btn-info mt-3">
                            <span class="btn-label">
                              <i class="fas fa-download mdb_12"></i>
                            </span>
                            {{ __('Attachment') }}
                          </a>
                        @endif
                      </div>
                    @endif
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    @if ($ticket->status == 'open')
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Reply To Ticket') }}</div>
          </div>

          <div class="card-body">
            <form id="replyForm" action="{{ route('seller.support_ticket.reply', ['id' => $ticket->id]) }}"
              method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <textarea class="form-control summernote" name="reply" placeholder="Write Your Reply Here..." data-height="200"></textarea>
                    @error('reply')
                      <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="attachment">
                        <label class="custom-file-label">{{ __('Choose File') }}</label>
                      </div>
                    </div>

                    <div class="progress mt-3 d-none">
                      <div class="progress-bar mdb_0" role="progressbar"></div>
                    </div>

                    <p id="attachment-info" class="mt-2 mb-0 text-warning">
                      {{ '*' . __('Upload only .zip file.') . ' ' . __('Max file size is 20 MB.') }}
                    </p>

                    @error('attachment')
                      <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success" form="replyForm">
                  {{ __('Submit') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection

@section('script')
  <script src="{{ asset('assets/js/support-ticket.js') }}"></script>
@endsection
