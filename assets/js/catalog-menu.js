const dropdown = document.querySelectorAll('.catalog__dropdown');

dropdown.forEach((el) => {
	const menu = el.querySelector('.catalog__dropdown-menu');
	el.addEventListener('click', function (event) {
		menu.classList.toggle('catalog__dropdown-menu_show');
		el.classList.toggle('catalog__dropdown_open');
		event.stopPropagation();
	})
})


/**
	 * Setup AJAX call refreshing notices
	 */
var $notices_refresh = {
	url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_notices' ),
	type: 'GET', // might deliver cached data
	timeout: wc_cart_fragments_params.request_timeout,
	success: function( data ) {
		if ( data && data.notices ) {

			// Remove existing notices
			$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();	
			// Add new notices
			$('.woocommerce-notices-wrapper:first').prepend(data.notices);

			$( document.body ).trigger( 'wc_notices_refreshed' );
		}
	},
	error: function() {
		$( document.body ).trigger( 'wc_notices_ajax_error' );
	}
};

/* Named callback for refreshing notices */
function refresh_notices() {
	$.ajax( $notices_refresh );
}