jQuery(function ($){
    $( 'body' ).on( 'click', 'div.plus, div.minus', function() {

	var qty = $(this).parent().find( 'input' ),
	val = parseInt( qty.val() ),
	min = parseInt( qty.attr( 'min' ) ),
	max = parseInt( qty.attr( 'max' ) ),
	step = parseInt( qty.attr( 'step' ) );

 
	// дальше определяем новое значение количества в зависимости от нажатия кнопки
	var newVal;
	if ( $( this ).is( '.plus' ) ) {
		$( '[name="update_cart"]' ).attr("data-metrika_action",'add'); //для Yandex Metrika E-commerce в корзине #TODO если val = max
		if ( max && ( max <= val ) ) {
			newVal= max;
		} else {
			newVal= val + step ;
		}
	}

	if ( $( this ).is( '.minus' ) ) {
		$( '[name="update_cart"]' ).attr("data-metrika_action",'remove'); //для Yandex Metrika E-commerce в корзине
		if ( min && ( min >= val ) ) {
			newVal =  min;
		} else if ( val > 1 ) {
			newVal= val - step;
		}
	}

	qty.attr('value', newVal );  //устанавливаем новое значение для инпута
	qty.val(newVal);  //устанавливаем новое значение для инпута

	// меняем стили кнопок на активные/неактивные
	if (newVal === max) {
		$(".plus").attr('style','opacity:50%; cursor: default;');
	} else {
		$(".plus").attr('style','opacity:100%; cursor: pointer;');
	}

	if (newVal === min) {
		$(".minus").attr('style','opacity:50%; cursor: default;');
	} else {
		$(".minus").attr('style','opacity:100%; cursor: pointer;');
	}

	qty.parent().parent().find(".add_to_cart_button").attr( 'data-quantity', newVal ); //устанавливаем новое значение для атрибута кнопки добавить в корзину. div "quantity" должен находится в одном родительском узле с кнопкой в корзирну

	//уведомление для backorder
	var stock = parseInt(qty.parent().parent().find(".product_type_simple").attr( 'data-stock-quantity'));
	var backorderInfo = qty.parent().parent().parent().parent().parent().parent().find(".backorder-info");
	var backorderInfoMob = qty.parent().parent().parent().parent().find(".backorder-info");
	if (stock >0) {
		if (newVal == (stock + 1)) {
			backorderInfo.addClass('backorder-info_active');
			backorderInfoMob.addClass('backorder-info_active');
		} 
		if (newVal <= (stock)) {
			backorderInfo.removeClass('backorder-info_active');
			backorderInfoMob.removeClass('backorder-info_active');
		}
	} 


	// определеям товар, для которого изменили кол-во и находим его параметры, записанные в кнопку удаления remove - для Yandex Metrika E-commerce
	var $productRemove = $(this).parent().parent().parent().find('.plnt_product-remove > a')[0];
	if ($productRemove) {
		var $productData = $productRemove.dataset;
		// console.log($productData);
	
		$( '[name="update_cart"]' ).attr("data-product_name",$productData.product_name);
		$( '[name="update_cart"]' ).attr("data-product_category",$productData.product_category);
		$( '[name="update_cart"]' ).attr("data-product_price",$productData.product_price);
	}

	
	$( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки 

	$( document.body ).on( 'trigger','added_to_cart', function() {console.log('hoho')});
});
})


// аналогичная функция - работает при изменении кол-ва в инпуте напрямую в карточке товара
jQuery(function ($){	
	$( 'div.quantity .qty' ).change( function() {
	const qty = $(this).val();
	$(this).parent().parent().find(".add_to_cart_button").attr( 'data-quantity', qty );

	//уведомление для backorder
	var stock = parseInt($(this).parent().parent().find(".product_type_simple").attr( 'data-stock-quantity'));
	var backorderInfo = $(this).parent().parent().parent().parent().find(".backorder-info");
	if (stock >0) {
		if (qty == (stock + 1)) {
			backorderInfo.addClass('backorder-info_active');
		} 
		if (qty <= (stock)) {
			backorderInfo.removeClass('backorder-info_active');
		} 
	}

	} )
});