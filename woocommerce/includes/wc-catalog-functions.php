<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*--------------------------------------------------------------
Contents
#Init
#Catalog design
#Card in Catalog design
#Catalog Functions
--------------------------------------------------------------*/
/*--------------------------------------------------------------
#Init
--------------------------------------------------------------*/
add_action('woocommerce_before_shop_loop','plnt_set_constants',5);

/*--------------------------------------------------------------
#Catalog design
--------------------------------------------------------------*/

// // структура каталога - добавлеям обертки

// фильтр Товары в наличии #berocket #filters




// add_action('woocommerce_before_shop_loop',function(){
//      global $plnt_start_timer;
//      $plnt_start_timer = microtime(true);
// },19);
// add_action('woocommerce_before_shop_loop',function(){
//     global $plnt_start_timer;
//     echo "<!-- Timing: plnt_catalog_sidebar = " . round((microtime(true) - $plnt_start_timer) * 1000, 2) . " ms -->";
// },21);

// // вывод фильтров над каталогом  #filters #berocket
add_action('woocommerce_before_shop_loop','plnt_catalog_filters_main_area', 20);

function plnt_catalog_filters_main_area() {
	global $filter_icon;
	// // #filters ID's
	
	global $filter_active_id;
	?>
    <div class="catalog__filter-wrap">
      <div class = "catalog__mob-filter-wrap"> 
        <?php woocommerce_catalog_ordering() //сортировка с дополнительной оберткой?>  
        <div class="catalog__mob-filter-btn"><img src="<?php echo $filter_icon ?>" alt="Фильтр"></div>
      </div>
      <div class="catalog__filter-active">
        <?php echo do_shortcode('[br_filter_single filter_id='.$filter_active_id.']') //Активные фильтры //56531 //6057?>
      </div>
    </div>
    <?php 	
};






// // заголовок каталога 

// меняем название заголовка для shop
//add_filter( "get_the_archive_title", "plnt_change_my_title" );
function plnt_change_my_title( $title ){
    if ( $title == "Магазин" ) $title = "Все товары";
    return $title;
}

// настройки заголовков для страницы каталога с атрибутом цвет #color

// // заголовок страницы 
add_filter( 'woocommerce_page_title', 'plnt_attribute_page_title',10);

function plnt_attribute_page_title($page_title) {
    if ( is_tax('pa_color') ) {
        $new_text = plnt_get_color_name_title($page_title);
        $page_title = "Горшки и кашпо ".$new_text." цвета";
		return $page_title;
    }
	else {
		return $page_title;
	}
}

// заголовок для страниц пагинации
add_filter( 'woocommerce_page_title', 'plnt_woocommerce_page_title',20);

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


function plnt_get_color_name_title($text) {
    $new_text;
    switch($text) {
        case 'Серебро':
            $new_text = 'серебряного';
            break;
        case 'Золотой':
            $new_text = 'золотого';
            break;
        default:
            $new_text = str_replace('ый','ого',mb_strtolower($text));
    }
    return $new_text;
}

// // seo title для атрибута цвет #seo #yoast

add_filter('wpseo_title', 'plnt_attribute_seo_title');

function plnt_attribute_seo_title($title) {
    if ( is_tax('pa_color') ) {
        $new_text = plnt_get_color_name_title($title);
        $title = "Горшки и кашпо ".$new_text." цвета – купить с доставкой в Москве в интернет-магазине – Plantis";
        if ( is_paged() ) {
            $pageNum = get_query_var('paged');

            $title .= " - Страница ".$pageNum;
            return $title;
        }
    }
    return $title;
}

// добавляем атрибуты schema.org
add_filter('woocommerce_product_loop_start', 'plnt_get_catalog_list_schema_data',10);

