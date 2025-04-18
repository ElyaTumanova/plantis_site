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
	// // #filters ID's
	global $filter_in_stock_id;
	global $filter_price_id;
	global $filter_height_id;
	global $filter_poliv_id;
	global $filter_svet_id;
	global $filter_vlaga_id;
	global $filter_diametr_id;
	global $filter_color_id;
	global $filter_forma_id;
	global $filter_materilal_id;
	global $filter_volume_id;
	global $filter_gift_id; 
	global $filter_razmer_id;
	$close_icon = carbon_get_theme_option('close_icon')
	?>
    <div class="catalog__sidebar modal-mob">
		<p class="catalog__sidebar-filters-heading">Фильтры</p>
		<div class="modal-mob__close catalog-sidebar__close button"><?php echo $close_icon ?></div>
		<?php plnt_catalog_menu() ?>
		<div class="catalog__sidebar-filters">
			<div class="catalog__instock-filter">
				<?php echo do_shortcode('[br_filter_single filter_id='.$filter_in_stock_id.']') //товары в наличии //56534 //6110?>
			</div>
			<?php 
			echo do_shortcode('[br_filter_single filter_id='.$filter_price_id.']'); // цена  \\56529 //6055
			if (!is_shop()) {
				echo do_shortcode('[br_filter_single filter_id='.$filter_height_id.']'); // высота //56530 //6056
				echo do_shortcode('[br_filter_single filter_id='.$filter_poliv_id.']'); //	полив //56533 //6109
				echo do_shortcode('[br_filter_single filter_id='.$filter_svet_id.']'); // освещение //56538 //11115
				echo do_shortcode('[br_filter_single filter_id='.$filter_vlaga_id.']'); // влажность //56539 //11116
				//echo do_shortcode('[br_filter_single filter_id=12018]'); // автополив
				echo do_shortcode('[br_filter_single filter_id='.$filter_diametr_id.']'); // диаметр горшка //56540 //11117
				//echo do_shortcode('[br_filter_single filter_id=56545]'); // диаметр кашпо Treez //56545 //12017
				echo do_shortcode('[br_filter_single filter_id='.$filter_color_id.']'); // цвет //56532 //6108
				echo do_shortcode('[br_filter_single filter_id='.$filter_forma_id.']'); // форма //56541 //12013
				echo do_shortcode('[br_filter_single filter_id='.$filter_materilal_id.']'); // материал //56543 //12015
				//echo do_shortcode('[br_filter_single filter_id='.$filter_razmer_id.']'); // размер для растений Treez
				echo do_shortcode('[br_filter_single filter_id='.$filter_volume_id.']'); // Объем //56544 //12016
				echo do_shortcode('[br_filter_single filter_id='.$filter_gift_id.']'); // в подарок //56535 //10988
			}
			?>
		</div>
    </div>
    <?php 
};

// // вывод фильтров над каталогом  #filters #berocket
add_action('woocommerce_before_shop_loop','plnt_catalog_filters_main_area', 20);

function plnt_catalog_filters_main_area() {
	global $filter_icon;
	// // #filters ID's
	global $filter_podborki_id;
	global $filter_active_id;
	?>
    <div class="catalog__filter-wrap">
		<div class="catalog__filter-metki">
			<?php echo do_shortcode('[br_filter_single filter_id='.$filter_podborki_id.']') //Подборки //56536 //10989?>  
		</div>
		<div class = "catalog__mob-filter-wrap"> 
			<?php plnt_woocommerce_catalog_ordering() //сортировка с дополнительной оберткой?>  
			<div class="catalog__mob-filter-btn"><img src="<?php echo $filter_icon ?>" alt="Фильтр"></div>
		</div>
		<div class="catalog__filter-active">
			<?php echo do_shortcode('[br_filter_single filter_id='.$filter_active_id.']') //Активные фильтры //56531 //6057?>
		</div>
    </div>
    <?php 	
};

// // сортировка - добавляем обертку

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'plnt_woocommerce_catalog_ordering', 30 );

function plnt_woocommerce_catalog_ordering() {
	?>
	<div class="woocommerce-ordering__wrap">
		<svg viewBox="0 0 24 24" width="30" height="30" fill="none" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M16.3066 8.30859V18.3086" stroke="#212121" stroke-width="1.3"></path><path d="M20 15.3086L16.3077 19.0009L12.6154 15.3086" stroke="#212121" stroke-width="1.3" stroke-linejoin="round"></path><path d="M7.69336 15V5" stroke="#212121" stroke-width="1.3"></path><path d="M4 8L7.69231 4.30769L11.3846 8" stroke="#212121" stroke-width="1.3" stroke-linejoin="round"></path></svg>
		<?php woocommerce_catalog_ordering() ?>
	</div>
	<?php
}

// // переключатель сетки
add_action('woocommerce_before_shop_loop','plnt_catalog_grid_columns', 30);

function plnt_catalog_grid_columns () {
	?>
    <div class="catalog__grid-buttons">
		<button class="catalog__grid-button" id="catalog__grid-button-3" disabled>
		</button>
		<button class="catalog__grid-button" id="catalog__grid-button-2" >
		</button>
    </div>
    <?php 	
}


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

