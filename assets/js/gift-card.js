let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#ywgc-manual-amount')
console.log(giftAmounts)

giftAmounts.forEach(el => {
  el.addEventListener('click', function () {
    let amount = el.childNodes[0].textContent
    amountInput.value = amount
    
  })

})