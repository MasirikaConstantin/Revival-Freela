@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Settings') }}</h4>
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
        <a href="#">{{ __('Service Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Settings') }}</div>
            </div>

            <div class="col-lg-3">

            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="ajaxForm" action="{{ route('admin.service_management.settings.update') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Tax') . ' (%)' }}</label>
                  <input type="number" name="tax" step="0.1" class="form-control"
                    value="{{ $service_settings->tax }}">
                  <p class="text-danger" id="err_tax"></p>
                </div>
                <div class="form-group">
                  <label for="">{{ __('Max file upload in chat box') }} ({{ __('KB') }})</label>
                  <input type="number" name="chat_max_file" step="100" class="form-control"
                    value="{{ $service_settings->chat_max_file }}">
                  <p class="text-danger" id="err_chat_max_file"></p>
                  <p class="text-warning">{{ __('Ex : 1000') }},
                    <strong>{{ __('Note:') }}</strong>{{ __('1000=1MB') }}
                  </p>
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
