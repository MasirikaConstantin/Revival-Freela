@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Categories') }}</h4>
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
        <a href="#">{{ __('Categories') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Service Categories') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.service_management.bulk_delete_category') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($categories) == 0)
                <h3 class="text-center mt-2">{{ __('NO CATEGORY FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Add to Menu') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($categories as $category)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $category->id }}">
                          </td>
                          <td>
                            <img src="{{ asset('assets/img/service-categories/' . $category->image) }}"
                              alt="category image" width="45">
                          </td>
                          <td>
                            {{ strlen($category->name) > 50 ? mb_substr($category->name, 0, 50, 'UTF-8') . '...' : $category->name }}
                          </td>
                          <td>
                            @if ($category->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>
                          <td>{{ $category->serial_number }}</td>
                          <td>
                            <form id="featuredForm-{{ $category->id }}" class="d-inline-block"
                              action="{{ route('admin.service_management.category.update_featured_status', ['id' => $category->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm @if ($category->is_featured == 'yes') bg-success @else bg-danger @endif"
                                name="is_featured"
                                onchange="document.getElementById('featuredForm-{{ $category->id }}').submit()">
                                <option value="yes" {{ $category->is_featured == 'yes' ? 'selected' : '' }}>
                                  {{ __('Yes') }}
                                </option>
                                <option value="no" {{ $category->is_featured == 'no' ? 'selected' : '' }}>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <form id="addToMenuForm-{{ $category->id }}" class="d-inline-block"
                              action="{{ route('admin.service_management.category.update_add_to_menu', ['id' => $category->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm @if ($category->add_to_menu == 1) bg-success @else bg-danger @endif"
                                name="is_menu"
                                onchange="document.getElementById('addToMenuForm-{{ $category->id }}').submit()">
                                <option value="1" @selected($category->add_to_menu == 1)>
                                  {{ __('Yes') }}
                                </option>
                                <option value="0" @selected($category->add_to_menu == 0)>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>

                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a data-toggle="modal" data-target="#editModal" data-id="{{ $category->id }}"
                                  data-image="{{ asset('assets/img/service-categories/' . $category->image) }}"
                                  data-name="{{ $category->name }}" data-status="{{ $category->status }}"
                                  data-serial_number="{{ $category->serial_number }}"" class="dropdown-item editBtn">
                                  {{ __('Edit') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.service_management.delete_category', ['id' => $category->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
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
  @include('backend.client-service.category.create')

  {{-- edit modal --}}
  @include('backend.client-service.category.edit')
@endsection
