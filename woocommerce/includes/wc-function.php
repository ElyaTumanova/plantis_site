<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//Хлебные крошки
if ( ! function_exists( 'ast_breadcrumbs_yoast' ) ) {
	add_action( 'woocommerce_before_main_content', 'ast_breadrumbs_yoast', 10 );
	function ast_breadrumbs_yoast() {
		if ( !is_product() && !is_product_category() && !is_shop() && !is_product_tag() ) {
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				$before      = '<div id="breadcrumbs">';
				$after       = '</div>';
				$breadcrumbs = yoast_breadcrumb( $before, $after, true );
				
				return $breadcrumbs;
			}
		}
	}
}

//lazy load for images
// function plnt_add_lazy_class ($attr) {
// 	$attr['class'] .= ' lazy progressive replace';
// 	return $attr;
// }

// add_filter( 'wp_get_attachment_image_attributes', 'plnt_add_lazy_class', 10, 2);