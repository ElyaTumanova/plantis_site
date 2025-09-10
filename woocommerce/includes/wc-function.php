<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function plnt_add_lazy_attr ($attr) {
	$attr['loading'] = 'lazy';
	return $attr;
}

 add_filter( 'wp_get_attachment_image_attributes', 'plnt_add_lazy_attr', 10, 2);