@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Features Section') }}</h4>
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
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Features Section') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        @if ($settings->theme_version == 1)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <div class="card-title">{{ __('Image') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <form id="bgImgForm" action="{{ route('admin.home_page.update_features_bg') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">{{ __('Background Image') . '*' }}</label>
                                        <br>
                                        <div class="thumb-preview">
                                            @if (empty($bgImg))
                                                <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                                    class="uploaded-background-img">
                                            @else
                                                <img src="{{ asset('assets/img/' . $bgImg) }}" alt="image"
                                                    class="uploaded-background-img">
                                            @endif
                                        </div>

                                        <div class="mt-3">
                                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                {{ __('Choose Image') }}
                                                <input type="file" class="background-img-input" name="feature_bg_img">
                                            </div>
                                        </div>
                                        @error('feature_bg_img')
                                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" form="bgImgForm" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="@if ($settings->theme_version == 1) col-lg-6 @else col-12 @endif">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title">{{ __('Features') }}</div>
                        </div>

                        <div class="col-lg-3">
                            @includeIf('backend.partials.languages')
                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                                class="btn btn-primary btn-sm float-lg-right float-left">
                                <i class="fas fa-plus"></i> {{ __('Add') }}
                            </a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.home_page.bulk_delete_feature') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @if (count($allFeature) == 0)
                                <h3 class="text-center mt-2">{{ __('NO FEATURE FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Icon') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allFeature as $feature)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $feature->id }}">
                                                    </td>
                                                    <td><i class="{{ $feature->icon }}"></i></td>
                                                    <td>
                                                        {{ strlen($feature->title) > 30 ? mb_substr($feature->title, 0, 30, 'UTF-8') . '...' : $feature->title }}
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1 editBtn mb-1" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $feature->id }}" data-icon="{{ $feature->icon }}"
                                                            data-color="{{ $feature->color }}"
                                                            data-title="{{ $feature->title }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>

                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.home_page.delete_feature', ['id' => $feature->id]) }}"
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
    @includeIf('backend.home-page.feature-section.create')

    {{-- edit modal --}}
    @includeIf('backend.home-page.feature-section.edit')
@endsection
