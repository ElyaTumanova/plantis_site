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
		<?php echo do_shortcode('[br_filter_single filter_id=6055]') ?>
		<?php echo do_shortcode('[br_filter_single filter_id=6056]') ?>
    </div>
    <?php 
};

remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);

add_action('woocommerce_after_shop_loop','plnt_catalog_grid_end',20);

function plnt_catalog_grid_end() {
	?>
	</div>
    <?php 
};

// // вывод фильтров
add_action('woocommerce_before_shop_loop','plnt_catalog_filters_main_area', 20);

function plnt_catalog_filters_main_area() {
	?>
    <div class="catalog__filter-wrap">
		<?php echo do_shortcode('[br_filter_single filter_id=6054]') ?>
		<?php echo do_shortcode('[br_filter_single filter_id=6057]') ?>
    </div>
    <?php 	
};

//оформление карточки товара в каталоге

// // перенос ссылки на фото

remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_link_close', 20);

// // замена фото на слайдер

remove_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title','plnt_catalog_gallery', 10);

function plnt_catalog_gallery() {

	if (is_shop() || is_category() ||is_tag()) {
		global $product;
		$image = $product->get_image();	
		$attachment_ids = $product->get_gallery_attachment_ids();

		echo '<div class="product__image-slider-wrap nivo-catalog-gallery" >';
			echo $image;
			foreach( $attachment_ids as $attachment_id ) {
				echo wp_get_attachment_image( $attachment_id, 'shop_catalog' );
			};
		echo '</div>';

		?>
		<script>
			jQuery(function($){
				$('.nivo-catalog-gallery').nivoSlider({
					effect: 'random',               // эффекты, например: 'fold, fade, sliceDown, sliceDownLeft, sliceUp, sliceUpLeft, sliceUpDown, sliceUpDownLeft, slideInRight, slideInLeft'
					animSpeed: 500,                 // скорость анимации
					pauseTime: 3000,                // пауза между сменой слайдов
					directionNav: true,             // нужно ли отображать кнопки перехода на следующий и предыдущий слайд
					controlNav: true,               // 1,2,3... навигация (например в виде точек)
					pauseOnHover: true,             // останавливать прокрутку слайдов при наведении мыши
					manualAdvance: true,           // true - отключить автопрокрутку
					prevText: 'Назад',               // текст перехода на предыдущий слайд
					nextText: 'Вперед',               // текст кнопки перехода на следующий слайд
					randomStart: false,             // начинать со случайного слайда
				});
			});
		</script>
		<?php
	} else {
		woocommerce_template_loop_product_thumbnail();
	}
};


// // бейдж распродажа
remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10);
add_action('woocommerce_before_shop_loop_item_title','truemisha_sale_badge', 10);

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
	if(is_shop() || is_category() ||is_tag()) {
		global $product;
		global $tags_exeptions;
		$tags = $product->tag_ids;
		echo '<div class=catalog__tags>';
		foreach($tags as $tag) {
			if (!in_array($tag, $tags_exeptions, true)) {
				echo '<a class=catalog__tag-link href="'.get_tag_link(get_term($tag)->term_taxonomy_id).'">
					<span class=catalog__tag>'.get_term($tag)->name.' </span>
				</a>';
			}
		}
		echo '</div>';
	}
}