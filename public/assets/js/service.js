"use strict";
let prevGatewayId;

$(document).ready(function () {
  //first hid stripe element
  $('#stripe-element').addClass('d-none');

  $('body').on('submit', '#searchForm', function (e) {
    e.preventDefault();
    $('.service-container').empty();
    $('.request-loader').addClass('show');
    // Serialize the form data directly
    var fd = $(this).serialize(); // 'this' refers to the form being submitted

    $.ajax({
      url: searchUrl, // Replace with your actual route URL for search
      type: 'GET',
      data: fd,
      success: function (data) {
        // Update the services container with the filtered services
        $('.service-container').html(data);
        $('.request-loader').removeClass('show');
        $('#catModal').modal('hide');
      },
      error: function (xhr, status, error) {
        // Handle errors
        $('.request-loader').removeClass('show');
      }
    });
  });

  // Simplified form submission without the need for extra FormData usage
  $('#serviceSearch').on('submit', function (e) {
    e.preventDefault();
    var keyword = $('.input-search').val();
    $('#keyword-id').val(keyword);
    filterInputs();
  });
  $('.serviceSearchBtn').on('click', function (e) {
    $('#serviceSearch').submit();
  });
  // search service by filtering the pricing
  $('.pricing-search').on('change', function () {
    let value = $(this).val();
    $('#pricing-id').val(value);
    filterInputs();
  });
  $('.delivery_time').on('change', function () {
    let value = $(this).val();
    $('#delivery_time').val(value);
    filterInputs();
  });

  //filter price
  // range slider init
  if (typeof position != 'undefined' && typeof symbol != 'undefined' &&
    typeof min_price != 'undefined' && typeof max_price != 'undefined' &&
    typeof curr_min != 'undefined' && typeof curr_max != 'undefined') {
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
  //filter price end


  // search service by filtering the rating
  $('.rating-search').on('change', function () {
    let value = $(this).val();

    $('#rating-id').val(value);
    filterInputs();
  });
  // search service by click on tag
  $('.tag-search').on('click', function (e) {
    e.preventDefault();
    let value = $(this).data('tag');

    $('#tag-id').val(value);
    filterInputs();
  });
  // search service by sorting
  $('#sort-search').on('change', function () {
    let value = $(this).val();

    $('#sort-id').val(value);
    filterInputs();
  });
  // category wise search
  $('.category-search').on('click', function (e) {
    e.preventDefault();

    // Find the parent li element and its sibling li elements
    var $parentLi = $(this).closest('li');
    var $siblingsLi = $parentLi.siblings('li');

    // Remove 'd-none' class from the clicked element's child ul and add 'd-none' to siblings' ul
    $('.category-search').removeClass('active');
    $(this).addClass('active');

    let value = $(this).data('category_slug');
    $('#subcategory-id').val('');

    $('#category-id').val(value);
    filterInputs();
  });

  function attachEventHandlers() {
    // Remove all previous event handlers
    $('.widget-categories .cat-item').off('click mouseenter mouseleave');

    if (window.innerWidth < 1200) {
      // If screen width is greater than 1200px, use click event
      $('.widget-categories .cat-item').on('click', handleEvent);
    } else {
      // If screen width is 1200px or less, use hover event
      $('.widget-categories .cat-item').hover(handleEvent);
    }
  }

  function handleEvent(e) {
    e.preventDefault();

    // Find the parent li element and its sibling li elements
    var $this = $(this);
    var subCategory = $this.find('.widget-subcategories');

    // Close other open subcategories
    $('.widget-subcategories.open').not(subCategory).removeClass('open');

    // Toggle the open class for the clicked/entered subcategory
    subCategory.toggleClass('open');
  }

  // Attach initial event handlers
  attachEventHandlers();

  // Update event handlers on window resize
  $(window).on('resize', function () {
    attachEventHandlers();
  });



  $('.category-search-modal').on('click', function (e) {
    e.preventDefault();
    $('.category-search-modal').removeClass('active');
    $('.category-search').removeClass('active');
    $('.subcategory-search').removeClass('active');
    $('.subcategory-search-modal').removeClass('active');
    let value = $(this).data('category_slug');
    $(this).addClass('active');
    if (value) {
      $('#subcategory-id').val('');
    }

    $('#category-id').val(value);
    filterInputs();
  });


  $('.subcategory-search').on('click', function (e) {
    e.preventDefault();

    // Remove 'active' class from all .subcategory-search elements
    $('.subcategory-search').removeClass('active');

    // Add 'active' class to the clicked .subcategory-search element
    $(this).addClass('active');
    let value = $(this).data('subcategory_slug');

    $('#subcategory-id').val(value);
    filterInputs();
  });

  $('.subcategory-search-modal').on('click', function (e) {
    e.preventDefault();

    // Remove 'active' class from all .subcategory-search elements
    $('.subcategory-search').removeClass('active');
    $('.subcategory-search-modal').removeClass('active');
    let value = $(this).data('subcategory_slug');
    let c_value = $(this).data('category_slug');
    $('.category-search-modal').removeClass('active');
    $(this).closest('.menu-list').find('.category-search-modal').addClass('active');
    $(this).addClass('active');


    $('#category-id').val(c_value);
    $('#subcategory-id').val(value);
    filterInputs();
  });

  //select 2 init and search skills
  var $eventSelect = $(".js-select2");

  $eventSelect.select2({
    placeholder: selectSkills,
    allowClear: true
  });
  $eventSelect.on("change", function (e) {
    var selectedOptions = $(this).val();
    if (selectedOptions.length < 1) {
      $('#skills').html('');
      filterInputs();
    } else {
      $('#skills').html(JSON.stringify(selectedOptions));
      filterInputs();
    }
  });
  //select 2 init and search skills end


  // Listen for click events on pagination links
  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var page_number = $(this).attr('href').split('page=')[1];
    e.preventDefault();
    $('.service-container').empty();
    $('.request-loader').addClass('show');

    var serializedData = $('#searchForm').serializeArray(); // Serialize the form data as an array

    // Filter out empty fields from the serialized data
    serializedData = serializedData.filter(function (item) {
      return item.value !== '';
    });

    // Convert filtered array back to serialized string
    serializedData = $.param(serializedData);

    serializedData += '&page=' + page_number; // Append page_number as a query parameter

    $.ajax({
      url: searchUrl, // Replace with your actual route URL for search
      type: 'GET',
      data: serializedData, // Use the serialized data with appended page number
      success: function (data) {
        // Update the services container with the filtered services
        $('.service-container').html(data);
        $('.request-loader').removeClass('show');
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(xhr.responseText);
        $('.request-loader').removeClass('show');
      }
    });
  });




  // remove empty input field from search-form and, then submit the search form
  function filterInputs() {
    $('#searchForm').submit();
  }
  $('.reset-search').on('click', function (e) {
    e.preventDefault();
    $('.request-loader').addClass('show');
    $.ajax({
      url: searchUrl, // Replace with your actual route URL for search
      type: 'GET',
      success: function (data) {
        // Update the services container with the filtered services
        $('.service-container').html(data);
        $('.request-loader').removeClass('show');
        //empty all hidden input values
        $("#searchForm input, #searchForm textarea").val('');
        //empty all skills
        $("#js-select2").val([]).trigger("change");
        //empty all skills
        $(".delivery_time option").prop("selected", false);
        // Update NiceSelect to reflect the changes
        $(".delivery_time option:last").prop("selected", true);
        $(".delivery_time").niceSelect('update');

        //remove input search value
        $('.input-search').val('');

        //reset sort by value
        $("#sort-search option").prop("selected", false);
        // Update NiceSelect to reflect the changes
        $("#sort-search option:first").prop("selected", true);
        $("#sort-search").niceSelect('update');
      },
      error: function (xhr, status, error) {
        $('.request-loader').removeClass('show');
      }
    });
    //replace url by original
    var url = serviceUrl;
    var newUrl = removeQueryParams(url);
    history.replaceState({}, '', newUrl);
    //select 2 reset
    $eventSelect.val(null).trigger("change");
    $('#alls').prop('checked', true);
    $('#all').prop('checked', true);
    //re init range slider
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
  })
  // Function to remove all parameters from URL
  function removeQueryParams(url) {
    var urlParts = url.split('?');
    return urlParts[0]; // Remove everything after the base URL
  }


  //service details page and checkout page
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
        $('.iyzico-element').addClass('d-none');
      } if (value == 'iyzico') {
        $('.iyzico-element').removeClass('d-none');
        $('#stripe-element').addClass('d-none');
      } else {
        $('#stripe-element').addClass('d-none');
        $('.iyzico-element').addClass('d-none');
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
      if (!$('.iyzico-element').hasClass('d-none')) {
        $('.iyzico-element').addClass('d-none');
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
    $('.review-value span').css('color', '#777777');

    // second, add '#FBA31C' color to the selected parent class
    let parentClass = `review-${ratingValue}`;
    $(`.${parentClass} span`).css('color', '#FBA31C');

    // finally, set the rating value to a hidden input field
    $('#rating-id').val(ratingValue);
  });


  //payment gateway js start

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
  //payment gateway js end
})
