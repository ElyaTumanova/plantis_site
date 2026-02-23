//переменные для управления попапом Логирования
const loginOpenPopupBtn = document.querySelectorAll('.login-btn');
const loginPopup = document.querySelector('.login-popup');
const loginClosePopupBtn = document.querySelector('.login__close');
const loginPopupOverlay = document.querySelector('.login__popup-overlay');

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
}


function toggle_login_popup () {
    loginPopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};


//переменные для управления попапом Регистрации
const regOpenPopupBtn = document.querySelectorAll('.login-form__registration-btn');
// const regPopup = document.querySelector('.register-popup');
// const regClosePopupBtn = document.querySelector('.register__close');
// const regPopupOverlay = document.querySelector('.register__popup-overlay');
const loginOnRegPopupBtn = document.querySelectorAll('.register-form__login-btn');
let isLogin = true;

const loginForm = document.querySelector('#customer_login')
const regForm = document.querySelector('#customer_registration')

function changeLoginReg() {
  loginForm.classList.loggle('d-none',!isLogin)
  regForm.classList.loggle('d-none', isLogin)
}
document.addEventListener('DOMContentLoaded', changeLoginReg)

// if(regPopup) {
    regOpenPopupBtn.forEach((btn)=>
        btn.addEventListener ("click", (evt)=>{
          isLogin = false;
          changeLoginReg()
            // toggle_login_popup ();
            // toggle_reg_popup ();
        })
    );
    
    loginOnRegPopupBtn.forEach((btn)=>
        btn.addEventListener ("click", (evt)=>{
          isLogin = true
          changeLoginReg()
            // toggle_reg_popup ();
            // toggle_login_popup ();
        })
    );
    
//     regClosePopupBtn.addEventListener ("click", (evt)=>{
//         toggle_reg_popup ();
//     });
    
//     regPopupOverlay.addEventListener ("click", (evt)=>{
//         toggle_reg_popup ();
//     });
    
//     document.addEventListener('keydown', function(e){
//         if((e.key=='Escape'||e.key=='Esc')){
//             if(regPopup.classList.contains('popup_active')) {
//                 toggle_reg_popup ();
//             } 
//         }
//     }, true);    
// }

// function toggle_reg_popup () {
//     regPopup.classList.toggle ('popup_active');
//     body.classList.toggle ('fix-body');
// };

// // переменные для управления через мобильное меню

// const mobOpenBtn = document.querySelector('.burger-menu__account'); 
// if(mobOpenBtn) {
//     mobOpenBtn.addEventListener ("click", (evt)=>{
//         toggle_mob_menu ();
//         toggle_login_popup ();
//     });
// }

// уведомление об ошибке

if (loginPopup) {
    const errorMsg = loginPopup.querySelector('.woocommerce-error'); 
    
    if (errorMsg) {
        toggle_login_popup ();
    };
}