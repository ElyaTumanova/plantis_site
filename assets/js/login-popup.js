//переменные для управления попапом
const loginOpenPopupBtn = document.querySelectorAll('.login-btn');
const loginPopup = document.querySelector('.login-popup');
const loginClosePopupBtn = document.querySelector('.login__close');
const loginPopupOverlay = document.querySelector('.login__popup-overlay');
//переменные для переключения форм
const regOpenPopupBtn = document.querySelectorAll('.login-form__registration-btn');
const loginOnRegPopupBtn = document.querySelectorAll('.register-form__login-btn');
const loginForm = loginPopup.querySelector('.u-column1.col-1')
const regForm = loginPopup.querySelector('.u-column2.col-2')
let isLogin = true;



function toggle_login_popup () {
    loginPopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};

function changeLoginReg() {
  loginForm.classList.toggle('d-none',!isLogin)
  regForm.classList.toggle('d-none', isLogin)
}
 
// уведомление об ошибке

if (loginPopup) {
    const errorMsg = loginPopup.querySelector('.woocommerce-error'); 
    
    if (errorMsg) {
        toggle_login_popup ();
    };
}

document.addEventListener('DOMContentLoaded', changeLoginReg)

loginOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        toggle_login_popup ();
    })
);

if(loginPopup) {
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

  regOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
      isLogin = false;
      changeLoginReg()
    })
  );

  loginOnRegPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
      isLogin = true
      changeLoginReg()
    })
  );
}



   