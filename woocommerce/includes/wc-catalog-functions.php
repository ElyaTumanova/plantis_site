<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// оформление каталога целиком

// // убираем лишнее
remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description',10);
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);

// // структура каталога

add_action('woocommerce_before_shop_loop','plnt_catalog_grid_start',15);
function plnt_catalog_grid_start() {
	?>
	<div class="catalog__grid">
    <?php 
};

add_action('woocommerce_before_shop_loop','plnt_catalog_sidebar',20);

function plnt_catalog_sidebar() {
	?>
    <div class="catalog__sidebar">
   		catalog sidebaer here
    </div>
    <?php 
};

add_action('woocommerce_after_shop_loop','plnt_catalog_grid_end',20);

function plnt_catalog_grid_end() {
	?>
	</div>
    <?php 
};

//оформление карточки товара в каталоге

// // перенос ссылки на фото

remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_link_close', 20);

// // перенос кнопки в корзину

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
add_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_add_to_cart', 30);

// // обрамляем загловок в ссылку
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_open', 5);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_close', 15);

// // короткое описание
add_action('woocommerce_shop_loop_item_title','woocommerce_template_single_excerpt', 20);


// // вывод меток 

add_action('woocommerce_after_shop_loop_item', 'plnt_get_product_tags', 20);

function plnt_get_product_tags() {
	global $product;
	global $tags_exeptions;
	$tags = $product->tag_ids;
	echo '<div class=catalog__tags>';
	foreach($tags as $tag) {
		if (!in_array($tag, $tags_exeptions, true)) {
			echo '<pre>';
			print_r( get_term($tag) );
			print_r(get_tag_link(get_term($tag)->term_taxonomy_id));
			echo '</pre>';
			echo '<a class=catalog__tag-link href="'.get_tag_link(get_term($tag)->term_taxonomy_id).'">
				<span class=catalog__tag>'.get_term($tag)->name.' </span>
			</a>';
		}
	}
	echo '</div>';
}