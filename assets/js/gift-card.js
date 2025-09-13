let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#ywgc-manual-amount')
let submitBtn = document.querySelector('.gift_card_add_to_cart_button')
let gifForm = document.querySelector('.gift-cards_form')

let minAmount = 1500
let maxAmount = 30000
amountInput.value = minAmount;

//for dev
let mail = document.querySelector('#ywgc-recipient-email')
mail.value = 'elyagi@mail.ru'
let nameF = document.querySelector('#ywgc-recipient-name')
nameF.value = 'Ela'


giftAmounts.forEach(el => {
  el.addEventListener('click', function () {
    let amount = el.childNodes[0].textContent
    amountInput.value = amount
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
      this.dataset.prevValue = this.value;
    }
  });

  // После завершения ввода — проверяем минимум
  amountInput.addEventListener('blur', function () {
    let val = this.value;
    if (val === '' || parseInt(val, 10) < minAmount) {
      this.value = minAmount;
      this.focus();
      this.dispatchEvent(new MouseEvent('click',     { bubbles: true }));
    }
  });

let buyNowInput = document.createElement('input');
buyNowInput.type = 'hidden';
buyNowInput.name = 'buy_now';
buyNowInput.value = '1';
gifForm.appendChild(buyNowInput);
