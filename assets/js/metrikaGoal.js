
jQuery(document).ready(function() {
    // Клик по кнопке «Положить в корзину», листинг
	jQuery('.catalog__products-wrap .add_to_cart_button').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-listing'); 
		ym(87781741, 'reachGoal', 'click-button-cart-listing'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», кросс-сейлы
	jQuery('.cross-sells .add_to_cart_button').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-cross-sells');
		ym(87781741, 'reachGoal', 'click-button-cart-cross-sells'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», ап-сейлы
	jQuery('.up-sells .add_to_cart_button').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-up-sells');
		 ym(87781741, 'reachGoal', 'click-button-cart-up-sells'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», товары для ухода
	jQuery('.card__grid .card__ukhod-wrap .add_to_cart_button').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-ukhod-loop');
		 ym(87781741, 'reachGoal', 'click-button-cart-ukhod-loop'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», спецпредложения - убрать из метрики #TODO
	// jQuery('.main__sale-gallery .add_to_cart_button').click(function(){
	// 	//yaCounter87781741.reachGoal('click-button-cart-sale');
	// 	 ym(87781741, 'reachGoal', 'click-button-cart-sale'); 
    //     return true;
	// });

    // Клик по кнопке «Положить в корзину», деталка
	jQuery('.card__price-wrap .add_to_cart_button').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-detalka');
		 ym(87781741, 'reachGoal', 'click-button-cart-detalka'); 
        return true;
	});
    //Клик по кнопке «Оформить заказ», корзина
    jQuery('.wc-proceed-to-checkout .checkout-button').click(function(){
		//yaCounter87781741.reachGoal('click-button-order');
		 ym(87781741, 'reachGoal', 'click-button-order'); 
        return true;
	});
    //Клик по кнопке «Подтвердить заказ»
    jQuery('#place_order').click(function(){
		//yaCounter87781741.reachGoal('click-button-confirm-order');
		 ym(87781741, 'reachGoal', 'click-button-confirm-order'); 
        return true;
	});

    //Удачная отправка формы «Подтвердить заказ» - см файл checkout.min.js

    //UX Клик по кнопке «Переход в корзину», хедер
    jQuery('.header-cart__link').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart');
		 ym(87781741, 'reachGoal', 'click-button-cart'); 
		return true;
	});

    //UX Клик по кнопке «Переход в корзину», мини корзина
    jQuery('.mini-cart__wrap .woocommerce-mini-cart .button .wc-forward').click(function(){
		//yaCounter87781741.reachGoal('click-minicart-button-cart');
		 ym(87781741, 'reachGoal', 'click-minicart-button-cart'); 
		return true;
	});

    //UX Клик по кнопке «Переход в оформление заказа», мини корзина
    jQuery('.mini-cart__wrap .woocommerce-mini-cart .button .checkout .wc-forward').click(function(){
		//yaCounter87781741.reachGoal('click-minicart-button-order');
		 ym(87781741, 'reachGoal', 'click-minicart-button-order'); 
		return true;
	});

    //это боковая корзина side cart
    //UX Клик по кнопке вызова модалки «Корзина»
	jQuery('.side-cart__open-btn').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-call-modal');
		 ym(87781741, 'reachGoal', 'click-button-cart-call-modal'); 
		return true;
	});

    //Клик по кнопке «Корзина», внутри модалки
	jQuery('.side-cart .woocommerce-mini-cart .button .wc-forward').click(function(){
		//yaCounter87781741.reachGoal('click-button-cart-modal');
		 ym(87781741, 'reachGoal', 'click-button-cart-modal'); 
		return true;
	});
	
    //Клик по кнопке «Оформить заказ», модалка
	jQuery('.side-cart .woocommerce-mini-cart .button .checkout .wc-forward').click(function(){
		//yaCounter87781741.reachGoal('click-button-order-modal');
		 ym(87781741, 'reachGoal', 'click-button-order-modal'); 
		return true;
	});
	
    //Клик на кнопку «Положить в избранное»
    jQuery('.yith-wcwl-add-button .add_to_wishlist').click(function(){
		//yaCounter87781741.reachGoal('click-button-favorites');
		 ym(87781741, 'reachGoal', 'click-button-favorites'); 
		return true;
	});
    
    //Клик на кнопку «Убрать из избранного»
    jQuery('.yith-wcwl-add-button .delete_item').click(function(){
		//yaCounter87781741.reachGoal('click-button-favorites-remove');
		 ym(87781741, 'reachGoal', 'click-button-favorites-remove'); 
		return true;
	});	
});

//Отправка формы «Предзаказ»
document.addEventListener( 'wpcf7mailsent', function( event ) {
	if ( '27633' == event.detail.contactFormId ) {      // #TODO - ПРОВЕРИТЬ ИД И КЛАСС ФОРМЫ
		//yaCounter87781741.reachGoal('form-predzakaz');
		 ym(87781741, 'reachGoal', 'form-predzakaz');
	}
}, false );






