@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Packages') }}</h4>
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
        <a href="#">{{ __('Service Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.service_management.services', ['language' => $defaultLang->code]) }}">
          {{ __('Services') }}
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">
          {{ strlen($serviceTitle) > 30 ? mb_substr($serviceTitle, 0, 30, 'UTF-8') . '...' : $serviceTitle }}
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Packages') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Packages') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('admin.service_management.services', ['language' => request()->input('language')]) }}"
                class="btn btn-info btn-sm float-lg-right float-left">
                <span class="btn-label">
                  <i class="fas fa-backward mdb_12"></i>
                </span>
                {{ __('Back') }}
              </a>

              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left mr-2">
                <i class="fas fa-plus"></i> {{ __('Add') }}
              </a>

              <button class="btn btn-danger btn-sm float-lg-right float-left mr-2 d-none bulk-delete"
                data-href="{{ route('admin.service_management.service.bulk_delete_package') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        @php
          $position = $currencyInfo->base_currency_symbol_position;
          $symbol = $currencyInfo->base_currency_symbol;
          $currencyText = $currencyInfo->base_currency_text;
        @endphp

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($packages) == 0)
                <h3 class="text-center mt-2">{{ __('NO PACKAGE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Current Price') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($packages as $package)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $package->id }}">
                          </td>
                          <td>
                            {{ strlen($package->name) > 50 ? mb_substr($package->name, 0, 50, 'UTF-8') . '...' : $package->name }}
                          </td>
                          <td>
                            {{ $position == 'left' ? $symbol : '' }}{{ $package->current_price }}{{ $position == 'right' ? $symbol : '' }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 mb-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $package->id }}" data-name="{{ $package->name }}"
                              data-current_price="{{ $package->current_price }}"
                              data-previous_price="{{ $package->previous_price }}"
                              data-delivery_time="{{ $package->delivery_time }}"
                              data-number_of_revision="{{ $package->number_of_revision }}"
                              data-features="{{ $package->features }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.service_management.service.delete_package', ['id' => $package->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm  mb-1 deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
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

  {{-- create modal --}}
  @include('backend.client-service.package.create')

  {{-- edit modal --}}
  @include('backend.client-service.package.edit')
@endsection
