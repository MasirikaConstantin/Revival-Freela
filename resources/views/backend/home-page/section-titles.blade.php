@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Section Titles') }}</h4>
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
        <a href="#">{{ __('Section Titles') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.home_page.update_section_titles', ['language' => request()->input('language')]) }}"
          method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Section Titles') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label>{{ __('Category Section Title') }}</label>
                  <input class="form-control" name="category_section_title"
                    value="{{ !is_null($data) ? $data->category_section_title : '' }}"
                    placeholder="Enter Category Section Title">
                </div>

                <div class="form-group">
                  <label>{{ __('Featured Services Section Title') }}</label>
                  <input class="form-control" name="featured_services_section_title"
                    value="{{ !is_null($data) ? $data->featured_services_section_title : '' }}"
                    placeholder="Enter Featured Services Section Title">
                </div>

                <div class="form-group">
                  <label>{{ __('Testimonials Section Title') }}</label>
                  <input class="form-control" name="testimonials_section_title"
                    value="{{ !is_null($data) ? $data->testimonials_section_title : '' }}"
                    placeholder="Enter Testimonials Section Title">
                </div>

                <div class="form-group">
                  <label>{{ __('Blog Section Title') }}</label>
                  <input class="form-control" name="blog_section_title"
                    value="{{ !is_null($data) ? $data->blog_section_title : '' }}"
                    placeholder="Enter Blog Section Title">
                </div>

                @if ($settings->theme_version == 2)
                  <div class="form-group">
                    <label>{{ __('Featured Products Section Title') }}</label>
                    <input class="form-control" name="featured_products_section_title"
                      value="{{ !is_null($data) ? $data->featured_products_section_title : '' }}"
                      placeholder="Enter Featured Products Section Title">
                  </div>
                @endif
                @if ($settings->theme_version == 2 || $settings->theme_version == 3)
                  <div class="form-group">
                    <label>{{ __('Newsletter Section Title') }}</label>
                    <input class="form-control" name="newsletter_section_title"
                      value="{{ !is_null($data) ? $data->newsletter_section_title : '' }}"
                      placeholder="Enter Newsletter Section Title">
                  </div>
                @endif
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
