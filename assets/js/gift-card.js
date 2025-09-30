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
