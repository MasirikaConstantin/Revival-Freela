@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Forms') }}</h4>
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
        <a href="#">{{ __('Forms') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('All Forms') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
            <div class="col-lg-2">
              <form action="" method="GET">
                <input type="hidden" name="language" value="{{ request()->input('language') }}">
                <select name="seller" id="" class="form-control select2" onchange="this.form.submit()">
                  <option value="" selected>{{ __('All') }}</option>
                  <option value="admin" @selected(request()->input('seller') == 'admin')>{{ __('Admin') }}</option>
                  @foreach ($sellers as $seller)
                    <option @selected($seller->id == request()->input('seller')) value="{{ $seller->id }}">{{ $seller->username }}</option>
                  @endforeach
                </select>
              </form>
            </div>

            <div class="col-lg-3 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left">
                <i class="fas fa-plus"></i> {{ __('Add') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (session()->has('error'))
                <div class="alert alert-warning alert-block">
                  <strong class="text-dark">{{ session()->get('error') }}</strong>
                  <button type="button" class="close" data-dismiss="alert">×</button>
                </div>
              @endif

              @if (count($forms) == 0)
                <h3 class="text-center mt-2">{{ __('NO FORM FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Seller') }}</th>
                        <th scope="col">{{ __('Form Inputs') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($forms as $form)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $form->name }}</td>
                          <td>
                            @if (!is_null($form->seller_id))
                              <a target="_blank"
                                href="{{ route('admin.seller_management.seller_details', ['id' => $form->seller_id, 'language' => $defaultLang->code]) }}">{{ @$form->seller->username }}</a>
                            @else
                              {{ __('Admin') }}
                            @endif
                          </td>
                          <td>
                            <a href="{{ route('admin.service_management.form.input', ['id' => $form->id, 'language' => request()->input('language')]) }}"
                              class="btn btn-sm btn-info">
                              {{ __('Manage') }}
                            </a>
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm editBtn mb-1" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $form->id }}" data-name="{{ $form->name }}"
                              data-seller_id="{{ $form->seller_id }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.service_management.delete_form', ['id' => $form->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn mb-1">
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
  @includeIf('backend.client-service.form.create')

  {{-- edit modal --}}
  @includeIf('backend.client-service.form.edit')
@endsection
