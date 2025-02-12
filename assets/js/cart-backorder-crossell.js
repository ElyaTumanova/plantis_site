let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

backorderWrap.forEach((el) =>{
    let dropDownBtn = el.querySelector('.backorder-crossells__preview-down');
    console.log(el);
    console.log(dropDownBtn);

    dropDownBtn.addEventListener('click', (evt)=>{toggleBackorderDropdown(evt,el)});
});

function toggleBackorderDropdown(evt, el) {
    console.log(el);
}