// описание категории и преимущества в каталоге

add_action('woocommerce_after_shop_loop','woocommerce_taxonomy_archive_description',15);
add_action('woocommerce_after_shop_loop','plnt_get_advantages',15);

function plnt_get_advantages() {
	get_template_part( 'template-parts/advantages' );			
};

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

	if (is_shop() || is_product_category() || is_product_tag() ) {
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
		jQuery(function($){
			swiper_catalog_card_imgs_init ();
		})
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

// вывод статуса в наличии

add_action('woocommerce_after_shop_loop_item', 'plnt_check_stock_status', 30);

// // добавляем класс для swiper для каталог гридов
add_filter('post_class', 'plnt_add_class_loop_item_swiper');
function plnt_add_class_loop_item_swiper($clasess){
	if(is_product() || is_front_page() || is_cart() || is_page('wishlist') || is_search()) {
		$clasess[] .= 'swiper-slide';
	}
	//get_pr($clasess, false);
	return $clasess;
}

/*--------------------------------------------------------------
# Catalog Functions
--------------------------------------------------------------*/
// функции для вывода товаров

// // количетсво товаров и кол-во колонок в каталоге

add_filter('loop_shop_columns', 'plnt_loop_columns');
if (!function_exists('plnt_loop_columns')) {
    function plnt_loop_columns() {
    	return 3;
    }
}

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
	global $plants_treez_cat_id;
    global $ukhod_cat_id;
    global $lechuza_cat_id;
    global $avtopoliv_tag_id;

	if( is_shop() || 
	is_product_category($gorshki_cat_id) || 
	term_is_ancestor_of( $gorshki_cat_id, get_queried_object_id(), 'product_cat' ) || 
	is_product_category($treez_cat_id) || 
	term_is_ancestor_of( $treez_cat_id, get_queried_object_id(), 'product_cat' )|| 
	is_product_category($plants_treez_cat_id) || 
	term_is_ancestor_of( $plants_treez_cat_id, get_queried_object_id(), 'product_cat' )||
	is_product_category($ukhod_cat_id) || 
	term_is_ancestor_of( $ukhod_cat_id, get_queried_object_id(), 'product_cat' ) ||
	is_product_category($lechuza_cat_id) || 
	term_is_ancestor_of( $lechuza_cat_id, get_queried_object_id(), 'product_cat' ) ||
	is_product_tag ($avtopoliv_tag_id) ||
	is_search()) { 		//где хотим срыть товары не в наличии
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

// // скрываем Treez Plnats из результатов поиска 

add_action( 'woocommerce_product_query', 'truemisha_exclude_category', 25 );
 
function truemisha_exclude_category( $query ) {
	if(is_search()) {
		$tax_query = (array) $query->get( 'tax_query' );
	
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => array( 'iskusstvennye-rasteniya-treez' ),
			'operator' => 'NOT IN'
		);
	
	
		$query->set( 'tax_query', $tax_query );
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

// изменяем canonical для страниц пагинации #SEO

// Disable Canonical for - ALL pages
function remove_canonical() {
	add_filter( 'wpseo_canonical', '__return_false');
}
//add_action('wp', 'remove_canonical', -19999);

//add_action( 'wpseo_head', 'remove_canonical', 4);

//add_filter( 'wpseo_canonical', '__return_false', 20);

// add_filter( 'wpseo_next_rel_link', '__return_false' );
// add_filter( 'wpseo_prev_rel_link', '__return_false' );

function plnt_change_canonical() {
	if (is_shop() || is_category() || is_tag()) {
		// Получаем URL первой страницы текущего архива
        $first_page_url = get_pagenum_link(1);
		
		// Разбираем URL на составляющие
		$parsedUrl = parse_url($first_page_url);

		// Строим новый URL без query-параметров
		$first_page_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

		$canonical = $first_page_url;

		return $canonical;
	} 
	// else {
	// 	$current_page_url = get_page_link();
	// 	$canonical = $current_page_url;
	// 	return $canonical;
	// }
}

function plnt_change_canonical_init() {
	add_filter( 'wpseo_canonical', 'plnt_change_canonical', 99999);
}

//add_action('init', 'plnt_change_canonical_init');

//убирем канонакал, который выводит Load More плагин
function remove_my_theme_canonical() {
    $br_aapf_paid_instance = BeRocket_AAPF_paid::getInstance();
    remove_action('wp_head', array($br_aapf_paid_instance, 'wp_head_canonical'), 99999);
}
add_action('init', 'remove_my_theme_canonical');

function add_custom_canonical_tags() {
    if (is_paged()) {
        // Получаем URL первой страницы текущего архива
        $first_page_url = get_pagenum_link(1);
		
		// Разбираем URL на составляющие
		$parsedUrl = parse_url($first_page_url);
		// print_r($parsedUrl);

		// Строим новый URL без query-параметров
		$first_page_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
				
        // Добавляем canonical тег
        echo '<link rel="canonical" href="' . esc_url($first_page_url) . '" />' . "\n";

        // Получаем номер текущей страницы
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        // Добавляем ссылки на следующую и предыдущую страницы
        if ($paged > 1) {
            $prev_page_url = get_pagenum_link($paged - 1);
            echo '<link rel="prev" href="' . esc_url($prev_page_url) . '" />' . "\n";
        }
        
        // Проверяем, есть ли следующая страница
        global $wp_query;
        if ($paged < $wp_query->max_num_pages) {
            $next_page_url = get_pagenum_link($paged + 1);
            echo '<link rel="next" href="' . esc_url($next_page_url) . '" />' . "\n";
        }
    }
}

// Добавляем действие в WordPress, чтобы выполнить функцию при выводе тегов в head
add_action('wp_head', 'add_custom_canonical_tags');

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

// #filters добавляем классы к фильтрам #berocket для работы слайдера #swiper

add_filter('BeRocket_AAPF_template_full_content', 'some_custom_berocket_aapf_template_full_content', 4000, 1);
add_filter('BeRocket_AAPF_template_full_element_content', 'some_custom_berocket_aapf_template_full_content', 4000, 1);
function some_custom_berocket_aapf_template_full_content($template_content) {
	if ($template_content['template']['attributes']['data-name']==='Подборки') {
		if ($template_content['template']['attributes']['id']==='bapf_13' || $template_content['template']['attributes']['id']==='bapf_3') {
	
			array_push($template_content['template']['content']['filter']['attributes']['class'],'metki_swiper_wrap');
			array_push($template_content['template']['content']['filter']['attributes']['class'],'swiper');
			
			$template_content['template']['content']['filter']['content']['list']['attributes']['class'] = 'swiper-wrapper';
	
			$elements = $template_content['template']['content']['filter']['content']['list']['content'];
			$new_elements = [];
			$i = 0;
			foreach($elements as $element) {
				$element['attributes']['class'] = 'swiper-slide';
				$new_elements[$i] = $element;
				$i++;
			}
			$template_content['template']['content']['filter']['content']['list']['content'] = $new_elements;
	
			$template_content['template']['content'] = berocket_insert_to_array(
				$template_content['template']['content'],
				'filter',
				array(
					'custom_content' => '<div class="swiper-scrollbar"></div>'
				),
				true
			);
	
			// echo '<pre>';
			// print_r( $template_content );
			// echo '</pre>';
	
		}
	}
    return $template_content;
}

// add_filter('BeRocket_AAPF_template_full_content', 'plnt_berocket_gift_filter_header', 4000, 1);
// add_filter('BeRocket_AAPF_template_full_element_content', 'plnt_berocket_gift_filter_header', 4000, 1);
// function plnt_berocket_gift_filter_header($template_content) {
// 	if ($template_content['template']['attributes']['id']==='bapf_12') {
//     $template_content['template']['attributes']['data-name'] = 'В подарок';
// 	// echo '<pre>';
// 	// print_r( $template_content );
// 	// echo '</pre>';
// 	}
//     return $template_content;
// }


// add_filter('BeRocket_AAPF_template_full_content', 'plnt_berocket_active_filter', 4000, 1);
// add_filter('BeRocket_AAPF_template_full_element_content', 'plnt_berocket_active_filter', 4000, 1);
// function plnt_berocket_active_filter($template_content) {
// 	if ($template_content['template']['attributes']['data-name']==='Активные фильтры') {
//     $template_content['template']['attributes']['id'] = 'active_id';
// 	// echo '<pre>';
// 	// print_r( $template_content );
// 	// echo '</pre>';
// 	}
//     return $template_content;
// }

// вывод слайдеров товаров на главной
add_action('wp_ajax_get_main_cats_term', 'plnt_main_cats_slider_action_callback');
add_action('wp_ajax_nopriv_get_main_cats_term', 'plnt_main_cats_slider_action_callback');

function plnt_main_cats_slider_action_callback() {
	$term_slug = $_POST['term'];
	$term_type = $_POST['type'];

    $args = array(
        'post_type' => 'product',
        'ignore_sticky_posts' => 1,
        'no_found_rows' => 1,
        'posts_per_page' => 8,
        'orderby' => 'rand',
        'meta_query' => array( 
            array(
                'key'       => '_stock_status',
                'value'     => array('outofstock','onbackorder'),
                'compare'   => 'NOT IN'
            )
        ),
		'tax_query' => array(
			array(
				'taxonomy' => $term_type,
				'field' => 'slug',
				'terms' => $term_slug,
			)
		),
    );
    
    $products = new WP_Query( $args );
	$json_data['out'] = ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
    if ( $products->have_posts() ) : ?>  
	
		<div class="product-slider-wrap product-slider-swiper swiper">
			<ul class="products columns-3 swiper-wrapper">
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?> 
			</ul>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
		<a class="main__cats-all" href="<?php echo get_term_link( $term_slug, $term_type );?>">Все товары категории</a>

    <?php endif;

    
    $json_data['out'] = ob_get_clean();
    wp_send_json($json_data);
    wp_die();
};


