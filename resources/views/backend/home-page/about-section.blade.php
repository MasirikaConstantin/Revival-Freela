@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('About Section') }}</h4>
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
        <a href="#">{{ __('About Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col">
              <div class="card-title">{{ __('Section Image') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="aboutImgForm" action="{{ route('admin.home_page.update_about_img') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Background Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (empty($info->about_section_image))
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/' . $info->about_section_image) }}" alt="image"
                        class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="about_section_image">
                    </div>
                  </div>
                  @error('about_section_image')
                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>


                <div class="form-group">
                  <label for="">{{ __('Video Link') }}</label>
                  <input type="url" class="form-control ltr" name="about_section_video_link"
                    value="{{ empty($info->about_section_video_link) ? '' : $info->about_section_video_link }}"
                    placeholder="Enter Video Link">
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="aboutImgForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-9">
              <div class="card-title">{{ __('About Section Information') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">
            <div class="col-lg-12">
              <form id="aboutForm"
                action="{{ route('admin.home_page.update_about_info', ['language' => request()->input('language')]) }}"
                method="POST">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Title') }}</label>
                  <input type="text" class="form-control" name="title"
                    value="{{ empty($data) ? '' : $data->title }}" placeholder="Enter Title">
                </div>

                <div class="form-group">
                  <label for="">{{ __('Text') }}</label>
                  <textarea class="form-control summernote" name="text" placeholder="Enter Text" data-height="300">{{ empty($data) ? '' : $data->text }}</textarea>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Button Name') }}</label>
                      <input type="text" class="form-control" name="button_name" placeholder="Enter Button Name"
                        value="{{ empty($data) ? '' : $data->button_name }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Button URL') }}</label>
                      <input type="url" class="form-control ltr" name="button_url" placeholder="Enter Button URL"
                        value="{{ empty($data) ? '' : $data->button_url }}">
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
              <button type="submit" form="aboutForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
