@extends('frontend.layout')
@section('pageHeading')
  {{ __('Message') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => __('Message')])
  <!--====== Start Live Chat ======-->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div id="reload-div">
            <div class="message-wrapper mb-40">
              <h4 class="mb-3">
                {{ '#' . $order->order_number }} - <a
                  href="{{ route('service_details', ['slug' => $serviceInfo->slug, 'id' => $serviceInfo->service_id]) }}"
                  class="link_22422"
                  target="_blank">{{ strlen($serviceInfo->title) > 35 ? mb_substr($serviceInfo->title, 0, 35, 'UTF-8') . '...' : $serviceInfo->title }}</a>
              </h4>
              <div class="row">
                <div class="col-lg-12">
                  <div class="chat-wrapper-area">
                    <div class="chat-wrapper">
                      @if (count($messages) > 0)
                        @foreach ($messages as $msgInfo)
                          @if ($msgInfo->person_type == 'user')
                            <div class="chat-card mb-15">
                              <div class="chat-text">
                                <div class="content mb-15">
                                  @if (!empty($msgInfo->message))
                                    <p>{!! nl2br($msgInfo->message) !!}</p>
                                  @else
                                    {{-- check whether the uploaded file is image or not --}}
                                    @php
                                      $unqName = $msgInfo->file_name;
                                      $orgName = $msgInfo->file_original_name;

                                      if (strpos($orgName, '.jpg') == true || strpos($orgName, '.jpeg') == true || strpos($orgName, '.png') == true) {
                                          $isImg = true;
                                      } else {
                                          $isImg = false;
                                      }
                                    @endphp

                                    @if ($isImg == true)
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="me-2"><i
                                            class="far fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                      <br>
                                      <img src="{{ asset('assets/file/message-files/' . $unqName) }}" alt="image"
                                        width="150">
                                    @else
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="me-2"><i
                                            class="far fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                    @endif
                                  @endif
                                </div>
                              </div>

                              <div class="thumb">
                                <img
                                  src="{{ empty($msgInfo->user->image) ? asset('assets/img/users/profile.jpeg') : asset('assets/img/users/' . $msgInfo->user->image) }}"
                                  alt="user">
                              </div>
                            </div>
                          @elseif ($msgInfo->person_type == 'seller')
                            <div class="chat-card reply-chat mb-15">
                              <div class="thumb">
                                <img
                                  src="{{ empty($msgInfo->seller->photo) ? asset('assets/img/users/profile.jpeg') : asset('assets/admin/img/seller-photo/' . $msgInfo->seller->photo) }}"
                                  alt="admin">
                              </div>

                              <div class="chat-text">
                                <div class="content mb-15">
                                  @if (!empty($msgInfo->message))
                                    <p>{!! nl2br($msgInfo->message) !!}</p>
                                  @else
                                    {{-- check whether the uploaded file is image or not --}}
                                    @php
                                      $unqName = $msgInfo->file_name;
                                      $orgName = $msgInfo->file_original_name;

                                      if (strpos($orgName, '.jpg') == true || strpos($orgName, '.jpeg') == true || strpos($orgName, '.png') == true) {
                                          $isImg = true;
                                      } else {
                                          $isImg = false;
                                      }
                                    @endphp

                                    @if ($isImg == true)
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="me-2"><i
                                            class="far fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                      <br>
                                      <img src="{{ asset('assets/file/message-files/' . $unqName) }}" alt="image"
                                        width="150">
                                    @else
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="me-2"><i
                                            class="far fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                    @endif
                                  @endif
                                </div>
                              </div>
                            </div>
                          @else
                            <div class="chat-card reply-chat mb-15">
                              <div class="thumb">
                                <img
                                  src="{{ empty($msgInfo->admin->image) ? asset('assets/img/users/profile.jpeg') : asset('assets/img/admins/' . $msgInfo->admin->image) }}"
                                  alt="admin">
                              </div>

                              <div class="chat-text">
                                <div class="content mb-15">
                                  @if (!empty($msgInfo->message))
                                    <p>{!! nl2br($msgInfo->message) !!}</p>
                                  @else
                                    {{-- check whether the uploaded file is image or not --}}
                                    @php
                                      $unqName = $msgInfo->file_name;
                                      $orgName = $msgInfo->file_original_name;

                                      if (strpos($orgName, '.jpg') == true || strpos($orgName, '.jpeg') == true || strpos($orgName, '.png') == true) {
                                          $isImg = true;
                                      } else {
                                          $isImg = false;
                                      }
                                    @endphp

                                    @if ($isImg == true)
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="me-2"><i
                                            class="far fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                      <br>
                                      <img src="{{ asset('assets/file/message-files/' . $unqName) }}" alt="image"
                                        width="150">
                                    @else
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="me-2"><i
                                            class="far fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                    @endif
                                  @endif
                                </div>
                              </div>
                            </div>
                          @endif
                        @endforeach
                      @endif
                    </div>

                    <div class="chat-bottom">
                      <form action="{{ route('user.service_order.store_message', ['id' => $order->id]) }}" method="POST"
                        id="msg-form" autocomplete="off">
                        @csrf
                        <div class="chat-input-group">
                          <label class="helper-form">
                            <input type="file" name="attachment" id="attachment" class="mdf_display_none">
                            <i class="far fa-paperclip"></i>

                            <div class="helper-text">
                              <h6 class="mb-2">{{ __('Allow file types') }}</h6>
                              <ul class="helper-list">
                                <li>{{ __('.jpg') }},
                                  {{ __('.jpeg') }},
                                  {{ __('.png') }},
                                  {{ __('.rar') }},
                                  {{ __('.zip') }},
                                  {{ __('.txt') }},
                                  {{ __('.doc') }},
                                  {{ __('.docx') }},
                                  {{ __('.pdf') }}</li>
                              </ul>
                            </div>
                          </label>

                          <input type="text" name="msg" placeholder="{{ __('Type a message') . '...' }}"
                            autocomplete="off">

                          <div class="chat-send-button">
                            <button type="submit" id="chat-send-button"><i class="far fa-paper-plane"></i></button>
                          </div>
                        </div>

                      </form>
                      <div class="progress mt-2 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                          aria-valuemin="0" aria-valuemax="100">0%</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p class="mt-4 text-danger" id="msg-err"></p>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Live Chat ======-->
@endsection

@section('script')
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

  <script>
    let pusherKey = '{{ $bs->pusher_key }}';
    let pusherCluster = '{{ $bs->pusher_cluster }}';
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/message.js') }}"></script>
@endsection
