function cartUpsellsInit() {
    let cartUpsellsWrap = document.querySelectorAll('.product-cart-upsells');

    if(cartUpsellsWrap) {
        cartUpsellsWrap.forEach((el) =>{
            let dropDownBtn = el.querySelector('.cart-upsells__preview-down');
            if(dropDownBtn) {
                dropDownBtn.addEventListener('click', (evt)=>{toggleCartUpsellsDropdown(evt,el)});
            }
        }); 
    }        
};

function toggleCartUpsellsDropdown(evt, el) {
    el.classList.toggle('product-cart-upsells_active');
}



cartUpsellsInit();

