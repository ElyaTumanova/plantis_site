<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description',10);
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);

add_action('woocommerce_before_shop_loop','plnt_catalog_sidebar',20);

function plnt_catalog_sidebar() {
	?>
    <div class="catalog__sidebar">
   		catalod sidebaer here
    </div>
    <?php 
};


//вывод меток 

add_action('woocommerce_after_shop_loop_item', 'plnt_get_product_tags', 20);

function plnt_get_product_tags() {
	global $product;
	global $tags_exeptions;
	$tags = $product->tag_ids;
	echo '<div class=catalog__tags>';
	foreach($tags as $tag) {
		if (!in_array($tag, $tags_exeptions, true)) {
			echo '<span class=catalog__tag>'.get_term($tag)->name.' </span>';
		}
	}
	echo '</div>';
}