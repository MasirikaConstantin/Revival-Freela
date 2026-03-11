@extends('seller.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Services') }}</h4>
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

            <div class="col-lg-3">
              @includeIf('seller.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('seller.service_management.create_service') }}"
                class="btn btn-primary btn-sm float-right">
                <i class="fas fa-plus"></i> {{ __('Add Service') }}
              </a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('seller.service_management.bulk_delete_service') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @php
                $data = sellerPermission(Auth::guard('seller')->user()->id, 'service');
                $data2 = sellerPermission(Auth::guard('seller')->user()->id, 'service-featured');
              @endphp
              @if ($data['status'] == 'package_false')
                <div class="alert alert-warning text-dark">
                  {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
                </div>
              @else
                @if ($data['status'] == 'false')
                  <div class="alert alert-warning alert-block">
                    <strong
                      class="text-dark">{{ __('Currently, you have added ' . $data['total_service_added'] . ' services. ' . 'Your current package supports ' . $data['package_support'] . ' services. Please delete ' . $data['total_service_added'] - $data['package_support'] . ' services  to enable service management') }}</strong>
                  </div>
                @endif
                @if ($data2['status'] == 'false')
                  <div class="alert alert-warning alert-block">
                    <strong
                      class="text-dark">{{ __('Currently, you have featured ' . $data2['total_service_featured'] . ' services. ' . 'Your current package supports ' . $data2['package_support'] . ' services to make featured. Please unfeatured ' . $data2['total_service_featured'] - $data2['package_support'] . ' services to enable service management') }}</strong>
                  </div>
                @endif
              @endif

              @if (count($services) == 0)
                <h3 class="text-center mt-2">{{ __('NO SERVICE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
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
                              href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}">{{ strlen($service->title) > 75 ? mb_substr($service->title, 0, 75, 'UTF-8') . '...' : $service->title }}</a>
                          </td>
                          <td>{{ $service->categoryName }}</td>
                          <td>
                            @if ($service->quote_btn_status == 1)
                              <span class="ml-4">-</span>
                            @else
                              <a @if ($data['status'] == 'true') href="{{ route('seller.service_management.service.packages', ['id' => $service->id, 'language' => request()->input('language')]) }}" @endif
                                class="btn btn-primary btn-sm ">
                                {{ __('Manage') }}
                              </a>
                            @endif
                          </td>
                          <td>
                            @if ($service->quote_btn_status == 1)
                              <span class="ml-4">-</span>
                            @else
                              <a @if ($data['status'] == 'true' && $data2['status'] == 'true') href="{{ route('seller.service_management.service.addons', ['id' => $service->id, 'language' => request()->input('language')]) }}" @endif
                                class="btn btn-primary btn-sm  ">
                                {{ __('Manage') }}
                              </a>
                            @endif
                          </td>
                          <td>
                            <form id="featuredForm-{{ $service->id }}" class="d-inline-block"
                              action="{{ route('seller.service_management.service.update_featured_status', ['id' => $service->id]) }}"
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
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a @if ($data['status'] == 'true' && $data2['status'] == 'true') href="{{ route('seller.service_management.edit_service', ['id' => $service->id]) }}" @endif
                                  class="dropdown-item ">
                                  {{ __('Edit') }}
                                </a>

                                <a href="{{ route('seller.service_management.service.faqs', ['id' => $service->id, 'language' => request()->input('language')]) }}"
                                  class="dropdown-item">
                                  {{ __('FAQ') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('seller.service_management.delete_service', ['id' => $service->id]) }}"
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
@endsection
