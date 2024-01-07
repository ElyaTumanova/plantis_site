<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function plnt_woocommerce_cart_header() {
	?>
		<?php $cart_icon = carbon_get_theme_option('cart_icon')?>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart__link">
			<img class="header-cart__icon" src="<?php echo $cart_icon ?>" alt="cart" width="25" height="25">
			<span class="header-cart__label">Корзина</span>		
			<span class="header-cart__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
		</a>
	<?php
}


function plnt_woocommerce_cart_header_fragment( $fragments ) {
 
	ob_start();
	plnt_woocommerce_cart_header();
 
	$fragments[ 'a.header-cart__link' ] = ob_get_clean();
	
	return $fragments;
 
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_cart_header_fragment', 25 );