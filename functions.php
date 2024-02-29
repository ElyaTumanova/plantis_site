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
require get_template_directory() . '/includes/meta-data.php';
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
// /** Add XML */
// require get_template_directory() . '/includes/xml/create-test-xml.php';

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
// function plnt_check_page() {
// 	if ( is_cart() ) {
// 		echo 'Это корзина!';
// 	}
// 	else {
// 		echo 'Это какая-то другая страница.';
// 	}
// }

//  add_action( 'wp_footer', 'plnt_check_page' );

// function template_chooser($template) {    
// 	global $wp_query;   
// 	$post_type = get_query_var('post_type');   
// 	if( $wp_query->is_search )   
// 	{
// 	  return wc_locate_template('archive-product.php');  //  redirect to archive-product.php
// 	}   
// 	return $template;   
//   }
//   add_filter('template_include', 'template_chooser');

// function get_cats() {
// 	$categories = get_terms( [
// 		'taxonomy' => 'product_tag',
// 		'hide_empty' => false,
// 	] );
// 	echo '<pre>';
// 	print_r( $categories );
// 	echo '</pre>';
// }

// add_action( 'wp_footer', 'get_cats' );

/* function to create sitemap.xml file in root directory of site  */

add_action( "save_post", "eg_create_sitemap" );
function eg_create_sitemap() {
    $sitemap .= '<?xml version="1.0" encoding="UTF-8"?>' . '<?xml-stylesheet type="text/xsl"?>';
    $fp = fopen( ABSPATH . "lalalal.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );
}


function create_xml_button () {
	?>
	<button>Click me</button>
	<?php
}

add_action( 'wp_footer', 'plnt_check_page' );
