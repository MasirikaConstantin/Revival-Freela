'use strict';
$(document).ready(function () {


  // show selected user information
  $('#choose-user').on('change', function () {
    let fullName = $(this).find(':selected').data('full_name');
    let phoneNumber = $(this).find(':selected').data('phone_number');
    let emailAddress = $(this).find(':selected').data('email_address');
    let fullAddress = $(this).find(':selected').data('full_address');

    $('input[name="user_full_name"]').val(fullName);
    $('input[name="user_phone_number"]').val(phoneNumber);
    $('input[name="user_email_address"]').val(emailAddress);
    $('input[name="user_address"]').val(fullAddress);
  });


  // summation of unit prices to get the total
  $(document).on('input', '.quantity', function () {
    calcTotal();
  });

  $(document).on('input', '.unit-price', function () {
    calcTotal();
  });


  // new subtotal calculation
  $(document).on('input', 'input[name="discount"]', function () {
    calcSubtotal();
  });


  // calculation of grand-total after giving tax input
  $(document).on('input', 'input[name="tax"]', function () {
    let subtotal = parseFloat($('input[name="subtotal"]').val());

    calcGrandTotal(subtotal);
  });


  // invoice form
  $('#invoiceForm').on('submit', function (e) {
    e.preventDefault();
    $('.request-loader').addClass('show');

    let action = $(this).attr('action');
    let method = $(this).attr('method');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: method,
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

        $('#invoiceErrors ul').html(errors);
        $('#invoiceErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#invoiceErrors').offset().top - 100
        }, 1000);
      }
    });
  });
});

function calcTotal() {
  let quantities = $('.quantity').map(function () {
    return $(this).val();
  }).get();

  let unitPrices = $('.unit-price').map(function () {
    return $(this).val();
  }).get();

  // total
  let total = 0.00;

  if (quantities.length === unitPrices.length) {
    for (let index = 0; index < quantities.length; index++) {
      let perItemTotal = parseInt(quantities[index]) * parseFloat(unitPrices[index]);
      total += perItemTotal;
    }
  }

  $('input[name="total"]').val(total.toFixed(2));

  // subtotal
  let discount = $('input[name="discount"]').val();

  if (discount) { // when discount has value
    let subtotal = total - parseFloat(discount);

    $('input[name="subtotal"]').val(subtotal.toFixed(2));

    calcGrandTotal(subtotal);
  } else { // when discount has no value
    $('input[name="subtotal"]').val(total.toFixed(2));

    calcGrandTotal(total);
  }
}

function calcSubtotal() {
  let total = $('input[name="total"]').val();
  let discount;

  if ($('input[name="discount"]').val()) {
    discount = $('input[name="discount"]').val();
  } else {
    discount = '0.00';
  }

  let subtotal = parseFloat(total) - parseFloat(discount);

  $('input[name="subtotal"]').val(subtotal.toFixed(2));

  calcGrandTotal(subtotal);
}

// calculate grand total
function calcGrandTotal(amount) {
  // grand total
  let taxPercentage = $('input[name="tax"]').val();

  if (taxPercentage) { // when tax has value
    let tax = amount * (parseFloat(taxPercentage) / 100);
    let grandTotal = amount + tax;

    $('input[name="grand_total"]').val(grandTotal.toFixed(2));
  } else { // when tax has no value
    $('input[name="grand_total"]').val(amount.toFixed(2));
  }
}

new Vue({
  el: '#app',
  data: {
    items: []
  },
  methods: {
    addItem() {
      const randNum = Math.floor(Math.random() * 100) + 1;
      this.items.push({
        'unqId': randNum
      });
    },
    removeItem(index) {
      this.items.splice(index, 1);

      setTimeout(() => {
        calcTotal();
      }, 200);
    }
  },
  mounted() {
    if (invoiceEdit === true) {
      axios
        .get(url)
        .then(response => {
          const itemArr = response.data.all_items;

          for (let i = 0; i < itemArr.length; i++) {
            // format string to decimal point number
            let unitPrice = itemArr[i].unit_price;
            let price = (Math.round(unitPrice * 100) / 100).toFixed(2);

            this.items.push({
              'title': itemArr[i].title,
              'quantity': itemArr[i].quantity,
              'unit_price': price
            });
          }
        })
        .catch();
    }
  }
});
