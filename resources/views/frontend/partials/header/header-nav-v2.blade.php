<!-- Header-area start -->
<header class="header-area header_v1" data-aos="fade-down">
  <!-- Start mobile menu -->
  <div class="mobile-menu">
    <div class="container">
      <div class="mobile-menu-wrapper"></div>
    </div>
  </div>
  <!-- End mobile menu -->
  <div class="main-responsive-nav">
    <div class="container">
      <!-- Mobile Logo -->
      @if (!empty($websiteInfo->logo))
        <div class="logo">
          <a href="{{ route('index') }}" target="_self" title="">
            <img class="lazyload" data-src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="Brand Logo"
              style="width: auto; height: auto; max-width: 100%; max-height: clamp(40px, 6vw, 58px);">
          </a>
        </div>
      @endif
      <!-- Menu toggle button -->
      <button class="menu-toggler" type="button">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>

  <div class="main-navbar">
    <div class="container-fluid px-xl-5">
      <nav class="navbar navbar-expand-lg">
        <!-- Logo -->
        @if (!empty($websiteInfo->logo))
          <a class="navbar-brand" href="{{ route('index') }}" target="_self" title="">
            <img class="lazyload" data-src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="Brand Logo"
              style="width: auto; height: auto; max-width: 100%; max-height: clamp(40px, 6vw, 58px);">
          </a>
        @endif
        <!-- Navigation items -->
        <div class="collapse navbar-collapse">
          <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
            @php $menuDatas = json_decode($menuInfos); @endphp
            @foreach ($menuDatas as $menuData)
              @php $href = get_href($menuData); @endphp
              @if (!property_exists($menuData, 'children'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ $href }}">{{ __($menuData->text) }}</a>
                </li>
              @else
                <li class="nav-item">
                  <a class="nav-link toggle" href="{{ $href }}">{{ __($menuData->text) }}<i
                      class="fal fa-plus"></i></a>
                  <ul class="menu-dropdown">
                    @php $childMenuDatas = $menuData->children; @endphp

                    @foreach ($childMenuDatas as $childMenuData)
                      @php $child_href = get_href($childMenuData); @endphp
                      <li class="nav-item">
                        <a class="nav-link" href="{{ $child_href }}">{{ __($childMenuData->text) }}</a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endif
            @endforeach
          </ul>
        </div>
        <div class="more-option mobile-item">
          @if ($basicInfo->is_language == 1)
            <div class="item">
              <div class="language">
                <form action="{{ route('change_language') }}" method="GET">
                  <select class="niceselect" name="lang_code" onchange="this.form.submit()">
                    @foreach ($allLanguageInfos as $languageInfo)
                      <option value="{{ $languageInfo->code }}" @selected($languageInfo->code == $currentLanguageInfo->code)>
                        {{ $languageInfo->name }}
                      </option>
                    @endforeach
                  </select>
                </form>
              </div>
            </div>
          @endif
          <div class="item">
            <a href="#searchBox" class="btn-search btn-icon rounded-1" target="_self" aria-label="Search Form"
              title="{{ __('Search Form') }}" data-effect="mfp-zoom-in">
              <i class="far fa-search"></i>
            </a>
            <div id="searchBox" class="search-box mx-auto mfp-with-anim mfp-hide mt-30">
              <form action="{{ route('services') }}" method="GET">
                <div class="input-inline p-1 border radius-sm">
                  <input class="form-control border-0 color-light" placeholder="{{ __('Search Service') . '...' }}"
                    type="text" name="keyword">
                  <button class="btn-icon radius-sm" type="submit" aria-label="button">
                    <i class="far fa-search"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div class="item">
            <div class="dropdown">
              <button class="btn btn-sm btn-outline rounded-1 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span>{{ __('Seller') }}</span>
              </button>
              <ul class="dropdown-menu">
                @guest('seller')
                  <li><a class="dropdown-item" href="{{ route('seller.login') }}">{{ __('Login') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('seller.signup') }}">{{ __('Signup') }}</a></li>
                @endguest
                @auth('seller')
                  <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('seller.logout') }}">{{ __('Logout') }}</a></li>
                @endauth
              </ul>
            </div>
          </div>
          <div class="item">
            <div class="dropdown">
              <button class="btn btn-sm btn-primary rounded-1 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span>{{ __('Customer') }}</span>
              </button>
              <ul class="dropdown-menu">
                @guest('web')
                  <li><a class="dropdown-item" href="{{ route('user.login') }}">{{ __('Login') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Signup') }}</a></li>
                @endguest
                @auth('web')
                  <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Logout') }}</a></li>
                @endauth
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <!-- Category menu -->
  <div class="categories-menu" dir="ltr">
    <div class="container-fluid px-xl-5">
      <nav class="categories-menu-nav">
        <div class="arrows left-arrow">
          <i class="fal fa-chevron-left"></i>
        </div>
        <ul class="categories list-unstyled">
          @foreach ($menu_categories as $category)
            @php
              $subcategories = $category->subcategory()->get();
            @endphp
            @if (count($subcategories) > 0)
              <li class="sub-menu-item">
                <a class="active"
                  href="{{ route('services', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                <div class="sub-menu menu-panel">
                  <ul class="menu-list list-unstyled">
                    <li class="menu-item">
                      @foreach ($subcategories as $subcategory)
                        <a
                          href="{{ route('services', ['subcategory' => $subcategory->slug]) }}">{{ $subcategory->name }}</a>
                      @endforeach
                    </li>
                  </ul>
                </div>
              </li>
            @else
              <li class="sub-menu-item">
                <a href="{{ route('services', ['category' => $category->slug]) }}">{{ $category->name }}</a>
              </li>
            @endif
          @endforeach
        </ul>
        <div class="arrows right-arrow active">
          <i class="fal fa-chevron-right"></i>
        </div>
      </nav>
    </div>
  </div>
</header>
<!-- Header-area end -->
