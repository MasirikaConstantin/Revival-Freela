@extends('frontend.layout')

@php $title = __('Create Ticket'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Support Tickets Section ======-->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div class="user-profile-details mb-40">
            <div class="account-info">
              <div class="title">
                <h4>{{ __('Create New Ticket') }}</h4>

                <a href="{{ route('user.support_tickets') }}" class="btn btn-md btn-primary radius-sm icon-start">
                  <i
                    class="{{ $currentLanguageInfo->direction == 0 ? 'fas fa-chevron-left' : 'fas fa-chevron-right' }}"></i> {{ __('Back') }}
                </a>
              </div>

              <div class="edit-info-area support-ticket-area">
                <form action="{{ route('user.support_tickets.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12 mb-4">
                      <input type="text" class="form-control" placeholder="{{ __('Enter Subject') }}"
                        name="subject" value="{{ old('subject') ?? '' }}">
                      @error('subject')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="col-lg-12 mb-4">
                      <textarea class="form-control summernote" placeholder="{{ __('Enter Message') }}" name="message" data-height="220">{{ old('message') ?? '' }}</textarea>
                      @error('message')
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
                        <div class="progress-bar mdf_w34322" role="progressbar"></div>
                      </div>
                      <small id="attachment-info">{{ '*' . __('Upload only .zip file') . '. ' . __('Max file size is 20 MB') . '.' }}</small>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Support Tickets Section ======-->
@endsection
