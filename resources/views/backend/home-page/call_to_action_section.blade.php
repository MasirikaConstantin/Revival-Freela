@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Call to action section') }}</h4>
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
        <a href="#">{{ __('Call to action section') }}</a>
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
            <div class="col-lg-6 offset-lg-3">
              <form id="imgForm" action="{{ route('admin.home_page.update_calltoactionsection') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Background Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (empty($bgImg))
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-background-img">
                    @else
                      <img src="{{ asset('assets/img/' . $bgImg) }}" alt="image" class="uploaded-background-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="background-img-input" name="cta_bg_img">
                    </div>
                  </div>
                  @error('cta_bg_img')
                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="imgForm" class="btn btn-success">
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
              <div class="card-title">{{ __('Call to action Section Information') }}</div>
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
                action="{{ route('admin.home_page.update_calltoactionsection_info', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <label for="">{{ __('Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (!empty($data))
                      <img src="{{ asset('assets/img/'.$data->image) }}" alt="..." class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="image">
                    </div>
                  </div>
                  @error('image')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Title') . '*' }}</label>
                  <input type="text" class="form-control" name="title"
                    value="{{ empty($data) ? '' : $data->title }}" placeholder="Enter Title">
                  @error('title')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Button Name') }}</label>
                      <input type="text" class="form-control" name="button_text" placeholder="Enter Button Name"
                        value="{{ empty($data) ? '' : $data->button_text }}">
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
