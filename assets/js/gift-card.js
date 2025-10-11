let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#ywgc-manual-amount')
let imageAmount = document.querySelector('.gift-image-amount')
let submitBtn = document.querySelector('.gift_card_add_to_cart_button')
let gifForm = document.querySelector('.gift-cards_form')


if (gifForm) {
  let allInputs = gifForm.querySelectorAll('input')
  let allTextareas = gifForm.querySelectorAll('textarea')

  let minAmount = 1500
  let maxAmount = 30000
  amountInput.value = minAmount
  amountInput.setAttribute('value',minAmount)
  imageAmount.innerHTML = `${minAmount}<span>₽</span>`

  //for dev
  let mail = document.querySelector('#ywgc-recipient-email')
  mail.value = 'eleonoraatumanova@gmail.com'
  mail.classList.add('focus')
  let nameF = document.querySelector('#ywgc-recipient-name')
  nameF.value = 'Ela'
  nameF.classList.add('focus')
  //

  // для упралвения label
  allInputs.forEach(el=>{
    el.addEventListener('focus', function(){
      el.classList.add('focus')
    })
    el.addEventListener('blur', function(){
      if(el.value != '') {
        el.classList.add('focus')
      } else {
        el.classList.remove('focus')
      }
    })
  })
  allTextareas.forEach(el=>{
    el.addEventListener('focus', function(){
      el.classList.add('focus')
    })
    el.addEventListener('blur', function(){
      if(el.value != '') {
        el.classList.add('focus')
      } else {
        el.classList.remove('focus')
      }
    })
  })

  giftAmounts.forEach(el => {
    el.addEventListener('click', function () {
      let amount = el.childNodes[0].textContent
      amountInput.value = amount
      imageAmount.innerHTML = `${amount}<span>₽</span>`
      updateState()
    })
  })

  // validation
  function isValidAmount(v) {
      if (v == null) return false;
      let n = Number(v);
      return Number.isFinite(n) && n >= 1500 & n<=30000;
    }

  function updateState() {
    console.log(amountInput.value)
    let ok = isValidAmount(amountInput.value)
    submitBtn.disabled = !ok
    submitBtn.classList.toggle('is-disabled', !ok)
  }

  function submitGiftForm() {
    console.log('form submitted')
    let buyNowInput = document.createElement('input');
    buyNowInput.type = 'hidden';
    buyNowInput.name = 'buy_now';
    buyNowInput.value = '1';
    gifForm.appendChild(buyNowInput);
    gifForm.submit()
  }

  updateState()
  //amountInput.addEventListener('input', updateState)
  amountInput.addEventListener('blur', updateState)

  //input field control
  amountInput.addEventListener('input', function () {
      // Удаляем все нецифровые символы
      let digits = this.value.replace(/\D/g, '');

      if (digits !== '') {
        let num = parseInt(digits, 10);

        // Если число больше максимального, запрещаем добавление лишней цифры
        if (num > maxAmount) {
          // возвращаем старое значение (до ввода этой цифры)
          this.value = this.dataset.prevValue || maxAmount;
          return;
        }

        // Обновляем значение и запоминаем как «предыдущее валидное»
        this.value = digits;
        this.dataset.prevValue = this.value;
      } else {
        // Позволяем временно очистить поле
        this.value = '';
        this.dataset.prevValue = '';
      }
  });

    // После потери фокуса проверяем минимум
    amountInput.addEventListener('blur', function () {
      if (this.value === '' || parseInt(this.value, 10) < minAmount) {
        this.value = minAmount;
        this.setAttribute('value',minAmount)
        imageAmount.innerHTML = `${minAmount}<span>₽</span>`
        updateState()
      } else {
        imageAmount.innerHTML = `${this.value}<span>₽</span>`
      }
    });


  let buyNowInput = document.createElement('input');
  buyNowInput.type = 'hidden';
  buyNowInput.name = 'buy_now';
  buyNowInput.value = '1';
  gifForm.appendChild(buyNowInput);
}


// gift card page

// FAQ toggle
document.querySelectorAll('.faq-item').forEach(item => {
  const q = item.querySelector('.faq-question');
  if(!q) return;
  q.addEventListener('click', () => item.classList.toggle('open'));
});

// Copy gift code
document.querySelectorAll('[data-copy-target]').forEach(btn=>{
  btn.addEventListener('click', async ()=>{
    const target = document.querySelector(btn.getAttribute('data-copy-target'));
    if(!target) return;
    const text = target.textContent.trim();
    try{
      await navigator.clipboard.writeText(text);
      const prev = btn.textContent;
      btn.textContent = 'Скопировано ✓';
      setTimeout(()=> btn.textContent = prev, 1400);
    }catch(e){
      btn.textContent = 'Ошибка копирования';
      setTimeout(()=> btn.textContent = 'Скопировать', 1400);
    }
  });
});

