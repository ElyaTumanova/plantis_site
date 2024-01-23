//переменные для управления попапом
const preorderOpenPopupBtn = document.querySelector('.card__preorder-btn');
const preorderPopup = document.querySelector('.preorder-popup');
const preorderClosePopupBtn = document.querySelector('.preorder__close');
const preorderPopupOverlay = document.querySelector('.preorder__popup-overlay');

if (preorderPopup === null || preorderOpenPopupBtn === null) {
    return;
} else {
    preorderOpenPopupBtn.addEventListener ("click", (evt)=>{
        toggle_preorder_popup ();
    });


    preorderClosePopupBtn.addEventListener ("click", (evt)=>{
        toggle_preorder_popup ();
    });

    preorderPopupOverlay.addEventListener ("click", (evt)=>{
        toggle_preorder_popup ();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(preorderPopup.classList.contains('popup_active')) {
                toggle_preorder_popup ();
            } 
        }
    }, true);
}

function toggle_preorder_popup () {
    preorderPopup.classList.toggle ('popup_active');
};

