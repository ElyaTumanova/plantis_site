let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

backorderWrap.forEach((el) =>{
    let dropDown = el.querySelector('.backorder-crossells__preview-down');
    console.log(el);
    console.log(dropDown);

    dropDown.addEventListener('click','toggleBackorderDropdown');
});

function toggleBackorderDropdown(evt) {
    console.log(evt.target);
}