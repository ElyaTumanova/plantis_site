//переменные для управления попапом
const sideCartWrap = document.querySelector('.side-cart-wrap');
const sideCartOpenPopupBtn = document.querySelector('.side-cart__open-btn');
const sideCartOpenPopupBtnMob = document.querySelector('.header__nav-wrap .header-cart');
const sideCartClosePopupBtn = document.querySelector('.side-cart__close');

const sideCartPopup = document.querySelector('.side-cart__popup');
const sideCartPopupOverlay = document.querySelector('.side-cart__popup-overlay');

if (sideCartPopup != null && sideCartOpenPopupBtn != null || sideCartOpenPopupBtnMob !=null) {
    
    sideCartOpenPopupBtn.addEventListener ("click", (evt)=>{
        plntAjaxGetMiniCartOnSide();
    },{once:true});

    sideCartOpenPopupBtn.addEventListener ("click", (evt)=>{
        toggle_side_cart_popup ();
    });

    sideCartOpenPopupBtnMob.addEventListener ("click", (evt)=>{
        toggle_side_cart_popup ();
    });

    sideCartPopupOverlay.addEventListener ("click", (evt)=>{
        toggle_side_cart_popup ();
    });

    sideCartClosePopupBtn.addEventListener ("click", (evt)=>{
        toggle_side_cart_popup ();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(sideCartPopup.classList.contains('popup_active')) {
                toggle_side_cart_popup ();
            } 
        }
    }, true);
}

function toggle_side_cart_popup () {
    sideCartPopup.classList.toggle ('popup_active');
    if (sideCartOpenPopupBtn) {
        sideCartOpenPopupBtn.classList.toggle ('side-cart__open-btn_active');
        body.classList.toggle ('fix-body');
    }
};

function plntAjaxGetMiniCartOnSide() {
  ( function ( $ ) {
      "use strict";
    // Define the PHP function to call from here
      var data = {
        'action': 'plnt_get_mini_cart'
      };
      $.get(
        woocommerce_params.ajax_url, // The AJAX URL
        data, // Send our PHP function
        function(response){
          //console.log(response);
          sideCartWrap.innerHTML = response;
        }
      );
  // Close anon function.
  }( jQuery ) );
}





