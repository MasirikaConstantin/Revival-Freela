@extends('seller.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('seller.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Forms') }}</h4>
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

            <div class="col-lg-3">
              @includeIf('seller.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
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
              @php
                $data = sellerPermission(Auth::guard('seller')->user()->id, 'form', $defaultLang->id);
              @endphp
              @if ($data['status'] == 'false')
                <div class="alert alert-warning alert-block">
                  <strong
                    class="text-dark">{{ __('Currently, you have added ' . $data['total_form_added'] . ' forms. ' . 'Your current package supports ' . $data['package_support'] . ' forms. Please delete ' . $data['total_form_added'] - $data['package_support'] . ' forms to enable service management.') }}</strong>
                </div>
              @endif
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
                            <a @if ($data['status'] == 'true') href="{{ route('seller.service_management.form.input', ['id' => $form->id, 'language' => request()->input('language')]) }}" @endif
                              class="btn btn-sm btn-info">
                              {{ __('Manage') }}
                            </a>
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm editBtn mb-1 {{ $data['status'] == 'false' ? 'disabled' : '' }}"
                              href="#" data-toggle="modal" data-target="#editModal" data-id="{{ $form->id }}"
                              data-name="{{ $form->name }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('seller.service_management.delete_form', ['id' => $form->id]) }}"
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
  @includeIf('seller.form.create')

  {{-- edit modal --}}
  @includeIf('seller.form.edit')
@endsection
