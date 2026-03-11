'use strict';
let prevGatewayId;

$(document).ready(function () {
  // remove all search filters except category & subcategory filter
  function resetForm(selectionType) {
    $('#keyword-id').remove();
    $('#tag-id').remove();
    $('#rating-id').remove();
    $('#min-id').remove();
    $('#max-id').remove();
    $('#sort-id').remove();

    if (selectionType == 'category') {
      $('#subcategory-id').remove();
    }
  }


  // remove empty input field from search-form and, then submit the search form
  function filterInputs() {
    $('input[type="hidden"]').each(function () {
      if (!$(this).val()) {
        $(this).remove();
      }
    });

    $('#submitBtn').trigger('click');
  }


  // search service by typing in the input field
  $('#input-search').on('keypress', function (e) {
    if (e.which == 13) {
      let value = $(this).val();

      if (value == '') {
        alert('Please enter something.');
      } else {
        $('#keyword-id').val(value);
        filterInputs();
      }
    }
  });


  // search service by click on category
  $('.category-search').on('click', function (e) {
    e.preventDefault();

    resetForm('category');

    let value = $(this).data('category_slug');

    $('#category-id').val(value);
    $('#submitBtn').trigger('click');
  });


  // search service by click on subcategory
  $('.subcategory-search').on('click', function (e) {
    e.preventDefault();

    resetForm('subcategory');

    let value = $(this).data('subcategory_slug');

    $('#subcategory-id').val(value);
    $('#submitBtn').trigger('click');
  });


  // search service by click on tag
  $('.tag-search').on('click', function (e) {
    e.preventDefault();
    let value = $(this).data('tag');

    $('#tag-id').val(value);
    filterInputs();
  });


  // search service by filtering the pricing
  $('.pricing-search').on('change', function () {
    let value = $(this).val();
    $('#pricing-id').val(value);
    filterInputs();
  });

  // search service by filtering the rating
  $('.rating-search').on('change', function () {
    let value = $(this).val();

    $('#rating-id').val(value);
    filterInputs();
  });


  // range slider init
  if (
    typeof position != 'undefined' && typeof symbol != 'undefined' &&
    typeof min_price != 'undefined' && typeof max_price != 'undefined' &&
    typeof curr_min != 'undefined' && typeof curr_max != 'undefined'
  ) {
    // initialization is here
    $('#range-slider').slider({
      range: true,
      min: min_price,
      max: max_price,
      values: [curr_min, curr_max],
      slide: function (event, ui) {
        // while the slider moves, then this function will show that range value
        $('#amount').val((position == 'left' ? symbol : '') + ui.values[0] + (position == 'right' ? symbol : '') + ' - ' + (position == 'left' ? symbol : '') + ui.values[1] + (position == 'right' ? symbol : ''));
      }
    });

    // initially this is showing the price range value
    $('#amount').val((position == 'left' ? symbol : '') + $('#range-slider').slider('values', 0) + (position == 'right' ? symbol : '') + ' - ' + (position == 'left' ? symbol : '') + $('#range-slider').slider('values', 1) + (position == 'right' ? symbol : ''));

    // search service by filtering the price
    $('#range-slider').on('slidestop', function () {
      let value = $('#amount').val();

      let priceArray = value.split('-');
      let minPrice = parseFloat(priceArray[0].replace(symbol, ' '));
      let maxPrice = parseFloat(priceArray[1].replace(symbol, ' '));

      $('#min-id').val(minPrice);
      $('#max-id').val(maxPrice);
      filterInputs();
    });
  }


  // search service by sorting
  $('#sort-search').on('change', function () {
    let value = $(this).val();

    $('#sort-id').val(value);
    filterInputs();
  });


  let data = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

  // add checked addon price with package price
  $('.service-addon').on('change', function () {
    let addonPrice = $(this).data('addon_price');

    let packageId = $(this).data('package_id');

    let packagePrice = $('#package-' + packageId + '-price').text();

    let newTotal;
    let packagePrevPrice;
    let newPrevTotal;

    if ($('#package-' + packageId + '-prev_price').length > 0) {
      packagePrevPrice = $('#package-' + packageId + '-prev_price').text();
    }

    if ($(this).prop('checked') == true) {
      // calculate new current total
      newTotal = parseFloat(packagePrice) + parseFloat(addonPrice);

      // calculate new previous total
      if ($('#package-' + packageId + '-prev_price').length > 0) {
        newPrevTotal = parseFloat(packagePrevPrice) + parseFloat(addonPrice);
      }
    } else if ($(this).prop('checked') == false) {
      // calculate new current total
      newTotal = parseFloat(packagePrice) - parseFloat(addonPrice);

      // calculate new previous total
      if ($('#package-' + packageId + '-prev_price').length > 0) {
        newPrevTotal = parseFloat(packagePrevPrice) - parseFloat(addonPrice);
      }
    }

    $('#package-' + packageId + '-price').text(newTotal.toLocaleString(undefined, data));

    if ($('#package-' + packageId + '-prev_price').length > 0) {
      $('#package-' + packageId + '-prev_price').text(newPrevTotal.toLocaleString(undefined, data));
    }
  });


  /**
   * show or hide payment gateway input fields,
   * also show or hide offline gateway informations according to checked payment gateway
   */
  $('select[name="gateway"]').on('change', function () {
    let value = $(this).val();
    let gatewayType = $(this).find(':selected').data('gateway_type');
    let hasAttachment = $(this).find(':selected').data('has_attachment');

    if (gatewayType == 'online') {
      // hide previously selected gateway
      if (prevGatewayId) {
        $(`#gateway-attachment-${prevGatewayId}`).hide();
        $(`#gateway-description-${prevGatewayId}`).hide();
        $(`#gateway-instructions-${prevGatewayId}`).hide();
      }

      // show or hide 'stripe' form
      if (value == 'stripe') {
        $('#stripe-element').removeClass('d-none');
      } else {
        $('#stripe-element').addClass('d-none');
      }

      // show or hide 'authorize.net' form
      if (value == 'authorize.net') {
        $('#authorizenet-form').show();
        $('#authorizenet-form input').removeAttr('disabled');
      } else {
        $('#authorizenet-form').hide();
        $('#authorizenet-form input').attr('disabled', true);
      }
    } else {
      // hide 'stripe' & 'authorize.net' form
      if (!$('#stripe-element').hasClass('d-none')) {
        $('#stripe-element').addClass('d-none');
        $('#stripe-element').removeClass('d-block');
      }


      $('#authorizenet-form').hide();
      $('#authorizenet-form input').attr('disabled', true);

      // hide previously selected gateway
      if (prevGatewayId) {
        $(`#gateway-attachment-${prevGatewayId}`).hide();
        $(`#gateway-description-${prevGatewayId}`).hide();
        $(`#gateway-instructions-${prevGatewayId}`).hide();
      }

      // show attachment input field, description & instructions of offline gateway
      if (hasAttachment == 1) {
        $(`#gateway-attachment-${value}`).show();
      }
      $(`#gateway-description-${value}`).show();
      $(`#gateway-instructions-${value}`).show();

      prevGatewayId = value;
    }
  });


  $('#payment-form-btn').on('click', function (e) {
    e.preventDefault();

    let gateway = $('select[name="gateway"]').val();

    if (gateway == 'authorize.net') {
      sendPaymentDataToAnet();
    } else if (gateway == 'stripe') {
      paymentForStripe();
    } else {
      $('#payment-form').submit();
    }
  });


  // get the star rating value in integer
  $('.review-value').on('click', function () {
    let ratingValue = $(this).attr('data-ratingVal');

    // first, remove '#FBA31C' color and add '#777777' color to the star
    $('.review-value').css('color', '#777777');

    // second, add '#FBA31C' color to the selected parent class
    let parentClass = `review-${ratingValue}`;
    $(`.${parentClass} span`).css('color', '#FBA31C');

    // finally, set the rating value to a hidden input field
    $('#rating-id').val(ratingValue);
  });


  // update services in the wishlist
  $('.wishlist-link').on('click', function (event) {
    event.preventDefault();

    let _this = $(this);

    let url = $(this).attr('href');
    let element = $(this).data('element_type');

    let data = {
      _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.post(url, data, function (response) {
      if ('login_route' in response) {
        window.location = response.login_route;
      } else {
        if (element == 'icon') {
          _this.children().toggleClass('added-in-wishlist');
        } else {
          if (response.status == 'Added') {
            _this.children("span").text(rmvBtnTxt);
          } else {
            _this.children("span").text(addBtnTxt);
          }

          _this.blur();
        }

        toastr['success'](response.message);
      }
    });
  });
});




// Authorize.Net js code
function sendPaymentDataToAnet() {
  // set up authorisation to access the gateway.
  var authData = {};
  authData.clientKey = clientKey;
  authData.apiLoginID = loginId;

  var cardData = {};
  cardData.cardNumber = document.getElementById('cardNumber').value;
  cardData.month = document.getElementById('expMonth').value;
  cardData.year = document.getElementById('expYear').value;
  cardData.cardCode = document.getElementById('cardCode').value;

  // now send the card data to the gateway for tokenisation.
  // The responseHandler function will handle the response.
  var secureData = {};
  secureData.authData = authData;
  secureData.cardData = cardData;
  Accept.dispatchData(secureData, responseHandler);
}

function responseHandler(response) {
  if (response.messages.resultCode === 'Error') {
    var i = 0;
    let errors = ``;

    while (i < response.messages.message.length) {
      errors += `<p class="text-danger" style="margin-bottom: 5px; list-style-type: disc;">
        ${response.messages.message[i].text}
      </p>`;

      i = i + 1;
    }

    $('#anetErrors').html(errors);
    $('#anetErrors').show();
  } else {
    paymentFormUpdate(response.opaqueData);
  }
}

function paymentFormUpdate(opaqueData) {
  document.getElementById('opaqueDataDescriptor').value = opaqueData.dataDescriptor;
  document.getElementById('opaqueDataValue').value = opaqueData.dataValue;
  document.getElementById('payment-form').submit();
}
function paymentForStripe() {
  stripe.createToken(cardElement).then(function (result) {
    if (result.error) {
      // Display errors to the customer
      var errorElement = document.getElementById('stripe-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server
      stripeTokenHandler(result.token);
    }
  });
}

if (typeof stripe_key != 'undefined') {

  // Set your Stripe public key
  var stripe = Stripe(stripe_key);
  // Create a Stripe Element for the card field
  var elements = stripe.elements();

  var cardElement = elements.create('card', {
    style: {
      base: {
        iconColor: '#454545',
        color: '#454545',
        fontWeight: '500',
        lineHeight: '50px',
        fontSmoothing: 'antialiased',
        backgroundColor: '#f2f2f2',
        ':-webkit-autofill': {
          color: '#454545',
        },
        '::placeholder': {
          color: '#454545',
        },
      }
    },
  });

  // Add an instance of the card Element into the `card-element` div
  cardElement.mount('#stripe-element');
  // Send the token to your server
}
function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  document.getElementById('payment-form').submit();
}