function plnt_get_catalog_list_schema_data ($html) {
    if ( ! (is_shop() || is_product_category() || is_product_tag() || is_tax()) || is_search() ) {
        return $html;
    }
    $html = preg_replace(
        '/<ul\s+class="products([^"]*)"/',
        '<ul itemscope itemtype="https://schema.org/OfferCatalog" class="products$1"',
        $html,
        1
    );

    $ctx = wc_get_catalog_context();

    $html .= '<meta itemprop="name" content="' . $ctx['title'] . '" />' . "\n"; 
    
    if ( $ctx['desc'] ) {
        $html .= '<meta itemprop="description" content="' . $ctx['desc'] . '" />' . "\n";
    } else {
        $html .= '<meta itemprop="description" content="' . $ctx['title'] . '" />' . "\n";
    }

    if($ctx['term']) {
      $thumbnail_id = get_term_meta( $ctx['term']->term_id, 'thumbnail_id', true );
      $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
      $html .= '<meta itemprop="image" content="' . $thumbnail_url . '" />' . "\n";
    } else {
      $html .= '<meta itemprop="image" content="' . get_template_directory_uri() . '/images/interior.webp" />' . "\n";
    }

    return $html;
};




/*--------------------------------------------------------------
#Catalog Functions
--------------------------------------------------------------*/
// функции для вывода товаров

// // количетсво товаров и кол-во колонок в каталоге

add_filter('loop_shop_columns', 'plnt_loop_columns');
if (!function_exists('plnt_loop_columns')) {
    function plnt_loop_columns() {
      // if(is_page('search-results')) {
      //   return 3;
      // } else {
      //   return 2;
      // }
      return 3;
    }
}

add_filter( 'loop_shop_per_page', 'plnt_products_per_page', 20 );
 
function plnt_products_per_page( $products ) {
 
	$products = 24;
	// по умолчанию wc_get_default_products_per_row() * wc_get_default_product_rows_per_page()
 
	return $products;
 
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
	// is_product_category($gorshki_cat_id) || 
	// term_is_ancestor_of( $gorshki_cat_id, get_queried_object_id(), 'product_cat' ) || 
	is_product_category($treez_cat_id) || 
	term_is_ancestor_of( $treez_cat_id, get_queried_object_id(), 'product_cat' )|| 
	is_product_category($plants_treez_cat_id) || 
	term_is_ancestor_of( $plants_treez_cat_id, get_queried_object_id(), 'product_cat' )||
	is_product_category($ukhod_cat_id) || 
	term_is_ancestor_of( $ukhod_cat_id, get_queried_object_id(), 'product_cat' ) ||
	is_product_category($lechuza_cat_id) || 
	term_is_ancestor_of( $lechuza_cat_id, get_queried_object_id(), 'product_cat' ) ||
	is_product_tag ($avtopoliv_tag_id)) { 		//где хотим скрыть товары не в наличии
		$meta_query[] = array(
			'key' => '_stock_status',
			'value' => 'outofstock',
			'compare' => '!='
			);
		return $meta_query;
	} else if (is_product_category($gorshki_cat_id) || 
		term_is_ancestor_of( $gorshki_cat_id, get_queried_object_id(), 'product_cat' ) 
	) {
		$meta_query = array(
			array(
				'key' => '_stock',
				'type'    => 'numeric',
				'value' => '0',
				'compare' => '>'
			)
		);
		return $meta_query;
	}	else {
		return $meta_query;
	}
}

// // скрываем Treez Plants из результатов поиска 

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
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

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

add_action('woocommerce_after_main_content', 'move_to_top_on_pagination');


// добавляем директивы ноиндекс, фоллоу для страниц пагинации, начиная со 2 #SEO
add_filter( 'wpseo_robots', 'filter_wpseo_robots', 10, 1 );

/* Yoast SEO -  add noindex, follow for paginated pages */
function filter_wpseo_robots( $robotsstr ) {
    if ( is_paged() || is_page( 'wishlist' )) {
        return 'noindex, follow';
    } 
    return $robotsstr;
}

