<?php
/** Add Carbon Fields */
add_action( 'carbon_fields_register_fields', 'ast_register_custom_fields' );
function ast_register_custom_fields() {
	require get_template_directory() . '/includes/custom-fields/post-meta.php';
	require get_template_directory() . '/includes/custom-fields/theme-options.php';
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	load_template( get_template_directory() . '/includes/carbon-fields/vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}

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
/** Add constants */
require get_template_directory() . '/includes/constants.php';
/** Add Ynadex metrika */
require get_template_directory() . '/includes/metrika.php';
/** Create Yandex XML */
require get_template_directory() . '/includes/xml/create_yandex_xml.php';

/** Add Woocommerce files */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/woocommerce/includes/wc-cart-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-checkout-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-custom-fields.php';
	require get_template_directory() . '/woocommerce/includes/wc-function.php';
	require get_template_directory() . '/woocommerce/includes/wc-remove-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-card-function.php';
	require get_template_directory() . '/woocommerce/includes/wc-catalog-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-yith-wishlist-finctions.php';
	require get_template_directory() . '/woocommerce/includes/wc-account-functions.php';
}


// FOR DEV


function plnt_check_page() {
	// global $gorshki_cat_id;
	// if ( term_is_ancestor_of( $gorshki_cat_id, get_queried_object_id(), 'product_cat' ) ) {
	// 	echo 'подкатегория';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	// echo '<pre>';
	// print_r( get_queried_object_id() );
	// print_r( $gorshki_cat_id );
	// echo '</pre>';
	// if ( is_cart() ) {
	// 	echo 'Это корзина!';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	if( is_paged() ){
		$canon_url = get_pagenum_link(1);
		echo '<pre>';
		print_r( $canon_url );
		echo '</pre>';
	}
}

//add_action( 'wp_footer', 'plnt_check_page' );



add_action( 'wp_footer', 'plnt_order_payment' );


function plnt_order_payment() {
	$payment_method = WC()->session->get( 'chosen_payment_method' );
	echo '<pre>';
	print_r( $payment_method );
	echo '</pre>';
}

function name_of_your_function( $posted_data) {

    global $woocommerce;

    // Parsing posted data on checkout
    $post = array();
    $vars = explode('&', $posted_data);
    foreach ($vars as $k => $value){
        $v = explode('=', urldecode($value));
        $post[$v[0]] = $v[1];
    }

    // Here we collect payment method
    $payment_method = $post['payment_method'];
	echo '<pre>';
	var_dump( $payment_method );
	echo '</pre>'; 

    // Run code custom code for each specific payment option selected
    // if ($payment_method == "paypal") {
    //     // Your code goes here
    // }

    // elseif ($payment_method == "bacs") {
    //     // Your code goes here
    // }

    // elseif ($payment_method == "stripe") {
    //     // Your code goes here
    // }
}

//add_action('woocommerce_checkout_update_order_review', 'name_of_your_function');

// function get_cats() {
// 	$categories = get_terms( [
// 		'taxonomy' => 'product_tag',
// 		'hide_empty' => false,
// 	] );
// 	echo '<pre>';
// 	print_r( $categories );
// 	echo '</pre>';
// }


