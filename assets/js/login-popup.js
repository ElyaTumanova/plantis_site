//переменные для управления попапом
// const loginOpenPopupBtn = document.querySelectorAll('.login-btn');
const loginPopup = document.querySelector('.login-popup');
// const loginClosePopupBtn = document.querySelector('.login__close');
// const loginPopupOverlay = document.querySelector('.login__popup-overlay');
const customerLogin = document.querySelector('#customer_login');
//переменные для переключения форм
const regOpenBtn = document.querySelectorAll('.login-form__registration-btn');
const loginOpenBtn = document.querySelectorAll('.register-form__login-btn');
const loginFormWrap = customerLogin.querySelector('.u-column1.col-1')
const regFormWrap = customerLogin.querySelector('.u-column2.col-2')
const loginForm = customerLogin.querySelector('.woocommerce-form-login')
const regForm = customerLogin.querySelector('.woocommerce-form-register')
let isLogin = true;

// function toggle_login_popup () {
//     loginPopup.classList.toggle ('popup_active');
//     body.classList.toggle ('fix-body');
// };

function changeLoginReg() {
  console.log(isLogin)
  loginFormWrap.classList.toggle('d-none',!isLogin)
  regFormWrap.classList.toggle('d-none', isLogin)
}
 
//добавляем кнопку показать пароль

function initPasswords() {
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

function addFormValidationElements() {
  if (!customerLogin) return
  
  const inputs = customerLogin.querySelectorAll('#username, #reg_email, #password, #reg_password')
  console.log(inputs)
  inputs.forEach((input) => {
    const errorsField = document.createElement('span');
    errorsField.className = 'field__errors';
    input.insertAdjacentElement('afterend', errorsField);
  })
  regForm.setAttribute('novalidate', '')
  const regEmail = customerLogin.querySelector('#reg_email')
  regEmail.setAttribute('pattern', '^[^\s@]+@[^\s@]+\.[^\s@]{2,}$')
}

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
document.addEventListener('DOMContentLoaded', addFormValidationElements);

// loginOpenPopupBtn.forEach((btn)=>
//     btn.addEventListener ("click", (evt)=>{
//         toggle_login_popup ();
//     })
// );

// if(loginPopup) {
//   loginClosePopupBtn.addEventListener ("click", (evt)=>{
//     toggle_login_popup ();
//   });
  
//   loginPopupOverlay.addEventListener ("click", (evt)=>{
//     toggle_login_popup ();
//   });
  
//   document.addEventListener('keydown', function(e){
//     if((e.key=='Escape'||e.key=='Esc')){
//         if(loginPopup.classList.contains('popup_active')) {
//             toggle_login_popup ();
//         } 
//     }
//   }, true);

// }
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





   