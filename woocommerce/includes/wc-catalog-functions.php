<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# Catalog 
--------------------------------------------------------------*/
// оформление каталога целиком

// // убираем лишнее
remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description',10);
remove_action('woocommerce_before_shop_loop','woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop','woocommerce_output_all_notices', 10);
remove_action('woocommerce_sidebar','woocommerce_get_sidebar', 10);

// // структура каталога - добавлеям обертки

add_action('woocommerce_before_shop_loop','plnt_catalog_grid_start',15);
function plnt_catalog_grid_start() {
	?>
	<div class="catalog__grid">
    <?php 
};

// фильтр Товары в наличии #berocket #filters
add_action('woocommerce_before_shop_loop','plnt_catalog_products_wrap_start',40);
function plnt_catalog_products_wrap_start() {
	?>
		<div class="catalog__products-wrap">
	<?php 
};

add_action('woocommerce_after_shop_loop','plnt_catalog_products_wrap_end',20);
function plnt_catalog_products_wrap_end() {
	?>
		</div>
	<?php 
};

add_action('woocommerce_after_shop_loop','plnt_catalog_grid_end',20);

function plnt_catalog_grid_end() {
	?>
	</div>
    <?php 
};


// // вывод меню и фильтров в сайд баре  #filters #berocket
add_action('woocommerce_before_shop_loop','plnt_catalog_sidebar',20);
function plnt_catalog_sidebar() {
	?>
    <div class="catalog__sidebar modal-mob">
		<p class="catalog__sidebar-filters-heading">Фильтры</p>
		<div class="modal-mob__close catalog-sidebar__close button">&#10006;</div>
		<?php plnt_catalog_menu() ?>
		<div class="catalog__sidebar-ordering">
			<p class="catalog__sidebar-ordering-heading">Сортировка</p>
			<?php woocommerce_catalog_ordering() ?>
		</div>
		<div class="catalog__sidebar-filters">
			<div class="catalog__instock-filter">
				<?php echo do_shortcode('[br_filter_single filter_id=56534]') //товары в наличии //56534 //6110?>
			</div>
			<?php 
			echo do_shortcode('[br_filter_single filter_id=56529]'); // цена  \\56529 //6055
			if (!is_shop()) {
				echo do_shortcode('[br_filter_single filter_id=56530]'); // высота //56530 //6056
				echo do_shortcode('[br_filter_single filter_id=56533]'); //	полив //56533 //6109
				echo do_shortcode('[br_filter_single filter_id=56538]'); // освещение //56538 //11115
				echo do_shortcode('[br_filter_single filter_id=56539]'); // влажность //56539 //11116
				//echo do_shortcode('[br_filter_single filter_id=12018]'); // автополив
				echo do_shortcode('[br_filter_single filter_id=56540]'); // диаметр горшка //56540 //11117
				echo do_shortcode('[br_filter_single filter_id=56545]'); // диаметр кашпо Treez //56545 //12017
				echo do_shortcode('[br_filter_single filter_id=56532]'); // цвет //56532 //6108
				echo do_shortcode('[br_filter_single filter_id=56541]'); // форма //56541 //12013
				echo do_shortcode('[br_filter_single filter_id=56543]'); // материал //56543 //12015
				echo do_shortcode('[br_filter_single filter_id=56544]'); // Объем //56544 //12016
				echo do_shortcode('[br_filter_single filter_id=56535]'); // в подарок //56535 //10988
			}?>
		</div>
    </div>
    <?php 
};

// // вывод фильтров над каталогом  #filters #berocket
add_action('woocommerce_before_shop_loop','plnt_catalog_filters_main_area', 20);

function plnt_catalog_filters_main_area() {
	global $filter_icon;
	?>
    <div class="catalog__filter-wrap">
		<div class="catalog__filter-metki">
			<?php echo do_shortcode('[br_filter_single filter_id=56536]') //Подборки //56536 //10989?>  
		</div>
		<div class = "catalog__mob-filter-wrap"> 
			<div class="catalog__instock-filter">
				<?php echo do_shortcode('[br_filter_single filter_id=56537]') //Товары в наличии для моб //56537 // 10996?>
			</div>
			<div class="catalog__mob-filter-btn button"><img src="<?php echo $filter_icon ?>" alt="Фильтр"></div>
		</div>
		<div class="catalog__filter-active">
			<?php echo do_shortcode('[br_filter_single filter_id=56531]') //Активные фильтры //56531 //6057?>
		</div>
    </div>
    <?php 	
};

// // заголовок каталога 

add_filter( 'woocommerce_page_title', 'plnt_woocommerce_page_title');

function plnt_woocommerce_page_title($page_title) {
	if ( is_paged() ) {
		$pageNum = get_query_var('paged');

		$page_title .= " - Страница ".$pageNum;
		return $page_title;
	}
	
	else {
		return $page_title;
	}
}


