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
	if ( is_search() ) {
		echo 'Это поиск!';
	}
	else {
		echo 'Это какая-то другая страница.';
	}

	// if(is_page_template('themes/plantis_site/woocommerce/loop/no-products-found.php')) {
	// 	echo 'Это то что нужно';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	
	//echo  basename( get_page_template() );
	//echo wp_kses_data(WC()->cart->get_cart_contents_count());
	echo rand(5, 150);
}

//add_action( 'wp_footer', 'plnt_check_page' );



add_action( 'wp_footer', 'plnt_get_checkout_fields' );

function plnt_get_checkout_fields() {
	$field = WC()->session->get('date'); 
	global $post_data;
	echo '<pre>';
	print_r( $post_data );
	echo '</pre>';
}




// Conditionally show/hide shipping methods
//add_filter( 'woocommerce_package_rates', 'shipping_package_rates_filter_callback', 100, 2 );
function shipping_package_rates_filter_callback( $rates, $package ) {
    // The defined rate id
    $company_rate_id = 'flat_rate:23';
	echo '<pre>';
	print_r( WC()->session->get('date' ) );
	echo '</pre>';

    if( WC()->session->get('date' ) === '11' ) {
        $rates = array( $company_rate_id => $rates[ $company_rate_id ] );
    } else {
        unset( $rates[ $company_rate_id ] );
    }
    return $rates;
}


add_action( 'wp_ajax_get_checkout_date', 'plnt_get_checkout_date' );
add_action( 'wp_ajax_nopriv_get_checkout_date', 'plnt_get_checkout_date' );
function plnt_get_checkout_date() {
    // if ( isset($_POST['date']) && ! empty($_POST['date']) ){
    //     WC()->session->set('date', '11' );
    // } else {
    //     WC()->session->set('date', '0' );
    // }

	WC()->session->set('date', '12' );
    die(); // (required)
}