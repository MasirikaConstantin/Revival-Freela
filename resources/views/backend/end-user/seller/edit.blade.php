@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Seller') }}</h4>
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
        <a href="#">{{ __('Seller Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.seller_management.registered_seller') }}">{{ __('Registered Sellers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Seller') }}</a>
      </li>
    </ul>
    <a href="{{ route('admin.seller_management.registered_seller') }}"
      class="btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Seller') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <form id="ajaxEditForm"
                action="{{ route('admin.seller_management.seller.update_seller', ['id' => $seller->id]) }}"
                method="post">
                @csrf
                <h2>{{ __('Details') }}</h2>
                <hr>
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
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="{{ $seller->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" value="{{ $seller->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Recipient Mail*') }}</label>
                      <input type="text" value="{{ $seller->recipient_mail }}" class="form-control"
                        name="recipient_mail">
                      <p id="editErr_recipient_mail" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $seller->phone }}" class="form-control" name="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="row">
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
                            <label class="custom-control-label"
                              for="show_contact_form">{{ __('Show Contact Form') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div id="accordion" class="mt-5">
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

                          @php
                            $sellerInfo = App\Models\SellerInfo::where('seller_id', $seller->id)
                                ->where('language_id', $language->id)
                                ->first();
                          @endphp

                          <div id="collapse{{ $language->id }}"
                            class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                            <div class="version-body">
                              <div class="row">
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Name*') }}</label>
                                    <input type="text" value="{{ !empty($sellerInfo) ? $sellerInfo->name : '' }}"
                                      class="form-control" name="{{ $language->code }}_name"
                                      placeholder="{{ __('Enter Name') }}">
                                    <p id="editErr_{{ $language->code }}_name" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    @php
                                      $skills = App\Models\Skill::where([['language_id', $language->id], ['status', 1]])->get();
                                      if ($sellerInfo) {
                                          if (!is_null($sellerInfo->skills)) {
                                              $selected_skills = json_decode($sellerInfo->skills);
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
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text" value="{{ !empty($sellerInfo) ? $sellerInfo->country : '' }}"
                                      class="form-control" name="{{ $language->code }}_country"
                                      placeholder="{{ __('Enter Country') }}">
                                    <p id="editErr_{{ $language->code }}_country" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" value="{{ !empty($sellerInfo) ? $sellerInfo->city : '' }}"
                                      class="form-control" name="{{ $language->code }}_city"
                                      placeholder="{{ __('Enter City') }}">
                                    <p id="editErr_{{ $language->code }}_city" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" value="{{ !empty($sellerInfo) ? $sellerInfo->state : '' }}"
                                      class="form-control" name="{{ $language->code }}_state"
                                      placeholder="{{ __('Enter State') }}">
                                    <p id="editErr_{{ $language->code }}_state" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text"
                                      value="{{ !empty($sellerInfo) ? $sellerInfo->zip_code : '' }}"
                                      class="form-control" name="{{ $language->code }}_zip_code"
                                      placeholder="{{ __('Enter Zip Code') }}">
                                    <p id="editErr_{{ $language->code }}_zip_code" class="mt-1 mb-0 text-danger em">
                                    </p>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="{{ $language->code }}_address" class="form-control" placeholder="{{ __('Enter Address') }}">{{ !empty($sellerInfo) ? $sellerInfo->address : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_email" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" class="form-control" rows="5"
                                      placeholder="{{ __('Enter Details') }}">{{ !empty($sellerInfo) ? $sellerInfo->details : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_details" class="mt-1 mb-0 text-danger em"></p>
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
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <h2 class="mt-3 text-warning">{{ __('Seller Balance') . ' : ' }}
                {{ $seller->amount == null ? 0.0 : symbolPrice($seller->amount) }}</h2>
              <hr>
              <form id="ajaxEditForm2"
                action="{{ route('admin.seller_management.seller.update_seller_balance', ['id' => $seller->id]) }}"
                method="post">
                @csrf
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Seller Balance') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="amount_status" value="1" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Add') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="amount_status" value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Subtract') }}</span>
                        </label>
                      </div>
                      <p id="editErr_amount_status" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Amount') }} ({{ $settings->base_currency_symbol }}) *</label>
                      <input type="number" name="amount" class="form-control">
                      <p id="editErr_amount" class="mt-1 mb-0 text-danger em"></p>
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
              <button type="submit" id="updateBtn2" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
