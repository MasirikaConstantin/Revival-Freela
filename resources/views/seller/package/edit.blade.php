<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Service Package') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form"
          action="{{ route('seller.service_management.service.update_package') }}" method="post">
          @csrf
          <input type="hidden" id="in_id" name="id">

          <div class="form-group">
            <label for="">{{ __('Name') . '*' }}</label>
            <input type="text" id="in_name" class="form-control" name="name" placeholder="Enter Package Name">
            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Current Price') . '* (' . $currencyText . ')' }}</label>
                <input type="number" id="in_current_price" step="0.01" class="form-control ltr" name="current_price"
                  placeholder="Enter Current Price">
                <p id="editErr_current_price" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Previous Price') . ' (' . $currencyText . ')' }}</label>
                <input type="number" id="in_previous_price" step="0.01" class="form-control ltr"
                  name="previous_price" placeholder="Enter Previous Price">
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Delivery Time') . ' (' . __('Days') . ')' }}</label>
                <input type="number" id="in_delivery_time" class="form-control ltr" name="delivery_time"
                  placeholder="Enter Delivery Time">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Number of Revision') }}</label>
                <input type="number" id="in_number_of_revision" class="form-control ltr" name="number_of_revision"
                  placeholder="Enter Number of Revision">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Features') . '*' }}</label>
            <textarea id="in_features" class="form-control" name="features" placeholder="Enter Package Features" rows="7"></textarea>
            <p id="editErr_features" class="mt-2 mb-0 text-danger em"></p>
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
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
