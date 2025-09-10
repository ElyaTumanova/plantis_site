let giftAmounts = document.querySelectorAll('.gift__amounts p')
console.log(giftAmounts)

giftAmounts.forEach(el => {
  el.addEventListener('click', function () {
    console.log(el.innerHTML)
  })
})