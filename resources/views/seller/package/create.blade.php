<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Service Package') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form create"
          action="{{ route('seller.service_management.service.store_package') }}" method="post">
          @csrf
          <input type="hidden" name="service_id" value="{{ $service->id }}">

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

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Package Name">
            <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Current Price') . '* (' . $currencyText . ')' }}</label>
                <input type="number" step="0.01" class="form-control ltr" name="current_price"
                  placeholder="Enter Current Price">
                <p id="err_current_price" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Previous Price') . ' (' . $currencyText . ')' }}</label>
                <input type="number" step="0.01" class="form-control ltr" name="previous_price"
                  placeholder="Enter Previous Price">
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Delivery Time') . ' (' . __('Days') . ')' }}</label>
                <input type="number" class="form-control ltr" name="delivery_time" placeholder="Enter Delivery Time">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Number of Revision') }}</label>
                <input type="number" class="form-control ltr" name="number_of_revision"
                  placeholder="Enter Number of Revision">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Features') . '*' }}</label>
            <textarea class="form-control" name="features" placeholder="Enter Package Features" rows="7"></textarea>
            <p id="err_features" class="mt-2 mb-0 text-danger em"></p>
            <p class="text-warning mt-2 mb-0">
              {{ __('To seperate the features, enter a new line after each feature.') }}
            </p>
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
