@extends('seller.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Message') }}</h4>
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
        <a href="#">{{ __('Service Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('seller.service_orders') }}">{{ __('All Orders') }}</a>
      </li>

      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Message') }}</a>
      </li>
    </ul>
    <a href="{{ route('seller.service_orders') }}" class="btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>

  <div class="row justify-content-center">
    <div class="col-8">
      <div class="card">
        <div class="card-body pb-0">
          <div id="reload-div">
            <div class="message-wrapper">
              <h4 class="mb-3">
                {{ '#' . $order->order_number }} -
                {{ strlen($serviceInfo->title) > 50 ? mb_substr($serviceInfo->title, 0, 50, 'UTF-8') . '...' : $serviceInfo->title }}
              </h4>

              <div class="row">
                <div class="col-lg-12">
                  <div class="chat-wrapper-area">
                    <div class="chat-wrapper">
                      @if (count($messages) > 0)
                        @foreach ($messages as $msgInfo)
                          @if ($msgInfo->person_type == 'admin')
                            <div class="chat-card mdb-15">
                              <div class="chat-text">
                                <div class="content mdb-15">
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
                                        <span class="mr-2"><i
                                            class="fas fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                      <br>
                                      <img src="{{ asset('assets/file/message-files/' . $unqName) }}" alt="image"
                                        width="150">
                                    @else
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="mr-2"><i
                                            class="fas fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                    @endif
                                  @endif
                                </div>
                              </div>

                              <div class="thumb">
                                <img src="{{ asset('assets/img/admins/' . $msgInfo->admin->image) }}" alt="admin"
                                  title="Admin">
                              </div>
                            </div>
                          @elseif ($msgInfo->person_type == 'seller')
                            <div class="chat-card mdb-15">
                              <div class="chat-text">
                                <div class="content mdb-15">
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
                                        <span class="mr-2"><i
                                            class="fas fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                      <br>
                                      <img src="{{ asset('assets/file/message-files/' . $unqName) }}" alt="image"
                                        width="150">
                                    @else
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="mr-2"><i
                                            class="fas fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                    @endif
                                  @endif
                                </div>
                              </div>

                              <div class="thumb">
                                @if (!is_null($msgInfo->seller->photo))
                                  <img src="{{ asset('assets/admin/img/seller-photo/' . $msgInfo->seller->photo) }}"
                                    alt="seller" title="{{ __('Seller') }}">
                                @else
                                  <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="seller"
                                    title="{{ __('Seller') }}">
                                @endif

                              </div>
                            </div>
                          @else
                            <div class="chat-card reply-chat mdb-15">
                              <div class="thumb">
                                @if (!is_null($msgInfo->user->image))
                                  <img src="{{ asset('assets/img/users/' . $msgInfo->user->image) }}" alt="user"
                                    title="User">
                                @else
                                  <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="user" title="User">
                                @endif
                              </div>

                              <div class="chat-text">
                                <div class="content mdb-15">
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
                                        <span class="mr-2"><i
                                            class="fas fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
                                      </a>
                                      <br>
                                      <img src="{{ asset('assets/file/message-files/' . $unqName) }}" alt="image"
                                        width="150">
                                    @else
                                      <a href="{{ asset('assets/file/message-files/' . $unqName) }}"
                                        download="{{ $orgName }}">
                                        <span class="mr-2"><i
                                            class="fas fa-arrow-alt-circle-down"></i></span>{{ $orgName }}
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
                      <form action="{{ route('seller.service_order.store_message', ['id' => $order->id]) }}"
                        method="POST" id="msg-form">
                        @csrf
                        <div class="chat-input-group">
                          <input type="text" name="msg" placeholder="{{ __('Type a message') . '...' }}"
                            autocomplete="off">

                          <label id="file-input-label">
                            <input type="file" name="attachment" class="mdb_display_none">
                            <i class="fas fa-paperclip"
                              title="{{ __('Allow file types') . ': ' }}{{ __('.jpg, .jpeg, .png, .rar, .zip, .txt, .doc, .docx, .pdf') }}"
                              data-toggle="tooltip" data-placement="top"></i>
                          </label>
                        </div>

                        <div class="chat-send-button">
                          <button type="submit" clas><i class="fas fa-paper-plane"></i></button>
                        </div>
                      </form>
                      <div class="progress mt-2 d-none message-progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                          aria-valuemin="0" aria-valuemax="100">0%</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <p class="mt-1 ml-2 text-danger" id="msg-err"></p>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/js/pusher.min.js') }}"></script>

  <script>
    let pusherKey = '{{ $bs->pusher_key }}';
    let pusherCluster = '{{ $bs->pusher_cluster }}';
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/message.js') }}"></script>
@endsection
