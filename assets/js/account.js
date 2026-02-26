
const loginPopup = document.querySelector('.login-popup');
const customerLogin = document.querySelector('#customer_login');
const regOpenBtn = document.querySelectorAll('.login-form__registration-btn');
const loginOpenBtn = document.querySelectorAll('.register-form__login-btn');
const loginFormWrap = customerLogin.querySelector('.u-column1.col-1')
const regFormWrap = customerLogin.querySelector('.u-column2.col-2')
const loginForm = customerLogin.querySelector('.woocommerce-form-login')
const regForm = customerLogin.querySelector('.woocommerce-form-register')
let isLogin = true;



function changeLoginReg() {
  // console.log(isLogin)
  loginFormWrap.classList.toggle('d-none',!isLogin)
  regFormWrap.classList.toggle('d-none', isLogin)
}
 
//добавляем кнопку показать пароль

function initPasswords() {

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
  // console.log(inputs)
  inputs.forEach((input) => {
    const errorsField = document.createElement('span');
    errorsField.className = 'field__errors';
    input.insertAdjacentElement('afterend', errorsField);
  })
  regForm.setAttribute('novalidate', '')
  const regEmail = customerLogin.querySelector('#reg_email')
  regEmail.setAttribute('pattern', '^[^\s@]+@[^\s@]+\.[^\s@]{2,}$')
}

document.addEventListener('DOMContentLoaded', changeLoginReg)
document.addEventListener('DOMContentLoaded', initPasswords);
document.addEventListener('DOMContentLoaded', addFormValidationElements);


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


document.addEventListener('submit', async (e) => {
  const form = e.target;
  if (!form.matches('form.woocommerce-form-login')) return;

  e.preventDefault();

  const wrapper =
    document.querySelector('.woocommerce-notices-wrapper');

  if (wrapper) wrapper.innerHTML = '';

  const fd = new FormData(form);
  fd.append('action', 'plantis_ajax_login');
  fd.append('nonce', window.PLANTIS_LOGIN?.nonce || '');

  try {
    const res = await fetch(window.PLANTIS_LOGIN.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: fd
    });

    const data = await res.json();

    if (data?.data?.ok) {
      window.location.href = data.data.redirect || window.location.href;
      return;
    }

    if (wrapper) {
      wrapper.innerHTML = data?.data?.notices || '<div class="woocommerce-error">Ошибка входа</div>';
    }
  } catch (err) {
    if (wrapper) wrapper.innerHTML = '<div class="woocommerce-error">Ошибка сети. Попробуйте ещё раз.</div>';
  }
});
