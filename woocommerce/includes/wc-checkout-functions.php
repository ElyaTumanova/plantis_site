<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_checkout_after_order_review', 'woocommerce_checkout_payment', 20);

