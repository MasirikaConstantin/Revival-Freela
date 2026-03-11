"use strict";
$('body').on('change', '#withdraw_method', function () {
  $.get(baseUrl + '/seller/withdraw/get-method/input/' + $(this).val(), function (data) {
    $('#appned_input').html('');
    var input = '';
    $.each(data, function (key, value) {
      if (value.required == 1) {
        var required = '*'
      } else {
        var required = '';
      }
      if (value.type == 1) {
        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='text' class='form-control' id="${value.name}" name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
      } else if (value.type == 2) {
        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <select class="form-control" id="${value.name}" name="${value.name}">`;
        $.each(value.options, function (k, option) {
          input += `<option value="${option.name}">${option.name}</option>`;
        })
        input += `</select>
            <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
      } else if (value.type == 3) {

        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <div class="custom-control custom-checkbox">`;
        $.each(value.options, function (k, option) {
          input += `<div class="custom-control custom-checkbox">
                      <input type="checkbox" id="customRadio${option.id}" name="${value.name}"
                        class="custom-control-input" value="${option.name}">
                    <label class="custom-control-label"
                        for="customRadio${option.id}">${option.name}</label>
                      </div>`;
        })
        input += `</div><p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p> </div>`;
      } else if (value.type == 4) {
        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <textarea class="form-control" id="${value.name}" name="${value.name}" placeholder="${value.placeholder}"></textarea>
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
      } else if (value.type == 5) {
        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='date' class='form-control' name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
      } else if (value.type == 6) {
        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='time' class='form-control' name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
      } else if (value.type == 7) {
        input += `<div class='form-group'>
                  <label>${value.label} ${required}</label>
                  <input type='number' class='form-control' id="${value.name}" name="${value.name}" placeholder="${value.placeholder}">
                  <p id="err_${value.name}" class="mt-2 mb-0 text-danger em"></p>
                </div>`;
      }
    });

    $('#appned_input').html(input);
  });

  $("#receive_amount").html('');
  $("#total_charge").html('');
  $("#your_balance").html('');

  var method = $(this).val();

  $.get(baseUrl + '/seller/withdraw/balance-calculation/' + method + '/' + $('#withdraw_amount').val(), function (data) {
    $("#receive_amount").html(data.receive_balance);
    $("#total_charge").html(data.total_charge);
    $("#your_balance").html(data.user_balance);
  })
})

$('body').on('blur', '#withdraw_amount', function () {

  $("#receive_amount").html('');
  $("#total_charge").html('');
  $("#your_balance").html('');

  var method = $('#withdraw_method').val();

  $.get(baseUrl + '/seller/withdraw/balance-calculation/' + method + '/' + $(this).val(), function (data) {
    $("#receive_amount").html(data.receive_balance);
    $("#total_charge").html(data.total_charge);
    $("#your_balance").html(data.user_balance);
  })
});

$('body').on('change', '#withdraw_amount', function () {

  $("#receive_amount").html('');
  $("#total_charge").html('');
  $("#your_balance").html('');

  var method = $('#withdraw_method').val();

  $.get(baseUrl + '/seller/withdraw/balance-calculation/' + method + '/' + $(this).val(), function (data) {
    $("#receive_amount").html(data.receive_balance);
    $("#total_charge").html(data.total_charge);
    $("#your_balance").html(data.user_balance);
  })
});
$('body').on('keyup', '#withdraw_amount', function () {

  $("#receive_amount").html('');
  $("#total_charge").html('');
  $("#your_balance").html('');

  var method = $('#withdraw_method').val();

  $.get(baseUrl + '/seller/withdraw/balance-calculation/' + method + '/' + $(this).val(), function (data) {
    $("#receive_amount").html(data.receive_balance);
    $("#total_charge").html(data.total_charge);
    $("#your_balance").html(data.user_balance);
  })
});