// меняем название заголовка для shop
function plnt_change_my_title( $title ){
    if ( $title == "Магазин" ) $title = "Все товары";
    return $title;
}
add_filter( "get_the_archive_title", "plnt_change_my_title" );

/*--------------------------------------------------------------
# Card in Catalog 
--------------------------------------------------------------*/

//название товара - меняем тег h2

remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'soChangeProductsTitle', 10 );
function soChangeProductsTitle() {
    echo '<div class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</div>';
}

//оформление карточки товара в каталоге

// // перенос ссылки на фото

remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_link_close', 20);

// // замена фото на слайдер

remove_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title','plnt_catalog_gallery', 10);
add_action('woocommerce_before_product_loop_end','plnt_img_gallery_swiper_init', 10);

function plnt_catalog_gallery() {

	if (is_shop() || is_product_category() || is_product_tag()) {
		global $product;
		$image = $product->get_image();	
		$attachment_ids = $product->get_gallery_attachment_ids();
		echo '
		<div class="product__image-slider-wrap swiper">
			<div class="swiper-wrapper" >';
				echo $image;
				foreach( $attachment_ids as $attachment_id ) {
					// $params = [ 'class' => "attachment-woocommerce_thumbnail" ];
					echo wp_get_attachment_image( $attachment_id, 'large');
				};
			echo '
			</div>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>';
	} else {
		woocommerce_template_loop_product_thumbnail();
	}
};

function plnt_img_gallery_swiper_init() {
	?>
	<script>
		swiper_catalog_card_imgs = new Swiper('.product__image-slider-wrap', {
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			slidesPerView: 1,
			slidesPerGroup: 1,
			spaceBetween: 0,
			loop: true,
			freeMode: false,
			observer: true,
			observeParents: true,
			observeSlideChildren: true,
			breakpoints: {
				320: {
					navigation: {
						enabled: false,
					},
				},
				768: {
					navigation: {
						enabled: true,
					},
				}
			}
		});
	</script>
	<?php
}

// добавляем класс для swiper к изображениям товара 
add_filter( 'wp_get_attachment_image_attributes', 'AddThumbnailClass', 20, 2 );
function AddThumbnailClass( $atts, $attachment ) {
	if (is_shop() || is_product_category() || is_product_tag()) {
		$atts['class'] .= " swiper-slide"; 
	}
		return $atts;
}

// // бейдж распродажа
remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10);
add_action('woocommerce_before_shop_loop_item_title','truemisha_sale_badge', 5);

// // перенос кнопки в корзину
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
add_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_add_to_cart', 30);

// // уведомление о том, что в корзину добавили максимальное кол-во товара
add_action('woocommerce_before_shop_loop_item_title', 'plnt_cart_notice', 40);

// // меняем текст кнопки в корзину, если товар не в наличии

add_filter('woocommerce_product_add_to_cart_text','plnt_change_add_to_cart_text');

function plnt_change_add_to_cart_text($text) {
	global $product;
	if ($product->is_in_stock()) {
		return $text;
	} else {
		$text = __( 'Заказать', 'woocommerce' );
		return $text;
	}
}

// // добавляем класс для кнопки в корзину, если товар не в наличии
add_filter( 'woocommerce_loop_add_to_cart_args', 'plnt_woocommerce_loop_add_to_cart_args_outofstock', 10, 2 );

function plnt_woocommerce_loop_add_to_cart_args_outofstock( $args, $product ){
	if ($product->is_in_stock()) {
		return $args;
	} else {
		$args['class'] .= ' product_out_of_stock';
		return $args;
	}
};

// // обрамляем загловок в ссылку
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_open', 5);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_close', 15);

// // короткое описание
add_action('woocommerce_shop_loop_item_title','woocommerce_template_single_excerpt', 20);


// // вывод меток 
add_action('woocommerce_after_shop_loop_item', 'plnt_get_product_tags', 20);

function plnt_get_product_tags() {
	if(is_shop() || is_product_category() || is_product_tag()) {
		global $product;
		global $tags_podarki;
		$tags = $product->tag_ids;
		echo '<div class=catalog__tags>';
		foreach($tags as $tag) {
			if (!in_array($tag, $tags_podarki, true)) {
				echo '<a class=catalog__tag-link href="'.get_tag_link(get_term($tag)->term_taxonomy_id).'">
					<span class=catalog__tag>'.get_term($tag)->name.'</span>
				</a>';
			}
		}
		echo '</div>';
	}
}

// // добавляем класс для swiper для каталог гридов
add_filter('post_class', 'plnt_add_class_loop_item_swiper');
function plnt_add_class_loop_item_swiper($clasess){
	if(is_product() || is_front_page()){
		$clasess[] .= 'swiper-slide';
	}
	//get_pr($clasess, false);
	return $clasess;
}

