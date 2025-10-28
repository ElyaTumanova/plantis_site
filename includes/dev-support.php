<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// FOR DEV

//add_action( 'wp_footer', 'plnt_echo_smth' );


function plnt_echo_smth() {
    global $plants_treez_cat_id;
    global $peresadka_cat_id;
    global $plants_cat_id;
    $product_sku_id_1 = wc_get_product_id_by_sku( 'lalal' );
    $product_sku_id_2 = wc_get_product_id_by_sku( 'P00623' );
    $product_sku_id = $product_sku_id_2 ?: $product_sku_id_1 ?: 0;
    echo('<pre>');
    print_r($product_sku_id);
    echo('</pre>');
}


function echo_hi() {
	echo ('hihi');
}

function plnt_dev_functions() {

	global $plants_treez_cat_id;
	global $plants_cat_id;
	global $gorshki_cat_id;
	global $ukhod_cat_id;
	global $treez_cat_id;
	global $treez_poliv_cat_id;
	global $lechuza_cat_id;
	global $misc_cat_id;
	global $peresadka_cat_id;

    $all_sizes = get_intermediate_image_sizes();
    print_r( $all_sizes );


    // $term = get_term( 'slug', $cat_slug, 'product_cat' );
    // $term_id = $term->term_id;
    $args = array( 'taxonomy' => 'product_cat', 'parent' => $plants_treez_cat_id );  
    $terms = get_terms( $args ); 
    //print_r($terms);
    // $category_thumbnail = get_term_meta(137, 'thumbnail_id', true);
    // $image = wp_get_attachment_url($category_thumbnail);
    // echo($category_thumbnail);
    // echo($image);
	// $cats_for_check = [$plants_cat_id, $gorshki_cat_id, $ukhod_cat_id,$treez_cat_id, $treez_poliv_cat_id, $plants_treez_cat_id, $lechuza_cat_id, $peresadka_cat_id, $misc_cat_id];
	// $cats_for_include = [];
	// $cats_for_include_clean = [];
	// foreach($cats_for_check as $item){
	// 	$args = array(
	// 		'post_type'      => 'product',
	// 		'posts_per_page' => -1,
	// 		'post_status'    => 'publish',
	// 		'meta_query' => array( 
	// 			array(
	// 				'key' => '_stock',
	// 				'type'    => 'numeric',
	// 				'value' => '0',
	// 				'compare' => '>'
	// 			)
	// 		),
	// 		'tax_query' => array(
	// 			array(
	// 				'taxonomy' => 'product_cat',
	// 				'field' => 'id',
	// 				'terms' => [$item],
	// 				'operator' => 'IN',
	// 				'include_children' => 1,
	// 			)
	// 		)
	// 	);
	// 	$query = new WP_Query;
	// 	$checkproducts = $query->query($args);

	// 	$checkproductscount = count($checkproducts);
	// 	// echo $item.' '.$checkproductscount;
	// 	// echo '<br>';
	// 	if($checkproductscount != 0) {
	// 		//print_r($checkproducts);
	// 		foreach ($checkproducts as $item) {
	// 			// print_r($item);
	// 			// echo '<br>';
	// 			$product = wc_get_product($item);
	// 			$prod_cats = $product->get_category_ids();
	// 			foreach ($prod_cats as $cat) {
	// 				array_push($cats_for_include, $cat);
	// 			}
	// 			// echo ('cat ids ');
	// 			// print_r($product->get_category_ids());
	// 			// echo '<br>';
	// 		}
	// 	};
	// }
	// echo 'cats_for_include ';
	// $cats_for_include_clean = array_unique($cats_for_include);
	// // print_r($cats_for_include);
	// // echo '<br>';
	// print_r($cats_for_include_clean);
	// echo '<br>';
	// echo 'cats_for_exclude ';
	// print_r($cats_for_exclude); 
	

	// $args_cats=array(
	// 	'taxonomy'   => 'product_cat',
	// 	'hide_empty' => true,
	// 	'include' => $cats_for_include_clean,
	// );

	// $terms=get_terms($args_cats);

	// foreach($terms as $item){
	// 	echo $item->name;
	// 	echo '<br>';
	// }

	
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


/**
 * Поиск WooCommerce:
 * - Растения (ветка $plants_cat_id) показывать всегда (в т.ч. outofstock)
 * - Остальные категории — только товары в наличии
 *
 * Без правок SQL; снимаем штатные фильтры WC на outofstock для поиска,
 * затем фильтруем результаты в PHP и чиним пагинацию.
 */

/**
 * 0) Помощник: получить все ID терминов ветки "Растения"
 */
function my_get_plants_branch_ids(): array {
    static $ids = null;
    if ( $ids !== null ) return $ids;

    // возьмём ID из глобали — как у вас
    global $plants_cat_id;
    $root_id = absint( $plants_cat_id );

    if ( ! $root_id ) {
        $ids = [];
        return $ids;
    }

    $ids = array_map( 'absint', array_unique( array_merge(
        [ $root_id ],
        (array) get_term_children( $root_id, 'product_cat' )
    ) ) );

    return $ids;
}

/**
 * 1) На поиске убираем из meta_query любые попытки скрыть outofstock.
 */
add_filter( 'woocommerce_product_query_meta_query', function( $meta_query, $query ) {
    if ( is_admin() || ! is_search() ) {
        return $meta_query;
    }

    if ( ! is_array( $meta_query ) ) return $meta_query;

    // рекурсивно убираем условия по _stock_status
    $clean = function( $clause ) use ( &$clean ) {
        if ( ! is_array( $clause ) ) return $clause;

        if ( isset( $clause['key'] ) && $clause['key'] === '_stock_status' ) {
            return null; // выкинуть
        }

        $out = [];
        foreach ( $clause as $k => $v ) {
            $v2 = is_array( $v ) ? $clean( $v ) : $v;
            if ( $v2 !== null && $v2 !== [] ) {
                $out[ $k ] = $v2;
            }
        }
        return $out;
    };

    $meta_query = $clean( $meta_query );

    // подчистим пустые элементы верхнего уровня
    if ( is_array( $meta_query ) ) {
        $meta_query = array_values( array_filter( $meta_query, function( $v ) {
            return $v !== null && $v !== [];
        } ) );
    }

    return $meta_query;
}, 10, 2 );

/**
 * 2) На поиске убираем из tax_query скрытие outofstock (таксономия product_visibility).
 */
add_filter( 'woocommerce_product_query_tax_query', function( $tax_query, $query ) {
    if ( is_admin() || ! is_search() ) {
        return $tax_query;
    }

    if ( ! is_array( $tax_query ) ) return $tax_query;

    // узнаём ID терма outofstock
    if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
        $vis = wc_get_product_visibility_term_ids();
        $outofstock_term_id = isset( $vis['outofstock'] ) ? absint( $vis['outofstock'] ) : 0;
    } else {
        $outofstock_term_id = 0;
    }

    // выкидываем любую часть, которая исключает outofstock
    $filtered = [];
    foreach ( $tax_query as $clause ) {
        if ( ! is_array( $clause ) ) {
            $filtered[] = $clause;
            continue;
        }
        $is_visibility_out = (
            isset( $clause['taxonomy'], $clause['operator'], $clause['terms'] )
            && $clause['taxonomy'] === 'product_visibility'
            && in_array( $clause['operator'], [ 'NOT IN', 'NOT EXISTS' ], true )
        );

        if ( $is_visibility_out ) {
            // если явно исключают outofstock — пропускаем эту часть
            $terms = array_map( 'absint', (array) $clause['terms'] );
            if ( $outofstock_term_id && in_array( $outofstock_term_id, $terms, true ) ) {
                continue; // выкинули
            }
        }
        $filtered[] = $clause;
    }

    return $filtered;
}, 10, 2 );

