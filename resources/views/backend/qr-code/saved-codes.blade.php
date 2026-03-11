@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Saved Codes') }}</h4>
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
        <a href="#">{{ __('Saved Codes') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Saved QR Codes') }}</div>
            </div>

            <div class="col-lg-2 mt-2 mt-lg-0">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete"
                data-href="{{ route('admin.qr_codes.bulk_delete_qr') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
            <div class="col-lg-2">
              <form action="" method="GET">
                <input type="hidden" name="language" value="{{ request()->input('language') }}">
                <input type="hidden" name="title" value="{{ request()->input('title') }}">
                <select name="seller" id="" class="form-control select2" onchange="this.form.submit()">
                  <option selected disabled>{{ __('Select Seller') }}</option>
                  <option value="" selected>{{ __('All') }}</option>
                  <option value="admin" @selected(request()->input('seller') == 'admin')>{{ __('Admin') }}</option>
                  @foreach ($sellers as $seller)
                    <option @selected($seller->id == request()->input('seller')) value="{{ $seller->id }}">{{ $seller->username }}</option>
                  @endforeach
                </select>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($qrcodes) == 0)
                <h3 class="text-center mt-2">{{ __('NO QR CODE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Seller') }}</th>
                        <th scope="col">{{ __('QR Code') }}</th>
                        <th scope="col">{{ __('URL') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($qrcodes as $qrcode)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $qrcode->id }}">
                          </td>
                          <td>{{ $qrcode->name }}</td>
                          <td>
                            @if (!is_null($qrcode->seller_id))
                              <a
                                href="{{ route('admin.seller_management.seller_details', ['id' => $qrcode->seller_id, 'language' => $defaultLang->code]) }}">{{ @$qrcode->seller->username }}</a>
                            @else
                              <span class="badge badge-primary">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td>
                            <a href="#" data-toggle="modal" data-target="#showModal-{{ $qrcode->id }}"
                              class="btn btn-primary btn-sm">
                              <i class="fas fa-eye"></i> {{ __('Show') }}
                            </a>
                          </td>
                          <td>{{ $qrcode->url }}</td>
                          <td>
                            <a href="{{ asset('assets/img/qr-codes/' . $qrcode->image) }}"
                              class="btn btn-secondary btn-sm mr-1 mb-1" download="{{ $qrcode->name . '.png' }}">
                              <i class="fas fa-download"></i> {{ __('Download') }}
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.qr_codes.delete_qr', ['id' => $qrcode->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn mb-1">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
                              </button>
                            </form>
                          </td>
                        </tr>

                        {{-- show modal --}}
                        @includeIf('backend.qr-code.show')
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>
@endsection
