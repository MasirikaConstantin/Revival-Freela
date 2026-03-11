@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Service') }}</h4>
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
        <a href="#">{{ __('Edit Service') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Service') }}</div>
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

              <div class="mdb_10">
                <label for=""><strong>{{ __('Slider Images') . '*' }}</strong></label>

                @php $sliderImages = json_decode($service->slider_images); @endphp

                @if (count($sliderImages) > 0)
                  <div id="reload-slider-div">
                    <div class="row mt-2">
                      <div class="col">
                        <table class="table" id="img-table">
                          @foreach ($sliderImages as $key => $sliderImage)
                            <tr class="table-row" id="{{ 'slider-image-' . $key }}">
                              <td>
                                <img class="thumb-preview mdb_3523"
                                  src="{{ asset('assets/img/services/slider-images/' . $sliderImage) }}"
                                  alt="slider image">
                              </td>
                              <td>
                                <i class="fa fa-times-circle"
                                  onclick="rmvStoredImg({{ $service->id }}, {{ $key }})"></i>
                              </td>
                            </tr>
                          @endforeach
                        </table>
                      </div>
                    </div>
                  </div>
                @endif

                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                  @csrf
                  <div class="fallback"></div>
                </form>
                <p class="em text-warning mt-3 mb-0">
                  {{ '*' . __('Upload 860x610 pixel size image for best quality.') }}</p>
                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
              </div>

              <form id="serviceForm"
                action="{{ route('admin.service_management.update_service', ['id' => $service->id]) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                <div id="slider-image-id"></div>

                <div class="form-group">
                  <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/services/thumbnail-images/' . $service->thumbnail_image) }}"
                      alt="image" class="uploaded-img">
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
                        placeholder="Enter Video Preview Link" value="{{ $service->video_preview_link }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Live Demo Link') }}</label>
                      <input type="url" class="form-control" name="live_demo_link" placeholder="Enter Live Demo Link"
                        value="{{ $service->live_demo_link }}">
                    </div>
                  </div>
                </div>

                <div class="row">

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Quote Button Status') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="quote_btn_status" value="1" class="selectgroup-input"
                            {{ $service->quote_btn_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="quote_btn_status" value="0" class="selectgroup-input"
                            {{ $service->quote_btn_status == 0 ? 'checked' : '' }}>
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
                          <input type="radio" name="service_status" value="1" class="selectgroup-input"
                            {{ $service->service_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="service_status" value="0" class="selectgroup-input"
                            {{ $service->service_status == 0 ? 'checked' : '' }}>
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
                          <option @selected($seller->id == $service->seller_id) value="{{ $seller->id }}">{{ $seller->username }}
                          </option>
                        @endforeach
                      </select>
                      <p class="text-warning">{{ __("leave it blank for admin's service") }}</p>
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    @php $serviceData = $language->serviceData; @endphp

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
                                  placeholder="Enter Service Title"
                                  value="{{ is_null($serviceData) ? '' : $serviceData->title }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $skills = App\Models\Skill::where([['language_id', $language->id], ['status', 1]])->get();
                                  if (!empty($serviceData)) {
                                      if (!is_null($serviceData->skills)) {
                                          $selected_skills = json_decode($serviceData->skills);
                                      } else {
                                          $selected_skills = [];
                                      }
                                  } else {
                                      $selected_skills = [];
                                  }
                                  if (is_null($selected_skills)) {
                                      $selected_skills = [];
                                  }
                                @endphp
                                <label>{{ __('Skills') }}</label>
                                <select name="{{ $language->code }}_skills[]" class="select2" multiple>
                                  @if (is_null($skills))
                                    <option selected disabled>
                                      {{ __('Select Skills') }}</option>
                                  @else
                                    <option disabled>{{ __('Select Skills') }}
                                    </option>

                                    @foreach ($skills as $skill)
                                      <option value="{{ $skill->id }}" @selected(in_array($skill->id, $selected_skills))>
                                        {{ $skill->name }}
                                      </option>
                                    @endforeach
                                  @endif
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
                                  @if (is_null($categories))
                                    <option selected disabled>
                                      {{ __('Select a Category') }}</option>
                                  @else
                                    <option disabled>{{ __('Select a Category') }}
                                    </option>

                                    @foreach ($categories as $category)
                                      <option value="{{ $category->id }}"
                                        {{ !empty($serviceData) && $serviceData->service_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                      </option>
                                    @endforeach
                                  @endif
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  if (!is_null($serviceData)) {
                                      $categoryId = $serviceData->service_category_id;
                                      $category = \App\Models\ClientService\ServiceCategory::query()->find($categoryId);
                                      $subcategories = $category
                                          ->subcategory()
                                          ->where('status', 1)
                                          ->orderByDesc('id')
                                          ->get();
                                  }
                                @endphp

                                <label>{{ __('Subcategory') . '*' }}</label>
                                <select name="{{ $language->code }}_subcategory_id" class="form-control">
                                  @if (is_null($subcategories))
                                    <option selected disabled>
                                      {{ __('Select a Subcategory') }}</option>
                                  @else
                                    <option disabled>{{ __('Select a Subcategory') }}
                                    </option>

                                    @foreach ($subcategories as $subcategory)
                                      <option value="{{ $subcategory->id }}"
                                        {{ !empty($serviceData) && $serviceData->service_subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                      </option>
                                    @endforeach
                                  @endif
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea class="form-control summernote" name="{{ $language->code }}_description"
                                  placeholder="Enter Service Description" data-height="300">{{ is_null($serviceData) ? '' : replaceBaseUrl($serviceData->description, 'summernote') }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Tags') }}</label>
                                <input class="form-control" name="{{ $language->code }}_tags" placeholder="Enter Tags"
                                  data-role="tagsinput" value="{{ is_null($serviceData) ? '' : $serviceData->tags }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  if ($service->seller_id == 0) {
                                      $s_seller_id = null;
                                  } else {
                                      $s_seller_id = $service->seller_id;
                                  }
                                  $forms = App\Models\ClientService\Form::where([['seller_id', $s_seller_id], ['language_id', $language->id]])->get();
                                @endphp

                                <label>{{ __('Form') . '*' }}</label>
                                <select name="{{ $language->code }}_form_id" class="form-control seller_form"
                                  data-lang_id="{{ $language->id }}" id="seller_form{{ $language->id }}">
                                  @if (is_null($forms))
                                    <option selected disabled>{{ __('Select a Form') }}
                                    </option>
                                  @else
                                    <option disabled>{{ __('Select a Form') }}</option>

                                    @foreach ($forms as $form)
                                      <option value="{{ $form->id }}"
                                        {{ !empty($serviceData) && $serviceData->form_id == $form->id ? 'selected' : '' }}>
                                        {{ $form->name }}
                                      </option>
                                    @endforeach
                                  @endif
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
                                  placeholder="Enter Meta Keywords" data-role="tagsinput"
                                  value="{{ is_null($serviceData) ? '' : $serviceData->meta_keywords }}">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description">{{ is_null($serviceData) ? '' : $serviceData->meta_description }}</textarea>
                              </div>
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
  <script>
    const imgUpUrl = "{{ route('admin.service_management.upload_slider_image') }}";
    const imgRmvUrl = "{{ route('admin.service_management.remove_slider_image') }}";
    const imgDetachUrl = "{{ route('admin.service_management.detach_slider_image') }}";
    var form_get_url = "{{ route('admin.service_management.get-form-by-vendor') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/slider-image.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
@endsection
