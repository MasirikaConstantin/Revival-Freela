<!-- Footer-area start -->
<footer class="footer-area bg-primary-light">
  <div class="footer-top pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5 justify-content-between">
        <div class="col-xl-4 col-lg-5 col-md-6">
          <div class="footer-widget">
            <!-- Logo -->
            <div class="logo mb-20">
              <a class="navbar-brand" href="{{ route('index') }}" target="_self" title="Link">
                @if (!empty($basicInfo->footer_logo))
                  <img class="lazyload" data-src="{{ asset('assets/img/' . $basicInfo->footer_logo) }}"
                    alt="Brand Logo"
                    style="width: auto; height: auto; max-width: 100%; max-height: clamp(40px, 6vw, 58px);">
                @endif
              </a>
            </div>
            <p>
              {{ !empty($footerInfo) ? $footerInfo->about_company : '' }}
            </p>

          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-6">
          <div class="footer-widget">
            <h5>{{ __('Useful Links') }}</h5>
            @if (count($quickLinkInfos) == 0)
              <h6 class="text-light">{{ __('No Link Found') . '!' }}</h6>
            @else
              <ul class="footer-links">
                @foreach ($quickLinkInfos as $quickLinkInfo)
                  <li>
                    <a href="{{ $quickLinkInfo->url }}" target="_blank">{{ __($quickLinkInfo->title) }}</a>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-sm-6">
          <div class="footer-widget">
            <h5>{{ __('Contact Us') }}</h5>
            <ul class="footer-links">
              @if (!empty($basicInfo->email_address))
                <li>
                  <a href="mailTo:{{ $basicInfo->email_address }}" target="_self"
                    title="link">{{ $basicInfo->email_address }}</a>
                </li>
              @endif
              @if (!empty($basicInfo->contact_number))
                <li>
                  <a href="tel:{{ $basicInfo->contact_number }}" target="_self"
                    title="link">{{ $basicInfo->contact_number }}</a>
                </li>
              @endif
              @if (!empty($basicInfo->address))
                <li>
                  {{ $basicInfo->address }}
                </li>
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-4 col-md-6">
          <div class="footer-widget">
            <h5>{{ __('Subscribe Us') }}</h5>
            <p>
              {{ @$basicExtend->news_letter_section_text }}
            </p>
            <form id="newsletterForm2" action="{{ route('store_subscriber') }}" class="subscription-form"
              method="POST">
              @csrf
              <div class="input-inline p-1 bg-white radius-sm">
                <input class="form-control border-0 size-md" placeholder="{{ __('Enter Your Email Address') }}"
                  type="text" name="email_id">
                <button class="btn-icon radius-sm" type="submit" aria-label="button">
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="copy-right-area">
    <div class="go-top">
      <div class="go-top-btn">
        <i class="far fa-angle-double-up"></i>
      </div>
    </div>
    <div class="container">
      <div class="copy-right-content ptb-30">
        @if (count($socialMediaInfos) > 0)
          <div class="social-link rounded style-2 justify-content-center mb-10">
            @foreach ($socialMediaInfos as $socialMediaInfo)
              <a href="{{ $socialMediaInfo->url }}" target="_blank" title="Link"><i
                  class="{{ $socialMediaInfo->icon }}"></i></a>
            @endforeach
          </div>
        @endif
        @if (!empty($footerInfo))
          <span>
            {{ $footerInfo->copyright_text }}
          </span>
        @endif
      </div>
    </div>
  </div>
</footer>
<!-- Footer-area end-->
