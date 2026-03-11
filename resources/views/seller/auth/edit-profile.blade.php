@extends('seller.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Seller Profile') }}</h4>
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
        <a href="#">{{ __('Profile Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Update Profile') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxEditForm" action="{{ route('seller.update_profile') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($seller->photo != null)
                          <img src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                        <p class="mt-2 mb-0 text-warning">{{ __('Image Size 100x100') }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="{{ $seller->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" value="{{ $seller->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $seller->phone }}" class="form-control" name="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" {{ $seller->show_email_addresss == 1 ? 'checked' : '' }}
                          name="show_email_addresss" class="custom-control-input" id="show_email_addresss">
                        <label class="custom-control-label"
                          for="show_email_addresss">{{ __('Show Email Address in Profile Page') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" {{ $seller->show_phone_number == 1 ? 'checked' : '' }}
                          name="show_phone_number" class="custom-control-input" id="show_phone_number">
                        <label class="custom-control-label"
                          for="show_phone_number">{{ __('Show Phone Number in Profile Page') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" {{ $seller->show_contact_form == 1 ? 'checked' : '' }}
                          name="show_contact_form" class="custom-control-input" id="show_contact_form">
                        <label class="custom-control-label" for="show_contact_form">{{ __('Show Contact Form') }}</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div id="accordion" class="mt-3">
                      @foreach ($languages as $language)
                        <div class="version">
                          <div class="version-header" id="heading{{ $language->id }}">
                            <h5 class="mb-0">
                              <button type="button" class="btn btn-link" data-toggle="collapse"
                                data-target="#collapse{{ $language->id }}"
                                aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $language->id }}">
                                {{ $language->name . __(' Language') }}
                                {{ $language->is_default == 1 ? '(Default)' : '' }}
                              </button>
                            </h5>
                          </div>

                          @php
                            $seller_info = App\Models\SellerInfo::where('seller_id', Auth::guard('seller')->user()->id)
                                ->where('language_id', $language->id)
                                ->first();
                          @endphp

                          <div id="collapse{{ $language->id }}"
                            class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                            <div class="version-body">
                              <div class="row">
                                <div class="col-lg-6">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_name"
                                      placeholder="Enter Your Full Name"
                                      value="{{ $seller_info ? $seller_info->name : '' }}">

                                    <p class="mt-2 mb-0 text-danger em" id="editErr_{{ $language->code }}_name"></p>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    @php
                                      $skills = App\Models\Skill::where([['language_id', $language->id], ['status', 1]])->get();
                                      if ($seller_info) {
                                          if (!is_null($seller_info->skills)) {
                                              $selected_skills = json_decode($seller_info->skills);
                                          } else {
                                              $selected_skills = [];
                                          }
                                      } else {
                                          $selected_skills = [];
                                      }
                                    @endphp
                                    <label>{{ __('Skills') }}</label>
                                    <select name="{{ $language->code }}_skills[]" multiple id=""
                                      class="select2">
                                      @foreach ($skills as $skill)
                                        <option value="{{ $skill->id }}" @selected(in_array($skill->id, $selected_skills))>
                                          {{ $skill->name }}</option>
                                      @endforeach
                                    </select>
                                    <p class="mt-2 mb-0 text-danger em" id="editErr_{{ $language->code }}_skills"></p>
                                  </div>
                                </div>

                                <div class="col-lg-6">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_country"
                                      value="{{ $seller_info ? $seller_info->country : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_city"
                                      value="{{ $seller_info ? $seller_info->city : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_state"
                                      value="{{ $seller_info ? $seller_info->state : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_zip_code"
                                      value="{{ $seller_info ? $seller_info->zip_code : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="{{ $language->code }}_address" class="form-control">{{ $seller_info ? $seller_info->address : '' }}</textarea>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" rows="5" class="form-control">{{ $seller_info ? $seller_info->details : '' }}</textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
