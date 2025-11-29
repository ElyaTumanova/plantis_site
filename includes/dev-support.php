<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// FOR DEV

//HOOKS

/**
 * Показывать имена WooCommerce-хуков на страницах каталога.
 * Вешаемся на спец. хук 'all' и печатаем маркер для каждого хука WooCommerce.
 */

add_action( 'init', function () {
    // Только фронт, не админка
    if ( is_admin() ) {
        return;
    }

    // Подключаем только если WooCommerce активен
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Вешаем глобальный перехватчик всех хуков
    add_action( 'all', 'plnt_wc_catalog_hooks_debug_marker', 9999 );
} );

/**
 * Вывод маркеров хуков.
 */
function plnt_wc_catalog_hooks_debug_marker() {
    // Текущий хук
    $hook = current_filter();

    // Не лезем в AJAX, REST и админку
    if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }

    // Ограничиваемся только страницами каталога WooCommerce
    if ( ! ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) ) {
        return;
    }

    // Ограничиваемся только хуками WooCommerce (чтобы не засорять вывод)
    if ( strpos( $hook, 'woocommerce_' ) !== 0 ) {
        return;
    }

    // На всякий случай не зацикливаемся
    if ( $hook === 'all' ) {
        return;
    }

    // Печатаем маленький видимый маркер хука
    echo '<span class="wc-hook-marker" data-hook="' . esc_attr( $hook ) . '">'
         . esc_html( $hook ) .
         '</span>';
}

//add_action( 'wp_footer', 'plnt_echo_smth' );


function plnt_echo_smth() {
  $result = plnt_get_search_query('lololo');
  echo ('<pre>');
  print_r($result['query']);
  echo ('</pre>');

}


function echo_hi() {
  if (is_page('gift-card')) {
    echo ('hihi');
  }
}

function plnt_check_page() {
	// global $gorshki_cat_id;
	// if ( term_is_ancestor_of( $gorshki_cat_id, get_queried_object_id(), 'product_cat' ) ) {
	// 	echo 'подкатегория';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	// echo '<pre>';
	// print_r( get_queried_object_id() );
	// print_r( $gorshki_cat_id );
	// echo '</pre>';
	if ( is_page( 'wishlist' ) ) {
		echo 'Это wishlist!';
	}
	else {
		echo 'Это какая-то другая страница.';
	}

  echo yith_wcwl_count_all_products();
	// if(is_page_template('themes/plantis_site/woocommerce/loop/no-products-found.php')) {
	// 	echo 'Это то что нужно';
	// }
	// else {
	// 	echo 'Это какая-то другая страница.';
	// }
	

}

function plnt_get_cats_data() {
	global $plants_treez_cat_id;
	global $plants_cat_id;
	global $gorshki_cat_id;
	global $ukhod_cat_id;
	global $treez_cat_id;
	global $treez_poliv_cat_id;
	global $lechuza_cat_id;
	global $misc_cat_id;
	global $peresadka_cat_id;

	$term = get_term( $plants_cat_id, 'product_cat');
	$term_name = $term->name; // получаем название конкретной категории товаров (в данном случае)
	//print_r($term_name);

	$terms = get_terms( [
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
	] );

	//print_r($terms);
	$my_terms_array = [];

	// foreach ($terms as $key => $term) {
	// 	//print_r($term);
	// 	//print_r($term->term_id);
	// 	echo ("['name' => '");
	// 	print_r($term->name);
	// 	echo ("','slug' => '");
	// 	print_r($term->slug);
	// 	echo ("'],");
	// 	//echo (';');
	// 	//print_r($term->description);
	// 	//echo (';');
	// 	//echo ('<br>');
	// 	//array_push($my_terms_array, ['name'=>$term->name, 'slug'=>$term->slug]);
	// }

	//print_r($my_terms_array);

	$cats_array = array();

	print_r ($cats_array[0]['name']);
}

//add_action( 'wp_footer', 'plnt_check_page' );

//add_action( 'wp_footer', 'plnt_get_cats_data' );


function plnt_get_prods_data() {

  $args = array(
      'post_type' => 'product',
      'ignore_sticky_posts' => 1,
      'no_found_rows' => 1,
      'posts_per_page' => -1,
      'orderby' => 'rand',
      'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => 'komnatnye-rasteniya'
                )
            )
  );

  $products = new WP_Query( $args );

	$count = 0;
	echo ('<br>');
	echo(count($products->posts));
	echo ('<br>');
  //print_r($products->posts);
		foreach ($products->posts as $key => $term) {
      echo ("['name' => '");
			print_r($term->post_title);
			echo ("', 'slug'=>'");
      echo($term->post_name);
      echo("'],");
 			echo ('<br>');
			// $slug = 'not found';
			// foreach ($cats_array as $key => $cat) {
			// 	if($term->name == $cat['name']) {
			// 		++$count;
			// 		$slug = $cat['slug'];
			// 	}
			// }
			// echo($count.' ');
			// echo ($term->name);
			// echo (';  ');
			// echo ($slug);
			// echo ('<br>');
			
			// $result = wp_update_term( $term->term_id, 'product_cat', [
			// 	'slug' => $slug,
			// ] );

			// // check the result
			// if( is_wp_error( $result ) ){

			// 	echo $result->get_error_message();
			// }
			// else {

			// 	echo 'Term was successfully updated.';
			// }
	}

}

//add_action( 'wp_footer', 'plnt_get_prods_data' );


// Вывод всех зарегистрированных размеров изображений
function show_image_sizes() {
    global $_wp_additional_image_sizes;
    
    echo '<pre>';
    echo 'Стандартные размеры WordPress:' . "\n";
    $default_sizes = get_intermediate_image_sizes();
    
    foreach ($default_sizes as $size) {
        echo "Размер: " . $size . "\n";
        if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
            $width = get_option($size . '_size_w');
            $height = get_option($size . '_size_h');
            $crop = get_option($size . '_size_crop');
            echo "  Ширина: $width\n";
            echo "  Высота: $height\n";
            echo "  Обрезка: " . ($crop ? 'Да' : 'Нет') . "\n";
        } elseif (isset($_wp_additional_image_sizes[$size])) {
            $sizes = $_wp_additional_image_sizes[$size];
            echo "  Ширина: " . $sizes['width'] . "\n";
            echo "  Высота: " . $sizes['height'] . "\n";
            echo "  Обрезка: " . ($sizes['crop'] ? 'Да' : 'Нет') . "\n";
        }
        echo "\n";
    }
    echo '</pre>';
}

// Вызвать функцию - можно добавить в хук, например:
//add_action('wp_head', 'show_image_sizes');
//show_image_sizes();


// add_action('wp_ajax_nopriv_ping', 'ajax_ping');
// add_action('wp_ajax_ping', 'ajax_ping');
// function ajax_ping(){
//   wp_send_json_success('ok'); // без лишней логики
// }