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


/* Способы доставки */

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
	
	$delivery_free = 'free_shipping:5';


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
	
	$delivery_free = 'free_shipping:4';
}




