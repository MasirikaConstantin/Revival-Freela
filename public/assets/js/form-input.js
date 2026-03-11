new Vue({
  el: '#app',
  data: {
    optionCount: 0,
    inputType: 1,
    showPlaceholder: true,
    showOptionArea: false,
    optionsArray: optArr,
    showFileSize: false
  },
  methods: {
    addOption() {
      this.optionCount++;
    },
    removeOption() {
      this.optionCount--;
    },
    changeInputType() {
      let val = parseInt(this.inputType);

      if (val == 4 || val == 8) {
        this.showPlaceholder = false;
      } else {
        this.showPlaceholder = true;
      }

      if (val == 3 || val == 4) {
        this.showOptionArea = true;
      } else {
        this.showOptionArea = false;
      }

      if (val == 8) {
        this.showFileSize = true;
      } else {
        this.showFileSize = false;
      }
    },

    // this below methods are for edit page
    addOpt() {
      this.optionsArray.push(null);
    },
    rmvOpt(index) {
      this.optionsArray.splice(index, 1);
    }
  }
});

$(document).ready(function () {
  'use strict';

  // sort form input fields
  $('#sort-content').sortable({
    stop: function (event, ui) {
      $('.request-loader').addClass('show');

      let fd = new FormData();

      $('.ui-state-default.ui-sortable-handle').each(function (index) {
        fd.append('ids[]', $(this).data('id'));

        let orderNo = parseInt(index) + 1;
        fd.append('orders[]', orderNo);
      });

      $.ajax({
        url: sortURL,
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          $('.request-loader').removeClass('show');

        }
      });
    }
  });
});
