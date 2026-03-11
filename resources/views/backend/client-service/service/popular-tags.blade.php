@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Popular Tags') }}</h4>
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
        <a href="#">{{ __('Popular Tags') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Popular Tags') }}</div>
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
              <form id="ajaxForm" action="{{ route('admin.service_management.popular_tags.update') }}" method="POST">
                @csrf

                <div class="form-group">
                  <label for="">{{ __('Language **') }}</label>
                  @if (!empty($langs))
                    <select name="language_id" class="form-control"
                      onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                      <option value="" selected disabled>{{ __('Select a Language') }}</option>
                      @foreach ($langs as $lang)
                        <option value="{{ $lang->code }}"
                          {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                          {{ $lang->name }}</option>
                      @endforeach
                    </select>
                  @endif
                  <p id="err_language_id" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Popular Tags **') }}</label>
                  <input type="text" class="form-control" name="popular_tags" value="{{ $data->popular_tags }}"
                    data-role="tagsinput" placeholder="Popular Tags">
                  <p id="err_popular_tags" class="mb-0 text-danger em"></p>
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
