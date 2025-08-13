<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$site = get_site_url();

function plnt_check_site() {
	global $site;
	if (is_front_page()) {
		if($site === 'https://plantis-shop.ru' || $site === 'http://dev.plantis-shop.ru') {
			echo '<script> console.log("hello plantis ru")</script>';
		} else {
			echo '<script> console.log("hello plantis")</script>';
		}
	}
	// echo '<pre>';
	// print_r( $site );
	// echo '</pre>';
}

add_action( 'wp_footer', 'plnt_check_site' );

/* Категории товаров */
if($site === 'https://plantis-shop.ru' || $site === 'http://dev.plantis-shop.ru') {
	//PLANTIS-SHOP.RU
	// constants for categories IDs
	$plants_cat_id = 329; //ok
	$gorshki_cat_id = 138; //ok
	$treez_cat_id = 119; //ok
	$treez_poliv_cat_id = 240; //ok
	$ukhod_cat_id = 325; //ok
	$misc_cat_id = 16; //ok
	$skidki_cat_id = 385;//ok
	$avtopoliv_tag_id = 264; //ok
	$peresadka_cat_id = 546; //ok
	$plants_treez_cat_id = 17; //ok
	$lechuza_cat_id = 265; //ok
	$uncategorized_cat_id = 466; //ok
	
	$tags_podarki = array(
        420,
        368,
        367,
        353,
        352,
        351,
        350,
        348,
        349,
        347,
        385,	//tag skidki
		389); //tag pegular assortiment
} else {
	// PLANTIS SHOP
	$plants_cat_id = 838;
	$gorshki_cat_id = 68;
	$treez_cat_id = 802;
	$treez_poliv_cat_id = 846;
	$ukhod_cat_id = 69;
	$misc_cat_id = 23;
	$skidki_cat_id = 578;
	$avtopoliv_tag_id = 1050;
	$peresadka_cat_id = 740; // to do update
	$plants_treez_cat_id = 1152;
	$lechuza_cat_id = 1261;

	$tags_podarki = array( //all ok
		1084,
		1073,
		1076,
		1075,
		1080,
		1082,
		1072,
		1081,
		1077,
		1078,
		1074,
		837,	//tag skidki
		1195); //tag pegular assortiment
}


/* Способы доставки и оплаты */

// if($site === 'https://plantis-shop.ru' || $site === 'http://dev.plantis-shop.ru') {
	//PLANTIS-SHOP.RU
	$local_pickup = 'local_pickup:5'; 
	
	$delivery_inMKAD = 'flat_rate:1';
	$delivery_outMKAD = 'flat_rate:2';
	
	$delivery_free = 'free_shipping:9';

	$delivery_pochta = 'flat_rate:4';

	$delivery_courier = 'free_shipping:1'; //to be updated

	$delivery_long_dist = 'free_shipping:3';

	//#filters ID's
    $filter_plant_type_id = 9310; //ok
    $filter_plant_name_id = 9311; //ok
	$filter_podborki_id = 9323; //ok
	$filter_in_stock_id = 6110; //undo
	$filter_price_id = 9300; //ok
	$filter_height_id = 9313; //ok
	$filter_poliv_id = 9315;//ok
	$filter_svet_id = 9316;//ok
	$filter_vlaga_id = 9317; //ok
	$filter_diametr_id = 9321; //ok
	$filter_color_id = 9314; //ok
	$filter_forma_id = 9318; //ok
	$filter_materilal_id = 9319;//ok
	$filter_volume_id = 9320; //ok
	$filter_gift_id = 9322; //ok
	$filter_active_id = 9324; //ok
	$filter_razmer_id = 60247; //to be updated
	$filter_razmer_kashpo_id = 56545; //to be updated


// } 
// else {
// 	// PLANTIS SHOP
// 	$local_pickup = 'local_pickup:1'; //самовывоз
	
// 	$delivery_inMKAD = 'flat_rate:2';
// 	$delivery_outMKAD = 'flat_rate:3';
// 	$delivery_inMKAD_small = 'flat_rate:23';
// 	$delivery_outMKAD_small = 'flat_rate:24';
// 	$delivery_inMKAD_large = 'flat_rate:17'; 
// 	$delivery_outMKAD_large = 'flat_rate:18'; 
// 	$delivery_inMKAD_medium = 'flat_rate:9';  
// 	$delivery_outMKAD_medium = 'flat_rate:10'; 


