@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Service') }}</h4>
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
        <a
          href="{{ route('admin.service_management.services', ['language' => $defaultLang->code]) }}">{{ __('Services') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Service') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Service') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.service_management.services', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward mdb_12"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 mdb_display_none" id="serviceErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <div class="mdb_353">
                <label for=""><strong>{{ __('Slider Images') . '*' }}</strong></label>
                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                  @csrf
                  <div class="fallback"></div>
                </form>
                <p class="text-warning mt-3 mb-0">
                  {{ '*' . __('Upload 860x610 pixel size image for best quality.') }}</p>
                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
              </div>

              <form id="serviceForm" action="{{ route('admin.service_management.store_service') }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div id="slider-image-id"></div>

                <div class="form-group">
                  <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="thumbnail_image">
                    </div>
                  </div>
                  <p class="text-warning">{{ __('Image size : 330 x 255 px') }}</p>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Video Preview Link') }}</label>
                      <input type="url" class="form-control" name="video_preview_link"
                        placeholder="Enter Video Preview Link">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Live Demo Link') }}</label>
                      <input type="url" class="form-control" name="live_demo_link" placeholder="Enter Live Demo Link">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Quote Button Status') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="quote_btn_status" value="1" class="selectgroup-input" checked>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="quote_btn_status" value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Service Status') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="service_status" value="1" class="selectgroup-input" checked>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="service_status" value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Seller') }}</label>
                      <select name="seller_id" id="seller_id_service" class="select2">
                        <option value="0">{{ __('Select Seller') }}</option>
                        @foreach ($sellers as $seller)
                          <option value="{{ $seller->id }}">{{ $seller->username }}</option>
                        @endforeach
                      </select>
                      <p class="text-warning">{{ __("leave it blank for admin's service") }}</p>
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button"
                            class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                            data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }}
                            {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Service Title">
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="form-group">
                                @php
                                  $skills = App\Models\Skill::where([['language_id', $language->id], ['status', 1]])->get();
                                @endphp
                                <label>{{ __('Skills') }}</label>
                                <select name="{{ $language->code }}_skills[]" multiple id="" class="select2">
                                  @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>


                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php $categories = $language->categories; @endphp

                                <label>{{ __('Category') . '*' }}</label>
                                <select name="{{ $language->code }}_category_id" class="form-control service-category"
                                  data-lang_code="{{ $language->code }}">
                                  <option selected disabled>{{ __('Select a Category') }}
                                  </option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                      {{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Subcategory') . '*' }}</label>
                                <select name="{{ $language->code }}_subcategory_id" class="form-control" disabled>
                                  <option selected disabled>
                                    {{ __('Select a Subcategory') }}</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote"
                                  name="{{ $language->code }}_description" placeholder="Enter Service Description" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Tags') }}</label>
                                <input class="form-control" name="{{ $language->code }}_tags" placeholder="Enter Tags"
                                  data-role="tagsinput">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $forms = App\Models\ClientService\Form::where([['seller_id', null], ['language_id', $language->id]])->get();
                                @endphp

                                <label>{{ __('Form') . '*' }}</label>
                                <select name="{{ $language->code }}_form_id" class="form-control seller_form"
                                  data-lang_id="{{ $language->id }}" id="seller_form{{ $language->id }}">
                                  <option selected disabled>{{ __('Select a Form') }}
                                  </option>

                                  @foreach ($forms as $form)
                                    <option value="{{ $form->id }}">
                                      {{ $form->name }}</option>
                                  @endforeach
                                </select>

                                <p class="mt-2 mb-0 text-warning">
                                  {{ '*' . __('The selected form will be used during the purchase of this service.') }}
                                </p>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp

                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="serviceForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    const imgUpUrl = "{{ route('admin.service_management.upload_slider_image') }}";
    const imgRmvUrl = "{{ route('admin.service_management.remove_slider_image') }}";
    var form_get_url = "{{ route('admin.service_management.get-form-by-vendor') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/slider-image.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
@endsection
