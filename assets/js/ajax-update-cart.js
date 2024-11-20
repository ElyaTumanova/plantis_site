/*--------------------------------------------------------------
# CART UPDATE 
--------------------------------------------------------------*/
//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования

function plnt_ajax_update_wish() {
  console.log('hi plnt_ajax_update_wish');
  jQuery( function( $ ) {
      $(document).ready( function() {
          $.get( yith_wcwl_l10n.ajax_url, {
          action: 'yith_wcwl_update_wishlist_count'
          }, function( data ) {
          $('.yith-wcwl-items-count').children('i').html( data.count );
          } );
      } );
  });
};

plnt_ajax_update_wish();

function get_wish_frahments() {
    
    $.ajax( {
        data: {
            action: yith_wcwl_l10n.actions.load_fragments,
            nonce: yith_wcwl_l10n.nonce.load_fragments_nonce,
            context: 'frontend',
            fragments: fragments
        },
        method: 'post',
        success: function( data ){
            if( typeof data.fragments !== 'undefined' ){
                replace_fragments( data.fragments );

                init_handling_after_ajax();

                $(document).trigger( 'yith_wcwl_fragments_loaded', [ fragments, data.fragments, search.firstLoad ] );

            }
        },
        url: yith_wcwl_l10n.ajax_url
    } );

};




( function ( $ ) {
    "use strict";
  // Define the PHP function to call from here
    var data = {
      'action': 'mode_theme_update_side_cart_count'
    };
    $.post(
      woocommerce_params.ajax_url, // The AJAX URL
      data, // Send our PHP function
      function(response){
        $('.side-cart__count').html(response); // Repopulate the specific element with the new content
      }
    );
  // Close anon function.
  }( jQuery ) );

( function ( $ ) {
    "use strict";
  // Define the PHP function to call from here
    var data = {
      'action': 'mode_theme_update_header_cart_count'
    };
    $.post(
      woocommerce_params.ajax_url, // The AJAX URL
      data, // Send our PHP function
      function(response){
        $('.header-cart__link .header__count').html(response); // Repopulate the specific element with the new content
      }
    );
  // Close anon function.
  }( jQuery ) );

  function getMiniCart() {
    console.log('hi get minicart');
    let responseText;
    let miniCartDiv = document.createElement('div');
    //console.log(miniCartDiv);
  
    ( function ( $ ) {
      "use strict";
    // Define the PHP function to call from here
    console.log('ajax mini cart');
      var data = {
        'action': 'mode_theme_update_mini_cart'
      };
      $.post(
        woocommerce_params.ajax_url, // The AJAX URL
        data, // Send our PHP function
        function(response){
          $('.mini-cart').html(response); // Repopulate the specific element with the new content
          responseText = response;
        }
      );
    // Close anon function.
    }( jQuery ) );
  
    setTimeout(() => {
      miniCartDiv.innerHTML = responseText;
      //console.log(miniCartDiv);
      updateCatalogButtons(miniCartDiv);
    }, 1000);
  }
  
  getMiniCart();

  /*--------------------------------------------------------------
# Update catalog add-to-cart buttons
--------------------------------------------------------------*/

function updateCatalogButtons(miniCartDiv) {

  // console.log('hiho');
  // console.log(miniCartDiv);
  let miniCartItems = miniCartDiv.querySelectorAll('.woocommerce-mini-cart-item .remove_from_cart_button');
  let addToCartBtns = document.querySelectorAll('.add_to_cart_button');
  let removeCartBtns = document.querySelectorAll('.remove_from_cart_button');
  let productsInCartIds = [];
  
  console.log(addToCartBtns.length);
  console.log(miniCartItems.length);
  
  
  miniCartItems.forEach(item => {
    //console.log(element.dataset.product_id);
    productsInCartIds.push(item.dataset.product_id);
  });
  
  //console.log(productsInCartIds);
  
  addToCartBtns.forEach(button => {
    //console.log(button.dataset.product_id);
    if(productsInCartIds.includes(button.dataset.product_id)) {
      console.log(button);
      button.innerHTML = 'Добавлен';
      button.setAttribute('href', button.dataset.remove_link);
      button.setAttribute('class', 'button product_type_simple remove_from_cart_button added');
    };
  });
  
  removeCartBtns.forEach(button => {
    if(productsInCartIds.includes(button.dataset.product_id)) {
      return
    } else {
      //console.log(button.dataset.product_id);
      button.innerHTML = 'В корзину';
      button.setAttribute('href', `?add-to-cart=${button.dataset.product_id}`);
      button.setAttribute('class', 'button product_type_simple add_to_cart_button ajax_add_to_cart');
    };
  });
  }
