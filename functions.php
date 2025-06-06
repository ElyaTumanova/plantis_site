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
/** Add Yandex metrika */
require get_template_directory() . '/includes/metrika.php';
/** Create Yandex XML */
require get_template_directory() . '/includes/xml/create_yandex_xml.php';
require get_template_directory() . '/includes/xml/create_google_xml.php';

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


//ЗАДАЕМ КОНСТАНТЫ ДЛЯ JS
add_action( 'wp_footer', 'plnt_set_constants_script' );
function plnt_set_constants_script() {
	global $delivery_inMKAD;
	global $delivery_outMKAD;
	global $delivery_inMKAD_small;
	global $delivery_outMKAD_small;
	global $delivery_inMKAD_large;
	global $delivery_outMKAD_large;
	global $delivery_inMKAD_medium;
	global $delivery_outMKAD_medium;

	global $urgent_delivery_inMKAD; 
	global $urgent_delivery_outMKAD; 
	global $urgent_delivery_inMKAD_small; 
	global $urgent_delivery_outMKAD_small;
	global $urgent_delivery_inMKAD_large; 
	global $urgent_delivery_outMKAD_large;
	global $urgent_delivery_inMKAD_medium;
	global $urgent_delivery_outMKAD_medium;

	global $local_pickup;
	global $delivery_free;
	global $delivery_pochta;
	global $delivery_courier;
	global $delivery_long_dist;

	$late_markup_delivery = carbon_get_theme_option('late_markup_delivery');

	$shipping_costs = plnt_get_shiping_costs();

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

	$in_mkad_medium = $shipping_costs[$delivery_inMKAD_medium];
	$out_mkad_medium = $shipping_costs[$delivery_outMKAD_medium];

	$in_mkad_medium_urg = $shipping_costs[$urgent_delivery_inMKAD_medium];
	$out_mkad_medium_urg = $shipping_costs[$urgent_delivery_outMKAD_medium];

	$isbackorders = plnt_is_backorder();
	$isTreezBackorders = plnt_is_treez_backorder();
   
	?>
	<script>
		// shipping methods IDs
		let deliveryInMKAD = '<?php echo $delivery_inMKAD; ?>';
		let deliveryOutMKAD = '<?php echo $delivery_outMKAD; ?>';
		let deliveryInMKADSmall = '<?php echo $delivery_inMKAD_small; ?>';
		let deliveryOutMKADSmall = '<?php echo $delivery_outMKAD_small; ?>';
		let deliveryInMKADLarge = '<?php echo $delivery_inMKAD_large; ?>';
		let deliveryOutMKADLarge = '<?php echo $delivery_outMKAD_large; ?>';
		let deliveryInMKADMedium = '<?php echo $delivery_inMKAD_medium; ?>';
		let deliveryOutMKADMedium = '<?php echo $delivery_outMKAD_medium; ?>';

		let deliveryInMKADUrg = '<?php echo $urgent_delivery_inMKAD; ?>';
		let deliveryOutMKADUrg = '<?php echo $urgent_delivery_outMKAD; ?>';
		let deliveryInMKADSmallUrg = '<?php echo $urgent_delivery_inMKAD_small; ?>';
		let deliveryOutMKADSmallUrg = '<?php echo $urgent_delivery_outMKAD_small; ?>';
		let deliveryInMKADLargeUrg = '<?php echo $urgent_delivery_inMKAD_large; ?>';
		let deliveryOutMKADLargeUrg = '<?php echo $urgent_delivery_outMKAD_large; ?>';
		let deliveryInMKADMediumUrg = '<?php echo $urgent_delivery_inMKAD_medium; ?>';
		let deliveryOutMKADMediumUrg = '<?php echo $urgent_delivery_outMKAD_medium; ?>';
		
		
		let localPickupId = '<?php echo $local_pickup; ?>';
		let deliveryFreeId = '<?php echo $delivery_free; ?>';
		let deliveryPochtaId = '<?php echo $delivery_pochta; ?>';
		let deliveryCourierId = '<?php echo $delivery_courier; ?>';
		let deliveryLongId = '<?php echo $delivery_long_dist; ?>';

		// shipping methods costs
		let deliveryCostInMkad = '<?php echo $in_mkad; ?>';
		let deliveryCostOutMkad = '<?php echo $out_mkad; ?>';
		let deliveryCostInMkadUrg = '<?php echo $in_mkad_urg; ?>';
		let deliveryCostOutMkadUrg = '<?php echo $out_mkad_urg; ?>';

		let deliveryCostInMkadLarge = '<?php echo $in_mkad_large; ?>';
		let deliveryCostOutMkadLarge = '<?php echo $out_mkad_large; ?>';
		let deliveryCostInMkadLargeUrg = '<?php echo $in_mkad_urg_large; ?>';
		let deliveryCostOutMkadLargeUrg = '<?php echo $out_mkad_urg_large; ?>';

		let deliveryCostInMkadSmall = '<?php echo $in_mkad_small; ?>';
		let deliveryCostOutMkadSmall = '<?php echo $out_mkad_small; ?>';
		let deliveryCostInMkadSmallUrg = '<?php echo $in_mkad_small_urg; ?>';
		let deliveryCostOutMkadSmallUrg = '<?php echo $out_mkad_small_urg; ?>';

		let deliveryCostInMkadMedium = '<?php echo $in_mkad_medium; ?>';
		let deliveryCostOutMkadMedium = '<?php echo $out_mkad_medium; ?>';
		let deliveryCostInMkadMediumUrg = '<?php echo $in_mkad_medium_urg; ?>';
		let deliveryCostOutMkadMediumUrg = '<?php echo $out_mkad_medium_urg; ?>';

		let deliveryLateMarkup = '<?php echo $late_markup_delivery; ?>';

        let isBackorder = '<?php echo $isbackorders; ?>';
        let isTreezBackorders = '<?php echo $isTreezBackorders; ?>';

	</script>
	<?php
}


