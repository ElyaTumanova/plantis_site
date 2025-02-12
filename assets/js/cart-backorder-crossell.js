let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

backorderWrap.forEach((el) =>{
    let dropDownBtn = el.querySelector('.backorder-crossells__preview-down');
    dropDownBtn.addEventListener('click', (evt)=>{toggleBackorderDropdown(evt,el)});
});

function toggleBackorderDropdown(evt, el) {
    console.log(el);
    el.classList.toggle('product-backorder-upsells_active');
}