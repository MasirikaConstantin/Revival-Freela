@extends('backend.layout')

@php
  use App\Models\Language;
  $selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <style>
      form:not(.modal-form) input,
      form:not(.modal-form) textarea,
      form:not(.modal-form) select,
      select[name='language'] {
        direction: rtl;
      }

      form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
      }
    </style>
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Packages') }}</h4>
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
        <a href="#">{{ __('Packages') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Package Page') }}</div>
            </div>
            <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i>
                {{ __('Add Package') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('admin.package.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($packages) == 0)
                <h3 class="text-center">{{ __('NO PACKAGE FOUND YET') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Cost') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($packages as $key => $package)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $package->id }}">
                          </td>
                          <td>
                            <strong>{{ strlen($package->title) > 30 ? mb_substr($package->title, 0, 30, 'UTF-8') . '...' : $package->title }}</strong>
                            @if ($package->term == 'monthly')
                              <small class="badge badge-primary">{{ __('Monthly') }}</small>
                            @elseif ($package->term == 'yearly')
                              <small class="badge badge-info">{{ __('Yearly') }}</small>
                            @elseif ($package->term == 'lifetime')
                              <small class="badge badge-secondary">{{ __('Lifetime') }}</small>
                            @endif


                          </td>
                          <td>
                            @if ($package->price == 0)
                              {{ __('Free') }}
                            @else
                              {{ format_price($package->price) }}
                            @endif

                          </td>
                          <td>
                            @if ($package->status == 1)
                              <h2 class="d-inline-block">
                                <span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block">
                                <span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mt-1"
                              href="{{ route('admin.package.edit', $package->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="packageDeleteForm d-inline-block" action="{{ route('admin.package.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="package_id" value="{{ $package->id }}">
                              <button type="submit" class="btn btn-danger btn-sm  mt-1 packageDeleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Create Blog Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Package') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
            action="{{ route('admin.package.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title">{{ __('Package title') }}*</label>
                  <input id="title" type="text" class="form-control" name="title"
                    placeholder="{{ __('Enter Package title') }}" value="">
                  <p id="err_title" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="price">{{ __('Price') }} ({{ $settings->base_currency_text }})*</label>
                  <input id="price" type="number" class="form-control" name="price"
                    placeholder="{{ __('Enter Package price') }}" value="">
                  <p class="text-warning">
                    <small>{{ __('If price is 0 , than it will appear as free') }}</small>
                  </p>
                  <p id="err_price" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="term">{{ __('Package term') }}*</label>
                  <select id="term" name="term" class="form-control" required>
                    <option value="" selected disabled>{{ __('Choose a Package term') }}</option>
                    <option value="monthly">{{ __('monthly') }}</option>
                    <option value="yearly">{{ __('yearly') }}</option>
                    <option value="lifetime">{{ __('lifetime') }}</option>
                  </select>
                  <p id="err_term" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">{{ __('Number of services') }} *</label>
                  <input type="number" class="form-control" name="number_of_service_add"
                    placeholder="{{ __('Enter number of services') }}">
                  <p id="err_number_of_service_add" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">{{ __('Number of featured services') }} *</label>
                  <input type="number" name="number_of_service_featured" class="form-control"
                    placeholder="{{ __('Enter number of featured services') }}">
                  <p id="err_number_of_service_featured" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">{{ __('Number of forms') }} *</label>
                  <input type="number" name="number_of_form_add" class="form-control"
                    placeholder="{{ __('Enter number of forms') }}">
                  <p id="err_number_of_form_add" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">{{ __('Number of service orders') }} *</label>
                  <input type="number" name="number_of_service_order" class="form-control"
                    placeholder="{{ __('Enter number of service orders') }}">
                  <p id="err_number_of_service_order" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status">{{ __('Live Chat') }}*</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="live_chat_status" value="1" class="selectgroup-input"
                        checked="">
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="live_chat_status" value="0" class="selectgroup-input">
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
                      <input type="radio" name="qr_builder_status" value="1"
                        class="selectgroup-input qr_builder_status" checked="">
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="qr_builder_status" value="0"
                        class="selectgroup-input qr_builder_status">
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                  <p id="err_qr_builder_status" class="mb-0 text-danger em"></p>
                </div>
              </div>

              <div class="col-md-6" id="qr_code_save_limit">
                <div class="form-group">
                  <label for="status">{{ __('QR Code Save Limit') }}*</label>
                  <input type="number" name="qr_code_save_limit" class="form-control">
                  <p id="err_qr_code_save_limit" class="mb-0 text-danger em"></p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="status">{{ __('Recommended') }}*</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="recommended" value="1" class="selectgroup-input"
                        checked="">
                      <span class="selectgroup-button">{{ __('Yes') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="recommended" value="0" class="selectgroup-input recommended">
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
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Deactive') }}</option>
                  </select>
                  <p id="err_status" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>{{ __('Custom Feature') }}</label>
                  <textarea name="custom_features" class="form-control"></textarea>
                  <p id="err_custom_features" class="mb-0 text-danger em"></p>
                  <p class="text-warning">{{ __('Each new line will be shown as a new feature in the pricing plan') }}
                  </p>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="{{ asset('assets/js/packages.js') }}"></script>
@endsection
