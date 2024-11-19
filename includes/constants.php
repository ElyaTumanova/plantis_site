<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$site = get_site_url();

function plnt_check_site() {
	global $site;
	if (is_front_page()) {
		if($site === 'http://new.plantis.shop') {
			echo '<script> console.log("hello new plantis")</script>';
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
if($site === 'http://new.plantis.shop') {
	//NEW PLANTIS SHOP
	// constants for categories IDs
	$plants_cat_id = 90;
	$gorshki_cat_id = 86;
	$treez_cat_id = 379;
	$treez_poliv_cat_id = 229;
	$ukhod_cat_id = 378;
	$misc_cat_id = 16;
	$skidki_cat_id = 105;
	$avtopoliv_tag_id = 1050;
	$peresadka_cat_id = 740;
	
	$tags_podarki = array(
		374,
		300,
		275,
		276,
		277,
		293,
		283,
		278,
		294,
		295,
		347);
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
	
	$tags_podarki = array(
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
		1074);
}


/* Способы доставки и оплаты */

if($site === 'http://new.plantis.shop') {
	//NEW PLANTIS SHOP
	$local_pickup = 'local_pickup:9'; //самовывоз
	
	$delivery_inMKAD = 'flat_rate:1';
	$delivery_outMKAD = 'flat_rate:12';
	$delivery_inMKAD_small = 'flat_rate:15';
	$delivery_outMKAD_small = 'flat_rate:16';


	$urgent_delivery_inMKAD = 'flat_rate:13'; 
	$urgent_delivery_outMKAD = 'flat_rate:14'; 
	$urgent_delivery_inMKAD_small = 'flat_rate:17'; 
	$urgent_delivery_outMKAD_small = 'flat_rate:18';


	$urgent_delivery_inMKAD_late = 'flat_rate:19'; 
	$urgent_delivery_outMKAD_late = 'flat_rate:20'; 
	$urgent_delivery_inMKAD_small_late = 'flat_rate:21'; 
	$urgent_delivery_outMKAD_small_late = 'flat_rate:22';


	
	$delivery_free = 'free_shipping:5';

	$payment_inn_chekbox = 'cheque'; //ID for payment checkbox
	$inn_field = 'additional_inn'; //ID for INN field

	//#filters ID's
	$filter_podborki_id = 10989;
	$filter_in_stock_id = 6110;
	$filter_price_id = 6055;
	$filter_height_id = 6056;
	$filter_poliv_id = 6109;
	$filter_svet_id = 11115;
	$filter_vlaga_id = 11116;
	$filter_diametr_id = 11117;
	$filter_color_id = 6108;
	$filter_forma_id = 12013;
	$filter_materilal_id = 12015;
	$filter_volume_id = 12016;
	$filter_gift_id = 10988;
	$filter_active_id = 6057;


} else {
	// PLANTIS SHOP
	$local_pickup = 'local_pickup:1'; //самовывоз
	
	$delivery_inMKAD = 'flat_rate:2';
	$delivery_outMKAD = 'flat_rate:3';
	$delivery_inMKAD_small = 'flat_rate:9';
	$delivery_outMKAD_small = 'flat_rate:10';


	$urgent_delivery_inMKAD = 'flat_rate:5'; 
	$urgent_delivery_outMKAD = 'flat_rate:6'; 
	$urgent_delivery_inMKAD_small = 'flat_rate:11'; 
	$urgent_delivery_outMKAD_small = 'flat_rate:12';

	$urgent_delivery_inMKAD_late = 'flat_rate:13'; 
	$urgent_delivery_outMKAD_late = 'flat_rate:14'; 
	$urgent_delivery_inMKAD_small_late = 'flat_rate:15'; 
	$urgent_delivery_outMKAD_small_late = 'flat_rate:16';
	
	$delivery_free = 'free_shipping:4';

	$payment_inn_chekbox = 'cheque';
	$inn_field = 'additional_inn';

	//#filters ID's
	$filter_podborki_id = 56536;
	$filter_in_stock_id = 56534;
	$filter_price_id = 56529;
	$filter_height_id = 56530;
	$filter_poliv_id = 56533;
	$filter_svet_id = 56538;
	$filter_vlaga_id = 56539;
	$filter_diametr_id = 56540;
	$filter_color_id = 56532;
	$filter_forma_id = 56541;
	$filter_materilal_id = 56543;
	$filter_volume_id = 56544;
	$filter_gift_id = 56535;
	$filter_active_id = 56531;
}


/* Изображения и иконки */

$filter_icon = "https://plantis.shop/wp-content/uploads/2024/07/filter_new.svg";




