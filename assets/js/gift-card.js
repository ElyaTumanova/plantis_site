let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#gift-manual-amount')
let imageAmount = document.querySelector('.gift-image-amount')
let submitBtn = document.querySelector('.gift_card_add_to_cart_button')
let giftForm = document.querySelector('.gift-cards_form')
let giftAmountPost = document.querySelector('#giftcard_amount')
console.log(giftAmountPost)


if (giftForm) {
  let allInputs = giftForm.querySelectorAll('input')
  let allTextareas = giftForm.querySelectorAll('textarea')

  // let minAmount = 10
  // let maxAmount = 30000
  // amountInput.value = minAmount
  // amountInput.setAttribute('value',minAmount)
  // imageAmount.innerHTML = `${minAmount}<span>₽</span>`
  // giftAmountPost.value = minAmount

  //for dev
  // let mail = document.querySelector('#gift-recipient-email')
  // mail.value = 'eleonoraatumanova@gmail.com'
  // mail.classList.add('focus')
  // let nameF = document.querySelector('#gift-recipient-name')
  // nameF.value = 'Ela'
  // nameF.classList.add('focus')
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

  // giftAmounts.forEach(el => {
  //   el.addEventListener('click', function () {
  //     let amount = el.childNodes[0].textContent
  //     amountInput.value = amount
  //     giftAmountPost.value = amount
  //     imageAmount.innerHTML = `${amount}<span>₽</span>`
  //     // updateState()
  //   })
  // })

  // validation
  // function isValidAmount(v) {
  //     if (v == null) return false;
  //     let n = Number(v);
  //     return Number.isFinite(n) && n >= minAmount & n<= maxAmount;
  //   }

  // function updateState() {
  //   console.log(amountInput.value)
  //   let ok = isValidAmount(amountInput.value)
  //   submitBtn.disabled = !ok
  //   submitBtn.classList.toggle('is-disabled', !ok)
  // }

  // // function submitGiftForm() {
  // //   console.log('form submitted')
  // //   let buyNowInput = document.createElement('input');
  // //   buyNowInput.type = 'hidden';
  // //   buyNowInput.name = 'buy_now';
  // //   buyNowInput.value = '1';
  // //   giftForm.appendChild(buyNowInput);
  // //   giftForm.submit()
  // // }

  // updateState()
  // //amountInput.addEventListener('input', updateState)
  // amountInput.addEventListener('blur', updateState)

  // //input field control
  // amountInput.addEventListener('input', function () {
  //     // Удаляем все нецифровые символы
  //     let digits = this.value.replace(/\D/g, '');

  //     if (digits !== '') {
  //       let num = parseInt(digits, 10);

  //       // Если число больше максимального, запрещаем добавление лишней цифры
  //       if (num > maxAmount) {
  //         // возвращаем старое значение (до ввода этой цифры)
  //         this.value = this.dataset.prevValue || maxAmount;
  //         return;
  //       }

  //       // Обновляем значение и запоминаем как «предыдущее валидное»
  //       this.value = digits;
  //       this.dataset.prevValue = this.value;
  //     } else {
  //       // Позволяем временно очистить поле
  //       this.value = '';
  //       this.dataset.prevValue = '';
  //     }
  // });

  //   // После потери фокуса проверяем минимум
  //   amountInput.addEventListener('blur', function () {
  //     if (this.value === '' || parseInt(this.value, 10) < minAmount) {
  //       this.value = minAmount;
  //       this.setAttribute('value',minAmount)
  //       imageAmount.innerHTML = `${minAmount}<span>₽</span>`
  //       updateState()
  //     } else {
  //       imageAmount.innerHTML = `${this.value}<span>₽</span>`
  //     }
  //   });


  // let buyNowInput = document.createElement('input');
  // buyNowInput.type = 'hidden';
  // buyNowInput.name = 'buy_now';
  // buyNowInput.value = '1';
  // giftForm.appendChild(buyNowInput);
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

document.addEventListener('DOMContentLoaded', function () {
    const wrap = document.querySelector('.gift-image-wrap');
    const buttons = document.querySelectorAll('.gift-gradient-picker__btn');
    const hiddenInput = document.querySelector('#gift_card_gradient');

    if (!wrap || !buttons.length) return;

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const gradient = this.dataset.gradient;

            wrap.style.background = gradient;

            buttons.forEach(function (btn) {
                btn.classList.remove('is-active');
            });

            this.classList.add('is-active');

            if (hiddenInput) {
                hiddenInput.value = gradient;
            }
        });
    });
});