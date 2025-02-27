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
    
}

function toggleBackorderDropdown(evt, el) {
    el.classList.toggle('product-backorder-upsells_active');
}

function replaceBackorderProduct(evt, btn) {
    console.log(evt.target);
    let prodId = btn.getAttribute('data-product_id');
    let cartItem = btn.getAttribute('data-cart_item');
    //let addToCartBtn = document.querySelector(`.ajax_add_to_cart[data-product_id="${prodId}"]`);
    //console.log(btn);
    //console.log(addToCartBtn);
    console.log(prodId);
    console.log(cartItem);

    jQuery( function($){
        $.ajax({
            type: 'POST',
            url: woocommerce_params.ajax_url,
            data: {
                'action': 'replace_backorder_product',
                'backorder_replace_prodId': prodId,
                'backorder_replace_cart_item': cartItem,
            },
            success: function (result) {
                // Trigger refresh checkout
                console.log('hi update cart');
                //console.log(result);
                //plntAjaxUpdateCartCount();
                //plntAjaxGetMiniCart();
                // $( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки 
               
                //console.log(addToCartBtn);
                //addToCartBtn.click();
                //$('.backorder_replace_btn').next().trigger( 'click' );

            }
        });
    });
    
}