<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require get_template_directory() . '/woocommerce/includes/core/wc-helpers.php';
require get_template_directory() . '/woocommerce/includes/core/wc-blocks.php';
require get_template_directory() . '/woocommerce/includes/core/wc-seo-functions.php';


require get_template_directory() . '/woocommerce/includes/catalog/wc-catalog-functions.php';
require get_template_directory() . '/woocommerce/includes/catalog/wc-catalog-filters.php';
require get_template_directory() . '/woocommerce/includes/catalog/wc-catalog-layout.php';
require get_template_directory() . '/woocommerce/includes/catalog/wc-product-layout.php';

require get_template_directory() . '/woocommerce/includes/product-card/wc-product-card-layout.php';

require get_template_directory() . '/woocommerce/includes/cart/wc-cart-functions.php';
require get_template_directory() . '/woocommerce/includes/cart/wc-cart-layout.php';
require get_template_directory() . '/woocommerce/includes/cart/wc-cart-fragments.php';

require get_template_directory() . '/woocommerce/includes/checkout/wc-checkout-functions.php';
require get_template_directory() . '/woocommerce/includes/checkout/wc-checkout-layout.php';