// 	$urgent_delivery_inMKAD = 'flat_rate:5'; 
// 	$urgent_delivery_outMKAD = 'flat_rate:6'; 
// 	$urgent_delivery_inMKAD_small = 'flat_rate:25'; 
// 	$urgent_delivery_outMKAD_small = 'flat_rate:26'; 
// 	$urgent_delivery_inMKAD_large = 'flat_rate:19'; 
// 	$urgent_delivery_outMKAD_large = 'flat_rate:20';
// 	$urgent_delivery_inMKAD_medium = 'flat_rate:11'; 
// 	$urgent_delivery_outMKAD_medium = 'flat_rate:12'; 
	
// 	$delivery_free = 'free_shipping:4';
// 	$delivery_pochta = 'flat_rate:27';
// 	$delivery_courier = 'free_shipping:21';
// 	$delivery_long_dist = 'free_shipping:22';
	
// 	//#filters ID's
// 	$filter_podborki_id = 56536;
// 	$filter_in_stock_id = 56534;
// 	$filter_price_id = 56529;
// 	$filter_height_id = 56530;
// 	$filter_poliv_id = 56533;
// 	$filter_svet_id = 56538;
// 	$filter_vlaga_id = 56539;
// 	$filter_diametr_id = 56540;
// 	$filter_color_id = 56532;
// 	$filter_forma_id = 56541;
// 	$filter_materilal_id = 56543;
// 	$filter_volume_id = 56544;
// 	$filter_gift_id = 56535;
// 	$filter_active_id = 56531;
// 	$filter_razmer_id = 60247;
// 	$filter_razmer_kashpo_id = 56545;

// }

/* Изображения и иконки */

$filter_icon = get_template_directory_uri() . '/images/icons/filter_new.svg';


//ЗАДАЕМ КОНСТАНТЫ ДЛЯ JS
add_action( 'wp_footer', 'plnt_set_constants_script' );
function plnt_set_constants_script() {
	global $delivery_inMKAD;
	global $delivery_outMKAD;
	global $local_pickup;
	global $delivery_free;
	global $delivery_pochta;
	global $delivery_courier;
	global $delivery_long_dist;

	$late_markup_delivery = carbon_get_theme_option('late_markup_delivery');
	$large_markup_delivery = carbon_get_theme_option('large_markup_delivery');
    $small_markup_delivery = carbon_get_theme_option('small_markup_delivery');
    $medium_markup_delivery = carbon_get_theme_option('medium_markup_delivery');
    $urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');

	$shipping_costs = plnt_get_shiping_costs();

    $in_mkad = $shipping_costs[$delivery_inMKAD];
    $out_mkad = $shipping_costs[$delivery_outMKAD];

	$isbackorders = plnt_is_backorder();
	$isTreezBackorders = plnt_is_treez_backorder();

	$delivery_murkup = get_delivery_markup();
   
	?>
	<script>
		// shipping methods IDs
		let deliveryInMKAD = '<?php echo $delivery_inMKAD; ?>';
		let deliveryOutMKAD = '<?php echo $delivery_outMKAD; ?>';
	
		let localPickupId = '<?php echo $local_pickup; ?>';
		let deliveryFreeId = '<?php echo $delivery_free; ?>';
		let deliveryPochtaId = '<?php echo $delivery_pochta; ?>';
		let deliveryCourierId = '<?php echo $delivery_courier; ?>';
		let deliveryLongId = '<?php echo $delivery_long_dist; ?>';

		// shipping methods costs
		let deliveryCostInMkad = '<?php echo $in_mkad; ?>';
		let deliveryCostOutMkad = '<?php echo $out_mkad; ?>';

        //markups
		let deliveryUrgMarkup = '<?php echo $delivery_murkup['urg']; ?>';
		let deliveryLateMarkup = '<?php echo $late_markup_delivery; ?>';

        let deliveryMarkupInMkad = '<?php echo $delivery_murkup['in_mkad']; ?>'
        let deliveryMarkupOutMkad = '<?php echo $delivery_murkup['out_mkad']; ?>'

        //is backorders
		let isBackorder = '<?php echo $isbackorders; ?>';
		let isTreezBackorders = '<?php echo $isTreezBackorders; ?>';

	</script>
	<?php
}