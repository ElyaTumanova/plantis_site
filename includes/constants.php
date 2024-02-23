<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$site = get_site_url();
	echo '<pre>';
	var_dump( $site );
	echo '</pre>';

if($site === 'http://new.plantis.shop') {
	//NEW PLANTIS SHOP
	// constants for categories IDs
	$plants_cat_id = 90;
	$gorshki_cat_id = 86;
	$treez_cat_id = 802;
	$treez_poliv_cat_id = 229;
	$ukhod_cat_id = 378;
	$misc_cat_id = 16;
	
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




