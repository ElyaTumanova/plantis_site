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


//ЗАДАЕМ КОНСТАНТЫ ДЛЯ JS
add_action( 'wp_footer', 'plnt_set_constants_script' );
function plnt_set_constants_script() {
	global $delivery_inMKAD;
	global $delivery_outMKAD;
	global $local_pickup;
	global $delivery_free;
	global $delivery_courier;
	global $delivery_long_dist;

	$in_mkad = carbon_get_theme_option('in_mkad');
    $out_mkad = carbon_get_theme_option('out_mkad');
    $urgent_delivery_markup = carbon_get_theme_option('urgent_delivery_markup');
	$large_delivery_markup_in_mkad = carbon_get_theme_option('large_delivery_markup_in_mkad');
	$large_delivery_markup_out_mkad = carbon_get_theme_option('large_delivery_markup_out_mkad');
	$small_delivery_markup = carbon_get_theme_option('small_delivery_markup');

	global $local_pickup;
   
	?>
	<script>
		let deliveryInMKAD = '<?php echo $delivery_inMKAD; ?>';
		let deliveryOutMKAD = '<?php echo $delivery_outMKAD; ?>';
		let localPickup = '<?php echo $local_pickup; ?>';
		let deliveryCourier = '<?php echo $delivery_courier; ?>';

		let deliveryCostInMkad = '<?php echo $in_mkad; ?>';
		let deliveryCostOutMkad = '<?php echo $out_mkad; ?>';
		let deliveryUrgentMarkup = '<?php echo $urgent_delivery_markup; ?>';
		let deliveryLargeMarkupInMkad = '<?php echo $large_delivery_markup_in_mkad; ?>';
		let deliveryLargeMarkupOutMkad = '<?php echo $large_delivery_markup_out_mkad; ?>';
		let deliverySmallMarkup = '<?php echo $small_delivery_markup; ?>';

		let localPickupId = '<?php echo $local_pickup; ?>';
		let deliveryFreeId = '<?php echo $delivery_free; ?>';
		let deliveryCourierId = '<?php echo $delivery_courier; ?>';
		let deliveryLongId = '<?php echo $delivery_long_dist; ?>';

	</script>
	<?php
}

add_action( 'wp_footer', 'plnt_get_shiping_costs' );

function plnt_get_shiping_costs() {
    $shipping_costs = [];
    $shipping_zones = WC_Shipping_Zones::get_zones();
 
	if( $shipping_zones ) {
 
		// для каждой зоны доставки
		foreach ( $shipping_zones as $shipping_zone_id => $shipping_zone ) {
 
			// получаем объект зоны доставки
			$shipping_zone = new WC_Shipping_Zone( $shipping_zone_id );
 
			// получаем доступные способы доставки для этой зоны
			$shipping_methods = $shipping_zone->get_shipping_methods( true, 'values' );
 
			if( $shipping_methods ) {
				foreach ( $shipping_methods as $shipping_method_id => $shipping_method ) {
                    $shipping_id = $shipping_method->id.":".$shipping_method_id;
                    $shipping_costs[$shipping_id]=$shipping_method->cost;
				}
			}
        }
    }

    global $delivery_inMKAD;
    print_r($shipping_costs); 
    //print_r($delivery_inMKAD); 
    //print_r($shipping_costs[$delivery_inMKAD]); 


	$in_mkad = $shipping_costs[$delivery_inMKAD];
    $out_mkad = $shipping_costs[$delivery_outMKAD];


	$in_mkad_urg = $shipping_costs[$urgent_delivery_inMKAD];
	$out_mkad_urg = $shipping_costs[$urgent_delivery_outMKAD];

	$in_mkad_large = $shipping_costs[$delivery_inMKAD_large];
	$out_mkad_large = $shipping_costs[$delivery_outMKAD_large];

	$in_mkad_urg_large = $shipping_costs[$urgent_delivery_inMKAD_large];
	$out_mkad_urg_large = $shipping_costs[$urgent_delivery_outMKAD_large];
    
 
	$in_mkad_small = $shipping_costs[$delivery_inMKAD_small];
	$out_mkad_small = $shipping_costs[$delivery_outMKAD_small];

	$in_mkad_small_urg = $shipping_costs[$urgent_delivery_inMKAD_small];
	$out_mkad_small_urg = $shipping_costs[$urgent_delivery_outMKAD_small];

	print_r($in_mkad); 


}