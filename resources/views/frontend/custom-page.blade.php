@extends('frontend.layout')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@section('pageHeading')
    {{ $pageInfo->title }}
@endsection

@section('metaKeywords')
    {{ $pageInfo->meta_keywords }}
@endsection

@section('metaDescription')
    {{ $pageInfo->meta_description }}
@endsection
@php
    $title = $pageInfo->title ?? __('No Page Title Found');
@endphp
@section('content')
    @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!--====== PAGE CONTENT PART START ======-->
    <section class="custom-page-area ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="summernote-content">
                        {!! replaceBaseUrl($pageInfo->content, 'summernote') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== PAGE CONTENT PART END ======-->
@endsection