/**
 * 3) Собственно наша логика выборки для результатов поиска.
 *    - Растения (ветка) — оставить всегда
 *    - Остальные — только если _stock_status != outofstock
 *    Плюс — правим пагинацию.
 */
add_filter( 'the_posts', function( $posts, $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
        return $posts;
    }

    // на всякий случай: ищем именно продукты
    $pt = $query->get( 'post_type' );
    if ( empty( $pt ) ) {
        $query->set( 'post_type', [ 'product' ] );
    } elseif ( is_string( $pt ) && $pt !== 'product' ) {
        return $posts;
    } elseif ( is_array( $pt ) && ! in_array( 'product', $pt, true ) ) {
        return $posts;
    }

    $plant_branch = my_get_plants_branch_ids();

    $filtered = [];
    foreach ( $posts as $p ) {
        if ( $p->post_type !== 'product' ) {
            $filtered[] = $p;
            continue;
        }

        // Если товар принадлежит ветке "Растения" — оставляем безусловно
        $is_plant = false;
        if ( $plant_branch ) {
            $term_ids = wp_get_post_terms( $p->ID, 'product_cat', [ 'fields' => 'ids' ] );
            if ( is_array( $term_ids ) && array_intersect( $term_ids, $plant_branch ) ) {
                $is_plant = true;
            }
        }

        if ( $is_plant ) {
            $filtered[] = $p;
            continue;
        }

        // Иначе — оставляем только если не outofstock
        $status = get_post_meta( $p->ID, '_stock_status', true );
        if ( $status !== 'outofstock' ) {
            $filtered[] = $p;
        }
    }

    // Сохраним число для фикса пагинации
    $query->set( 'my_filtered_count', count( $filtered ) );

    return $filtered;
}, 20, 2 );

/**
 * 4) Чиним пагинацию (пересчитываем found_posts по нашему фильтру).
 */
add_filter( 'found_posts', function( $found, $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
        return $found;
    }
    $cnt = (int) $query->get( 'my_filtered_count' );
    if ( $cnt > 0 ) {
        return $cnt;
    }
    // если после фильтрации ничего не осталось — возвращаем 0
    if ( $cnt === 0 ) {
        return 0;
    }
    return $found;
}, 20, 2 );

/**
 * 5) Подстрахуемся: если кто-то ещё не задал post_type — ограничим поиск продуктами.
 */
add_action( 'pre_get_posts', function( $q ) {
    if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() ) return;
    $pt = $q->get( 'post_type' );
    if ( empty( $pt ) ) {
        $q->set( 'post_type', [ 'product' ] );
    }
}, 20 );
