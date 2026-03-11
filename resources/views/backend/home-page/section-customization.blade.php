@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Section Customization') }}</h4>
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
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Section Customization') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.home_page.update_section_status') }}" method="POST">
          @csrf
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Home Page Sections') }}</div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                @if ($settings->theme_version == 1)
                  <div class="form-group">
                    <label>{{ __('Service Category Section Status') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="service_category_section_status" value="1"
                          class="selectgroup-input"
                          {{ $sectionInfo->service_category_section_status == 1 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="service_category_section_status" value="0"
                          class="selectgroup-input"
                          {{ $sectionInfo->service_category_section_status == 0 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                      </label>
                    </div>
                  </div>
                @endif
                <div class="form-group">
                  <label>{{ __('About Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="about_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->about_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="about_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->about_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>


                <div class="form-group">
                  <label>{{ __('Features Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="features_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->features_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="features_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->features_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Featured Services Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="featured_services_section_status" value="1"
                        class="selectgroup-input"
                        {{ $sectionInfo->featured_services_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="featured_services_section_status" value="0"
                        class="selectgroup-input"
                        {{ $sectionInfo->featured_services_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Testimonials Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="testimonials_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->testimonials_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="testimonials_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->testimonials_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Blog Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="blog_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->blog_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="blog_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->blog_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Partners Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="partners_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->partners_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="partners_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->partners_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>

                @if ($settings->theme_version != 1)
                  <div class="form-group">
                    <label>{{ __('Newsletter Section Status') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="newsletter_section_status" value="1"
                          class="selectgroup-input" {{ $sectionInfo->newsletter_section_status == 1 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                      </label>

                      <label class="selectgroup-item">
                        <input type="radio" name="newsletter_section_status" value="0"
                          class="selectgroup-input" {{ $sectionInfo->newsletter_section_status == 0 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                      </label>
                    </div>
                  </div>
                @endif

                <div class="form-group">
                  <label>{{ __('Call to action Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="cta_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->cta_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="cta_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->cta_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Footer Section Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="footer_section_status" value="1" class="selectgroup-input"
                        {{ $sectionInfo->footer_section_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="footer_section_status" value="0" class="selectgroup-input"
                        {{ $sectionInfo->footer_section_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
