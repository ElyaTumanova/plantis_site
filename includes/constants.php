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
	$plants_treez_cat_id = 768;
	
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
	$plants_treez_cat_id = 1152;

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
	$delivery_inMKAD_large = 'flat_rate:29';
	$delivery_outMKAD_large = 'flat_rate:30';


	$urgent_delivery_inMKAD = 'flat_rate:13'; 
	$urgent_delivery_outMKAD = 'flat_rate:14'; 
	$urgent_delivery_inMKAD_small = 'flat_rate:17'; 
	$urgent_delivery_outMKAD_small = 'flat_rate:18';
	$urgent_delivery_inMKAD_large = 'flat_rate:31'; 
	$urgent_delivery_outMKAD_large = 'flat_rate:32';
	
	$delivery_free = 'free_shipping:5';

	$delivery_courier = 'free_shipping:26';

	$delivery_long_dist = 'free_shipping:28';

	


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
	$filter_razmer_id = 60247; //to be updated


} else {
	// PLANTIS SHOP
	$local_pickup = 'local_pickup:1'; //самовывоз
	
	$delivery_inMKAD = 'flat_rate:2';
	$delivery_outMKAD = 'flat_rate:3';
	// $delivery_inMKAD_small = 'flat_rate:9';
	// $delivery_outMKAD_small = 'flat_rate:10';
	$delivery_inMKAD_large = 'flat_rate:29'; //TO BE UDATETD
	$delivery_outMKAD_large = 'flat_rate:30';//TO BE UDATETD


	// $urgent_delivery_inMKAD = 'flat_rate:5'; 
	// $urgent_delivery_outMKAD = 'flat_rate:6'; 
	// $urgent_delivery_inMKAD_small = 'flat_rate:11'; 
	// $urgent_delivery_outMKAD_small = 'flat_rate:12';
	$urgent_delivery_inMKAD_large = 'flat_rate:31'; //TO BE UDATETD
	$urgent_delivery_outMKAD_large = 'flat_rate:32';//TO BE UDATETD
	
	$delivery_free = 'free_shipping:4';
	$delivery_courier = 'free_shipping:26'; //TO BE UDATETD
	$delivery_long_dist = 'free_shipping:28';//TO BE UDATETD

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
	$filter_razmer_id = 60247;

}

/* Изображения и иконки */

$filter_icon = "https://plantis.shop/wp-content/uploads/2024/07/filter_new.svg";


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