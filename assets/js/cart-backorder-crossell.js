let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

backorderWrap.forEach((el) =>{
    let dropDownBtn = el.querySelector('.backorder-crossells__preview-down');
    console.log(el);
    console.log(dropDownBtn);

    dropDownBtn.addEventListener('click', toggleBackorderDropdown);
});

function toggleBackorderDropdown(evt) {
    console.log(evt.target);
}