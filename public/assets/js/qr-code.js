'use strict';

$(document).ready(function () {
  let type = $('select[name="type"]').val();
  loadDiv(type);

  $('input[type="range"]').on('input', function () {
    let sizeVal = $(this).val();

    $(this).next().text(sizeVal);
  });
});

function loadDiv(qrType) {
  $('.qrcode-type').hide();
  $(`#${qrType}-type`).show();
}

function generateQR() {
  if (account_status == 1 || secret_login == 1) {
    let type = $('select[name="type"]').val();
    loadDiv(type);

    $('.request-loader').addClass('show');

    let fd = new FormData($('#qrCodeForm')[0]);

    if (type == 'text') {
      let txt = $('input[name="text"]').val();
      $('#text-input').text(txt);

      let qrcodeSize = $('input[name="size"]').val();
      let textSize = $('input[name="text_size"]').val();
      let fontSize = (qrcodeSize * textSize) / 100;
      $('#text-input').css({ "font-family": "Lato-Regular", "font-size": fontSize });

      let width = $('#text-input').width();
      let textWidth = (width == 0) ? 1 : width;
      fd.append('text_width', textWidth);
    }

    $('input[type="range"]').attr('disabled', true);

    $.ajax({
      url: regenerateUrl,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        $('input[type="range"]').attr('disabled', false);

        let newQrCode = data.qrcode;

        $('#preview').attr('src', newQrCode);
        $('#btn-download').attr('href', newQrCode);
      },
      error: function (error) {
        $('.request-loader').removeClass('show');

        $('input[type="range"]').attr('disabled', false);

        let content = {};

        content.message = error.responseJSON.message;
        content.title = 'Warning';
        content.icon = 'fa fa-bell';

        $.notify(content, {
          type: 'warning',
          placement: {
            from: 'top',
            align: 'right'
          },
          showProgressbar: true,
          time: 1000,
          delay: 4000
        });
      }
    });
  } else {
    var content = {};
    content.message = 'Your account needs Admin approval!';
    content.title = 'Alert';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      allow_dismiss: true,
      delay: 4000
    });
  }
}
