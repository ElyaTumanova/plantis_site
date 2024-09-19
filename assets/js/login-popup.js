//переменные для управления попапом
const loginOpenPopupBtn = document.querySelectorAll('.login-btn');
const loginPopup = document.querySelector('.login-popup');
const loginClosePopupBtn = document.querySelector('.login__close');
const loginPopupOverlay = document.querySelector('.login__popup-overlay');

loginOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        toggle_login_popup ();
    })
);


loginClosePopupBtn.addEventListener ("click", (evt)=>{
    toggle_login_popup ();
});

loginPopupOverlay.addEventListener ("click", (evt)=>{
    toggle_login_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(loginPopup.classList.contains('popup_active')) {
            toggle_login_popup ();
        } 
    }
}, true);

function toggle_login_popup () {
    loginPopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};

