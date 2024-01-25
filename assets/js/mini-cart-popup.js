//переменные для управления попапом
const cartOpenPopupBtn = document.querySelector('.header-cart');
const cartPopup = document.querySelector('.mini-cart-popup');
//const searchClosePopupBtn = document.querySelector('.search__close');
const cartPopupOverlay = document.querySelector('.mini-cart__popup-overlay');

cartOpenPopupBtn.addEventListener ("hover", (evt)=>{
    toggle_cart_popup ();
});

// searchClosePopupBtn.addEventListener ("click", (evt)=>{
//     toggle_cart_popup ();
// });

cartPopupOverlay.addEventListener ("click", (evt)=>{
    toggle_cart_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(cartPopup.classList.contains('popup_active')) {
            toggle_cart_popup ();
        } 
    }
}, true);

function toggle_cart_popup () {
    cartPopup.classList.toggle ('popup_active');
};