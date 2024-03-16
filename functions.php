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

/**
 * @snippet       Status Filters @ My Account Orders - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @community     https://businessbloomer.com/club/
 */
 
// ------------
// 1. Let orders query listen to URL parameter
 
add_filter( 'woocommerce_my_account_my_orders_query', 'bbloomer_my_account_orders_filter_by_status' );
 
function bbloomer_my_account_orders_filter_by_status( $args ) {
   if ( isset( $_GET['status'] ) && ! empty( $_GET['status'] ) ) {
      $args['status'] = array( $_GET['status'] );
   }
   return $args;
}
 
// ------------
// 2. Display list of filters
 
add_action( 'woocommerce_before_account_orders', 'bbloomer_my_account_orders_filters' );
 
function bbloomer_my_account_orders_filters() {
   echo '<p>Filter by: ';
   $customer_orders = 0;
   foreach ( wc_get_order_statuses() as $slug => $name ) {
      $status_orders = count( wc_get_orders( [ 'status' => $slug, 'customer' => get_current_user_id(), 'limit' => -1 ] ) );
      if ( $status_orders > 0 ) {
         if ( isset( $_GET['status'] ) && ! empty( $_GET['status'] ) && $_GET['status'] == $slug ) { 
            echo '<b>' . $name . ' (' . $status_orders . ')</b><span class="delimit"> - </span>';
         } else echo '<a href="' . add_query_arg( 'status', $slug, wc_get_endpoint_url( 'orders' ) ) . '">' . $name . ' (' . $status_orders . ')</a><span class="delimit"> - </span>';
      }
      $customer_orders += $status_orders;
   }
   if ( isset( $_GET['status'] ) && ! empty( $_GET['status'] ) ) {
      echo '<a href="' . remove_query_arg( 'status' ) . '">All statuses (' . $customer_orders . ')</a>';
   } else echo '<b>All statuses (' . $customer_orders . ')</b>';
   echo '</p>';
}
 
// ------------
// 3. My Account Orders Pagination fix
 
add_filter( 'woocommerce_get_endpoint_url', 'bbloomer_my_account_orders_filter_by_status_pagination', 9999, 4 );
          
function bbloomer_my_account_orders_filter_by_status_pagination( $url, $endpoint, $value, $permalink ) {
   if ( 'orders' == $endpoint && isset( $_GET['status'] ) && ! empty( $_GET['status'] ) ) {
      return add_query_arg( 'status', $_GET['status'], $url );
   }
   return $url;
}

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


