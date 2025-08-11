/*--------------------------------------------------------------
# CART UPDATE  #CASH
--------------------------------------------------------------*/
//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования


// обновляем кол-во товаров в корзине в хедере в десктопе и в моб и боковой корзины - код повторяется в cart.min.js
// function plntAjaxUpdateCartCount() {
//   ( function ( $ ) {
//       "use strict";
//     // Define the PHP function to call from here
//       var data = {
//         'action': 'plnt_update_header_cart_count'
//       };
//       $.get(
//         woocommerce_params.ajax_url, // The AJAX URL
//         data, // Send our PHP function
//         function(response){
//           console.log(response);
//           $('.header-cart__link .header__count').html(response); // Repopulate the specific element with the new content
//           $('.header-cart__mob .header__count').html(response); // Repopulate the specific element with the new content
//           $('.side-cart__count').html(response);
//           if (response >0) {
//             $('.header__main .header-cart .header-btn__wrap').addClass("header-btn__wrap_active");
//             $('.header__main .header-cart .header__count').addClass("header__count_active");
//             $('.header__nav-wrap .header-cart .header-btn__wrap').addClass("header-btn__wrap_active");
//             $('.header__nav-wrap .header-cart .header__count').addClass("header__count_active");
//           }
//         }
//       );
//   // Close anon function.
//   }( jQuery ) );
// }

//plntAjaxUpdateCartCount();

// получаем корзину для обновления кнопок добавления в корзину
//функция используется в плагнах Load More и BeRocket filters
function plntAjaxGetWishMiniCart() {
  // console.log('hi get minicart');
  let miniCartDiv = document.createElement('div');
  //console.log(miniCartDiv);

  ( function ( $ ) {
    "use strict";
  // Define the PHP function to call from here
    var data = {
      'action': 'plnt_update_mini_cart'
    };
    $.get(
      woocommerce_params.ajax_url, // The AJAX URL
      data, // Send our PHP function
      function(response){
        //console.log(response);
        $('.mini-cart').html(response.mini_cart); // Repopulate the specific element with the new content
        //console.log(response);
        miniCartDiv.innerHTML = response.mini_cart;
        updateCatalogButtons(miniCartDiv);

        $('.header-cart__link .header__count').html(response.cart_count); // Repopulate the specific element with the new content
        $('.header-cart__mob .header__count').html(response.cart_count); // Repopulate the specific element with the new content
        $('.side-cart__count').html(response.cart_count);
        if (response.cart_count >0) {
          $('.header__main .header-cart .header-btn__wrap').addClass("header-btn__wrap_active");
          $('.header__main .header-cart .header__count').addClass("header__count_active");
          $('.header__nav-wrap .header-cart .header-btn__wrap').addClass("header-btn__wrap_active");
          $('.header__nav-wrap .header-cart .header__count').addClass("header__count_active");
        }

        updateWishBtns(response.wish);

        $('.yith-wcwl-items-count').children('i').html( response.count );
        if (response.count > 0) {
            $('.header__main .header__wishlist .header-btn__wrap').addClass("header-btn__wrap_active");
            $('.header__nav-wrap .header__wishlist .header-btn__wrap').addClass("header-btn__wrap_active");
            $('.header__main .header__wishlist .header__count').addClass("header__count_active");
            $('.header__nav-wrap .header__wishlist .header__count').addClass("header__count_active");
        }
        
      }
    );
  // Close anon function.
  }( jQuery ) );
} 

document.addEventListener('DOMContentLoaded', plntAjaxGetWishMiniCart);

/*--------------------------------------------------------------
# Update catalog add-to-cart buttons
--------------------------------------------------------------*/

