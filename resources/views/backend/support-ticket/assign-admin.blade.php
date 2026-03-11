<div class="modal fade" id="{{ 'assignModal-' . $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalLabel">{{ __('Assign Admin') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('admin.support_ticket.assign_admin', ['id' => $ticket->id]) }}" method="POST">
          @csrf
          <div class="form-group">
            <label>{{ __('Admin') . '*' }}</label>
            <select name="admin_id" class="form-control">
              <option disabled>{{ __('Select an Admin') }}</option>

              @foreach ($admins as $admin)
                <option value="{{ $admin->id }}" {{ $admin->id == $ticket->admin_id ? 'selected' : '' }}>
                  {{ $admin->first_name . ' ' . $admin->last_name . ' (username - ' . $admin->username . ')' }}
                </option>
              @endforeach
            </select>
            <p id="err_admin_id" class="mt-2 mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" id="submitBtn">
          {{ __('Assign') }}
        </button>
      </div>
    </div>
  </div>
</div>
