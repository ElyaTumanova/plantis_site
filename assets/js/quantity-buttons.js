document.querySelector('product__btn-plus').addEventListener('click', plnt_quantity_increment);
document.querySelector('product__btn-minus').addEventListener('click', plnt_quantity_increment);

function plnt_quantity_increment() {
 
	var qty = $(this).parent().find( 'input' ),
	    val = parseInt( qty.val() ),
	    min = parseInt( qty.attr( 'min' ) ),
	    max = parseInt( qty.attr( 'max' ) ),
	    step = parseInt( qty.attr( 'step' ) );
 
	// дальше меняем значение количества в зависимости от нажатия кнопки
	if ( $( this ).is( '.plus' ) ) {
		if ( max && ( max <= val ) ) {
			qty.val( max );
		} else {
			qty.val( val + step );
		}
	} else {
		if ( min && ( min >= val ) ) {
			qty.val( min );
		} else if ( val > 1 ) {
			qty.val( val - step );
		}
	}
 
};