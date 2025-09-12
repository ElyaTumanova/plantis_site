let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#ywgc-manual-amount')
// let submitBtn = document.querySelector('.gift_card_add_to_cart_button')
let submitBtn = document.querySelector('.gift-card-button')
let gifForm = document.querySelector('.gift-cards_form')
let minAmount = 1500
let maxAmount = 30000

if (amountInput) {
  amountInput.setAttribute('placeholder', minAmount)
}

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
  gifForm.requestSubmit()
}

updateState()
//amountInput.addEventListener('input', updateState)
amountInput.addEventListener('blur', updateState)

submitBtn.addEventListener('click',submitGiftForm)