// checkout page

const giftcardShow = document.querySelector('.ywgc-show-giftcard')
const giftCardApplyBtn = document.querySelector('.ywgc_apply_gift_card_button')
if(giftcardShow) {
  giftcardShow.addEventListener('click', () => giftcardShow.classList.toggle('open'));
}
if(giftCardApplyBtn) {
  giftCardApplyBtn.addEventListener('click', () => giftcardShow.classList.toggle('open'));
}


//balance check popup - не используем

// const gcPopupContainer = document.querySelector('.page-popup__container'); 
// const gcOpenPopupBtn = document.querySelectorAll('.gc-popup-open-btn');
// const gcPopup = document.querySelector('.gc-balance-popup');
// const gcClosePopupBtn = document.querySelector('.gc-balance-popup__close');
// const gcPopupOverlay = document.querySelector('.gc-balance-popup__popup-overlay');

// if (gcPopup != null && gcOpenPopupBtn != null) {
//     gcOpenPopupBtn.forEach(button => {
//       button.addEventListener ("click", (evt)=>{
//         toggle_gc_popup ();
//       });
//     });
    
//     if (gcClosePopupBtn) {
//         gcClosePopupBtn.addEventListener ("click", (evt)=>{
//             toggle_gc_popup ();
//             cleanBalanceForm();
//         });
//     }

//     gcPopupOverlay.addEventListener ("click", (evt)=>{
//         toggle_gc_popup ();
//         cleanBalanceForm();
//     });

//     document.addEventListener('keydown', function(e){
//         if((e.key=='Escape'||e.key=='Esc')){
//             if(gcPopup.classList.contains('popup_active')) {
//                 toggle_gc_popup ();
//                 cleanBalanceForm();
//             } 
//         }
//     }, true);
// }

// function toggle_gc_popup () {
//     gcPopup.classList.toggle ('popup_active');
//     body.classList.toggle ('fix-body');
// };


// if(gcBalanceForm !=null) {
//   // gcBalanceForm.addEventListener('submit', hidePopup);
//   // gcBalanceForm.addEventListener('submit', function(evt){
//   //   evt.preventDefault;
//   // });
// }


// function cleanBalanceForm() {
//   if(gcBalanceForm != null) {
//     gcBalanceForm.reset();
//   }
// }

// function hidePopup() {
//   setTimeout(() => {
//     gcPopupContainer.style.visibility = 'hidden';
//   }, 1000);
//   setTimeout(() => {
//     gcPopup.classList.remove('popup_active');
//     body.classList.remove('fix-body');
//     cleanBalanceForm();
//     gcPopupContainer.style.visibility = 'visible';
//   }, 3000);
// }

// проверка кода карты
const gcBalanceForm = document.querySelector('.gc-balance-form');

if (gcBalanceForm) {
  console.log(gcBalanceForm)
    const codeInput = document.getElementById('gcnum');
    const btn = document.querySelector('.gc-balance__checkBtn');
    const spin = document.getElementById('spin');
    const msg = document.getElementById('msg');
    const clearBtn = document.querySelector('.gc-balance__clearBtn');

   // Кириллица (включая Ё/ё) по Unicode
    const reCyrillic = /[\p{Script=Cyrillic}]/u;
    let justTriedCyrillic = false;

    // Отмечаем попытку ввести кириллицу (не блокируем ввод)
    codeInput.addEventListener('beforeinput', (e) => {
      if (typeof e.data === 'string' && reCyrillic.test(e.data)) {
        justTriedCyrillic = true;
      }
    });

    // При вставке — тоже отмечаем (не блокируем)
    codeInput.addEventListener('paste', (e) => {
      const text = (e.clipboardData || window.clipboardData).getData('text');
      if (reCyrillic.test(text)) {
        justTriedCyrillic = true;
      }
    });

    function validateNow({ forceShow = false } = {}) {
      const v = codeInput.value;

      if (!v.trim()) {
        codeInput.setCustomValidity('Поле не должно быть пустым');
      } else if (reCyrillic.test(v)) {
        codeInput.setCustomValidity('Используйте латинские буквы');
      } else {
        codeInput.setCustomValidity('');
      }

      if (forceShow || justTriedCyrillic) {
        codeInput.reportValidity();
        justTriedCyrillic = false;
      }
    }

    // Валидация на каждом вводе
    codeInput.addEventListener('input', () => validateNow());

    // Проверка при отправке
    gcBalanceForm.addEventListener('submit', (e) => {
      validateNow({ forceShow: true });
      if (!gcBalanceForm.checkValidity()) {
        e.preventDefault();
      }
    });

    // Кнопка «Очистить»
    clearBtn.addEventListener('click', () => {
      codeInput.value = '';
      codeInput.setCustomValidity('');
      codeInput.focus();
    });
}
