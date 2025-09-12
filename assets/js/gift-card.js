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
    // Убираем все символы кроме цифр
    let val = this.value.replace(/\D/g, '');

    if (val !== '') {
      let num = parseInt(val, 10);
      if (num > maxAmount) num = maxAmount;  // ограничение сверху
      this.value = num;
    } else {
      this.value = ''; // позволяем временно очистить поле
    }
  });

  // После завершения ввода — проверяем минимум
  amountInput.addEventListener('blur', function () {
    let val = this.value;
    if (val === '' || parseInt(val, 10) < minAmount) {
      this.value = minAmount;
    }
  });

let buyNowInput = document.createElement('input');
buyNowInput.type = 'hidden';
buyNowInput.name = 'buy_now';
buyNowInput.value = '1';
gifForm.appendChild(buyNowInput);
