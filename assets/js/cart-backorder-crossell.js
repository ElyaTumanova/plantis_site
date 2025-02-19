let backorderWrap = document.querySelectorAll('.product-backorder-upsells');

if(backorderWrap) {
    backorderWrap.forEach((el) =>{
        console.log(el);
        let dropDownBtn = el.querySelector('.backorder-crossells__preview-down');
        console.log(dropDownBtn);
        dropDownBtn.addEventListener('click', (evt)=>{toggleBackorderDropdown(evt,el)});

        let replaceBtns = el.querySelectorAll('.backorder_replace_btn');
        
        replaceBtns.forEach((btn)=>{
            btn.addEventListener('click', (evt)=>{replaceBackorderProduct(evt,btn)});
        })
    });
    
}

function toggleBackorderDropdown(evt, el) {
    el.classList.toggle('product-backorder-upsells_active');
}

function replaceBackorderProduct(evt, btn) {
    let prodId = btn.getAttribute('data-product_id');
    console.log(prodId);

    jQuery( function($){
        $.ajax({
            type: 'POST',
            url: woocommerce_params.ajax_url,
            data: {
                'action': 'replace_backorder_product',
                'backorder_replace_prodId': prodId,
            },
            success: function (result) {
                // Trigger refresh checkout
                //$('body').trigger('update_checkout');
                console.log('hi replaceBackorderProduct')
            }
        });
    });

    $( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки 
}