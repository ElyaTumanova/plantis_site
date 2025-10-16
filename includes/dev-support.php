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

<?php
// РАННИЙ маркер
add_filter('woocommerce_add_to_cart_fragments', function($fragments){
    if (isset($GLOBALS['wc_a2c_mark'])) ($GLOBALS['wc_a2c_mark'])('before fragments');
    return $fragments;
}, 1);

// БЕЗОПАСНАЯ отправка заголовков (ограничения длины + try/catch)
add_filter('woocommerce_add_to_cart_fragments', function($fragments){
    try {
        if (empty($GLOBALS['wc_a2c_t0']) || !isset($GLOBALS['wc_a2c_cps']) || headers_sent()) {
            return $fragments;
        }

        ($GLOBALS['wc_a2c_mark'])('after fragments');

        $t0  = (float) $GLOBALS['wc_a2c_t0'];
        $cps = is_array($GLOBALS['wc_a2c_cps']) ? $GLOBALS['wc_a2c_cps'] : [];

        // safety: если пусто — не ставим заголовки
        if (!$cps) return $fragments;

        // Нормализуем метки и считаем дельты
        $metrics = [];
        $prev = $t0;
        foreach ($cps as $i => $cp) {
            $label = isset($cp['name']) ? (string)$cp['name'] : "cp{$i}";
            // короткое имя: только a-z0-9_- и макс 16 символов
            $short = strtolower(preg_replace('~[^a-z0-9_-]+~i', '-', $label));
            if ($short === '' ) $short = "cp{$i}";
            if (strlen($short) > 16) $short = substr($short, 0, 16);

            $t = isset($cp['t']) ? (float)$cp['t'] : microtime(true);
            $dur = (int) round( ($t - $prev) * 1000 );
            if ($dur < 0) { $dur = 0; }

            $metrics[] = "{$short};dur={$dur}";
            $prev = $t;
        }

        // total
        $lastT = isset($cps[count($cps)-1]['t']) ? (float)$cps[count($cps)-1]['t'] : microtime(true);
        $total_ms = (int) round( ($lastT - $t0) * 1000 );
        array_push($metrics, "total;dur={$total_ms}");

        // Ограничим количество метрик и длину заголовка
        $MAX_METRICS = 12;               // максимум 12 шагов + total
        $metrics = array_slice($metrics, 0, $MAX_METRICS - 1);
        $metrics[] = "total;dur={$total_ms}";

        $value = implode(', ', $metrics);

        // Ограничим длину заголовка ~1800 символами (запасы под сервер)
        $MAX_LEN = 1800;
        if (strlen($value) > $MAX_LEN) {
            // обрежем до ближайшей запятой и добавим только total
            $value = substr($value, 0, $MAX_LEN);
            $value = rtrim(substr($value, 0, strrpos($value, ',')));
            $value .= ", total;dur={$total_ms}";
        }

        // Отправляем — ОДИН заголовок Server-Timing + X-Response-Time
        if (!headers_sent()) {
            @header('Server-Timing: ' . $value, true);
            @header('X-Response-Time: ' . $total_ms . 'ms', true);
        }
    } catch (Throwable $e) {
        // не роняем ответ
        error_log('[wc-add_to_cart headers] ' . $e->getMessage());
        return $fragments;
    }

    return $fragments;
}, 999);

