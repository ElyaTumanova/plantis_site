<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
#Init
--------------------------------------------------------------*/
// add_action('woocommerce_before_shop_loop','plnt_set_constants_catalog',5);

// function plnt_set_constants_catalog() {

//     $GLOBALS['plnt_catalog_type'] = false;
//     $GLOBALS['plnt_catalog_context'] = array(
//         'title' => '',
//         'desc'  => '',
//         'term'  => false,
//     );

//     if ( function_exists( 'plnt_define_catalog_type' ) ) {
//         $GLOBALS['plnt_catalog_type'] = plnt_define_catalog_type();
//     }

//     if ( function_exists( 'wc_get_catalog_context' ) ) {
//         $GLOBALS['plnt_catalog_context'] = wc_get_catalog_context();
//     }
// }

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

		$page_title .= ' <span>— Страница ' . esc_html( $pageNum ) . '</span>';
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
//add_filter('woocommerce_product_loop_start', 'plnt_get_catalog_list_schema_data',10);

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

    $ctx = plnt_get_catalog_context();

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
  global $avtopoliv_tag_id;

  $catalog_type = plnt_get_catalog_type();

  if (
    is_shop() ||
    in_array( $catalog_type, array( 'treez', 'plants_treez', 'ukhod', 'lechuza' ), true ) ||
    is_product_tag( $avtopoliv_tag_id )
  ) {
    $meta_query[] = array(
      'key'     => '_stock_status',
      'value'   => 'outofstock',
      'compare' => '!='
    );

    return $meta_query;

  } elseif ( $catalog_type === 'gorshki' ) {

    $meta_query = array(
      array(
        'key'     => '_stock',
        'type'    => 'numeric',
        'value'   => '0',
        'compare' => '>'
      )
    );

    return $meta_query;
  }

  return $meta_query;
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

function plnt_woocommerce_pagination_args_filter( $array ) {
	$prev_icon = plnt_icon('chevron-left');
  
	$next_icon = plnt_icon('chevron-right');

	$array = array(
		'prev_text' => is_rtl() ? $next_icon : $prev_icon,
		'next_text' => is_rtl() ? $prev_icon : $next_icon,
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




