//function to load books and display response
if (jQuery('.json-response').length > 0) {
  if (typeof(ajax) != "undefined") {
    jQuery.ajax({
      url: ajax.url,
      data: { action: 'testAction', nonce: ajax.nonce },
      success: function(response) {
        jQuery('.json-response').html(response);
      }
    });
  }
}

//onclick function that tries to create post
jQuery('.create-post').on('click', function () {
  jQuery.ajax({
    url: ajax.url,
    data: { action: 'createPost', nonce: ajax.nonce },
    success: function(response) {
      jQuery('.json-response').html(response);
    }
  });
});

//onclick function that put product into cart using api
jQuery('.product-to-cart1').on('click', function () {
  jQuery.post({
    url: '/wp-json/wc/store/cart/add-item',
    headers: {
      'X-WC-Store-API-Nonce': ajax.api_nonce
    },
    data: {
      id : 11,
      quantity: 1
    },
    dataType: 'json', 
    success: function(response) {
      jQuery('.json-response').html(JSON.stringify(response.items));
    }
  });
});

//using own function
jQuery('.product-to-cart').on('click', function () {
  jQuery.ajax({
    url: ajax.url,
    data: { action: 'productToCart', nonce: ajax.api_nonce },
    success: function(response) {
      jQuery('.json-response').html(response);
    }
  });
});

//function to create Product
jQuery('.create-product').on('click', function () {
  jQuery.ajax({
    url: ajax.url,
    data: { action: 'createProduct', nonce: ajax.nonce },
    success: function(response) {
      jQuery('.json-response').html(response);
    }
  });
});