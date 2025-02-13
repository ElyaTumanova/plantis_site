let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

if(backorderWrap) {
    backorderWrap.forEach((el) =>{
        let dropDownBtn = el.querySelector('.backorder-crossells__preview-down');
        dropDownBtn.addEventListener('click', (evt)=>{toggleBackorderDropdown(evt,el)});

        let replaceBtns = el.querySelectorAll('.backorder_replace_btn');
        
        replaceBtns.forEach((btn)=>{
            btn.addEventListener('click', (evt)=>{replaceBackorderProduct(evt,btn)});
        })
    });
    
    function toggleBackorderDropdown(evt, el) {
        el.classList.toggle('product-backorder-upsells_active');
    }
    function replaceBackorderProduct(evt, btn) {
        let prodId = btn.getAttribute('data-product_id');
        console.log(prodId);
    }
}