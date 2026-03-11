"use strict";
$(window).on('load', function () {

  // scroll to bottom
  if ($('.messages-container').length > 0) {
    $('.messages-container')[0].scrollTop = $('.messages-container')[0].scrollHeight;
  }
});

$(document).ready(function () {

  // post form
  $('#postForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#postErrors ul').html(errors);
        $('#postErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#postErrors').offset().top - 100
        }, 1000);
      }
    });
  });


  // custom page form
  $('#pageForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#pageErrors ul').html(errors);
        $('#pageErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#pageErrors').offset().top - 100
        }, 1000);
      }
    });
  });


  // show or hide input field according to selected ad type
  $('.ad-types').on('change', function () {
    let adType = $(this).val();

    if (adType == 'banner') {
      if (!$('#slot-input').hasClass('d-none')) {
        $('#slot-input').addClass('d-none');
      }

      $('#image-input').removeClass('d-none');
      $('#url-input').removeClass('d-none');
    } else {
      if (!$('#image-input').hasClass('d-none') && !$('#url-input').hasClass('d-none')) {
        $('#image-input').addClass('d-none');
        $('#url-input').addClass('d-none');
      }

      $('#slot-input').removeClass('d-none');
    }
  });

  $('.edit-ad-type').on('change', function () {
    let adType = $(this).val();

    if (adType == 'banner') {
      if (!$('#edit-slot-input').hasClass('d-none')) {
        $('#edit-slot-input').addClass('d-none');
      }

      $('#edit-image-input').removeClass('d-none');
      $('#edit-url-input').removeClass('d-none');
    } else {
      if (!$('#edit-image-input').hasClass('d-none') && !$('#edit-url-input').hasClass('d-none')) {
        $('#edit-image-input').addClass('d-none');
        $('#edit-url-input').addClass('d-none');
      }

      $('#edit-slot-input').removeClass('d-none');
    }
  });


  // show different input field according to input type for digital product
  $('select[name="input_type"]').on('change', function () {
    let optionVal = $(this).val();

    if (optionVal == 'upload') {
      $('#file-input').removeClass('d-none');

      if (!$('#link-input').hasClass('d-none')) {
        $('#link-input').addClass('d-none');
      }
    } else if (optionVal == 'link') {
      $('#link-input').removeClass('d-none');

      if (!$('#file-input').hasClass('d-none')) {
        $('#file-input').addClass('d-none');
      }
    }
  });

  // show uploaded zip file name
  $('.zip-file-input').on('change', function (e) {
    let fileName = e.target.files[0].name;
    $('.zip-file-info').text(fileName);
  });

  // product form
  $('#productForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#productErrors ul').html(errors);
        $('#productErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#productErrors').offset().top - 100
        }, 1000);
      }
    });
  });


  // get subcategory of selected category for service
  $('.service-category').on('change', function () {
    $('.request-loader').addClass('show');

    let categoryId = $(this).val();
    let langCode = $(this).data('lang_code');

    let url = `${baseUrl}/admin/service-management/category/${categoryId}/get-subcategory`;

    $.get(url, function (response) {
      $('.request-loader').removeClass('show');

      if ('successData' in response) {
        $(`select[name="${langCode}_subcategory_id"]`).removeAttr('disabled');

        let subcategories = response.successData;
        let markup = `<option selected disabled>Select a Subcategory</option>`;

        if (subcategories.length > 0) {
          for (let index = 0; index < subcategories.length; index++) {
            markup += `<option value="${subcategories[index].id}">${subcategories[index].name}</option>`;
          }
        } else {
          markup += `<option disabled>No Subcategory Exist</option>`;
        }

        $(`select[name="${langCode}_subcategory_id"]`).html(markup);
      } else {
        alert(response.errorData);
      }
    });
  });

  $('.service-category-seller').on('change', function () {
    $('.request-loader').addClass('show');

    let categoryId = $(this).val();
    let langCode = $(this).data('lang_code');

    let url = `${baseUrl}/seller/service-management/category/${categoryId}/get-subcategory`;

    $.get(url, function (response) {
      $('.request-loader').removeClass('show');

      if ('successData' in response) {
        $(`select[name="${langCode}_subcategory_id"]`).removeAttr('disabled');

        let subcategories = response.successData;
        let markup = `<option selected disabled>Select a Subcategory</option>`;

        if (subcategories.length > 0) {
          for (let index = 0; index < subcategories.length; index++) {
            markup += `<option value="${subcategories[index].id}">${subcategories[index].name}</option>`;
          }
        } else {
          markup += `<option disabled>No Subcategory Exist</option>`;
        }

        $(`select[name="${langCode}_subcategory_id"]`).html(markup);
      } else {
        alert(response.errorData);
      }
    });
  });

  // service form
  $('#serviceForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#serviceErrors ul').html(errors);
        $('#serviceErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#serviceErrors').offset().top - 100
        }, 1000);
      }
    });
  });


  // uploaded file progress bar and file name preview
  $('.custom-file-input').on('change', function (e) {
    let file = e.target.files[0];
    let fileName = e.target.files[0].name;

    let fd = new FormData();
    fd.append('attachment', file);

    $.ajax({
      xhr: function () {
        let xhr = new window.XMLHttpRequest();

        xhr.upload.addEventListener('progress', function (ele) {
          if (ele.lengthComputable) {
            let percentage = ((ele.loaded / ele.total) * 100);
            $('.progress').removeClass('d-none');
            $('.progress-bar').css('width', percentage + '%');
            $('.progress-bar').html(Math.round(percentage) + '%');

            if (Math.round(percentage) === 100) {
              $('.progress-bar').addClass('bg-success');
              $('#attachment-info').text(fileName);
            }
          }
        }, false);

        return xhr;
      },
      url: $(this).data('url'),
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (res) {

      }
    });
  });

  // close ticket using swal start
  $('.closeBtn').on('click', function (e) {
    e.preventDefault();
    $('.request-loader').addClass('show');

    swal({
      title: 'Are you sure?',
      text: "You want to close this ticket!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, close it',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent('.ticketForm').submit();
      } else {
        swal.close();

        $('.request-loader').removeClass('show');
      }
    });
  });
  // close ticket using swal end
});

function searchFormSubmit(e) {
  if (e.keyCode == 13) {
    $('#searchForm').submit();
  }
}

$('body').on('change', '#seller_id_service', function () {
  var id = $(this).val();
  $('.request-loader').addClass('show');
  $('.seller_form').each(function () {
    var selector_class_id = $(this).attr('id');
    var lang_id = $(this).attr('data-lang_id');
    var data = {
      id: id,
      lang_id: lang_id
    };
    $('#' + selector_class_id + ' option').remove();
    $.get(form_get_url, data, function (response) {
      $.each(response, function (key, value) {
        $('#' + selector_class_id).append($('<option></option>').val(value.id).html(value.name));
      })
    })
  })
  $('.request-loader').removeClass('show');
})


