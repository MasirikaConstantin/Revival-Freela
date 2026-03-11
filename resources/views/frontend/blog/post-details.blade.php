@extends('frontend.layout')
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-content.css') }}">
@endsection

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ @$details->title }}
  @endif
@endsection

@section('metaKeywords')
  {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $details->meta_description }}
@endsection

@section('content')
  {{-- breadcrumb --}}
  <section class="breadcrumbs-area bg_cover lazyload bg-img header-next"
    data-bg-img="{{ asset('assets/img/' . $breadcrumb) }}">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="breadcrumbs-title text-center">
            <h3>
              {{ @$details->title }}
            </h3>
            <ul class="breadcumb-link justify-content-center">
              <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
              <li class="active">{{ @$pageHeading->post_details_page_title }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- breadcrumb end --}}

  <!--====== Start Blog Details Section ======-->
  <section class="blog-details-section pt-100 pb-60">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="blog-details-wrapper mb-40">
            <div class="blog-post-item">
              <div class="post-thumbnail">
                <img data-src="{{ asset('assets/img/posts/' . $details->image) }}" alt="image" class="lazyload">
              </div>
              <div class="entry-content">
                <div class="post-meta">
                  <ul class="list-unstyled">
                    <li><span><i class="far fa-calendar-alt"></i>{{ date_format($details->created_at, 'F d, Y') }}</span>
                    </li>
                    <li><span><i class="far fa-th-large"></i>{{ $details->categoryName }}</span></li>
                  </ul>
                </div>

                <h3 class="title">{{ $details->title }}</h3>
                <div class="summernote-content">{!! replaceBaseUrl($details->content, 'summernote') !!}</div>

                <div class="blog-share">
                  <ul class="social-link list-unstyled">
                    <li>
                      <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        class="facebook">
                        <i class="fab fa-facebook-f"></i>{{ __('Share') }}
                      </a>
                    </li>

                    <li>
                      <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                        class="twitter">
                        <i class="fab fa-twitter"></i>{{ __('Tweet') }}
                      </a>
                    </li>

                    <li>
                      <a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"
                        class="linkedin">
                        <i class="fab fa-linkedin-in"></i>{{ __('Share') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="mb-30 mt-30 text-center advertise">
                {!! showAd(3) !!}
              </div>
            </div>
          </div>

          @if ($disqusInfo->disqus_status == 1)
            <div id="disqus_thread" class="mt-5"></div>
          @endif
        </div>

        @includeIf('frontend.blog.side-bar')
      </div>
    </div>
  </section>
  <!--====== End Blog Details Section ======-->
@endsection

@section('script')
  <script>
    const shortName = '{{ $disqusInfo->disqus_short_name }}';
  </script>
@endsection
