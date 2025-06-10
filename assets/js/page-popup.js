//переменные для управления попапом
const pageOpenPopupBtn = document.querySelector('.page-popup-open-btn');
const pagePopup = document.querySelector('.page-popup');
const pageClosePopupBtn = document.querySelector('.page-popup__close');
const pagePopupOverlay = document.querySelector('.page-popup__popup-overlay');

//additional for gift-card
const giftCardPopupContainer = document.querySelector('.page-popup__container_giftcard');

if (pagePopup != null && pageOpenPopupBtn != null) {
    
    pageOpenPopupBtn.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
    });

    if (pageClosePopupBtn) {
        pageClosePopupBtn.addEventListener ("click", (evt)=>{
            toggle_page_popup ();
        });
    }

    pagePopupOverlay.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(pagePopup.classList.contains('popup_active')) {
                toggle_page_popup ();
            } 
        }
    }, true);
}

function toggle_page_popup () {
    pagePopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
    //additional for gift-card
    giftCardPopupContainer.classList.toggle('d-none');
};





