@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Seller Details') }}</h4>
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
        <a href="#">{{ __('Sellers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.seller_management.registered_seller') }}">{{ __('Registered Sellers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Seller Details') }}</a>
      </li>
    </ul>
    <a href="{{ route('admin.seller_management.registered_seller') }}"
      class="btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="row">

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <div class="h4 card-title">{{ __('Seller Information') }}</div>
              <h2 class="text-center">
                @if ($seller->photo != null)
                  <img class="admin-seller-photo rounded-circle" src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}"
                    alt="..." class="uploaded-img">
                @else
                  <img class="admin-seller-photo rounded-circle" src="{{ asset('assets/img/blank-user.jpg') }}" alt="..."
                    class="uploaded-img">
                @endif

              </h2>
            </div>

            <div class="card-body">
              <div class="payment-information">

                @php
                  $currPackage = \App\Http\Helpers\SellerPermissionHelper::currPackageOrPending($seller->id);
                  $currMemb = \App\Http\Helpers\SellerPermissionHelper::currMembOrPending($seller->id);
                @endphp
                <div class="row mb-3">
                  <div class="col-lg-6">
                    <strong>{{ __('Current Package:') }}</strong>
                  </div>
                  <div class="col-lg-6">
                    @if ($currPackage)
                      <a target="_blank"
                        href="{{ route('admin.package.edit', $currPackage->id) }}">{{ $currPackage->title }}</a>
                      <span class="badge badge-secondary badge-xs mr-2">{{ $currPackage->term }}</span>
                      <button type="submit" class="btn btn-xs btn-warning" data-toggle="modal"
                        data-target="#editCurrentPackage"><i class="far fa-edit"></i></button>
                      <form action="{{ route('seller.currPackage.remove') }}" class="d-inline-block deleteForm"
                        method="POST">
                        @csrf
                        <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                        <button type="submit" class="btn btn-xs btn-danger deleteBtn"><i
                            class="fas fa-trash"></i></button>
                      </form>

                      <p class="mb-0">
                        @if ($currMemb->is_trial == 1)
                          ({{ __('Expire Date') . ':' }}
                          {{ Carbon\Carbon::parse($currMemb->expire_date)->format('M-d-Y') }})
                          <span class="badge badge-primary">{{ __('Trial') }}</span>
                        @else
                          ({{ __('Expire Date') . ':' }}
                          {{ $currPackage->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($currMemb->expire_date)->format('M-d-Y') }})
                        @endif
                        @if ($currMemb->status == 0)
                          <form id="statusForm{{ $currMemb->id }}" class="d-inline-block"
                            action="{{ route('admin.payment-log.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $currMemb->id }}">
                            <select class="form-control form-control-sm bg-warning" name="status"
                              onchange="document.getElementById('statusForm{{ $currMemb->id }}').submit();">
                              <option value=0 selected>{{ __('Pending') }}</option>
                              <option value=1>{{ __('Success') }}</option>
                              <option value=2>{{ __('Rejected') }}</option>
                            </select>
                          </form>
                        @endif
                      </p>
                    @else
                      <a data-target="#addCurrentPackage" data-toggle="modal" class="btn btn-xs btn-primary text-white"><i
                          class="fas fa-plus"></i> {{ __('Add Package') }}</a>
                    @endif
                  </div>
                </div>

                @php
                  $nextPackage = \App\Http\Helpers\SellerPermissionHelper::nextPackage($seller->id);
                  $nextMemb = \App\Http\Helpers\SellerPermissionHelper::nextMembership($seller->id);
                @endphp
                <div class="row mb-3">
                  <div class="col-lg-6">
                    <strong>{{ __('Next Package:') }}</strong>
                  </div>
                  <div class="col-lg-6">
                    @if ($nextPackage)
                      <a target="_blank"
                        href="{{ route('admin.package.edit', $nextPackage->id) }}">{{ $nextPackage->title }}</a>
                      <span class="badge badge-secondary badge-xs mr-2">{{ $nextPackage->term }}</span>
                      <button type="button" class="btn btn-xs btn-warning" data-toggle="modal"
                        data-target="#editNextPackage"><i class="far fa-edit"></i></button>
                      <form action="{{ route('seller.nextPackage.remove') }}" class="d-inline-block deleteForm"
                        method="POST">
                        @csrf
                        <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                        <button type="submit" class="btn btn-xs btn-danger deleteBtn"><i
                            class="fas fa-trash"></i></button>
                      </form>

                      <p class="mb-0">
                        @if ($currPackage->term != 'lifetime' && $nextMemb->is_trial != 1)
                          (
                          Activation Date:
                          {{ Carbon\Carbon::parse($nextMemb->start_date)->format('M-d-Y') }},
                          Expire Date:
                          {{ $nextPackage->term === 'lifetime' ? 'Lifetime' : Carbon\Carbon::parse($nextMemb->expire_date)->format('M-d-Y') }})
                        @endif
                        @if ($nextMemb->status == 0)
                          <form id="statusForm{{ $nextMemb->id }}" class="d-inline-block"
                            action="{{ route('admin.payment-log.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $nextMemb->id }}">
                            <select class="form-control form-control-sm bg-warning" name="status"
                              onchange="document.getElementById('statusForm{{ $nextMemb->id }}').submit();">
                              <option value=0 selected>{{ __('Pending') }}</option>
                              <option value=1>{{ __('Success') }}</option>
                              <option value=2>{{ __('Rejected') }}</option>
                            </select>
                          </form>
                        @endif
                      </p>
                    @else
                      @if (!empty($currPackage))
                        <a class="btn btn-xs btn-primary text-white" data-toggle="modal"
                          data-target="#addNextPackage"><i class="fas fa-plus"></i> {{ __('Add  Package') }}</a>
                      @else
                        -
                      @endif
                    @endif
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Name') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->name }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Username') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $seller->username }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Email') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $seller->email }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Phone') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ $seller->phone }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Country') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->country }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('City') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->city }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('State') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->state }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Zip Code') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->zip_code }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Address') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->address }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Details') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ @$seller->seller_info->details }}
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-lg-4">
                    <strong>{{ __('Balance') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-8">
                    {{ symbolPrice(@$seller->amount) }}
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-4">
                  <div class="card-title d-inline-block">{{ __('Services') }}</div>
                </div>

                <div class="col-lg-3">
                  @includeIf('backend.partials.languages')
                </div>


                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
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
                      <table class="table table-striped mt-3" id="basic-datatables">
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
                                  href="{{ route('service_details', ['slug' => $service->slug, 'id' => $service->id]) }}">{{ strlen($service->title) > 30 ? mb_substr($service->title, 0, 30, 'UTF-8') . '...' : $service->title }}</a>
                              </td>
                              <td>
                                @if (!is_null($service->seller_id))
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

            <div class="card-footer"></div>
          </div>
        </div>
      </div>
    </div>
    @includeIf('backend.end-user.seller.edit-current-package')
    @includeIf('backend.end-user.seller.add-current-package')
    @includeIf('backend.end-user.seller.edit-next-package')
    @includeIf('backend.end-user.seller.add-next-package')
  @endsection
