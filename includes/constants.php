<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// constants for categories IDs
$plants_cat_id = 90;
$gorshki_cat_id = 86;
$treez_cat_id = 802;

$tags_exeptions = array(66,106);


if(is_page('wishlist')){
	$wish_product_count = wc_get_loop_prop( 'total' );
}