function updateCatalogButtons(miniCartDiv) {

  //console.log('hi updateCatalogButtons');
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
    if(productsInCartIds.includes(button.dataset.product_id) && button.dataset.categoryName != 'Пересадка') {
      let addToCartForm = button.parentElement;
      if(addToCartForm.matches('form')) {
          addToCartForm.setAttribute('action', button.dataset.remove_link);
      } 
      if(button.matches('a')) {
          button.setAttribute('href', button.dataset.remove_link);
      } 
      button.setAttribute('class', 'button product_type_simple remove_from_cart_button added');
      button.innerHTML = 'Добавлен';
    };
  });
  
  removeCartBtns.forEach(button => {
    if(productsInCartIds.includes(button.dataset.product_id)) {
      return
    } else {
      //console.log(button.dataset.product_id);
      let addToCartForm = button.parentElement;
      if(addToCartForm.matches('form')) {
          addToCartForm.setAttribute('action', `?add-to-cart=${button.dataset.product_id}`);
      } 
      if(button.matches('a')) {
          button.setAttribute('href', `?add-to-cart=${button.dataset.product_id}`);
      }
      button.innerHTML = 'В корзину';
      button.setAttribute('class', 'button product_type_simple add_to_cart_button ajax_add_to_cart');
    };
  });
}

/*--------------------------------------------------------------
# Update catalog wishlist buttons
--------------------------------------------------------------*/

function updateWishBtns(wishListItemsStr) {
    //console.log('hi updateWishBtns');
   // console.log(wishListItemsStr);
   if(wishListItemsStr) {
        let wishListItems = wishListItemsStr.split(',');
        //console.log(wishListItems);
        let addToWishBtns = document.querySelectorAll('.yith-wcwl-add-button .add_to_wishlist');
        let removeToWishBtns = document.querySelectorAll('.yith-wcwl-add-button .delete_item');

        //console.log(wishBtns);
        addToWishBtns.forEach(button => {
            //console.log(button);
            //console.log(button.dataset.productId);
            if(wishListItems.includes(button.dataset.productId)) {
            //console.log(button);
            button.setAttribute('href', `?remove_from_wishlist=${button.dataset.productId}`);
            button.setAttribute('class', 'delete_item');
            let img = button.querySelector('img');
            img.setAttribute('src','https://plantis-shop.ru/wp-content/themes/plantis_site/images/icons/heart-red.svg'); //#todo проверить код
            };
        });

        removeToWishBtns.forEach(button => {
            //console.log(button);
            //console.log(button.dataset.productId);
            if(wishListItems.includes(button.dataset.productId)) {
                return;
            } else {
                //console.log(button);
                button.setAttribute('href', `?add_to_wishlist=${button.dataset.productId}`);
                button.setAttribute('class', 'add_to_wishlist single_add_to_wishlist');
                let img = button.querySelector('img');
                img.setAttribute('src','https://plantis-shop.ru/wp-content/themes/plantis_site/images/icons/heart_green.svg'); //#todo проверить код
            };
        });
    }
}

function getAddedPeresadka() {
    let peresadkaProdId = window.sessionStorage.getItem('peresadkaProdId');
    window.sessionStorage.removeItem('peresadkaProdId');
    console.log(peresadkaProdId);
    let peresadkaBtns = document.querySelectorAll('.cart__peresadka');
    console.log(peresadkaBtns);
    let peresadkaAdded = document.createElement('div');
    peresadkaAdded.setAttribute('class','cart__peresadka-added')
    peresadkaAdded.textContent = 'Пересадка добавлена';
    peresadkaBtns.forEach((btn)=> {
        console.log(btn.dataset.product_id);
        if(btn.dataset.product_id == peresadkaProdId) {
            console.log(btn.parentElement);
            btn.parentElement.append(peresadkaAdded);
        }
    })
}


/*--------------------------------------------------------------
# Start cart js after ajax cart update
--------------------------------------------------------------*/

jQuery(function($){
	$( document.body ).on( 'updated_cart_totals', function(){
		console.log('hi updated_cart_totals');
		swiper_backorder_crossells_init();
		swiper_cart_upsells_init();
		backorderCrossellInit();
        cartUpsellsInit();
		plntAjaxGetWishMiniCart();
        getAddedPeresadka();
	});
})

// jQuery(document.body).on('wc_update_cart', function() {
//     console.log('Cart was updated (via JS event)');
// });

jQuery(document.body).on('wc_cart_emptied', function() {
  swiper_popular_slider_init();
});
