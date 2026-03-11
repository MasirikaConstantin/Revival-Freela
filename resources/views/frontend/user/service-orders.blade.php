@extends('frontend.layout')

@php $title = __('Service Orders'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Service Orders Section ======-->
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
                    <h4>{{ __('Order List') }}</h4>
                  </div>

                  <div class="main-info">
                    @if (count($orders) == 0)
                      <div class="row text-center mt-2">
                        <div class="col">
                          <h4>{{ __('No Order Found') . '!' }}</h4>
                        </div>
                      </div>
                    @else
                      <div class="main-table">
                        <div class="table-responsive">
                          <table id="user-datatable" class="table table-striped w-100">
                            <thead>
                              <tr>
                                <th>{{ __('Order Number') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($orders as $order)
                                <tr>
                                  <td class="ps-3">{{ '#' . $order->order_number }}</td>
                                  <td class="ps-3">
                                    @php
                                      $title = @$order->serviceInfo->title;
                                      $slug = @$order->serviceInfo->slug;
                                    @endphp
                                    @if (!empty($slug))
                                      <a class="text-primary"
                                        href="{{ route('service_details', ['slug' => $slug, 'id' => $order->service_id]) }}"
                                        target="_blank">
                                        {{ strlen($title) > 75 ? mb_substr($title, 0, 75, 'UTF-8') . '...' : $title }}
                                      </a>
                                    @endif
                                  </td>
                                  <td class="ps-3">
                                    {{ date_format($order->created_at, 'M d, Y') }}
                                  </td>
                                  <td>
                                    @if ($order->order_status == 'pending' && $order->payment_status == 'completed')
                                      <form
                                        action="{{ route('user.service_order.confirm_order', ['id' => $order->id]) }}"
                                        method="post" class="completeForm">
                                        @csrf
                                        <select name="status" class="niceselect completeBtn">
                                          <option disabled @selected($order->order_status == 'pending') value="pending">
                                            {{ __('Pending') }}
                                          </option>
                                          <option value="completed">{{ __('Completed') }}
                                          </option>
                                        </select>
                                      </form>
                                    @else
                                      @if ($order->order_status == 'completed')
                                        <span
                                          class="completed text-success {{ $currentLanguageInfo->direction == 1 ? 'me-2' : 'ms-2' }}"><b>{{ __('Completed') }}</b></span>
                                      @elseif ($order->order_status == 'pending')
                                        <span
                                          class="rejected {{ $currentLanguageInfo->direction == 1 ? 'me-2' : 'ms-2' }}"><b>{{ __('Pending') }}</b></span>
                                      @else
                                        <span
                                          class="rejected text-danger {{ $currentLanguageInfo->direction == 1 ? 'me-2' : 'ms-2' }}"><b>{{ __('Rejected') }}</b></span>
                                      @endif
                                    @endif
                                  </td>
                                  <td class="ps-3">
                                    <div class="dropdown">
                                      <button class="btn btn-sm btn-primary rounded-1 dropdown-toggle dropdown_btn"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('Select') }}
                                      </button>
                                      <div class="dropdown-menu">
                                        <a class="dropdown-item font-sm"
                                          href="{{ route('user.service_order.details', ['id' => $order->id]) }}">{{ __('Details') }}</a>
                                        @if ($order->payment_status == 'completed')
                                          @if (!is_null($order->seller_id))
                                            @php
                                              $liveChatStatus = App\Http\Helpers\SellerPermissionHelper::getPackageInfoByMembership($order->seller_membership_id);
                                            @endphp
                                            @if ($liveChatStatus == true)
                                              <a href="{{ route('user.service_order.message', ['id' => $order->id]) }}"
                                                class="dropdown-item font-sm">
                                                {{ __('Chat with Seller') }}
                                              </a>
                                            @endif
                                          @else
                                            <a href="{{ route('user.service_order.message', ['id' => $order->id]) }}"
                                              class="dropdown-item font-sm">
                                              {{ __('Chat with Seller') }}
                                            </a>
                                          @endif
                                        @endif

                                        @if ($order->raise_status == 1)
                                          <a href="{{ route('user.service_order.raise_request', ['id' => $order->id, 'status' => 0]) }}"
                                            class="dropdown-item font-sm">{{ __('Cancel Dispute') }}</a>
                                        @elseif ($order->raise_status == 2)
                                          <a href="#" class="dropdown-item font-sm">{{ __('Dispute Resolved') }}</a>
                                        @elseif ($order->raise_status == 3)
                                          <a href="#" class="dropdown-item font-sm">{{ __('Dispute Rejected') }}</a>
                                        @else
                                          <a href="{{ route('user.service_order.raise_request', ['id' => $order->id, 'status' => 1]) }}"
                                            class="dropdown-item font-sm">{{ __('Raise Dispute') }}</a>
                                        @endif
                                      </div>
                                    </div>


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
  <!--====== End Service Orders Section ======-->
@endsection
