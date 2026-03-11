  {{-- modal start --}}
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Keyword') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form id="ajaxForm2" action="{{ route('admin.language_management.add_keyword') }}"
            method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Keyword*') }}</label>
              <input type="text" class="form-control" name="keyword" placeholder="Enter Keyword">
              <p id="err_keyword" class="mt-1 mb-0 text-danger em"></p>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            {{ __('Close') }}
          </button>
          <button id="submitBtn2" type="button" class="btn btn-primary btn-sm">
            {{ __('Submit') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- modal start end --}}  
