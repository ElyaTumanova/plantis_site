//переменные для управления попапом
const preorderOpenPopupBtn = document.querySelector('.page-popup-open-btn');
const preorderPopup = document.querySelector('.page-popup');
const preorderClosePopupBtn = document.querySelector('.page-popup__close');
const preorderPopupOverlay = document.querySelector('.page-popup__popup-overlay');

if (preorderPopup != null && preorderOpenPopupBtn != null) {
    
    preorderOpenPopupBtn.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
    });

    if (preorderClosePopupBtn) {
        preorderClosePopupBtn.addEventListener ("click", (evt)=>{
            toggle_page_popup ();
        });
    }

    preorderPopupOverlay.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(preorderPopup.classList.contains('popup_active')) {
                toggle_page_popup ();
            } 
        }
    }, true);
}

function toggle_page_popup () {
    preorderPopup.classList.toggle ('popup_active');
};





