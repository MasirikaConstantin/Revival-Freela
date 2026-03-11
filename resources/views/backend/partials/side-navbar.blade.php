<div class="sidebar sidebar-style-2"
  data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark2' }}">
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('admin')->user()->image != null)
            <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}" alt="Admin Image"
              class="avatar-img rounded-circle">
          @else
            <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="" class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('admin')->user()->first_name }}

              @if (is_null($roleInfo))
                <span class="user-level">{{ 'Super Admin' }}</span>
              @else
                <span class="user-level">{{ $roleInfo->name }}</span>
              @endif

              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('admin.edit_profile') }}">
                  <span class="link-collapse">{{ 'Edit Profile' }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.change_password') }}">
                  <span class="link-collapse">{{ 'Change Password' }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.logout') }}">
                  <span class="link-collapse">{{ 'Logout' }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      @php
        if (!is_null($roleInfo)) {
            $rolePermissions = json_decode($roleInfo->permissions);
        }
      @endphp

      <ul class="nav nav-primary">
        {{-- search --}}
        <div class="row mb-3">
          <div class="col-12">
            <form action="">
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="Search Menu Here...">
              </div>
            </form>
          </div>
        </div>

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
          <a href="{{ route('admin.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ 'Dashboard' }}</p>
          </a>
        </li>


        {{-- menu builder --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Menu Builder', $rolePermissions)))
          <li class="nav-item @if (request()->routeIs('admin.menu_builder')) active @endif">
            <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}">
              <i class="fal fa-bars"></i>
              <p>{{ __('Menu Builder') }}</p>
            </a>
          </li>
        @endif

        {{-- package management --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Package Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.package.settings')) active 
            @elseif (request()->routeIs('admin.package.index')) active 
            @elseif (request()->routeIs('admin.package.edit')) active @endif">
            <a data-toggle="collapse" href="#packageManagement">
              <i class="fal fa-receipt"></i>
              <p>Package Management</p>
              <span class="caret"></span>
            </a>

            <div id="packageManagement"
              class="collapse 
              @if (request()->routeIs('admin.package.settings')) show 
              @elseif (request()->routeIs('admin.package.index')) show 
              @elseif (request()->routeIs('admin.package.edit')) show @endif">
              <ul class="nav nav-collapse">

                <li class="{{ request()->routeIs('admin.package.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.package.settings', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">Settings</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.package.index') ? 'active' : '' }}">
                  <a href="{{ route('admin.package.index', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">Packages</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- payment log --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Subscription Log', $rolePermissions)))
          <li class="nav-item @if (request()->routeIs('admin.payment-log.index')) active @endif">
            <a href="{{ route('admin.payment-log.index') }}">
              <i class="fas fa-list-ol"></i>
              <p>Subscription Log</p>
            </a>
          </li>
        @endif

        {{-- service --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Service Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.service_management.categories')) active 
            @elseif (request()->routeIs('admin.service_management.settings')) active 
            @elseif (request()->routeIs('admin.service_management.popular_tags')) active 
            @elseif (request()->routeIs('admin.service_management.skills')) active 
            @elseif (request()->routeIs('admin.service_management.subcategories')) active 
            @elseif (request()->routeIs('admin.service_management.forms')) active 
            @elseif (request()->routeIs('admin.service_management.form.input')) active 
            @elseif (request()->routeIs('admin.service_management.form.edit_input')) active 
            @elseif (request()->routeIs('admin.service_management.services')) active 
            @elseif (request()->routeIs('admin.service_management.create_service')) active 
            @elseif (request()->routeIs('admin.service_management.edit_service')) active 
            @elseif (request()->routeIs('admin.service_management.service.packages')) active 
            @elseif (request()->routeIs('admin.service_management.service.addons')) active 
            @elseif (request()->routeIs('admin.service_management.service.faqs')) active @endif">
            <a data-toggle="collapse" href="#service">
              <i class="fal fa-headset"></i>
              <p>{{ __('Service Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="service"
              class="collapse 
              @if (request()->routeIs('admin.service_management.categories')) show 
              @elseif (request()->routeIs('admin.service_management.settings')) show 
              @elseif (request()->routeIs('admin.service_management.popular_tags')) show 
              @elseif (request()->routeIs('admin.service_management.skills')) show 
              @elseif (request()->routeIs('admin.service_management.subcategories')) show 
              @elseif (request()->routeIs('admin.service_management.forms')) show 
              @elseif (request()->routeIs('admin.service_management.form.input')) show 
              @elseif (request()->routeIs('admin.service_management.form.edit_input')) show 
              @elseif (request()->routeIs('admin.service_management.services')) show 
              @elseif (request()->routeIs('admin.service_management.create_service')) show 
              @elseif (request()->routeIs('admin.service_management.edit_service')) show 
              @elseif (request()->routeIs('admin.service_management.service.packages')) show 
              @elseif (request()->routeIs('admin.service_management.service.addons')) show 
              @elseif (request()->routeIs('admin.service_management.service.faqs')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.service_management.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.service_management.settings', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.service_management.popular_tags') ? 'active' : '' }}">
                  <a href="{{ route('admin.service_management.popular_tags', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Populal Tags') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.service_management.skills') ? 'active' : '' }}">
                  <a href="{{ route('admin.service_management.skills', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Skills') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.service_management.categories') ? 'active' : '' }}">
                  <a href="{{ route('admin.service_management.categories', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ 'Categories' }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.service_management.subcategories') ? 'active' : '' }}">
                  <a href="{{ route('admin.service_management.subcategories', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Subcategories') }}</span>
                  </a>
                </li>

                <li
                  class="@if (request()->routeIs('admin.service_management.forms')) active 
                  @elseif (request()->routeIs('admin.service_management.form.input')) active 
                  @elseif (request()->routeIs('admin.service_management.form.edit_input')) active @endif">
                  <a href="{{ route('admin.service_management.forms', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Forms') }}</span>
                  </a>
                </li>

                <li
                  class="@if (request()->routeIs('admin.service_management.services')) active 
                  @elseif (request()->routeIs('admin.service_management.create_service')) active 
                  @elseif (request()->routeIs('admin.service_management.edit_service')) active 
                  @elseif (request()->routeIs('admin.service_management.service.packages')) active 
                  @elseif (request()->routeIs('admin.service_management.service.addons')) active 
                  @elseif (request()->routeIs('admin.service_management.service.faqs')) active @endIf">
                  <a href="{{ route('admin.service_management.services', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ 'Services' }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- service order --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Service Orders', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.service_orders')) active 
            @elseif (request()->routeIs('admin.service_order.details')) active 
            @elseif (request()->routeIs('admin.service_order.message')) active 
            @elseif (request()->routeIs('admin.service_orders.report')) active @endif">
            <a data-toggle="collapse" href="#service_orders">
              <i class="far fa-cubes"></i>
              <p>{{ 'Service Orders' }}</p>
              <span class="caret"></span>
            </a>

            <div id="service_orders"
              class="collapse 
              @if (request()->routeIs('admin.service_orders')) show 
              @elseif (request()->routeIs('admin.service_order.details')) show 
              @elseif (request()->routeIs('admin.service_order.message')) show 
              @elseif (request()->routeIs('admin.service_orders.report')) show @endif">
              <ul class="nav nav-collapse">
                <li
                  class="{{ request()->routeIs('admin.service_orders') && empty(request()->input('order_status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.service_orders') }}">
                    <span class="sub-item">{{ __('All Orders') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.service_orders') && request()->input('order_status') == 'pending' ? 'active' : '' }}">
                  <a href="{{ route('admin.service_orders', ['order_status' => 'pending']) }}">
                    <span class="sub-item">{{ __('Pending Orders') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.service_orders') && request()->input('order_status') == 'processing' ? 'active' : '' }}">
                  <a href="{{ route('admin.service_orders', ['order_status' => 'processing']) }}">
                    <span class="sub-item">{{ __('Processing Orders') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.service_orders') && request()->input('order_status') == 'completed' ? 'active' : '' }}">
                  <a href="{{ route('admin.service_orders', ['order_status' => 'completed']) }}">
                    <span class="sub-item">{{ __('Completed Orders') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.service_orders') && request()->input('order_status') == 'rejected' ? 'active' : '' }}">
                  <a href="{{ route('admin.service_orders', ['order_status' => 'rejected']) }}">
                    <span class="sub-item">{{ __('Rejected Orders') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.service_orders.report') ? 'active' : '' }}">
                  <a href="{{ route('admin.service_orders.report') }}">
                    <span class="sub-item">{{ __('Report') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- dashboard --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Raise Disputs', $rolePermissions)))
          <li class="nav-item @if (request()->routeIs('admin.service_order.disputs')) active @endif">
            <a href="{{ route('admin.service_order.disputs') }}">
              <i class="fal fa-gavel"></i>
              <p>{{ 'Dispute Requests' }}</p>
            </a>
          </li>
        @endif

        {{-- withdraw method --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Withdrawals Management', $rolePermissions)))
          <li
            class="nav-item
          @if (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) active
          @elseif (request()->routeIs('admin.withdraw.withdraw_request')) active @endif">
            <a data-toggle="collapse" href="#withdraw_method">
              <i class="fal fa-credit-card"></i>
              <p>{{ __('Withdrawals Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="withdraw_method"
              class="collapse
            @if (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) show
            @elseif (request()->routeIs('admin.withdraw.withdraw_request')) show @endif">
              <ul class="nav nav-collapse">
                <li
                  class="{{ request()->routeIs('admin.withdraw.payment_method') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Payment Methods') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.withdraw.withdraw_request') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Withdraw Requests') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- Transaction --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Transactions', $rolePermissions)))
          <li class="nav-item @if (request()->routeIs('admin.transcation')) active @endif">
            <a href="{{ route('admin.transcation') }}">
              <i class="fal fa-exchange-alt"></i>
              <p>{{ __('Transactions') }}</p>
            </a>
          </li>
        @endif

        {{-- qr code --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('QR Codes', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.qr_codes.generate_code')) active 
            @elseif (request()->routeIs('admin.qr_codes.saved_codes')) active @endif">
            <a data-toggle="collapse" href="#qr_codes">
              <i class="fal fa-qrcode"></i>
              <p>{{ __('QR Codes') }}</p>
              <span class="caret"></span>
            </a>

            <div id="qr_codes"
              class="collapse 
              @if (request()->routeIs('admin.qr_codes.generate_code')) show 
              @elseif (request()->routeIs('admin.qr_codes.saved_codes')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.qr_codes.generate_code') ? 'active' : '' }}">
                  <a href="{{ route('admin.qr_codes.generate_code') }}">
                    <span class="sub-item">{{ __('Generate Code') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.qr_codes.saved_codes') ? 'active' : '' }}">
                  <a href="{{ route('admin.qr_codes.saved_codes') }}">
                    <span class="sub-item">{{ __('Saved Codes') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- user --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('User Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.user_management.registered_users')) active 
            @elseif (request()->routeIs('admin.user_management.user.details')) active 
            @elseif (request()->routeIs('admin.user_management.user.edit')) active 
            @elseif (request()->routeIs('admin.user_management.user.change_password')) active 
            @elseif (request()->routeIs('admin.user_management.subscribers')) active 
            @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active 
            @elseif (request()->routeIs('admin.user_management.push_notification.settings')) active 
            @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif">
            <a data-toggle="collapse" href="#user">
              <i class="la flaticon-users"></i>
              <p>{{ __('Customers Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="user"
              class="collapse 
              @if (request()->routeIs('admin.user_management.registered_users')) show 
              @elseif (request()->routeIs('admin.user_management.user.details')) show 
              @elseif (request()->routeIs('admin.user_management.user.edit')) show 
              @elseif (request()->routeIs('admin.user_management.user.change_password')) show 
              @elseif (request()->routeIs('admin.user_management.subscribers')) show 
              @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) show 
              @elseif (request()->routeIs('admin.user_management.push_notification.settings')) show 
              @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
              <ul class="nav nav-collapse">
                <li
                  class="@if (request()->routeIs('admin.user_management.registered_users')) active 
                  @elseif (request()->routeIs('admin.user_management.user.details')) active 
                  @elseif (request()->routeIs('admin.user_management.user.edit')) active 
                  @elseif (request()->routeIs('admin.user_management.user.change_password')) active @endif">
                  <a href="{{ route('admin.user_management.registered_users') }}">
                    <span class="sub-item">{{ __('Registered Customers') }}</span>
                  </a>
                </li>

                <li
                  class="@if (request()->routeIs('admin.user_management.subscribers')) active 
                  @elseif (request()->routeIs('admin.user_management.mail_for_subscribers')) active @endif">
                  <a href="{{ route('admin.user_management.subscribers') }}">
                    <span class="sub-item">{{ __('Subscribers') }}</span>
                  </a>
                </li>

                <li class="submenu">
                  <a data-toggle="collapse" href="#push_notification">
                    <span class="sub-item">{{ __('Push Notification') }}</span>
                    <span class="caret"></span>
                  </a>

                  <div id="push_notification"
                    class="collapse 
                    @if (request()->routeIs('admin.user_management.push_notification.settings')) show 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
                    <ul class="nav nav-collapse subnav">
                      <li
                        class="{{ request()->routeIs('admin.user_management.push_notification.settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.user_management.push_notification.settings') }}">
                          <span class="sub-item">{{ __('Settings') }}</span>
                        </a>
                      </li>

                      <li
                        class="{{ request()->routeIs('admin.user_management.push_notification.notification_for_visitors') ? 'active' : '' }}">
                        <a href="{{ route('admin.user_management.push_notification.notification_for_visitors') }}">
                          <span class="sub-item">{{ __('Send Notification') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- seller --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Sellers Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.seller_management.registered_seller')) active
            @elseif (request()->routeIs('admin.seller_management.add_seller')) active
            @elseif (request()->routeIs('admin.seller_management.seller_details')) active
            @elseif (request()->routeIs('admin.edit_management.seller_edit')) active
            @elseif (request()->routeIs('admin.seller_management.settings')) active
            @elseif (request()->routeIs('admin.seller_management.seller.change_password')) active @endif">
            <a data-toggle="collapse" href="#seller">
              <i class="la flaticon-users"></i>
              <p>Sellers Management</p>
              <span class="caret"></span>
            </a>

            <div id="seller"
              class="collapse
              @if (request()->routeIs('admin.seller_management.registered_seller')) show
              @elseif (request()->routeIs('admin.seller_management.seller_details')) show
              @elseif (request()->routeIs('admin.edit_management.seller_edit')) show
              @elseif (request()->routeIs('admin.seller_management.add_seller')) show
              @elseif (request()->routeIs('admin.seller_management.settings')) show
              @elseif (request()->routeIs('admin.seller_management.seller.change_password')) show @endif">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('admin.seller_management.settings')) active @endif">
                  <a href="{{ route('admin.seller_management.settings') }}">
                    <span class="sub-item">Settings</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.seller_management.registered_seller')) active
                  @elseif (request()->routeIs('admin.seller_management.seller_details')) active
                  @elseif (request()->routeIs('admin.edit_management.seller_edit')) active
                  @elseif (request()->routeIs('admin.seller_management.seller.change_password')) active @endif">
                  <a href="{{ route('admin.seller_management.registered_seller') }}">
                    <span class="sub-item">Registered Sellers</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('admin.seller_management.add_seller')) active @endif">
                  <a href="{{ route('admin.seller_management.add_seller') }}">
                    <span class="sub-item">Add Seller</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- support tickets --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Support Tickets', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.support_tickets.settings')) active 
            @elseif (request()->routeIs('admin.support_tickets')) active 
            @elseif (request()->routeIs('admin.support_ticket.conversation')) active @endif">
            <a data-toggle="collapse" href="#support_tickets">
              <i class="fal fa-ticket-alt"></i>
              <p>{{ 'Support Tickets' }}</p>
              <span class="caret"></span>
            </a>

            <div id="support_tickets"
              class="collapse 
              @if (request()->routeIs('admin.support_tickets.settings')) show 
              @elseif (request()->routeIs('admin.support_tickets')) show 
              @elseif (request()->routeIs('admin.support_ticket.conversation')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.support_tickets.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.support_tickets') && empty(request()->input('ticket_status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets') }}">
                    <span class="sub-item">{{ __('All Tickets') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.support_tickets') && request()->input('ticket_status') == 'pending' ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets', ['ticket_status' => 'pending']) }}">
                    <span class="sub-item">{{ __('Pending Tickets') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.support_tickets') && request()->input('ticket_status') == 'open' ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets', ['ticket_status' => 'open']) }}">
                    <span class="sub-item">{{ __('Open Tickets') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.support_tickets') && request()->input('ticket_status') == 'closed' ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets', ['ticket_status' => 'closed']) }}">
                    <span class="sub-item">{{ __('Closed Tickets') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- home page --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Home Page', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.home_page.hero_section')) active 
            @elseif (request()->routeIs('admin.home_page.calltoactionsection')) active
            @elseif (request()->routeIs('admin.home_page.section_titles')) active
            @elseif (request()->routeIs('admin.home_page.about_section')) active 
            @elseif (request()->routeIs('admin.home_page.features_section')) active 
            @elseif (request()->routeIs('admin.home_page.testimonials_section')) active 
            @elseif (request()->routeIs('admin.home_page.partners_section')) active 
            @elseif (request()->routeIs('admin.home_page.section_customization')) active @endif">
            <a data-toggle="collapse" href="#home_page">
              <i class="fal fa-layer-group"></i>
              <p>{{ __('Home Page') }}</p>
              <span class="caret"></span>
            </a>

            <div id="home_page"
              class="collapse 
              @if (request()->routeIs('admin.home_page.hero_section')) show 
              @elseif (request()->routeIs('admin.home_page.section_titles')) show
              @elseif (request()->routeIs('admin.home_page.calltoactionsection')) show
              @elseif (request()->routeIs('admin.home_page.about_section')) show 
              @elseif (request()->routeIs('admin.home_page.features_section')) show 
              @elseif (request()->routeIs('admin.home_page.testimonials_section')) show 
              @elseif (request()->routeIs('admin.home_page.partners_section')) show 
              @elseif (request()->routeIs('admin.home_page.section_customization')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.home_page.hero_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.hero_section', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Hero Section') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.home_page.section_titles') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.section_titles', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Section Titles') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.home_page.about_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.about_section', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('About Section') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.home_page.features_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.features_section', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Features Section') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.home_page.testimonials_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.testimonials_section', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Testimonials Section') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.home_page.calltoactionsection') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.calltoactionsection', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Call to action Section') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.home_page.partners_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.partners_section') }}">
                    <span class="sub-item">{{ __('Partners Section') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.home_page.section_customization') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.section_customization') }}">
                    <span class="sub-item">{{ __('Section Customization') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- footer --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Footer', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.footer.logo')) active 
            @elseif (request()->routeIs('admin.footer.content')) active 
            @elseif (request()->routeIs('admin.home_page.newsletter_section')) active 
            @elseif (request()->routeIs('admin.footer.quick_links')) active @endif">
            <a data-toggle="collapse" href="#footer">
              <i class="fal fa-shoe-prints"></i>
              <p>{{ __('Footer') }}</p>
              <span class="caret"></span>
            </a>

            <div id="footer"
              class="collapse @if (request()->routeIs('admin.footer.logo')) show 
              @elseif (request()->routeIs('admin.footer.content')) show 
              @elseif (request()->routeIs('admin.home_page.newsletter_section')) show 
              @elseif (request()->routeIs('admin.footer.quick_links')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.footer.logo') ? 'active' : '' }}">
                  <a href="{{ route('admin.footer.logo') }}">
                    <span class="sub-item">{{ __('Logo') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.footer.content') ? 'active' : '' }}">
                  <a href="{{ route('admin.footer.content', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Content') }}</span>
                  </a>
                </li>


                <li class="{{ request()->routeIs('admin.footer.quick_links') ? 'active' : '' }}">
                  <a href="{{ route('admin.footer.quick_links', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Quick Links') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.home_page.newsletter_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.newsletter_section', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Newsletter Section') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- blog --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Blog Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.blog_management.categories')) active 
            @elseif (request()->routeIs('admin.blog_management.posts')) active 
            @elseif (request()->routeIs('admin.blog_management.create_post')) active 
            @elseif (request()->routeIs('admin.blog_management.edit_post')) active @endif">
            <a data-toggle="collapse" href="#blog">
              <i class="fal fa-blog"></i>
              <p>{{ __('Blog Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="blog"
              class="collapse 
              @if (request()->routeIs('admin.blog_management.categories')) show 
              @elseif (request()->routeIs('admin.blog_management.posts')) show 
              @elseif (request()->routeIs('admin.blog_management.create_post')) show 
              @elseif (request()->routeIs('admin.blog_management.edit_post')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.blog_management.categories') ? 'active' : '' }}">
                  <a href="{{ route('admin.blog_management.categories', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ 'Categories' }}</span>
                  </a>
                </li>

                <li
                  class="@if (request()->routeIs('admin.blog_management.posts')) active 
                  @elseif (request()->routeIs('admin.blog_management.create_post')) active 
                  @elseif (request()->routeIs('admin.blog_management.edit_post')) active @endif">
                  <a href="{{ route('admin.blog_management.posts', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Posts') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- faq --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('FAQ Management', $rolePermissions)))
          <li class="nav-item {{ request()->routeIs('admin.faq_management') ? 'active' : '' }}">
            <a href="{{ route('admin.faq_management', ['language' => $defaultLang->code]) }}">
              <i class="la flaticon-round"></i>
              <p>{{ __('FAQ Management') }}</p>
            </a>
          </li>
        @endif

        {{-- custom page --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Custom Pages', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.custom_pages')) active 
            @elseif (request()->routeIs('admin.custom_pages.create_page')) active 
            @elseif (request()->routeIs('admin.custom_pages.edit_page')) active @endif">
            <a href="{{ route('admin.custom_pages', ['language' => $defaultLang->code]) }}">
              <i class="la flaticon-file"></i>
              <p>{{ __('Custom Pages') }}</p>
            </a>
          </li>
        @endif

        {{-- advertise --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Advertise', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.advertise.settings')) active 
            @elseif (request()->routeIs('admin.advertise.all_advertisement')) active @endif">
            <a data-toggle="collapse" href="#abecex">
              <i class="fab fa-buysellads"></i>
              <p>{{ __('Advertise') }}</p>
              <span class="caret"></span>
            </a>

            <div id="abecex"
              class="collapse @if (request()->routeIs('admin.advertise.settings')) show 
            @elseif (request()->routeIs('admin.advertise.all_advertisement')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.advertise.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.advertise.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.advertise.all_advertisement') ? 'active' : '' }}">
                  <a href="{{ route('admin.advertise.all_advertisement') }}">
                    <span class="sub-item">{{ __('All Advertisement') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- announcement popup --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Announcement Popups', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.announcement_popups')) active 
            @elseif (request()->routeIs('admin.announcement_popups.select_popup_type')) active 
            @elseif (request()->routeIs('admin.announcement_popups.create_popup')) active 
            @elseif (request()->routeIs('admin.announcement_popups.edit_popup')) active @endif">
            <a href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
              <i class="fal fa-bullhorn"></i>
              <p>{{ __('Announcement Popups') }}</p>
            </a>
          </li>
        @endif


        {{-- basic settings --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Basic Settings', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.basic_settings.favicon')) active
            @elseif (request()->routeIs('admin.basic_settings.logo')) active
            @elseif (request()->routeIs('admin.basic_settings.information')) active
            @elseif (request()->routeIs('admin.basic_settings.timezone')) active
            @elseif (request()->routeIs('admin.basic_settings.theme_and_home')) active
            @elseif (request()->routeIs('admin.basic_settings.currency')) active
            @elseif (request()->routeIs('admin.basic_settings.appearance')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_from_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_templates')) active
            @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active
            @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) active
            @elseif (request()->routeIs('admin.basic_settings.page_headings')) active
            @elseif (request()->routeIs('admin.basic_settings.plugins')) active
            @elseif (request()->routeIs('admin.basic_settings.seo')) active
            @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) active
            @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) active
            @elseif (request()->routeIs('admin.basic_settings.social_medias')) active @endif">
            <a data-toggle="collapse" href="#basic_settings">
              <i class="la flaticon-settings"></i>
              <p>{{ __('Basic Settings') }}</p>
              <span class="caret"></span>
            </a>

            <div id="basic_settings"
              class="collapse 
              @if (request()->routeIs('admin.basic_settings.favicon')) show
              @elseif (request()->routeIs('admin.basic_settings.logo')) show
              @elseif (request()->routeIs('admin.basic_settings.information')) show
              @elseif (request()->routeIs('admin.basic_settings.timezone')) show
              @elseif (request()->routeIs('admin.basic_settings.theme_and_home')) show
              @elseif (request()->routeIs('admin.basic_settings.currency')) show
              @elseif (request()->routeIs('admin.basic_settings.appearance')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_from_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
              @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show
              @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) show
              @elseif (request()->routeIs('admin.basic_settings.page_headings')) show
              @elseif (request()->routeIs('admin.basic_settings.plugins')) show
              @elseif (request()->routeIs('admin.basic_settings.seo')) show
              @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) show
              @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) show
              @elseif (request()->routeIs('admin.basic_settings.social_medias')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.basic_settings.favicon') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.favicon') }}">
                    <span class="sub-item">{{ __('Favicon') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.logo') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.logo') }}">
                    <span class="sub-item">{{ __('Logo') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.information') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.information') }}">
                    <span class="sub-item">{{ 'Information' }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.timezone') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.timezone') }}">
                    <span class="sub-item">{{ __('Timezone') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.theme_and_home') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.theme_and_home') }}">
                    <span class="sub-item">{{ __('Theme & Home') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.currency') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.currency') }}">
                    <span class="sub-item">{{ __('Currency') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.appearance') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.appearance') }}">
                    <span class="sub-item">{{ __('Website Appearance') }}</span>
                  </a>
                </li>

                <li class="submenu">
                  <a data-toggle="collapse" href="#mail-settings"
                    aria-expanded="{{ request()->routeIs('admin.basic_settings.mail_from_admin') || request()->routeIs('admin.basic_settings.mail_to_admin') || request()->routeIs('admin.basic_settings.mail_templates') || request()->routeIs('admin.basic_settings.edit_mail_template') ? 'true' : 'false' }}">
                    <span class="sub-item">{{ __('Email Settings') }}</span>
                    <span class="caret"></span>
                  </a>

                  <div id="mail-settings"
                    class="collapse 
                    @if (request()->routeIs('admin.basic_settings.mail_from_admin')) show 
                    @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
                    @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
                    @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show @endif">
                    <ul class="nav nav-collapse subnav">
                      <li class="{{ request()->routeIs('admin.basic_settings.mail_from_admin') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.mail_from_admin') }}">
                          <span class="sub-item">{{ __('Mail From Admin') }}</span>
                        </a>
                      </li>

                      <li class="{{ request()->routeIs('admin.basic_settings.mail_to_admin') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.mail_to_admin') }}">
                          <span class="sub-item">{{ __('Mail To Admin') }}</span>
                        </a>
                      </li>

                      <li
                        class="@if (request()->routeIs('admin.basic_settings.mail_templates')) active 
                        @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active @endif">
                        <a href="{{ route('admin.basic_settings.mail_templates') }}">
                          <span class="sub-item">{{ __('Mail Templates') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.breadcrumb') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.breadcrumb') }}">
                    <span class="sub-item">{{ __('Breadcrumb') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.page_headings') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.page_headings', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Page Headings') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.plugins') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.plugins') }}">
                    <span class="sub-item">{{ __('Plugins') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.seo') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.seo', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('SEO Informations') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.maintenance_mode') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.maintenance_mode') }}">
                    <span class="sub-item">{{ __('Maintenance Mode') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.cookie_alert') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.cookie_alert', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Cookie Alert') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.basic_settings.social_medias') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.social_medias') }}">
                    <span class="sub-item">{{ __('Social Medias') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- payment gateway --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Payment Gateways', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.payment_gateways.online_gateways')) active 
            @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) active @endif">
            <a data-toggle="collapse" href="#payment_gateways">
              <i class="la flaticon-paypal"></i>
              <p>{{ __('Payment Gateways') }}</p>
              <span class="caret"></span>
            </a>

            <div id="payment_gateways"
              class="collapse 
              @if (request()->routeIs('admin.payment_gateways.online_gateways')) show 
              @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.payment_gateways.online_gateways') ? 'active' : '' }}">
                  <a href="{{ route('admin.payment_gateways.online_gateways') }}">
                    <span class="sub-item">{{ __('Online Gateways') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.payment_gateways.offline_gateways') ? 'active' : '' }}">
                  <a href="{{ route('admin.payment_gateways.offline_gateways') }}">
                    <span class="sub-item">{{ __('Offline Gateways') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif
        {{-- admin --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Admin Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.admin_management.role_permissions')) active 
            @elseif (request()->routeIs('admin.admin_management.role.permissions')) active 
            @elseif (request()->routeIs('admin.admin_management.registered_admins')) active @endif">
            <a data-toggle="collapse" href="#admin">
              <i class="fal fa-users-cog"></i>
              <p>{{ __('Admin Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="admin"
              class="collapse 
              @if (request()->routeIs('admin.admin_management.role_permissions')) show 
              @elseif (request()->routeIs('admin.admin_management.role.permissions')) show 
              @elseif (request()->routeIs('admin.admin_management.registered_admins')) show @endif">
              <ul class="nav nav-collapse">
                <li
                  class="@if (request()->routeIs('admin.admin_management.role_permissions')) active 
                  @elseif (request()->routeIs('admin.admin_management.role.permissions')) active @endif">
                  <a href="{{ route('admin.admin_management.role_permissions') }}">
                    <span class="sub-item">{{ __('Role & Permissions') }}</span>
                  </a>
                </li>

                <li class="{{ request()->routeIs('admin.admin_management.registered_admins') ? 'active' : '' }}">
                  <a href="{{ route('admin.admin_management.registered_admins') }}">
                    <span class="sub-item">{{ __('Registered Admins') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- language --}}
        @if (is_null($roleInfo) || (!empty($rolePermissions) && in_array('Language Management', $rolePermissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.language_management')) active 
                        @elseif (request()->routeIs('admin.language_management.edit_keyword')) active
                        @elseif (request()->routeIs('admin.language_management.settings')) active @endif">

            <a data-toggle="collapse" href="#language_management">
              <i class="fal fa-language"></i>
              <p>{{ __('Language Management') }}</p>
              <span class="caret"></span>
            </a>
            <div id="language_management"
              class="collapse 
                            @if (request()->routeIs('admin.language_management')) show
                            @elseif (request()->routeIs('admin.language_management.edit_keyword')) show
                            @elseif (request()->routeIs('admin.language_management.settings')) show @endif">
              <ul class="nav
                            nav-collapse">
                <li class="{{ request()->routeIs('admin.language_management.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.language_management.settings', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.language_management') || request()->routeIs('admin.language_management.edit_keyword') ? 'active' : '' }}">
                  <a href="{{ route('admin.language_management') }}">
                    <span class="sub-item">{{ __('Languages') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

      </ul>
    </div>
  </div>
</div>
