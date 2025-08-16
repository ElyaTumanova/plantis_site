<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//Хлебные крошки #breadcrumb
// if ( ! function_exists( 'ast_breadrumbs_yoast' ) ) {
// 	add_action( 'woocommerce_before_main_content', 'ast_breadrumbs_yoast', 10 );
// 	function ast_breadrumbs_yoast() {
// 		if ( is_product() || is_product_category() ||is_product_tag() || is_shop() || is_tax('pa_color')) {
// 			if ( function_exists( 'yoast_breadcrumb' ) ) {
// 				$before      = '<div class="woocommerce-breadcrumb" id="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
// 				$after       = '</div>';
// 				$breadcrumbs = yoast_breadcrumb( $before, $after, true );
				
// 				return $breadcrumbs;
// 			}
// 		}
// 	}
// }


remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

function plnt_add_lazy_attr ($attr) {
	$attr['loading'] = 'lazy';
	return $attr;
}

 add_filter( 'wp_get_attachment_image_attributes', 'plnt_add_lazy_attr', 10, 2);