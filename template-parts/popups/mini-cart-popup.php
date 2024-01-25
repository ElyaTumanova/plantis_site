<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="mini-cart-popup popup">
    <div class="mini-cart">
			<?php woocommerce_mini_cart();?>
		</div>
    <div class="mini-cart__popup-overlay popup-overlay"></div>
</div>	