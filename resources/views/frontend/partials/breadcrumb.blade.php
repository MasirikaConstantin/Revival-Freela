
<section class="breadcrumbs-area bg_cover lazyload bg-img header-next" data-bg-img="{{ asset('assets/img/' . $breadcrumb) }}">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="breadcrumbs-title text-center">
          <h3>
            @isset($serviceTitle)
              {{ @$serviceTitle }}
            @endisset
            @empty($serviceTitle)
              {{ !empty($title) ? $title : '' }}
            @endempty
          </h3>
          <ul class="breadcumb-link justify-content-center">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="active">{{ !empty($title) ? $title : '' }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

