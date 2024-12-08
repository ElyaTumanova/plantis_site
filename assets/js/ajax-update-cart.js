/*--------------------------------------------------------------
# CART UPDATE  #CASH
--------------------------------------------------------------*/
//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования

// обновляем кол-во товаров в боковой корзине
function plntAjaxUpdateSideCart () {
  ( function ( $ ) {
      "use strict";
    // Define the PHP function to call from here
      var data = {
        'action': 'plnt_update_side_cart_count'
      };
      $.post(
        woocommerce_params.ajax_url, // The AJAX URL
        data, // Send our PHP function
        function(response){
          $('.side-cart__count').html(response); // Repopulate the specific element with the new content
        }
      );
      console.log('hi plntAjaxUpdateSideCart');
    // Close anon function.
    }( jQuery ) );
}

plntAjaxUpdateSideCart();

// обновляем кол-во товаров в корзине в хедере
function plntAjaxUpdateHeaderCart () {
  ( function ( $ ) {
      "use strict";
    // Define the PHP function to call from here
      var data = {
        'action': 'plnt_update_header_cart_count'
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
  console.log('hi plntAjaxUpdateHeaderCart');
}

plntAjaxUpdateHeaderCart();

// получаем корзину для обновления кнопок добавления в корзину
function plntAjaxGetMiniCart() {
  //console.log('hi get minicart');
  let miniCartDiv = document.createElement('div');
  //console.log(miniCartDiv);

  ( function ( $ ) {
    "use strict";
  // Define the PHP function to call from here
    var data = {
      'action': 'plnt_update_mini_cart'
    };
    $.post(
      woocommerce_params.ajax_url, // The AJAX URL
      data, // Send our PHP function
      function(response){
        $('.mini-cart').html(response); // Repopulate the specific element with the new content
        miniCartDiv.innerHTML = response;
        updateCatalogButtons(miniCartDiv);
      }
    );
  // Close anon function.
  }( jQuery ) );
}
  
plntAjaxGetMiniCart(); //функция используется в плагнах Load More и BeRocket filters

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
  
  // console.log(addToCartBtns.length);
  // console.log(miniCartItems.length);
  
  
  miniCartItems.forEach(item => {
    //console.log(element.dataset.product_id);
    productsInCartIds.push(item.dataset.product_id);
  });
  
  //console.log(productsInCartIds);
  
  addToCartBtns.forEach(button => {
    //console.log(button.dataset.product_id);
    if(productsInCartIds.includes(button.dataset.product_id)) {
      //console.log(button);
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