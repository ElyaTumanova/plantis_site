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
		console.log(this);
		$( '[name="update_cart"]' ).attr("data-metrika_action",'add'); //для Yandex Metrika E-commerce
		if ( max && ( max <= val ) ) {
			newVal= max;
		} else {
			newVal= val + step ;
		}
	} else {
		$( '[name="update_cart"]' ).attr("data-metrika_action",'remove'); //для Yandex Metrika E-commerce
		if ( min && ( min >= val ) ) {
			newVal=  min;
		} else if ( val > 1 ) {
			newVal= val - step;
		}
	}

	qty.attr('value', newVal );  //устанавливаем новое значение для инпута
	qty.val(newVal );  //устанавливаем новое значение для инпута

	qty.parent().parent().find(".add_to_cart_button").attr( 'data-quantity', newVal ); //устанавливаем новое значение для атрибута кнопки добавить в корзину. div "quantity" должен находится в одном родительском узле с кнопкой в корзирну

	// определеям товар, для которого изменили кол-во и находим его параметры, записанные в кнопку удаления remove - для Yandex Metrika E-commerce
	var $productData = $(this).parent().parent().parent().find('.product-remove > a')[0].dataset;
	console.log($productData);

	$( '[name="update_cart"]' ).attr("data-product_name",$productData.product_name);
	$( '[name="update_cart"]' ).attr("data-product_category",$productData.product_category);
	$( '[name="update_cart"]' ).attr("data-product_price",$productData.product_price);

	
	$( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки
 
});
})


// аналогичная функция - работает при изменении кол-ва в инпуте напрямую в карточке товара
jQuery(function ($){	
	$( 'div.quantity .qty' ).change( function() {
	const qty = $(this).val();
	$(this).parent().parent().find(".add_to_cart_button").attr( 'data-quantity', qty );
	} )
});


// аналогичная функция - работает при изменении кол-ва в инпуте напрямую
jQuery(function ($){	
	$( 'div.quantity .qty' ).change( function() {
	console.log(this);

	var $productData = $(this).parent().parent().parent().find('.product-remove > a')[0].dataset;
	console.log($productData);
	
	$(this).attr("data-product_name",$productData.product_name);
	$(this).attr("data-product_category",$productData.product_category);
	$(this).attr("data-product_price",$productData.product_price);

	} )
});