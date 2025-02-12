let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

backorderWrap.forEach((el) =>{
    let dropDown = el.querySelector('.backorder-crossells__preview-down');
    dropDown.addEventListener('click','toggleBackorderDropdown');
});

function toggleBackorderDropdown(evt) {
    console.log(evt.target);
}