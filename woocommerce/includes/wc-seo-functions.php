<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('wp_head', 'add_seo_tags');

function add_seo_tags() {
};

add_filter( 'document_title', 'wp_kama_document_title_filter' );

function wp_kama_document_title_filter( $title ){
    print_r($title);
    if (is_product()) {
        $product = wc_get_product( get_the_ID() );
        echo carbon_get_post_meta( get_the_ID(), 'seo_title_prod' );
        $seo_title = carbon_get_post_meta( get_the_ID(), 'seo_title_prod' );
        echo $seo_title;
        echo get_the_ID();
        if ($seo_title) {
            $title = $seo_title;
        } else {
            $title = $product->get_title().' – купить в Москве с доставкой – Plantis';
        }
    } else {
        $title = 'Интернет магазин комнатных растений с доставкой - Plantis';
    }

	return $title;
}

// ПРОИЗВОЛЬНЫЕ ПОЛЯ НА СТРАНИЦАХ ТАКСОНОМИИ

// add_action( 'product-category_edit_form_fields', 'true_edit_term_fields', 10, 2 );
 
// function true_edit_term_fields( $term, $taxonomy ) {
 
// 	// сначала получаем значения этих полей
// 	// заголовок
// 	$seo_title = get_term_meta( $term->term_id, 'seo_title', true );
 
// 	echo '<tr class="form-field">
// 	<th>
// 		<label for="seo_title">SEO-заголовок</label>
// 	</th>
// 	<td>
// 		<input name="seo_title" id="seo_title" type="text" value="' . esc_attr( $seo_title ) .'" />
// 	</td>
// 	</tr>';
 
// }

// add_action( 'created_product-category', 'true_save_term_fields' );
// add_action( 'edited_product-category', 'true_save_term_fields' );
 
// function true_save_term_fields( $term_id ) {
 
// 	if( isset( $_POST[ 'seo_title' ] ) ) {
// 		update_term_meta( $term_id, 'seo_title', sanitize_text_field( $_POST[ 'seo_title' ] ) );
// 	} else {
// 		delete_term_meta( $term_id, 'seo_title' );
// 	}
 
// }


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
