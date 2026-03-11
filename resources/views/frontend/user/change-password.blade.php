@extends('frontend.layout')

@php $title = __('Change Password'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!-- Start Change Password Section -->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details mb-40">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Change Your Password') }}</h4>
                  </div>

                  <div class="edit-info-area">
                    <form action="{{ route('user.update_password') }}" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-12 mb-4">
                          <input type="password" class="form-control" placeholder="{{ __('Current Password') }}" name="current_password">
                          @error('current_password')
                            <p class="text-danger mt-1">{{ $message }}</p>
                          @enderror
                        </div>

                        <div class="col-12 mb-4">
                          <input type="password" class="form-control" placeholder="{{ __('New Password') }}" name="new_password">
                          @error('new_password')
                            <p class="text-danger mt-1">{{ $message }}</p>
                          @enderror
                        </div>

                        <div class="col-12 mb-4">
                          <input type="password" class="form-control" placeholder="{{ __('Confirm New Password') }}" name="new_password_confirmation">
                          @error('new_password_confirmation')
                            <p class="text-danger mt-1">{{ $message }}</p>
                          @enderror
                        </div>

                        <div class="col-12">
                          <div class="form-button">
                            <button class="btn btn-md btn-primary radius-sm">{{ __('Submit') }}</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Change Password Section -->
@endsection
