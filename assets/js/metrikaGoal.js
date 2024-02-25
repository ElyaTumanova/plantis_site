// NEW PLANTIS SHOP COUNTER 96498108
// PLANTIS SHOP COUNTER 87781741


jQuery(document).ready(function() {
    // Клик по кнопке «Положить в корзину», листинг
	jQuery('.catalog__products-wrap .add_to_cart_button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart-listing'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», кросс-сейлы - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ
	jQuery('.cross-sells .add_to_cart_button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart-cross-sells'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», ап-сейлы - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ
	jQuery('.up-sells .add_to_cart_button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart-up-sells'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», товары для ухода - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ
	jQuery('.card__ukhod-wrap .add_to_cart_button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart-ukhod-loop'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», спецпредложения - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ
	jQuery('.main__sale-gallery .add_to_cart_button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart-sale'); 
        return true;
	});

    // Клик по кнопке «Положить в корзину», деталка
	jQuery('.card__price-wrap .add_to_cart_button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart-detalka'); 
        return true;
	});
    //Клик по кнопке «Оформить заказ», корзина
    jQuery('.wc-proceed-to-checkout .checkout-button').click(function(){
		ym(96498108, 'reachGoal', 'click-button-order'); 
        return true;
	});
    //Клик по кнопке «Подтвердить заказ»
    jQuery('#place_order').click(function(){
		ym(96498108, 'reachGoal', 'click-button-confirm-order'); 
        return true;
	});

    //Удачная отправка формы «Подтвердить заказ» - см файл checkout.min.js

    //UX Клик по кнопке «Переход в корзину», хедер
    jQuery('.header-cart__link').click(function(){
		ym(96498108, 'reachGoal', 'click-button-cart'); return true;
	});

    //UX Клик по кнопке «Переход в корзину», мини корзина - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ?
    jQuery('.woocommerce-mini-cart .button .wc-forward').click(function(){
		ym(96498108, 'reachGoal', 'click-minicart-button-cart'); return true;
	});

    //UX Клик по кнопке «Переход в оформление заказа», мини корзина - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ?
    jQuery('.woocommerce-mini-cart .button .checkout .wc-forward').click(function(){
		ym(96498108, 'reachGoal', 'click-minicart-button-order'); return true;
	});

    //это боковая корзина?
    //UX Клик по кнопке вызова модалки «Корзина»

    //Клик по кнопке «Корзина», внутри модалки

    //Клик по кнопке «Оформить заказ», модалка
   
    //Клик на кнопку «Положить в избранное»
    jQuery('.yith-wcwl-add-button .add_to_wishlist').click(function(){
		ym(96498108, 'reachGoal', 'click-button-favorites'); return true;
	});
    
    //Клик на кнопку «Убрать из избранного» - NEW #TODO - ДОБАВИТЬ В МЕТРИКУ?
    jQuery('.yith-wcwl-add-button .delete_item').click(function(){
		ym(96498108, 'reachGoal', 'click-button-favorites-remove'); return true;
	});	
});

//Отправка формы «Предзаказ»
document.addEventListener( 'wpcf7mailsent', function( event ) {
	if ( '27633' == event.detail.contactFormId ) {      // #TODO - ПРОВЕРИТЬ ИД И КЛАСС ФОРМЫ
		ym(87781741, 'reachGoal', 'form-predzakaz');
	}
}, false );






