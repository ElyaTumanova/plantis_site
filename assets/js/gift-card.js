let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#ywgc-manual-amount')
console.log(giftAmounts)

giftAmounts.forEach(el => {
  el.addEventListener('click', function () {
    console.log(el.childNodes[0].textContent)
    console.log(amountInput)
    amountInput.value = el.childNodes.text.textContent
    
  })

})