// FOR DEV

function plnt_dev_functions() {

	global $plants_treez_cat_id;
	global $plants_cat_id;
	global $gorshki_cat_id;
	global $ukhod_cat_id;
	global $treez_cat_id;
	global $treez_poliv_cat_id;
	global $lechuza_cat_id;
	global $misc_cat_id;
	global $peresadka_cat_id;

	$cats_for_check = [$plants_cat_id, $gorshki_cat_id, $ukhod_cat_id,$treez_cat_id, $treez_poliv_cat_id, $plants_treez_cat_id, $lechuza_cat_id, $peresadka_cat_id, $misc_cat_id];
	$cats_for_include = [];
	$cats_for_include_clean = [];
	foreach($cats_for_check as $item){
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query' => array( 
				array(
					'key' => '_stock',
					'type'    => 'numeric',
					'value' => '0',
					'compare' => '>'
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => [$item],
					'operator' => 'IN',
					'include_children' => 1,
				)
			)
		);
		$query = new WP_Query;
		$checkproducts = $query->query($args);

		$checkproductscount = count($checkproducts);
		// echo $item.' '.$checkproductscount;
		// echo '<br>';
		if($checkproductscount != 0) {
			//print_r($checkproducts);
			foreach ($checkproducts as $item) {
				// print_r($item);
				// echo '<br>';
				$product = wc_get_product($item);
				$prod_cats = $product->get_category_ids();
				foreach ($prod_cats as $cat) {
					array_push($cats_for_include, $cat);
				}
				// echo ('cat ids ');
				// print_r($product->get_category_ids());
				// echo '<br>';
			}
		};
	}
	echo 'cats_for_include ';
	$cats_for_include_clean = array_unique($cats_for_include);
	// print_r($cats_for_include);
	// echo '<br>';
	print_r($cats_for_include_clean);
	echo '<br>';
	echo 'cats_for_exclude ';
	print_r($cats_for_exclude); 
	

	$args_cats=array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'include' => $cats_for_include_clean,
	);

	$terms=get_terms($args_cats);

	foreach($terms as $item){
		echo $item->name;
		echo '<br>';
	}

	//print_r($terms);
}

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
	// if ( is_search() ) {
	// 	echo 'Это поиск!';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }

	// if(is_page_template('themes/plantis_site/woocommerce/loop/no-products-found.php')) {
	// 	echo 'Это то что нужно';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	
	//echo  basename( get_page_template() );
	//echo wp_kses_data(WC()->cart->get_cart_contents_count());
	//echo rand(5, 150);
  print_r(wc_get_product( 57313 ));
}

//add_action( 'wp_footer', 'plnt_check_page' );
//add_action( 'wp_footer', 'plnt_dev_functions' );


function gift_card_dev($gift_card) {
    $post_data = array(
    'post_title'    => wp_strip_all_tags( 'title' ),
    'post_content'  => $gift_card->gift_card_number ,
    'post_name' => 'gift',
    'post_status'   => 'publish',
    'post_author'   => 1,
  );

  // Вставляем запись в базу данных
  $post_id = wp_insert_post( $post_data );

}
add_filter( 'yith_ywgc_email_automatic_cart_discount_url', 'email_gftcard_link');

function email_gftcard_link($apply_discount_url, $args, $gift_card) {
    $apply_discount_url = 'http://new.plantis.shop/lalalal';
    return $apply_discount_url;
}

add_action('yith_ywgc_after_gift_card_generation_save', 'gift_card_dev');

