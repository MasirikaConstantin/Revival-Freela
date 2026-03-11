<div class="col-lg-5">
  <table class="table table-striped mdb_242">
    <thead>
      <tr>
        <th scope="col">{{ __('BB Code') }}</th>
        <th scope="col">{{ __('Meaning') }}</th>
      </tr>
    </thead>
    <tbody>
      @if (
          $templateInfo->mail_type == 'verify_email' ||
              $templateInfo->mail_type == 'admin_removed_next_package' ||
              $templateInfo->mail_type == 'admin_removed_current_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'payment_rejected_for_registration_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'membership_expired' ||
              $templateInfo->mail_type == 'membership_expiry_reminder' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'user_register_success')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of User') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'balance_add' || $templateInfo->mail_type == 'balance_subtract')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of Seller') }}</td>
        </tr>
        <tr>
          <td>{amount}</td>
          <td scope="row">{{ __('Amount') }}</td>
        </tr>
        <tr>
          <td>{current_balance}</td>
          <td scope="row">{{ __('Current Balance of Seller') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{verification_link}</td>
          <td scope="row">{{ __('Email Verification Link') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'reset_password' ||
              $templateInfo->mail_type == 'product_order' ||
              $templateInfo->mail_type == 'service_order' ||
              $templateInfo->mail_type == 'payment_success' ||
              $templateInfo->mail_type == 'payment_approved' ||
              $templateInfo->mail_type == 'payment_rejected' ||
              $templateInfo->mail_type == 'payment_paid' ||
              $templateInfo->mail_type == 'payment_unpaid')
        <tr>
          <td>{customer_name}</td>
          <td scope="row">{{ __('Name of The Customer') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'reset_password')
        <tr>
          <td>{password_reset_link}</td>
          <td scope="row">{{ __('Password Reset Link') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'product_order' || $templateInfo->mail_type == 'service_order')
        <tr>
          <td>{order_number}</td>
          <td scope="row">{{ __('Order Number') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'product_order' || $templateInfo->mail_type == 'service_order')
        <tr>
          <td>{order_link}</td>
          <td scope="row">{{ __('Link to View Order Details') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'payment_success' ||
              $templateInfo->mail_type == 'payment_approved' ||
              $templateInfo->mail_type == 'payment_rejected' ||
              $templateInfo->mail_type == 'payment_paid' ||
              $templateInfo->mail_type == 'payment_unpaid')
        <tr>
          <td>{invoice_number}</td>
          <td scope="row">{{ __('Invoice Number') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'withdraw_approve' || $templateInfo->mail_type == 'withdraw_rejected')
        <tr>
          <td>{seller_username}</td>
          <td>{{ __('Seller Username') }}</td>
        </tr>
        <tr>
          <td>{withdraw_id}</td>
          <td>{{ __('Withdrawal  Id') }}</td>
        </tr>
        <tr>
          <td>{current_balance}</td>
          <td>{{ __('Amount Current Balance') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'withdraw_approve')
        <tr>
          <td>{withdraw_amount}</td>
          <td>{{ __('Amount of Withdrawal') }}</td>
        </tr>
        <tr>
          <td>{charge}</td>
          <td>{{ __('Charge') }}</td>
        </tr>
        <tr>
          <td>{payable_amount}</td>
          <td>{{ __('Payable Amount') }}</td>
        </tr>
        <tr>
          <td>{withdraw_method}</td>
          <td>{{ __('Withdraw Method') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_removed_current_package')
        <tr>
          <td>{replaced_package}</td>
          <td scope="row">{{ __('Replace Package Name') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'admin_removed_current_package' ||
              $templateInfo->mail_type == 'admin_removed_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_registration_offline_gateway')
        <tr>
          <td>{package_title}</td>
          <td scope="row">{{ __('Package Name') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_registration_offline_gateway')
        <tr>
          <td>{package_price}</td>
          <td scope="row">{{ __('Price of Package') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'registration_with_premium_package')
        <tr>
          <td>{total}</td>
          <td scope="row">{{ __('Total Paid Amount') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway')
        <tr>
          <td>{activation_date}</td>
          <td scope="row">{{ __('Package activation date') }}</td>
        </tr>
      @endif
      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway')
        <tr>
          <td>{expire_date}</td>
          <td scope="row">{{ __('Package expire date') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'membership_expiry_reminder')
        <tr>
          <td>{last_day_of_membership}</td>
          <td scope="row">{{ __('Package expire last date') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'membership_expiry_reminder' || $templateInfo->mail_type == 'membership_expired')
        <tr>
          <td>{login_link}</td>
          <td scope="row">{{ __('Login Url') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'add_user_by_admin')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username') }}</td>
        </tr>
        <tr>
          <td>{user_type}</td>
          <td scope="row">{{ __('Customer / Seller') }}</td>
        </tr>
        <tr>
          <td>{password}</td>
          <td scope="row">{{ __('Password of Cutomer/Seller') }}</td>
        </tr>
      @endif


      <tr>
        <td>{website_title}</td>
        <td scope="row">{{ __('Your Website Name') }}</td>
      </tr>
    </tbody>
  </table>
</div>
