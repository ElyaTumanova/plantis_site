//переменные для управления попапом
const regOpenPopupBtn = document.querySelectorAll('.login-form__registration-btn');
const regPopup = document.querySelector('.register-popup');
const regClosePopupBtn = document.querySelector('.register__close');
const regPopupOverlay = document.querySelector('.register__popup-overlay');

regOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        toggle_reg_popup ();
    })
);

regClosePopupBtn.addEventListener ("click", (evt)=>{
    toggle_reg_popup ();
});

regPopupOverlay.addEventListener ("click", (evt)=>{
    toggle_reg_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(regPopup.classList.contains('popup_active')) {
            toggle_reg_popup ();
        } 
    }
}, true);

function toggle_reg_popup () {
    regPopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};

console.log(regOpenPopupBtn);
console.log(regPopup);
console.log(regClosePopupBtn);