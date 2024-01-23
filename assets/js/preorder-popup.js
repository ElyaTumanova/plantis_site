//переменные для управления попапом
const preorderOpenPopupBtn = document.querySelector('.card__preorder-btn');
const popup = document.querySelector('.preorder-popup');
const closePopupBtn = document.querySelector('.preorder__close');
const popupOverlay = document.querySelector('.preorder__popup-overlay');


preorderOpenPopupBtn.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

closePopupBtn.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

popupOverlay.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(popup.classList.contains('popup_active')) {
            toggle_search_popup ();
        } 
    }
}, true);

function toggle_search_popup () {
    popup.classList.toggle ('popup_active');
};

