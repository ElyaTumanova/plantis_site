<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// добавляем директивы ноиндекс, фоллоу для страниц пагинации, начиная со 2 #SEO
add_filter( 'wpseo_robots', 'filter_wpseo_robots' );

/* Yoast SEO -  add noindex, follow for paginated pages */
function filter_wpseo_robots( $robotsstr ) {
    if ( is_paged() ) {
        return 'noindex, follow';
    }
 
    return $robotsstr;
}

// add_filter( 'wpseo_canonical', 'yoast_remove_canonical_items' ); // если нужно убрать canonical на отдельных страницах

// function yoast_remove_canonical_items( $canonical ) {
//   if ( is_paged() ) {
//     return false;
//   }
//   /* Use a second if statement here when needed */
// 	return $canonical; /* Do not remove this line */
// }

// add_filter( 'wpseo_canonical', '__return_false', 10 );

// function remove_canonical() {
    
//     add_filter( 'wpseo_canonical', '__return_false', 10 );

// }
// add_action( 'wpseo_head', 'remove_canonical', 4);

// add_filter( 'wpseo_canonical', 'add_custom_canonical_tags', 20 );

// изменяем canonical для страниц пагинации #SEO

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
