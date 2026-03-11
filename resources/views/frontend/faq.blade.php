@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->faq_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_faq }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_faq }}
  @endif
@endsection
@php
  $title = $pageHeading->faq_page_title ?? __('No Page Title Found');
@endphp
@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start FAQ Section ======-->
  <section class="faq-area pt-100 pb-70">
    <div class="container">
      <div class="row">
        <div class="col">
          @if (count($faqs) == 0)
            <h3 class="text-center">{{ __('No FAQ Found') . '!' }}</h3>
          @else
            <div class="faq-wrapper-one">
              <div class="row justify-content-center">
                <div class="col-xl-10">
                  <div class="accordion" id="accordionExample">
                    @foreach ($faqs as $faq)
                    <div class="accordion-item border radius-md mb-20">
                      <h6 class="accordion-header" id="{{ 'heading-' . $faq->id }}">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                          data-bs-target="{{ '#collapse-' . $faq->id }}"
                          aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                          aria-controls="{{ 'collapse-' . $faq->id }}">
                            {{ $faq->question }}
                          </button>
                      </h6>
                      <div id="{{ 'collapse-' . $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                        aria-labelledby="{{ 'heading-' . $faq->id }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <p>{{ $faq->answer }}</p>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  <!--====== End FAQ Section ======-->
@endsection
