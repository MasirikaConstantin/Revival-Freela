@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Services') }}</h4>
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
        <a href="#">{{ __('Services') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Services') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
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
            <div class="col-lg-2">
              <form action="" method="GET">
                <input type="hidden" name="language" value="{{ request()->input('language') }}">
                <input type="hidden" name="seller" value="{{ request()->input('seller') }}">
                <input type="text" name="title" value="{{ request()->input('title') }}" placeholder="Title"
                  class="form-control">
              </form>
            </div>

            <div class="col-lg-2 mt-2 mt-lg-0">
              <a href="{{ route('admin.service_management.create_service') }}"
                class="btn btn-primary btn-sm float-right">
                <i class="fas fa-plus"></i> {{ __('Add Service') }}
              </a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.service_management.bulk_delete_service') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($services) == 0)
                <h3 class="text-center mt-2">{{ __('NO SERVICE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Seller') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Packages') }}</th>
                        <th scope="col">{{ __('Addons') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($services as $service)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $service->id }}">
                          </td>
                          <td>
                            <a target="_blank"
                              href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}">{{ strlen($service->title) > 70 ? mb_substr($service->title, 0, 70, 'UTF-8') . '...' : $service->title }}</a>
                          </td>
                          <td>
                            @if (!is_null($service->seller_id) && $service->seller_id != 0)
                              <a target="_blank"
                                href="{{ route('admin.seller_management.seller_details', ['id' => $service->seller_id, 'language' => $defaultLang->code]) }}">{{ @$service->seller->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td>{{ $service->categoryName }}</td>
                          <td>
                            @if ($service->quote_btn_status == 1)
                              <span class="ml-4">-</span>
                            @else
                              <a href="{{ route('admin.service_management.service.packages', ['id' => $service->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-primary btn-sm">
                                {{ __('Manage') }}
                              </a>
                            @endif
                          </td>
                          <td>
                            @if ($service->quote_btn_status == 1)
                              <span class="ml-4">-</span>
                            @else
                              <a href="{{ route('admin.service_management.service.addons', ['id' => $service->id, 'language' => request()->input('language')]) }}"
                                class="btn btn-primary btn-sm">
                                {{ __('Manage') }}
                              </a>
                            @endif
                          </td>
                          <td>
                            <form id="featuredForm-{{ $service->id }}" class="d-inline-block"
                              action="{{ route('admin.service_management.service.update_featured_status', ['id' => $service->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm @if ($service->is_featured == 'yes') bg-success @else bg-danger @endif"
                                name="is_featured"
                                onchange="document.getElementById('featuredForm-{{ $service->id }}').submit()">
                                <option value="yes" {{ $service->is_featured == 'yes' ? 'selected' : '' }}>
                                  {{ __('Yes') }}
                                </option>
                                <option value="no" {{ $service->is_featured == 'no' ? 'selected' : '' }}>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.service_management.edit_service', ['id' => $service->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <a href="{{ route('admin.service_management.service.faqs', ['id' => $service->id, 'language' => request()->input('language')]) }}"
                                  class="dropdown-item">
                                  {{ __('FAQ') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.service_management.delete_service', ['id' => $service->id]) }}"
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

        <div class="card-footer">
          <div class="pl-3 pr-3">
            {{ $services->appends([
                    'language' => request()->input('language'),
                    'seller' => request()->input('seller'),
                    'title' => request()->input('title'),
                ])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
