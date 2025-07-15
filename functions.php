<?php
add_action( 'after_setup_theme', function() {
	load_theme_textdomain( 'plantis-theme', get_template_directory() . '/languages' );
} );


/** Add Carbon Fields */

require_once __DIR__ . '/../../../vendor/autoload.php'; // путь может отличаться
use Carbon_Fields\Carbon_Fields;

add_action( 'after_setup_theme', function() {
    Carbon_Fields::boot();
} );


add_action( 'carbon_fields_register_fields', 'ast_register_custom_fields' );
function ast_register_custom_fields() {
	require get_template_directory() . '/includes/custom-fields/post-meta.php';
	require get_template_directory() . '/includes/custom-fields/theme-options.php';
}

/** Add server timing debug */
require get_template_directory() . '/includes/server-debug.php';

/** Add functions for DEV & DEBUG */
//require get_template_directory() . '/includes/dev-support.php';

/** Add constants */
require get_template_directory() . '/includes/constants.php';
/** Add SEO support */
require get_template_directory() . '/includes/seo-support.php';
/** Add theme support */
require get_template_directory() . '/includes/theme-support.php';
/** Enqueue scripts */
require get_template_directory() . '/includes/enqueue-scripts-style.php';
/** Various clean up functions */
require get_template_directory() . '/includes/cleanup.php';
/** Return entry meta information for posts */
// require get_template_directory() . '/includes/meta-data.php';
/** Create widget areas in sidebar and footer */
// require get_template_directory() . '/includes/widget-areas.php';
/** Add register nav menu */
require get_template_directory() . '/includes/navigation.php';
/** Add ajax */
require get_template_directory() . '/includes/ajax.php';
/** Add Yandex metrika */
require get_template_directory() . '/includes/metrika.php';
/** Create Yandex XML */
//require get_template_directory() . '/includes/xml/create_yandex_xml.php';
//require get_template_directory() . '/includes/xml/create_google_xml.php';

/** Add Woocommerce files */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/woocommerce/includes/wc-helpers-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-cart-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-shipping-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-checkout-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-custom-fields.php';
	require get_template_directory() . '/woocommerce/includes/wc-function.php';
	require get_template_directory() . '/woocommerce/includes/wc-remove-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-card-function.php';
	require get_template_directory() . '/woocommerce/includes/wc-catalog-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-yith-wishlist-finctions.php';
	require get_template_directory() . '/woocommerce/includes/wc-account-functions.php';
}



