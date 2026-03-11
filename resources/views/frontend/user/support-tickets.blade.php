@extends('frontend.layout')

@php $title = __('Support Tickets'); @endphp

@section('pageHeading')
  {{ $title }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

  <!--====== Start Support Tickets Section ======-->
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
                    <h4>{{ __('Ticket List') }}</h4>

                    <a href="{{ route('user.support_tickets.create') }}" class="btn btn-md btn-primary rounded-1">{{ __('New Ticket') }}</a>
                  </div>

                  <div class="main-info">
                    @if (count($tickets) == 0)
                      <div class="row text-center mt-2">
                        <div class="col">
                          <h4>{{ __('No Ticket Found') . '!' }}</h4>
                        </div>
                      </div>
                    @else
                      <div class="main-table">
                        <div class="table-responsive">
                          <table id="user-datatable" class="table table-striped w-100">
                            <thead>
                              <tr>
                                <th>{{ __('Ticket ID') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($tickets as $ticket)
                                <tr>
                                  <td class="{{ $currentLanguageInfo->direction == 1 ? 'pe-3' : 'ps-3' }}">
                                    {{ '#' . $ticket->id }}
                                  </td>
                                  <td class="{{ $currentLanguageInfo->direction == 1 ? 'pe-3' : 'ps-3' }}">
                                    {{ strlen($ticket->subject) > 60 ? mb_substr($ticket->subject, 0, 60, 'UTF-8') . '...' : $ticket->subject }}
                                  </td>
                                  <td>
                                    @if ($ticket->status == 'pending')
                                      <span class="pending {{ $currentLanguageInfo->direction == 1 ? 'me-2' : 'ms-2' }}">{{ __('Pending') }}</span>
                                    @elseif ($ticket->status == 'open')
                                      <span class="open {{ $currentLanguageInfo->direction == 1 ? 'me-2' : 'ms-2' }}">{{ __('Open') }}</span>
                                    @else
                                      <span class="closed {{ $currentLanguageInfo->direction == 1 ? 'me-2' : 'ms-2' }}">{{ __('Closed') }}</span>
                                    @endif
                                  </td>
                                  <td class="{{ $currentLanguageInfo->direction == 1 ? 'pe-3' : 'ps-3' }}">
                                    <a href="{{ route('user.support_ticket.conversation', ['id' => $ticket->id]) }}" class="btn btn-sm btn-primary rounded-1">
                                      {{ __('Conversation') }}
                                    </a>
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
  <!--====== End Support Tickets Section ======-->
@endsection
