@extends('frontend.layout')

@php $title = __('Ticket Conversation'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Support Tickets Section ======-->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details mb-40">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Ticket') . ': #' . $ticket->id }}</h4>

                    <a href="{{ route('user.support_tickets') }}" class="btn btn-sm btn-primary rounded-1">
                      <i
                        class="{{ $currentLanguageInfo->direction == 0 ? 'fas fa-chevron-left' : 'fas fa-chevron-right' }}"></i>
                      {{ __('Back') }}
                    </a>
                  </div>

                  <div class="ticket-info">
                    <div class="subject">
                      <h5>{{ $ticket->subject }}</h5>

                      <div>
                        @if ($ticket->status == 'pending')
                          <span class="badge badge-warning">{{ __('Pending') }}</span>
                        @elseif ($ticket->status == 'open')
                          <span class="badge badge-success">{{ __('Open') }}</span>
                        @else
                          <span class="badge badge-danger">{{ __('Closed') }}</span>
                        @endif

                        <span class="date-time">{{ $ticket->created_at->format('M d, Y - h:i A') }}</span>
                      </div>
                    </div>

                    <div class="message mt-2 summernote-content">
                      {!! replaceBaseUrl($ticket->message, 'summernote') !!}
                    </div>

                    @if (!is_null($ticket->attachment))
                      <div class="attachment mt-4">
                        <a href="{{ asset('assets/file/ticket-files/' . $ticket->attachment) }}" download
                          class="btn btn-sm btn-primary rounded-1">
                          <i class="fas fa-download"></i> {{ __('Attachment') }}
                        </a>
                      </div>
                    @endif
                  </div>

                  <div class="conversation-info">
                    <h4 class="mb-3">{{ __('Conversations') }}</h4>

                    @if (count($conversations) == 0)
                      <p>{{ __('No Conversation Found') . '!' }}</p>
                    @else
                      <div class="message-list">
                        @foreach ($conversations as $conversation)
                          @if ($conversation->person_type == 'user')
                            @php $user = $conversation->user()->first(); @endphp

                            <div class="single-message">
                              <div class="user-details">
                                <div class="user-img">
                                  <img
                                    data-src="{{ !is_null($user->image) ? asset('assets/img/users/' . $user->image) : asset('assets/img/blank-user.jpg') }}"
                                    alt="{{ $user->first_name . ' ' . $user->last_name }}" class="lazyload">
                                </div>

                                <div class="user-infos">
                                  <h6 class="name">{{ $user->first_name . ' ' . $user->last_name }}</h6>
                                  <span class="type"><i
                                      class="fas fa-user {{ $currentLanguageInfo->direction == 0 ? 'me-2' : 'ms-2' }}"></i>{{ __('Customer') }}</span>
                                  <span
                                    class="badge badge-secondary text-dark">{{ $conversation->created_at->format('M d, Y - h:i A') }}</span>
                                </div>
                              </div>

                              <div class="message summernote-content">
                                {!! replaceBaseUrl($conversation->reply, 'summernote') !!}
                              </div>

                              @if (!is_null($conversation->attachment))
                                <a href="{{ asset('assets/file/ticket-files/' . $conversation->attachment) }}"
                                  download="support.zip" class="btn btn-sm btn-primary rounded-1">
                                  <i class="fas fa-download"></i> {{ __('Attachment') }}
                                </a>
                              @endif
                            </div>
                          @else
                            @php $admin = $conversation->admin()->first(); @endphp

                            <div class="single-message">
                              <div class="user-details">
                                <div class="user-img">
                                  <img
                                    data-src="{{ !is_null($admin->image) ? asset('assets/img/admins/' . $admin->image) : asset('assets/img/blank-user.jpg') }}"
                                    alt="{{ $admin->first_name . ' ' . $admin->last_name }}" class="lazyload">
                                </div>

                                <div class="user-infos">
                                  <h6 class="name">{{ $admin->first_name . ' ' . $admin->last_name }}</h6>
                                  <span class="type"><i
                                      class="fas fa-user {{ $currentLanguageInfo->direction == 0 ? 'me-2' : 'ms-2' }}"></i>{{ is_null($admin->role_id) ? __('Super Admin') : $admin->role->name }}</span>
                                  <span
                                    class="badge badge-secondary text-dark">{{ $conversation->created_at->format('M d, Y - h:i A') }}</span>
                                </div>
                              </div>

                              <div class="message summernote-content">
                                {!! replaceBaseUrl($conversation->reply, 'summernote') !!}
                              </div>

                              @if (!is_null($conversation->attachment))
                                <a href="{{ asset('assets/file/ticket-files/' . $conversation->attachment) }}"
                                  download="support.zip" class="btn btn-lg btn-primary radius-sm">
                                  <i class="fas fa-download"></i>{{ __('Attachment') }}
                                </a>
                              @endif
                            </div>
                          @endif
                        @endforeach
                      </div>
                    @endif
                  </div>

                  @if ($ticket->status == 'open')
                    <div class="edit-info-area support-ticket-area">
                      <h4 class="mb-4">{{ __('Reply To Ticket') }}</h4>

                      <form action="{{ route('user.support_ticket.reply', ['id' => $ticket->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                          <div class="col-lg-12 mb-4">
                            <textarea class="form-control" placeholder="{{ __('Write Your Reply Here') . '...' }}" name="reply" data-height="220"
                              autocomplete="off"></textarea>
                            @error('reply')
                              <p class="text-danger mt-2">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12 mb-3">
                            <div class="form-group mb-1">
                              <label for="formFile" class="form-label">{{ __('Choose File') }}</label>
                              <input type="file" class="form-control size-md w-100" id="formFile" name="attachment"
                                data-url="{{ route('user.support_tickets.store_temp_file') }}">
                            </div>
                            <div class="progress mt-3 mb-1 d-none">
                              <div class="progress-bar mdf_34322" role="progressbar"></div>
                            </div>
                            <small
                              id="attachment-info">{{ '*' . __('Upload only .zip file') . '. ' . __('Max file size is 20 MB') . '.' }}</small>
                            @error('attachment')
                              <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12">
                            <div class="form-button">
                              <button class="btn btn-md btn-primary radius-sm">{{ __('Submit') }}</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Support Tickets Section ======-->
@endsection
