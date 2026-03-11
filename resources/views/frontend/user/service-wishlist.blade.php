@extends('frontend.layout')

@php $title = __('Service Wishlist'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Service Wishlist Section ======-->
  <section class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row">
        @includeIf('frontend.user.side-navbar')

        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details mb-40">
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Services') }}</h4>
                  </div>

                  <div class="main-info">
                    @if (count($listedServices) == 0)
                      <div class="row text-center mt-2">
                        <div class="col">
                          <h4>{{ __('No Service Found') . '!' }}</h4>
                        </div>
                      </div>
                    @else
                      <div class="main-table">
                        <div class="table-responsive">
                          <table id="user-datatable" class="table table-striped w-100">
                            <thead>
                              <tr>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Action') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($listedServices as $listedService)
                                <tr id="service-{{ $listedService->service_id }}">
                                  @php
                                    $serviceTitle = $listedService->serviceContent->title;
                                    $slug = $listedService->serviceContent->slug;
                                    $serviceId = $listedService->service_id;
                                  @endphp

                                  <td class="ps-3">
                                    <a class="text-primary"
                                      href="{{ route('service_details', ['slug' => $slug, 'id' => $serviceId]) }}"
                                      target="_blank">
                                      {{ strlen($serviceTitle) > 60 ? mb_substr($serviceTitle, 0, 60, 'UTF-8') . '...' : $serviceTitle }}
                                    </a>
                                  </td>
                                  <td class="ps-3">
                                    <a href="{{ route('service_details', ['slug' => $slug, 'id' => $serviceId]) }}"
                                      class="btn btn-sm btn-primary rounded-1 {{ $currentLanguageInfo->direction == 1 ? 'ms-1' : 'me-1' }}"
                                      target="_blank">
                                      {{ __('Details') }}
                                    </a>

                                    <form
                                      action="{{ route('user.service_wishlist.remove_service', ['service_id' => $serviceId]) }}"
                                      method="POST" class="d-inline">
                                      @csrf
                                      <button type="submit" class="btn btn-sm btn-primary rounded-1">
                                        {{ __('Remove') }}
                                      </button>
                                    </form>
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Service Wishlist Section ======-->
@endsection
