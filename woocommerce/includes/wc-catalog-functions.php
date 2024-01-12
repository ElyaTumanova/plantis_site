<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//вывод меток 

add_action('woocommerce_after_shop_loop_item', 'plnt_get_product_tags', 20);

function plnt_get_product_tags() {
	$tags_exeptions = array(66,106);
	global $product;
	$tags = $product->tag_ids;
	echo '<div class=catalog__tags>';
	foreach($tags as $tag) {
		if (!in_array($tag, $tags_exeptions, true)) {
			echo '<span class=catalog__tag>'.get_term($tag)->name.' </span>';
		}
	}
	echo '</div>';
}