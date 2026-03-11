{{-- receipt modal --}}
<div class="modal fade" id="showModal-{{ $qrcode->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('QR Code') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="bg-white p-5 text-center">
          <img src="{{ asset('assets/img/qr-codes/' . $qrcode->image) }}" alt="qr code">
        </div>
      </div>

      <div class="modal-footer"></div>
    </div>
  </div>
</div>
