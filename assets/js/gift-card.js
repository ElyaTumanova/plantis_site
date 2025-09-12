let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#ywgc-manual-amount')
let submitBtn = document.querySelector('.gift_card_add_to_cart_button')
let gifForm = document.querySelector('.gift-cards_form')

let minAmount = 1500
let maxAmount = 30000

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
  // Удаляем все символы, кроме цифр
  let val = this.value.replace(/\D/g, '');

  // Преобразуем строку в число
  let num = parseInt(val, 10);

  // Если поле пустое, просто оставляем пустым
  if (val === '') {
    this.value = '';
    return;
  }

  // Если меньше минимального — сразу ставим минимум
  if (num < minAmount) {
    num = minAmount;
  }

  // Если больше максимального — ставим максимум
  if (num > maxAmount) {
    num = maxAmount;
  }

  // Обновляем поле отфильтрованным и ограниченным значением
  this.value = num;
});

let buyNowInput = document.createElement('input');
buyNowInput.type = 'hidden';
buyNowInput.name = 'buy_now';
buyNowInput.value = '1';
gifForm.appendChild(buyNowInput);
