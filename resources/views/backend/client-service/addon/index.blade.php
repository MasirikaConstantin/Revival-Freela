@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Addons') }}</h4>
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
        <a href="#">{{ __('Addons') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Addons') }}</div>
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
                data-href="{{ route('admin.service_management.service.bulk_delete_addon') }}">
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
              @if (count($addons) == 0)
                <h3 class="text-center mt-2">{{ __('NO ADDON FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($addons as $addon)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $addon->id }}">
                          </td>
                          <td>
                            {{ strlen($addon->name) > 50 ? mb_substr($addon->name, 0, 50, 'UTF-8') . '...' : $addon->name }}
                          </td>
                          <td>
                            {{ $position == 'left' ? $symbol : '' }}{{ $addon->price }}{{ $position == 'right' ? $symbol : '' }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mb-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $addon->id }}" data-name="{{ $addon->name }}"
                              data-price="{{ $addon->price }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.service_management.service.delete_addon', ['id' => $addon->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm mb-1 deleteBtn">
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
  @include('backend.client-service.addon.create')

  {{-- edit modal --}}
  @include('backend.client-service.addon.edit')
@endsection
