/*--------------------------------------------------------------
# CART UPDATE  #CASH
--------------------------------------------------------------*/
//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования

// получаем корзину для обновления кнопок добавления в корзину
//функция используется в плагнах Load More и BeRocket filters
function plntAjaxGetWishMiniCart() {
  //console.log('hi get minicart');

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
        console.log(response);
        $('.mini-cart').html(response.mini_cart); // Repopulate the specific element with the new content
        //console.log(response.mini_cart);
        updateCatalogButtons();

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

function updateCatalogButtons() {
  console.log('hi updateCatalogButtons')
  // // mini-cart уже должен быть обновлён: $('.mini-cart').html(response.mini_cart)
  // const miniCartRoot = document.querySelector('.mini-cart');
  // if (!miniCartRoot) return;

  // // Собираем product_id из мини-корзины (O(n))
  // const productsInCart = new Set(
  //   Array.from(
  //     miniCartRoot.querySelectorAll('.woocommerce-mini-cart-item .remove_from_cart_button')
  //   )
  //     .map(el => el.dataset.product_id)
  //     .filter(Boolean)
  // );

  // // Один проход по всем кнопкам (и add, и remove)
  const buttons = document.querySelectorAll('.add_to_cart_button, .remove_from_cart_button');
  console.log(buttons)

  // buttons.forEach(btn => {
  //   const pid = btn.dataset.product_id;
  //   if (!pid) return;

  //   // исключение "Пересадка"
  //   if (btn.dataset.categoryName === 'Пересадка') return;

  //   const inCart = productsInCart.has(pid);

  //   // Родитель (иногда form, иногда нет)
  //   const parent = btn.parentElement;
  //   const isForm = parent && parent.matches && parent.matches('form');

  //   if (inCart) {
  //     // поставить remove link если он есть
  //     const removeLink = btn.dataset.remove_link;
  //     if (removeLink) {
  //       if (isForm) parent.setAttribute('action', removeLink);
  //       if (btn.matches('a')) btn.setAttribute('href', removeLink);
  //     }

  //     // классы/текст (не сносим все классы целиком)
  //     btn.classList.remove('add_to_cart_button', 'ajax_add_to_cart');
  //     btn.classList.add('remove_from_cart_button', 'added');
  //     btn.textContent = 'Добавлен';
  //   } else {
  //     // вернуть add-to-cart ссылку/экшен
  //     const addLink = `?add-to-cart=${pid}`;
  //     if (isForm) parent.setAttribute('action', addLink);
  //     if (btn.matches('a')) btn.setAttribute('href', addLink);

  //     btn.classList.remove('remove_from_cart_button', 'added');
  //     btn.classList.add('add_to_cart_button', 'ajax_add_to_cart');
  //     btn.textContent = 'В корзину';
  //   }
  // });
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
            img.setAttribute('src','https://plantis-shop.ru/wp-content/uploads/2025/07/heart_black_new.svg');
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
                img.setAttribute('src','https://plantis-shop.ru/wp-content/uploads/2025/07/heart_gray_new.svg');
            };
        });
    }
}

/*--------------------------------------------------------------
# Get message about peresadka added
--------------------------------------------------------------*/

function getAddedPeresadka() {
    let peresadkaProdId = window.sessionStorage.getItem('peresadkaProdId');
    window.sessionStorage.removeItem('peresadkaProdId');
    let cart = document.querySelector('.woocommerce-cart-form');
    let peresadkaBtns = cart.querySelectorAll('.cart__peresadka');

    peresadkaBtns.forEach((btn)=> {
        if(btn.dataset.product_id == peresadkaProdId ) {
            let peresadkaAdded = btn.querySelector('.cart__peresadka-added_mini');
            peresadkaAdded.textContent = 'Пересадка добавлена';
            peresadkaAdded.setAttribute('style', 'visibility: visible');
        }
    })
}

function getAddedPeresadkaMini() {
    let peresadkaProdId = window.sessionStorage.getItem('peresadkaProdId');
    let miniCarts = document.querySelectorAll('.woocommerce-mini-cart');
    let peresadkaBtns = [];
    miniCarts.forEach((cart)=> {
        peresadkaBtns = [...peresadkaBtns,...cart.querySelectorAll('.cart__peresadka')]; 
    })

    peresadkaBtns.forEach((btn)=> {
        if(btn.dataset.product_id == peresadkaProdId ) {
            btn.querySelector('.cart__peresadka-added_mini').setAttribute('style', 'visibility: visible');
        }
    })
}


/*--------------------------------------------------------------
# Start cart js after ajax cart update
--------------------------------------------------------------*/

jQuery(function($){
	$( document.body ).on( 'updated_cart_totals', function(){
		console.debug('hi updated_cart_totals');
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

jQuery(document.body).on('wc_fragments_loaded', function() {
    console.debug('Фрагменты корзины обновлены!');
    getAddedPeresadkaMini();
});

/*--------------------------------------------------------------
# Update popular slider after ajax wish update
--------------------------------------------------------------*/

jQuery(document.body).on('added_to_wishlist', function(){
    //console.log('hello wish');
    updatePopularSwiper();
});

jQuery(document.body).on('removed_from_wishlist', function(){
    //console.log('bye wish');
    updatePopularSwiper();
});


function updatePopularSwiper() {
    let wishlist = document.querySelector('.yith-wcwl-form');
    if(wishlist) {
        let populars = wishlist.querySelectorAll('.product');
        populars.forEach((el)=>{
            el.classList.add('swiper-slide');
        })
        swiper_popular_slider_init();
    }
}