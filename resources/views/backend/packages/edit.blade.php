@extends('backend.layout')

@php
  use App\Models\Language;
  $selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang->language) && $selLang->language->rtl == 1)
  @section('styles')
    <style>
      form input,
      form textarea,
      form select {
        direction: rtl;
      }

      form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
      }
    </style>
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit package') }}</h4>
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
        <a href="#">{{ __('Packages Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.package.index', ['language' => $defaultLang->code]) }}">{{ __('Packages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit package') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.package.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxForm" class="" action="{{ route('admin.package.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="title">{{ __('Package title') }}*</label>
                      <input id="title" type="text" class="form-control" name="title"
                        value="{{ $package->title }}" placeholder="{{ __('Enter name') }}">
                      <p id="err_title" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="price">{{ __('Price') }} ({{ $settings->base_currency_text }})*</label>
                      <input id="price" type="number" class="form-control" name="price"
                        placeholder="{{ __('Enter Package price') }}" value="{{ $package->price }}">
                      <p class="text-warning">
                        <small>{{ __('If price is 0 , than it will appear as free') }}</small>
                      </p>
                      <p id="err_price" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="plan_term">{{ __('Package term') }}*</label>
                      <select id="plan_term" name="term" class="form-control">
                        <option value="" selected disabled>{{ __('Select a Term') }}</option>
                        <option value="monthly" {{ $package->term == 'monthly' ? 'selected' : '' }}>
                          {{ __('monthly') }}</option>
                        <option value="yearly" {{ $package->term == 'yearly' ? 'selected' : '' }}>
                          {{ __('yearly') }}</option>
                        <option value="lifetime" {{ $package->term == 'lifetime' ? 'selected' : '' }}>
                          {{ 'lifetime' }}</option>
                      </select>
                      <p id="err_term" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Number of services add') }} *</label>
                      <input type="number" class="form-control" name="number_of_service_add"
                        placeholder="{{ __('Enter number of services add') }}"
                        value="{{ $package->number_of_service_add }}">
                      <p id="err_number_of_service_add" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Number of featured services') }} *</label>
                      <input type="number" name="number_of_service_featured" class="form-control"
                        placeholder="{{ __('number of featured services') }}"
                        value="{{ $package->number_of_service_featured }}">
                      <p id="err_number_of_service_featured" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Number of forms') }} *</label>
                      <input type="number" name="number_of_form_add" class="form-control"
                        placeholder="{{ __('Enter number of forms') }}" value="{{ $package->number_of_form_add }}">
                      <p id="err_number_of_form_add" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Number of service orders') }} *</label>
                      <input type="number" name="number_of_service_order" class="form-control"
                        placeholder="{{ __('Enter number of service orders') }}"
                        value="{{ $package->number_of_service_order }}">
                      <p id="err_number_of_service_order" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">{{ __('Live Chat') }}*</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="live_chat_status" value="1" class="selectgroup-input"
                            {{ $package->live_chat_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Enable') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="live_chat_status"
                            {{ $package->live_chat_status == 0 ? 'checked' : '' }} value="0"
                            class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Disable') }}</span>
                        </label>
                      </div>
                      <p id="err_live_chat_status" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">{{ __('QR Builder') }}*</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="qr_builder_status" value="1" class="selectgroup-input"
                            {{ $package->qr_builder_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Enable') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="qr_builder_status" value="0" class="selectgroup-input"
                            {{ $package->qr_builder_status == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Disable') }}</span>
                        </label>
                      </div>
                      <p id="err_qr_builder_status" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6 {{ $package->qr_builder_status == 0 ? 'd-none' : '' }}" id="qr_code_save_limit">
                    <div class="form-group">
                      <label for="">{{ __('QR Code Save Limit') }}*</label>
                      <input type="number" name="qr_code_save_limit" value="{{ $package->qr_code_save_limit }}"
                        class="form-control">
                      <p id="err_qr_code_save_limit" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">{{ __('Recommended') }}*</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="recommended" value="1" class="selectgroup-input"
                            {{ $package->recommended == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Yes') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="recommended" value="0" class="selectgroup-input"
                            {{ $package->recommended == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('No') }}</span>
                        </label>
                      </div>
                      <p id="err_recommended" class="mb-0 text-danger em"></p>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">{{ __('Status') }}*</label>
                      <select id="status" class="form-control ltr" name="status">
                        <option value="" selected disabled>{{ __('Select a status') }}</option>
                        <option value="1" {{ $package->status == '1' ? 'selected' : '' }}>
                          {{ __('Active') }}</option>
                        <option value="0" {{ $package->status == '0' ? 'selected' : '' }}>
                          {{ __('Deactive') }}</option>
                      </select>
                      <p id="err_status" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Custom Feature') }}</label>
                      <textarea name="custom_features" rows="4" class="form-control">{{ $package->custom_features }}</textarea>
                      <p class="text-warning">
                        {{ __('each new line will be shown as a new feature in the pricing plan') }}</p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('script')
  <script src="{{ asset('assets/js/packages.js') }}"></script>
  <script src="{{ asset('assets/admin/js/edit-package.js') }}"></script>
@endsection
