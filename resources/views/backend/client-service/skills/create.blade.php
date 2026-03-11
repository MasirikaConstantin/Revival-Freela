<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Skill') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form create" action="{{ route('admin.service_management.store_skill') }}"
          method="post">
          @csrf

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Language') . '*' }}</label>
                <select name="language_id" class="form-control">
                  <option selected disabled>{{ __('Select a Language') }}</option>
                  @foreach ($langs as $lang)
                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                  @endforeach
                </select>
                <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Name') . '*' }}</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Name">
                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Status') . '*' }}</label>
                <select name="status" class="form-control">
                  <option selected disabled>{{ __('Select a Status') }}</option>
                  <option value="1">{{ __('Active') }}</option>
                  <option value="0">{{ __('Deactive') }}</option>
                </select>
                <p id="err_status" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
