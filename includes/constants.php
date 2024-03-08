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
	echo '<pre>';
	print_r( $site );
	echo '</pre>';
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
	$local_pickup = 'local_pickup:9';
	$urgent_delivery1 = 'flat_rate:13';
	$urgent_delivery2 = 'flat_rate:14';
	$urgent_delivery3 = 'flat_rate:17';
	$urgent_delivery4 = 'flat_rate:18';
} else {
	// PLANTIS SHOP
	
}




