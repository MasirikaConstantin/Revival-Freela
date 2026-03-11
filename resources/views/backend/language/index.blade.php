@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Language Management') }}</h4>
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
        <a href="#">{{ __('Language Management') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between ">
          <div class="card-title d-inline-block">{{ __('Languages') }}</div>
          <div>
            <a href="#" class="btn btn-sm btn-primary " data-toggle="modal" data-target="#addModal">
              <i class="fas fa-plus"></i> {{ __('Add Language Keyword') }}
            </a>
            <a href="#" class="btn btn-sm btn-secondary " data-toggle="modal" data-target="#createModal">
              <i class="fas fa-plus"></i> {{ __('Add') }}
            </a>

          </div>

        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($languages) == 0)
                <h3 class="text-center">{{ __('NO LANGUAGE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('#') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Code') }}</th>
                        <th scope="col">{{ __('Direction') }}</th>
                        <th scope="col">{{ __('Website Language') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($languages as $language)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $language->name }}</td>
                          <td>{{ $language->code }}</td>
                          <td>{{ $language->direction == 1 ? __('RTL') : __('LTR') }}</td>
                          <td>
                            @if ($language->is_default == 1)
                              <strong class="badge badge-success">{{ __('Default') }}</strong>
                            @else
                              <form class="d-inline-block" action="{{ route('admin.language_management.make_default_language', ['id' => $language->id]) }}" method="post">
                                @csrf
                                <button class="btn btn-primary btn-sm" type="submit" name="button">
                                  {{ __('Make Default') }}
                                </button>
                              </form>
                            @endif
                          </td>

                          <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle"
                                    type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ __('Select') }}
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="#" class="dropdown-item editBtn"
                                        data-toggle="modal" data-target="#editModal"
                                        data-id="{{ $language->id }}"
                                        data-name="{{ $language->name }}"
                                        data-code="{{ $language->code }}"
                                        data-direction="{{ $language->direction }}">
                                        {{ __('Edit') }}
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.language_management.edit_keyword', $language->id) }}">
                                        {{ __('Edit Keyword') }}
                                    </a>
                                    <form class="deleteForm d-block"
                                        action="{{ route('admin.language_management.delete', ['id' => $language->id]) }}"
                                        method="post">
                                        @csrf
                                        <button type="submit" class="deleteBtn mb-1">

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
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.language.create')
  {{--create language keywords modal--}}
  @include('backend.language.add-language-keyword')
  {{-- edit modal --}}
  @include('backend.language.edit')
@endsection
@section('script')
<script src="{{ asset('assets/js/admin-submit-button.js') }}"></script>
@endsection
