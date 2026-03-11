@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->blog_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_blog }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_blog }}
  @endif
@endsection
@php
  $title = $pageHeading->blog_page_title ?? __('No Page Title Found');
@endphp
@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $breadcrumb,
      'title' => $title,
  ])

  <!-- Blog-area start -->
  <section class="blog-area blog-area_v1 pt-100 pb-60">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          @if (count($posts) == 0)
            <h3 class="text-center mt-3 mb-4">{{ __('No Post Found') . '!' }}</h3>
          @else
            <div class="blog-post-wrapper pb-10">
              <div class="row">
                @foreach ($posts as $post)
                  <div class="col-md-6" data-aos="fade-up">
                    <article class="card mb-30">
                      <div class="card_img">
                        <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                          target="_self" class="lazy-container ratio ratio-5-4">
                          <img class="lazyload" data-src="{{ asset('assets/img/posts/' . $post->image) }}"
                            alt="Blog Image">
                        </a>
                        <ul class="card_list list-unstyled">
                          <li class="icon-start">
                            <a href="#" target="_self"><i class="fal fa-user"></i>{{ $post->author }}</a>
                          </li>
                          <li class="icon-start">
                            <a href="{{ route('blog', ['category' => $post->categorySlug]) }}" target="_self"><i
                                class="fal fa-th-large"></i>{{ $post->categoryName }} </a>
                          </li>
                          <li class="icon-start">
                            <a href="#" target="_self"><i
                                class="fal fa-calendar-check"></i>{{ $post->created_at->toFormattedDateString() }}</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card_content p-25 border">
                        <h4 class="card_title lc-2 mb-15">
                          <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                            target="_self">
                            {{ strlen($post->title) > 45 ? mb_substr($post->title, 0, 45, 'UTF-8') . '...' : $post->title }}
                          </a>
                        </h4>
                        <p class="card_text lc-2">
                          {!! strlen(strip_tags($post->content)) > 100
                              ? mb_substr(strip_tags($post->content), 0, 100, 'UTF-8') . '...'
                              : strip_tags($post->content) !!}
                        </p>
                        <div class="cta-btn mt-15">
                          <a href="{{ route('blog.post_details', ['slug' => $post->slug, 'id' => $post->id]) }}"
                            class="btn-text color-primary" target="_self"
                            title="{{ __('READ MORE') }}">{{ __('READ MORE') }}</a>
                        </div>
                      </div>
                    </article>
                  </div>
                @endforeach
              </div>

              <div class="row">
                <div class="col-md-12">
                  <nav class="pagination-nav mb-40">
                    <ul class="pagination justify-content-center">
                      {{ $posts->appends([
                              'title' => request()->input('title'),
                              'category' => request()->input('category'),
                          ])->links() }}
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          @endif
          <div class="mb-40  text-center advertise">
            {!! showAd(3) !!}
          </div>
        </div>
        @includeIf('frontend.blog.side-bar')
      </div>
    </div>
  </section>
  <!-- Blog-area end -->
@endsection
