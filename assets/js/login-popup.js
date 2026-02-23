//переменные для управления попапом
const loginOpenPopupBtn = document.querySelectorAll('.login-btn');
const loginPopup = document.querySelector('.login-popup');
const loginClosePopupBtn = document.querySelector('.login__close');
const loginPopupOverlay = document.querySelector('.login__popup-overlay');
const customerLogin = document.querySelector('#customer_login');
//переменные для переключения форм
const regOpenBtn = document.querySelectorAll('.login-form__registration-btn');
const loginOpenBtn = document.querySelectorAll('.register-form__login-btn');
const loginForm = customerLogin.querySelector('.u-column1.col-1')
const regForm = customerLogin.querySelector('.u-column2.col-2')
let isLogin = true;

function toggle_login_popup () {
    loginPopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};

function changeLoginReg() {
  console.log(isLogin)
  loginForm.classList.toggle('d-none',!isLogin)
  regForm.classList.toggle('d-none', isLogin)
}
 
//добавляем кнопку показать пароль



const initPasswords = () => {
  if (!loginPopup) return;

  const inputs = loginPopup.querySelectorAll('#password, #reg_password');

  inputs.forEach((input) => {

    // если уже обёрнут — пропускаем
    if (input.closest('.password-input')) return;

    // создаём span
    const wrapper = document.createElement('span');
    wrapper.className = 'password-input';

    // вставляем wrapper перед input
    input.parentNode.insertBefore(wrapper, input);

    // переносим input внутрь wrapper
    wrapper.appendChild(input);
  });
};

// уведомление об ошибке

// if (loginPopup) {
//     const errorMsg = loginPopup.querySelector('.woocommerce-error'); 
    
//     if (errorMsg) {
//         toggle_login_popup ();
//         changeLoginReg();
//     };
// }

document.addEventListener('DOMContentLoaded', changeLoginReg)
document.addEventListener('DOMContentLoaded', initPasswords);

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
regOpenBtn.forEach((btn)=>
  btn.addEventListener ("click", (evt)=>{
    isLogin = false;
    changeLoginReg()
  })
);

loginOpenBtn.forEach((btn)=>
  btn.addEventListener ("click", (evt)=>{
    isLogin = true
    changeLoginReg()
  })
);





   