@extends('seller.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('seller.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Input Field') }}</h4>
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
        <a href="#">{{ __('Service Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('seller.service_management.forms', ['language' => $defaultLang->code]) }}">{{ __('Forms') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Input Field') }}</a>
      </li>
    </ul>
  </div>

  <div class="row" id="app">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Edit Input Field') }}</div>
            </div>

            <div class="col-lg-2">
              <a href="{{ route('seller.service_management.form.input', ['id' => request()->route('form_id'), 'language' => request()->input('language')]) }}"
                class="btn btn-info btn-sm float-right">
                <span class="btn-label">
                  <i class="fas fa-backward mdb_12"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-lg-6">
              <form id="ajaxEditForm"
                action="{{ route('seller.service_management.form.update_input', ['id' => $inputField->id]) }}"
                method="POST">
                @csrf
                <input type="hidden" name="form_id" value="{{ request()->route('form_id') }}">

                <input type="hidden" name="type" value="{{ $inputField->type }}">

                <div class="form-group">
                  <label>{{ __('Required Status') . '*' }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="is_required" value="1" class="selectgroup-input"
                        @if ($inputField->is_required == 1) checked @endif>
                      <span class="selectgroup-button">{{ __('Yes') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="is_required" value="0" class="selectgroup-input"
                        @if ($inputField->is_required == 0) checked @endif>
                      <span class="selectgroup-button">{{ __('No') }}</span>
                    </label>
                  </div>
                  <p class="mt-1 mb-0 text-danger em" id="editErr_is_required"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('Label') . '*' }}</label>
                  <input type="text" class="form-control" name="label" placeholder="Enter Input Label"
                    value="{{ $inputField->label }}">
                  <p class="mt-2 mb-0 text-danger em" id="editErr_label"></p>
                </div>

                @if ($inputField->type != 4 && $inputField->type != 8)
                  <div class="form-group">
                    <label>{{ __('Placeholder') . '*' }}</label>
                    <input type="text" class="form-control" name="placeholder" placeholder="Enter Input Placeholder"
                      value="{{ $inputField->placeholder }}">
                    <p class="mt-2 mb-0 text-danger em" id="editErr_placeholder"></p>
                  </div>
                @endif

                @if ($inputField->type == 3 || $inputField->type == 4)
                  <div class="form-group">
                    <label>{{ __('Options') . '*' }}</label><br>
                    <button class="btn btn-sm btn-success" type="button" v-on:click="addOpt()">
                      {{ __('Add Option') }}
                    </button>
                    <p class="mt-2 mb-0 text-danger em" id="editErr_options"></p>
                  </div>

                  <div class="row no-gutters" v-for="(option, index) in optionsArray" v-bind:key="index">
                    <div class="col-lg-10">
                      <div class="form-group">
                        <input type="text" class="form-control" name="options[]" placeholder="Enter Option"
                          v-bind:value="option">
                      </div>
                    </div>

                    <div class="col-lg-2">
                      <button type="button" class="btn btn-danger btn-sm mt-3" v-on:click="rmvOpt(index)">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                @endif

                @if ($inputField->type == 8)
                  <div class="form-group">
                    <label>{{ __('Maximum Size of Uploaded File') . '*' }}</label>
                    <div class="input-group">
                      <input type="number" step="0.01" class="form-control ltr" name="file_size"
                        placeholder="Enter File Size" value="{{ $inputField->file_size }}">
                      <div class="input-group-append">
                        <span class="input-group-text">{{ __('MB') }}</span>
                      </div>
                    </div>
                    <p class="mt-2 mb-0 text-danger em" id="editErr_file_size"></p>
                  </div>
                @endif
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button id="updateBtn" type="button" class="btn btn-primary">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    let optArr = {!! json_encode($options) !!};
  </script>

  {{-- vue js --}}
  <script type="text/javascript" src="{{ asset('assets/js/vue-js.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/form-input.js') }}"></script>
@endsection