// добавляем директивы ноиндекс, фоллоу для страниц с фильтами
add_filter( 'wpseo_robots', function( $robots ) {

    // Только фронтенд, только HTML-страницы
    // if ( is_admin() || is_feed() ) {
    //     return $robots;
    // }

    // Условие: есть параметр filters в URL
    if ( (isset( $_GET['filters'] ) && $_GET['filters'] !== '') 
        || (isset( $_GET['orderby'] ) && $_GET['orderby'] !== '') 
        || (isset( $_GET['interest'] ))) {
        return 'noindex, follow';
    }

    return $robots;
}, 20, 1 );


add_filter( 'wpseo_robots', function( $robots ) {

    // Условие: есть параметр в URL
    if ( (isset( $_GET['add-to-cart'] ) && $_GET['add-to-cart'] !== '')
        || (isset( $_GET['add_to_wishlist'] ) && $_GET['add_to_wishlist'] !== '')){
        return 'noindex, nofollow';
    }
    return $robots;
}, 30, 1 );

// functions.php или в mu-plugin
// add_filter( 'yith_wcwl_button_html', function( $html, $product_id, $atts ) {

//     // если rel уже есть — аккуратно дописываем токены
//     if ( preg_match( '/\srel=(["\'])(.*?)\1/i', $html, $m ) ) {
//         $rel = preg_split( '/\s+/', trim( $m[2] ) );
//         foreach ( [ 'nofollow', 'ugc', 'noopener' ] as $t ) {
//             if ( ! in_array( $t, $rel, true ) ) { $rel[] = $t; }
//         }
//         $new = ' rel="' . implode( ' ', $rel ) . '"';
//         $html = preg_replace( '/\srel=(["\'])(.*?)\1/i', $new, $html, 1 );
//     } else {
//         // если rel нет — добавляем
//         $html = preg_replace( '/<a\s+/i', '<a rel="nofollow ugc noopener" ', $html, 1 );
//     }

//     return $html;
// }, 10, 3 ); // ВАЖНО: accepted args = 3


// add_action( 'send_headers', function () {
//     if ( (isset($_GET['add_to_wishlist']) && $_GET['add_to_wishlist'] !== '')
//       || (isset($_GET['add-to-cart']) && $_GET['add-to-cart'] !== '') ) {
//         header( 'X-Robots-Tag: noindex, nofollow', true );
//     }
// }, 9 );


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
	// if (is_shop() || is_category() || is_tag()) {
		// Получаем URL первой страницы текущего архива
        $first_page_url = get_pagenum_link(1);
		
		// Разбираем URL на составляющие
		$parsedUrl = parse_url($first_page_url);

		// Строим новый URL без query-параметров
		$first_page_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

		$canonical = $first_page_url;

		return $canonical;
	// } 
	// else {
	// 	$current_page_url = get_page_link();
	// 	$canonical = $current_page_url;
	// 	return $canonical;
	// }
}

add_filter( 'wpseo_canonical', 'plnt_change_canonical');

function plnt_change_canonical_init() {
	add_filter( 'wpseo_canonical', 'plnt_change_canonical', 99999);
}

add_action('init', 'plnt_change_canonical_init');

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


// меняем rel для ссылки добавления товаров в корзину #seo

add_filter( 'woocommerce_loop_add_to_cart_link', 'plnt_change_rel', 10, 2); 
function plnt_change_rel( $html, $product ) {
 $html = str_replace( 'rel="nofollow"', 'rel="noindex, nofollow"', $html );
 return $html;
}



// add_filter( 'woocommerce_get_image_size_woocommerce_thumbnail', 'custom_thumbnail_size_for_homepage' );

// function custom_thumbnail_size_for_homepage( $size ) {
//     if ( is_front_page() ) {
//         return array(
//             'width'  => 300,
//             'height' => 300,
//             'crop'   => 1, // обрезка по центру
//         );
//     }

//     return $size; // оставляем как есть на других страницах
// }

//add_action('wp_head','plnt_debug');

function plnt_debug() {
  echo 'term '.(WC()->session->get('term_slug' )).'  ';
  echo 'term '.(WC()->session->get('term_type' )).'  ';
}

