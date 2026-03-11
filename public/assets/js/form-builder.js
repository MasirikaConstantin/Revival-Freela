"use strict";
$(function () {
  $("#sortable").sortable({
    stop: function (event, ui) {
      $(".request-loader").addClass('show');
      let fd = new FormData();
      $(".ui-state-default.ui-sortable-handle").each(function (index) {
        fd.append('ids[]', $(this).data('id'));
        let order = parseInt(index) + 1
        fd.append('orders[]', order);
      });
      $.ajax({
        url: order_url,
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          $(".request-loader").removeClass('show');
        }
      })
    }
  });
  $("#sortable").disableSelection();
});
