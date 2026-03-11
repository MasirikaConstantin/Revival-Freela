@extends('seller.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('seller.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Form Inputs') }}</h4>
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
        <a href="#">{{ $form->name }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Input Fields') }}</a>
      </li>
    </ul>
  </div>

  <div class="row" id="app">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col">
              <div class="card-title d-inline-block">{{ __('Form Input Fields') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              <p class="text-warning">
                {{ '*' . __('Do not create') }} <span class="text-info">{{ __('Name, Email & Phone Number') }}</span>
                {{ __('input field, it will be in the form by default.') }}<br>
                {{ '*' . __('Drag & drop to sort the input fields of this form.') }}
              </p>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="ui-state-default ui-state-disabled">
                <div class="form-group">
                  <label>{{ __('Name') . '*' }}</label>
                  <div class="row">
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="name"
                        placeholder="{{ __('Enter Your Full Name') }}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="ui-state-default ui-state-disabled">
                <div class="form-group">
                  <label>{{ __('Email Address') . '*' }}</label>
                  <div class="row">
                    <div class="col-lg-9">
                      <input type="email" class="form-control" name="email_address"
                        placeholder="{{ __('Enter Your Email Address') }}">
                    </div>
                  </div>
                </div>
              </div>

              @if (count($inputFields) > 0)
                <div id="sort-content">
                  @foreach ($inputFields as $inputField)
                    @if ($inputField->type == 1)
                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <input type="text" class="form-control" name="{{ $inputField->name }}"
                                placeholder="{{ $inputField->placeholder }}">
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white "></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @elseif ($inputField->type == 2)
                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <input type="number" class="form-control" name="{{ $inputField->name }}"
                                placeholder="{{ $inputField->placeholder }}">
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white "></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @elseif ($inputField->type == 3)
                      @php $options = json_decode($inputField->options); @endphp

                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <select class="form-control" name="{{ $inputField->name }}">
                                <option selected disabled>{{ $inputField->placeholder }}</option>

                                @foreach ($options as $option)
                                  <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                              </select>
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white "></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @elseif ($inputField->type == 4)
                      @php $options = json_decode($inputField->options); @endphp

                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              @foreach ($options as $option)
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" id="{{ 'option-' . $loop->iteration }}" name="values[]"
                                    class="custom-control-input">
                                  <label class="custom-control-label"
                                    for="{{ 'option-' . $loop->iteration }}">{{ $option }}</label>
                                </div>
                              @endforeach
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white "></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @elseif ($inputField->type == 5)
                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <textarea class="form-control" name="{{ $inputField->name }}" rows="5" cols="50"
                                placeholder="{{ $inputField->placeholder }}"></textarea>
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white "></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @elseif ($inputField->type == 6)
                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <input type="text" class="form-control datepicker" name="{{ $inputField->name }}"
                                placeholder="{{ $inputField->placeholder }}" readonly autocomplete="off">
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white mdb_23423"></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @elseif ($inputField->type == 7)
                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <input type="text" class="form-control timepicker" name="{{ $inputField->name }}"
                                placeholder="{{ $inputField->placeholder }}" readonly autocomplete="off">
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white mdb_23423"></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @else
                      <form class="ui-state-default"
                        action="{{ route('seller.service_management.form.delete_input', ['id' => $inputField->id]) }}"
                        method="POST" data-id="{{ $inputField->id }}">
                        @csrf
                        <div class="form-group">
                          <label>
                            {{ $inputField->label }}{{ $inputField->is_required == 1 ? '*' : '' }}
                            ({{ 'Maximum Size' . ' ' . $inputField->file_size . ' ' . 'MB' }})
                          </label>
                          <div class="row no-gutters">
                            <div class="col-lg-9 mr-3">
                              <input type="file" name="{{ $inputField->name }}" class="ltr">
                            </div>

                            <div class="col-lg-1">
                              <a href="{{ route('seller.service_management.form.edit_input', ['form_id' => $form->id, 'input_id' => $inputField->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-sm btn-secondary mt-1">
                                <i class="fas fa-edit text-white mdb_23423"></i>
                              </a>
                            </div>

                            <div class="col-lg-1">
                              <button type="submit" class="btn btn-sm btn-danger mt-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    @endif
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-9">
              <div class="card-title d-inline-block">{{ __('Create Input Field') }}</div>
            </div>

            <div class="col-lg-3">
              <a href="{{ route('seller.service_management.forms', ['language' => request()->input('language')]) }}"
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
          <div class="row">
            <div class="col">
              <form id="ajaxForm"
                action="{{ route('seller.service_management.form.store_input', ['id' => $form->id]) }}" method="POST">
                @csrf

                <div class="form-group">
                  <label>{{ __('Input Field Type') . '*' }}</label><br>
                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="1" id="input-type-1"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-1">{{ __('Text Field') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="2" id="input-type-2"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-2">{{ __('Number Field') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="3" id="input-type-3"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-3">{{ __('Select') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="4" id="input-type-4"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-4">{{ __('Checkbox') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="5" id="input-type-5"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-5">{{ __('Textarea') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="6" id="input-type-6"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-6">{{ __('Datepicker') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="7" id="input-type-7"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-7">{{ __('Timepicker') }}</label>
                  </div>

                  <div class="form-check form-check-inline pl-0">
                    <input type="radio" class="form-check-input" name="type" value="8" id="input-type-8"
                      v-model="inputType" v-on:change="changeInputType()">
                    <label class="form-check-label mt-2" for="input-type-8">{{ __('File') }}</label>
                  </div>
                  <p class="mb-0 text-danger em" id="err_type"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('Required Status') . '*' }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="is_required" value="1" class="selectgroup-input" checked>
                      <span class="selectgroup-button">{{ __('Yes') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="is_required" value="0" class="selectgroup-input">
                      <span class="selectgroup-button">{{ __('No') }}</span>
                    </label>
                  </div>
                  <p class="mt-1 mb-0 text-danger em" id="err_is_required"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('Label') . '*' }}</label>
                  <input type="text" class="form-control" name="label" placeholder="Enter Input Label">
                  <p class="mt-2 mb-0 text-danger em" id="err_label"></p>
                </div>

                <div class="form-group" v-if="showPlaceholder">
                  <label>{{ __('Placeholder') . '*' }}</label>
                  <input type="text" class="form-control" name="placeholder" placeholder="Enter Input Placeholder">
                  <p class="mt-2 mb-0 text-danger em" id="err_placeholder"></p>
                </div>

                <div v-if="showOptionArea">
                  <div class="form-group">
                    <label>{{ __('Options') . '*' }}</label><br>
                    <button class="btn btn-sm btn-success" type="button" v-on:click="addOption()">
                      {{ __('Add Option') }}
                    </button>
                    <p class="mt-2 mb-0 text-danger em" id="err_options"></p>
                  </div>

                  <div v-if="optionCount > 0">
                    <div class="row no-gutters" v-for="n in optionCount">
                      <div class="col-lg-10">
                        <div class="form-group">
                          <input type="text" class="form-control" name="options[]" placeholder="Enter Option">
                        </div>
                      </div>

                      <div class="col-lg-2">
                        <button class="btn btn-danger btn-sm mt-3" v-on:click="removeOption()"><i
                            class="fas fa-times"></i></button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group" v-if="showFileSize">
                  <label>{{ __('Maximum Size of Uploaded File') . '*' }}</label>
                  <div class="input-group">
                    <input type="number" step="0.01" class="form-control ltr" name="file_size"
                      placeholder="Enter File Size">
                    <div class="input-group-append">
                      <span class="input-group-text">{{ __('MB') }}</span>
                    </div>
                  </div>
                  <p class="mt-2 mb-0 text-danger em" id="err_file_size"></p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="button" id="submitBtn" class="btn btn-primary">
                {{ __('Submit') }}
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
    let optArr = {!! json_encode([]) !!};

    const sortURL = "{{ route('seller.service_management.form.sort_input') }}";
  </script>

  {{-- vue js --}}
  <script type="text/javascript" src="{{ asset('assets/js/vue-js.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/form-input.js') }}"></script>
@endsection
