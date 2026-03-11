'use strict';
$(window).on('load', function () {
  // scroll to bottom
  $('.chat-wrapper')[0].scrollTop = $('.chat-wrapper')[0].scrollHeight;
});


$(document).ready(function () {
  $(document).on('change', 'input[name="attachment"]', function (e) {
    let fileName = e.target.files[0].name;

    $('input[name="msg"]').attr('disabled', true);
    $('.progress').removeClass('d-none');
    $('#msg-form').submit();
  });

  $(document).on('submit', '#msg-form', function (e) {
    e.preventDefault();
    $('#msg-err').text('');
    let action = $(this).attr('action');
    let method = $(this).attr('method');
    let fd = new FormData($(this)[0]);

    $.ajax({
      xhr: function () {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener('progress', function (e) {
          if (e.lengthComputable) {
            var percent = Math.round((e.loaded / e.total) * 100);
            $('.progress-bar').width(percent + '%').text(percent + '%');
          }
        });
        return xhr;
      },
      url: action,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('#msg-form')[0].reset();
      },
      error: function (errRes) {
        for (let x in errRes.responseJSON.errors) {
          $('#msg-err').text(errRes.responseJSON.errors[x][0]);
          $('#msg-form')[0].reset();
          $('input[name="msg"]').attr('disabled', false);
          $('.progress').addClass('d-none');
        }
      }
    });
  });
});

// pusher js code
// Pusher.logToConsole = true;
var pusher = new Pusher(pusherKey, {
  cluster: pusherCluster
});

var channel = pusher.subscribe('message-channel');


channel.bind('message.stored', function (data) {

  // reload message wrapper div
  $('#reload-div').load(`${location.href} .message-wrapper`, function () {
    // scroll to bottom
    $('.chat-wrapper')[0].scrollTop = $('.chat-wrapper')[0].scrollHeight;
  });
});