/*--------------------------------------------------------------
# Catalog Functions
--------------------------------------------------------------*/
// функции для вывода товаров

// // вывод товаров в каталоге с учетом наличия - instock products first 

add_filter('posts_clauses', 'order_by_stock_status', 9999);
function order_by_stock_status($posts_clauses) {
    global $wpdb;
    // only change query on WooCommerce loops
    if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())) {
        $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
        $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
        $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
    }
    return $posts_clauses;
}

// // скрываем товары не в наличии для определенных страниц и категорий 

add_filter( 'woocommerce_product_query_meta_query', 'shop_only_instock_products', 10, 2 );

function shop_only_instock_products( $meta_query, $query ) {
	global $plants_cat_id;
	global $gorshki_cat_id;
    global $treez_cat_id;
	if( is_shop() || is_product_category($gorshki_cat_id) || cat_is_ancestor_of( $gorshki_cat_id, get_queried_object_id() ) || is_product_category($treez_cat_id) || is_search()) { 		//где хотим срыть товары не в наличии
		$meta_query[] = array(
			'key' => '_stock_status',
			'value' => 'outofstock',
			'compare' => '!='
			);
		return $meta_query;
	} else {
		return $meta_query;
	}
}

// // варианты сортировки товаров в каталоге

add_filter( 'woocommerce_catalog_orderby', 'truemisha_remove_orderby_options' );
 
function truemisha_remove_orderby_options( $sortby ) {
	unset( $sortby[ 'popularity' ] ); // по популярности
	unset( $sortby[ 'date' ] ); // Сортировка по более позднему
	unset( $sortby[ 'price' ] ); // Цены: по возрастанию
	unset( $sortby[ 'price-desc' ] ); // Цены: по убыванию
 
	return $sortby;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );

function custom_woocommerce_get_catalog_ordering_args( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

	if ( 'name_list_asc' == $orderby_value ) {
	$args['orderby'] = 'name';
	$args['order'] = 'ASC';
	$args['meta_key'] = '';
	}

	if ( 'name_list_desc' == $orderby_value ) {
	$args['orderby'] = 'name';
	$args['order'] = 'DESC';
	$args['meta_key'] = '';
	}

	return $args;
}

add_filter( 'woocommerce_catalog_orderby', 'truemisha_custom_orderby_option' );
 
function truemisha_custom_orderby_option( $sortby ) {
	$sortby['date'] = 'По новизне';
	$sortby['popularity'] = 'По популярности';
	$sortby['price-desc'] = 'По уменьшению цены';
	$sortby['price'] = 'По увеличению цены';
	$sortby['name_list_asc'] = 'По названию от А до Я';
	$sortby['name_list_desc'] = 'По названию от Я до А';
	return $sortby;
}

// параметры пагинации #pagination #woocommerce-pagination

add_filter( 'woocommerce_pagination_args', 'plnt_woocommerce_pagination_args_filter' );

function plnt_woocommerce_pagination_args_filter( $array ){
	$array = array(
		'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
		'next_text' => is_rtl() ? '&larr;' : '&rarr;',
		'type'      => 'list',
		'end_size'  => 2,
		'mid_size'  => 1,
	);
	return $array;
}

function move_to_top_on_pagination() {
	?>
		<script>
			jQuery(function ($) {
				$(document.body).on('click', '.page-numbers:not(.current)', function (event) {
					event.preventDefault();
					$('html, body').animate({
						scrollTop: $(".catalog__products-wrap").offset().top - 200
					}, 777);

				});
			});
		</script>
	<?php
	}

add_action('wp_footer', 'move_to_top_on_pagination');

// добавляем директивы ноиндекс, фоллоу для страниц пагинации, начиная со 2 #SEO
add_filter( 'wpseo_robots', 'filter_wpseo_robots' );

/* Yoast SEO -  add noindex, follow for paginated pages */
function filter_wpseo_robots( $robotsstr ) {
    if ( is_paged() ) {
        return 'noindex, follow';
    }
 
    return $robotsstr;
}


// изменяем названия меток на подборки для хлебных крошек #breadcrumb
add_filter( 'woocommerce_get_breadcrumb', 'plnt_woocommerce_get_breadcrumb_filter', 10, 2 );

function plnt_woocommerce_get_breadcrumb_filter( $crumbs, $that ){
	foreach ( $crumbs as $crumb ) {
		if (str_contains($crumb[0], 'Товары с меткой ')) {
			$key = array_search($crumb, $crumbs);
			$newstring = str_replace('Товары с меткой ', "Товары из подборки ", $crumb[0]);

			$replacements = array(0 => $newstring);

			$crumbNew = array_replace($crumb, $replacements);
			$replacements2 = array($key => $crumbNew);
			$crumbsNew = array_replace($crumbs, $replacements2);
			$crumbs = $crumbsNew;
		}
	}

	return $crumbs;
}