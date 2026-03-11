@extends('seller.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Saved Codes') }}</h4>
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

            <div class="col-lg-4 mt-2 mt-lg-0">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete"
                data-href="{{ route('seller.qr_codes.bulk_delete_qr') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
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
                            <a href="#" data-toggle="modal" data-target="#showModal-{{ $qrcode->id }}"
                              class="btn btn-primary btn-sm">
                              <i class="fas fa-eye"></i> {{ __('Show') }}
                            </a>
                          </td>
                          <td>{{ $qrcode->url }}</td>
                          <td>
                            <a href="{{ asset('assets/img/qr-codes/' . $qrcode->image) }}"
                              class="btn btn-secondary btn-sm mr-1" download="{{ $qrcode->name . '.png' }}">
                              <i class="fas fa-download"></i> {{ __('Download') }}
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('seller.qr_codes.delete_qr', ['id' => $qrcode->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
                              </button>
                            </form>
                          </td>
                        </tr>

                        {{-- show modal --}}
                        @includeIf('seller.qr-code.show')
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
