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

	qty.attr('value', '55');
 
	// дальше меняем значение количества в зависимости от нажатия кнопки
	// if ( $( this ).is( '.plus' ) ) {
	// 	if ( max && ( max <= val ) ) {
	// 		qty.val( max );
	// 	} else {
	// 		qty.val( val + step );
	// 	}
	// } else {
	// 	if ( min && ( min >= val ) ) {
	// 		qty.val( min );
	// 	} else if ( val > 1 ) {
	// 		qty.val( val - step );
	// 	}
	// }


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