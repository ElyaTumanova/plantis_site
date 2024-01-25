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
 
	// дальше меняем значение количества в зависимости от нажатия кнопки
	// if ( $( this ).is( '.plus' ) ) {
	// 	if ( max && ( max <= val ) ) {
	// 		qty.attr('value', max );
	// 	} else {
	// 		qty.attr('value', val + step );
	// 	}
	// } else {
	// 	if ( min && ( min >= val ) ) {
	// 		qty.attr('value', min );
	// 	} else if ( val > 1 ) {
	// 		qty.attr('value', val - step );
	// 	}
	// }
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

	console.log (newVal);
	qty.attr('value', newVal );
	qty.parent().prev().attr( 'data-quantity', newVal );


	//$( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки
 
});
})

jQuery(function ($){
	//console.log('hi');
	
	$( 'div.quantity .qty' ).change( function() {
	//console.log('hello');

	const qty = $(this).val();
	//console.log(qty);
	//console.log($(this).parent().prev());
	$(this).parent().prev().attr( 'data-quantity', qty );
	//console.log($(this).parent().prev());

	} )
});