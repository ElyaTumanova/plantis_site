<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Catalog */

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

