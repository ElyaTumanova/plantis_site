<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// FOR DEV

//add_action( 'wp_footer', 'plnt_echo_smth' );


function plnt_echo_smth() {
    if(is_page('test-result')) {
      echo('<pre>');
        echo ('this is test res');
        $plant_types = require get_theme_file_path('assets/data/plant-types.php');
        $plants_by_slug = [];
        foreach ($plant_types as $it) { $plants_by_slug[$it['slug']] = $it; };
        print_r($plants_by_slug);
        $gen = get_query_var('gen');
        $plant = get_query_var('plant');
        echo ($gen);
        echo ($plant);

        if ($gen && $plant && isset($plants_by_slug[$plant]) && isset($plants_by_slug[$plant]['image'][$gen])) {
          $img = $plants_by_slug[$plant]['image'][$gen];
        } else {
          $img = get_template_directory_uri().'/images/test/test_cover.webp';
        }
        echo($img);
        echo('<pre>');
    }
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



// ========== Плейсхолдер в футере (безопасный) ==========
add_action( 'wp_footer', function () {
    if ( is_admin() ) { return; }
    echo '<div id="wc-ajax-metrics" style="position:fixed;right:12px;bottom:12px;z-index:9999;padding:8px 10px;border-radius:10px;background:#f2f2f2;font:13px/1.35 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;">Ожидание замера…</div>';
}, 99 );

// ========== Вся логика замеров целиком внутри init и ТОЛЬКО для данного AJAX ==========
add_action( 'init', function () {

    // Гарантируем, что хук активируется лишь для wc-ajax=add_to_cart
    if ( ! function_exists('wp_doing_ajax') || ! wp_doing_ajax() ) { return; }
    if ( empty($_GET['wc-ajax']) || $_GET['wc-ajax'] !== 'add_to_cart' ) { return; }

    // Стартовая метка
    $t0 = microtime(true);

    // Хранилище чекпоинтов в замыкании
    $cps = [];
    $mark = function( $label ) use ( &$cps ) {
        $cps[] = [ 'name' => (string) $label, 't' => microtime(true) ];
        // Пишем в debug.log на всякий случай (не критично, если WP_DEBUG_LOG=off)
        if ( function_exists('error_log') ) {
            @error_log('[wc-add_to_cart] ' . $label);
        }
    };

    // Поставим первую метку
    $mark('init (start)');

    // До валидации
    add_filter( 'woocommerce_add_to_cart_validation', function( $passed, $product_id, $qty, $variation_id, $variations, $cart_item_data ) use ( $mark ) {
        $mark('before validation');
        return $passed;
    }, 1, 6 );

    // После валидации
    add_filter( 'woocommerce_add_to_cart_validation', function( $passed, $product_id, $qty, $variation_id, $variations, $cart_item_data ) use ( $mark ) {
        $mark('after validation');
        return $passed;
    }, 999, 6 );

    // После фактического добавления в корзину
    add_action( 'woocommerce_add_to_cart', function( $cart_item_key, $product_id, $quantity, $variation_id, $variations, $cart_item_data ) use ( $mark ) {
        $mark('after WC_Cart::add_to_cart');
    }, 10, 6 );

    // Хук AJAX Woo — перед генерацией фрагментов
    add_action( 'woocommerce_ajax_added_to_cart', function( $product_id ) use ( $mark ) {
        $mark('woocommerce_ajax_added_to_cart');
    }, 10, 1 );

    // Перед фрагментами
    add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) use ( $mark ) {
        $mark('before fragments');
        return $fragments;
    }, 1 );

    // Финальный фрагмент, который попадёт в футер
    add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) use ( &$cps, $t0, $mark ) {

        // Последняя метка и подготовка HTML
        $mark('after fragments');

        // Если по какой-то причине меток нет — просто вернём фрагменты как есть
        if ( empty($cps) ) { return $fragments; }

        // Построим таблицу
        $rows = '';
        $prev = $t0;
        $i    = 0;
        foreach ( $cps as $cp ) {
            $step_ms = (int) round( ( $cp['t'] - $prev ) * 1000 );
            $cum_ms  = (int) round( ( $cp['t'] - $t0 ) * 1000 );
            $name    = esc_html( $cp['name'] );
            $rows   .= "<tr><td>{$i}</td><td>{$name}</td><td style='text-align:right'>{$step_ms}&nbsp;мс</td><td style='text-align:right'>{$cum_ms}&nbsp;мс</td></tr>";
            $prev    = $cp['t'];
            $i++;
        }
        $total_ms = (int) round( ( end($cps)['t'] - $t0 ) * 1000 );

        $html = "
        <div id='wc-ajax-metrics' style='position:fixed;right:12px;bottom:12px;z-index:9999;max-width:420px;background:#fff;border:1px solid rgba(0,0,0,.1);box-shadow:0 6px 24px rgba(0,0,0,.12);border-radius:10px;font:13px/1.35 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif'>
          <div style='display:flex;align-items:center;gap:10px;padding:10px 12px;border-bottom:1px solid rgba(0,0,0,.06)'>
            <strong>WC add_to_cart — таймлайны</strong>
            <span style='margin-left:auto;opacity:.7'>Σ {$total_ms} мс</span>
          </div>
          <div style='max-height:260px;overflow:auto'>
            <table style='width:100%;border-collapse:collapse'>
              <thead>
                <tr style='background:#fafafa'>
                  <th style='text-align:left;padding:6px 10px;width:38px'>#</th>
                  <th style='text-align:left;padding:6px 10px'>Шаг</th>
                  <th style='text-align:right;padding:6px 10px;width:90px'>Δ, мс</th>
                  <th style='text-align:right;padding:6px 10px;width:90px'>Σ, мс</th>
                </tr>
              </thead>
              <tbody>{$rows}</tbody>
            </table>
          </div>
          <div style='padding:8px 10px;color:#666;border-top:1px solid rgba(0,0,0,.06)'>
            Обновляется после успешного add_to_cart
          </div>
        </div>";

        // Подменяем содержимое футерного блока по селектору
        $fragments['#wc-ajax-metrics'] = $html;
        return $fragments;
    }, 999 );
}, 0 );
