<div class="col-lg-4">
  <div class="sidebar-widget-area pb-10">
    <div class="widget search-widget mb-30">
      <form action="{{ route('blog') }}" method="GET">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="{{ __('Search By Title') }}" name="title" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">
          <input type="hidden" name="category" value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
          <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>

    @if (count($categories) > 0)
      <div class="widget categories-widget mb-30">
        <h4 class="widget-title">{{ __('Categories') }}</h4>
        <ul class="widget-link list-unstyled">
          <li>
            <a href="#" class="blog-category @if (empty(request()->input('category'))) active @endif" data-category_slug="">
              {{ __('All') }} <span>{{ $totalPost }}</span>
            </a>
          </li>

          @foreach ($categories as $category)
            <li>
              <a href="#" class="blog-category @if ($category->slug == request()->input('category')) active @endif" data-category_slug="{{ $category->slug }}">
                {{ $category->name }} <span>{{ $category->postCount }}</span>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="mb-30 text-center">
      {!! showAd(2) !!}
    </div>
  </div>

  {{-- search form start --}}
  <form class="d-none" action="{{ route('blog') }}" method="GET">
    <input type="hidden" name="title" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">

    <input type="hidden" id="categoryKey" name="category">

    <button type="submit" id="submitBtn"></button>
  </form>
  {{-- search form end --}}
</div>
