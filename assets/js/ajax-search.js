jQuery(function ($){
    $('.search-form input[name="s"]').on('keyup', function (){  //стили контейнеров формы поиска из header
        var search = $('.search-form input[name="s"]').val();
        if (search.length <4) {
            return false; //ajax works after 4 digits input
        }
        var data = {
            s: search,
            action: 'search-ajax',
            nonce: search_form.nonce
        };
        $.ajax ({
            url: search_form.url,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
            },
            success: function(data){
                // console.log(data);
                $('.search .search-result').html(data.out);    //стили контейнеров формы поиска из header
            }
        });
    });
});

/*--------------------------------------------------------------
# CART UPDATE 
--------------------------------------------------------------*/
//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования

( function ( $ ) {
    "use strict";
   // Define the PHP function to call from here
    var data = {
      'action': 'mode_theme_update_mini_cart'
    };
    $.post(
      woocommerce_params.ajax_url, // The AJAX URL
      data, // Send our PHP function
      function(response){
        $('.mini-cart').html(response); // Repopulate the specific element with the new content
      }
    );
   // Close anon function.
   }( jQuery ) );
   
   
   jQuery( function( $ ) {
       $(document).ready( function() {
           $.get( yith_wcwl_l10n.ajax_url, {
           action: 'yith_wcwl_update_wishlist_count'
           }, function( data ) {
           $('.yith-wcwl-items-count').children('i').html( data.count );
           } );
       } );
   });
   
   
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
