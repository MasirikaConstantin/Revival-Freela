<div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Save QR Code') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form class="modal-form" action="{{ route('admin.qr_codes.save_qr') }}" method="post" id="qrSaveForm">
          @csrf
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="">{{ __('QR Code Name') . '*' }}</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Code Name">
                <p class="mt-2 mb-0 text-warning">
                  {{ __('This name will be used to identify this specific qr code from other qr codes') . '.' }}
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button form="qrSaveForm" type="submit" class="btn btn-primary btn-sm">
          {{ __('OK') }}
        </button>
      </div>
    </div>
  </div>
</div>
