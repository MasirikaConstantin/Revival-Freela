@extends('backend.layout')

@section('style')
  <style type="text/css">
    @font-face {
      font-family: 'Lato-Regular';
      src: url('{{ asset("assets/fonts/Lato-Regular.ttf") }}');
    }

    input[type='range'] {
      cursor: pointer;
    }
  </style>
@endsection

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Generate Code') }}</h4>
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
        <a href="#">{{ __('QR Codes') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Generate Code') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col">
              <div class="card-title">{{ __('Generate QR Code') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="alert alert-info text-center" role="alert">
            <strong class="text-dark">
              {{ __('Click the mouse after giving the input in \'URL\' and \'Text\' field.') }}
            </strong>
          </div>

          <form id="qrCodeForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('URL') . '*' }}</label>
                  <input type="url" class="form-control" name="url" value="{{ $bs->qr_url }}" onchange="generateQR()">
                  <p class="mt-1 mb-0 text-warning">
                    {{ __('QR Code will be generate for this url') . '.' }}
                  </p>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Color') }}</label>
                  <input type="text" class="form-control jscolor" name="color" value="{{ $bs->qr_color }}" onchange="generateQR()">
                  <p class="mt-1 mb-0 text-warning">
                    {{ __('If the QR Code cannot be scanned, then chosse a darker color') . '.' }}
                  </p>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Size') }}</label>
                  <input type="range" class="form-control p-0" name="size" min="200" max="350" value="{{ $bs->qr_size }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_size }}</span>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('White Space') }}</label>
                  <input type="range" class="form-control p-0" name="margin" min="0" max="5" value="{{ $bs->qr_margin }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_margin }}</span>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Style') }}</label>
                  <select name="style" class="form-control" onchange="generateQR()">
                    <option value="square" {{ $bs->qr_style == 'square' ? 'selected' : '' }}>
                      {{ __('Square') }}
                    </option>
                    <option value="round" {{ $bs->qr_style == 'round' ? 'selected' : '' }}>
                      {{ __('Round') }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Eye Style') }}</label>
                  <select name="eye_style" class="form-control" onchange="generateQR()">
                    <option value="square" {{ $bs->qr_eye_style == 'square' ? 'selected' : '' }}>
                      {{ __('Square') }}
                    </option>
                    <option value="circle" {{ $bs->qr_eye_style == 'circle' ? 'selected' : '' }}>
                      {{ __('Circle') }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="form-group">
                  <label for="">{{ __('Code Type') }}</label>
                  <select name="type" class="form-control" onchange="generateQR()">
                    <option value="default" {{ $bs->qr_type == 'default' ? 'selected' : '' }}>
                      {{ __('Default') }}
                    </option>
                    <option value="image" {{ $bs->qr_type == 'image' ? 'selected' : '' }}>
                      {{ __('Image') }}
                    </option>
                    <option value="text" {{ $bs->qr_type == 'text' ? 'selected' : '' }}>
                      {{ __('Text') }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row qrcode-type" id="image-type">
              <div class="col-lg-12">
                <div class="form-group">
                  <label for="">{{ __('Image') }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (empty($bs->qr_inserted_image))
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/qr-codes/' . $bs->qr_inserted_image) }}" alt="inserted image" class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="image" onchange="generateQR()">
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="form-group">
                  <label for="">{{ __('Image Size') }}</label>
                  <input type="range" class="form-control p-0" name="image_size" min="1" max="20" value="{{ $bs->qr_inserted_image_size }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_inserted_image_size }}</span>
                  <p class="mt-1 mb-0 text-warning">
                    {{ __('If the QR Code cannot be scanned, then reduce the image size') . '.' }}
                  </p>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Image Horizontal Position') }}</label>
                  <input type="range" class="form-control p-0" name="img_x_pos" min="0" max="100" value="{{ $bs->qr_inserted_image_x }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_inserted_image_x }}</span>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Image Vertical Position') }}</label>
                  <input type="range" class="form-control p-0" name="img_y_pos" min="0" max="100" value="{{ $bs->qr_inserted_image_y }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_inserted_image_y }}</span>
                </div>
              </div>
            </div>

            <div class="row qrcode-type" id="text-type">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Text') }}</label>
                  <input type="text" class="form-control" name="text" value="{{ $bs->qr_text }}" onchange="generateQR()">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Text Color') }}</label>
                  <input type="text" class="form-control jscolor" name="text_color" value="{{ $bs->qr_text_color }}" onchange="generateQR()">
                </div>
              </div>

              <div class="col-lg-12">
                <div class="form-group">
                  <label for="">{{ __('Text Size') }}</label>
                  <input type="range" class="form-control p-0" name="text_size" min="1" max="15" value="{{ $bs->qr_text_size }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_text_size }}</span>
                  <p class="mt-1 mb-0 text-warning">
                    {{ __('If the QR Code cannot be scanned, then reduce the text size') . '.' }}
                  </p>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Text Horizontal Position') }}</label>
                  <input type="range" class="form-control p-0" name="txt_x_pos" min="0" max="100" value="{{ $bs->qr_text_x }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_text_x }}</span>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Text Vertical Position') }}</label>
                  <input type="range" class="form-control p-0" name="txt_y_pos" min="0" max="100" value="{{ $bs->qr_text_y }}" onchange="generateQR()">
                  <span class="text-info float-right">{{ $bs->qr_text_y }}</span>
                </div>
              </div>

              <span id="text-input" class="invisible"></span>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card bg-white">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title text-dark">{{ __('Preview') }}</div>
            </div>

            <div class="col-lg-8">
              <form action="{{ route('admin.qr_codes.clear') }}" method="post" class="d-inline-block float-lg-right float-left">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                  {{ __('Clear') }}
                </button>
              </form>

              <a href="#" data-toggle="modal" data-target="#saveModal" class="btn btn-success btn-sm float-lg-right float-left mr-2">{{ __('Save') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body text-center py-5">
          <div class="bg-light d-inline-block p-3 border rounded">
            <img src="{{ asset('assets/img/qr-codes/' . $bs->qr_image) }}" alt="qr code" id="preview">
          </div>
        </div>

        <div class="card-footer text-center">
          <a href="{{ asset('assets/img/qr-codes/' . $bs->qr_image) }}" class="btn btn-primary btn-sm" download="qrcode.png" id="btn-download">
            {{ __('Download') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- save modal --}}
  @includeIf('backend.qr-code.save')
@endsection

@section('script')
  <script>
    let regenerateUrl = "{{ route('admin.qr_codes.regenerate_code') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/qr-code.js') }}"></script>
@endsection
