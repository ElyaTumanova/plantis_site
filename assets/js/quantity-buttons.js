jQuery(function ($){
	console.log('hi');
    $( 'body' ).on( 'click', 'button.plus, button.minus', function() {
 
	var qty = $(this).parent().find( 'input' ),
	val = parseInt( qty.val() ),
	min = parseInt( qty.attr( 'min' ) ),
	max = parseInt( qty.attr( 'max' ) ),
	step = parseInt( qty.attr( 'step' ) );
	console.log($(this));
	console.log(qty);
 
	// дальше определяем новое значение количества в зависимости от нажатия кнопки
	var newVal;
	if ( $( this ).is( '.plus' ) ) {
		if ( max && ( max <= val ) ) {
			newVal= max;
		} else {
			newVal= val + step ;
		}
	} else {
		if ( min && ( min >= val ) ) {
			newVal=  min;
		} else if ( val > 1 ) {
			newVal= val - step;
		}
	}

	qty.attr('value', newVal );  //устанавливаем новое значение для инпута
	qty.parent().parent().find(".add_to_cart_button").attr( 'data-quantity', newVal ); //устанавливаем новое значение для атрибута кнопки добавить в корзину. div "quantity" должен идти сразу за кнопкой в корзирну


	$( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки
 
});
})


// аналогичная функция - работает при изменении кол-ва в инпуте напрямую
jQuery(function ($){	
	$( 'div.quantity .qty' ).change( function() {
	const qty = $(this).val();
	$(this).parent().prev(".add_to_cart_button").attr( 'data-quantity', qty );
	} )
});