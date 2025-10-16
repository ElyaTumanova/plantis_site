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
/**
 * Подробные замеры add_to_cart с выводом в футере.
 * Вставить в functions.php дочерней темы или в отдельный плагин.
 */

// ===== Хелперы для чекпоинтов ===============================================
function wcac_mark( $name ) {
    $t = microtime( true );
    if ( ! isset( $GLOBALS['wc_add_cart_cp'] ) || ! is_array( $GLOBALS['wc_add_cart_cp'] ) ) {
        $GLOBALS['wc_add_cart_cp'] = [];
    }
    $GLOBALS['wc_add_cart_cp'][] = [ 'name' => (string) $name, 't' => $t ];
}

function wcac_render_table() {
    $cps = isset( $GLOBALS['wc_add_cart_cp'] ) ? $GLOBALS['wc_add_cart_cp'] : [];
    if ( empty( $cps ) ) {
        return '<div id="wc-ajax-metrics">Нет данных замеров.</div>';
    }

    // Считаем шаговые и накопительные миллисекунды
    $rows = [];
    $t0 = $cps[0]['t'];
    $prev = $t0;
    foreach ( $cps as $i => $cp ) {
        $step_ms = (int) round( ( $cp['t'] - $prev ) * 1000 );
        $cum_ms  = (int) round( ( $cp['t'] - $t0 ) * 1000 );
        $rows[]  = sprintf(
            '<tr><td>%d</td><td>%s</td><td style="text-align:right">%d&nbsp;мс</td><td style="text-align:right">%d&nbsp;мс</td></tr>',
            $i, esc_html( $cp['name'] ), $step_ms, $cum_ms
        );
        $prev = $cp['t'];
    }

    $total_ms = (int) round( ( end( $cps )['t'] - $t0 ) * 1000 );

    // Компактный фиксированный виджет в «футере» (fixed снизу справа)
    $html = '
    <div id="wc-ajax-metrics" style="position:fixed;right:12px;bottom:12px;z-index:9999;max-width:420px;background:#fff;border:1px solid rgba(0,0,0,.1);box-shadow:0 6px 24px rgba(0,0,0,.12);border-radius:10px;font:13px/1.35 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif">
      <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-bottom:1px solid rgba(0,0,0,.06)">
        <strong>WC add_to_cart — таймлайны</strong>
        <span style="margin-left:auto;opacity:.7">Σ '.$total_ms.' мс</span>
      </div>
      <div style="max-height:260px;overflow:auto">
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#fafafa">
              <th style="text-align:left;padding:6px 10px;width:38px">#</th>
              <th style="text-align:left;padding:6px 10px">Шаг</th>
              <th style="text-align:right;padding:6px 10px;width:90px">Δ, мс</th>
              <th style="text-align:right;padding:6px 10px;width:90px">Σ, мс</th>
            </tr>
          </thead>
          <tbody>' . implode( '', $rows ) . '</tbody>
        </table>
      </div>
      <div style="padding:8px 10px;color:#666;border-top:1px solid rgba(0,0,0,.06)">
        Обновляется после успешного add_to_cart
      </div>
    </div>';

    return $html;
}

// ===== 1) Плейсхолдер в футере страницы =====================================
add_action( 'wp_footer', function () {
    if ( is_admin() ) return;
    echo '<div id="wc-ajax-metrics" style="position:fixed;right:12px;bottom:12px;z-index:9999;padding:8px 10px;border-radius:10px;background:#f2f2f2;font:13px/1.35 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;">Ожидание первого замера…</div>';
}, 99 );

// ===== 2) Чекпоинты по ходу обработки запроса ===============================

// Старт как можно раньше в конкретном AJAX-запросе add_to_cart
add_action( 'init', function () {
    if ( wp_doing_ajax() && isset( $_GET['wc-ajax'] ) && $_GET['wc-ajax'] === 'add_to_cart' ) {
        wcac_mark( 'init (start)' );
    }
}, 0 );

// До валидации (ранний приоритет)
add_filter( 'woocommerce_add_to_cart_validation', function( $passed, $product_id, $qty, $variation_id, $variations, $cart_item_data ){
    wcac_mark( 'before validation' );
    return $passed;
}, 1, 6 );

// После валидации (поздний приоритет)
add_filter( 'woocommerce_add_to_cart_validation', function( $passed, $product_id, $qty, $variation_id, $variations, $cart_item_data ){
    wcac_mark( 'after validation' );
    return $passed;
}, 999, 6 );

// После фактического добавления в корзину
add_action( 'woocommerce_add_to_cart', function( $cart_item_key, $product_id, $quantity, $variation_id, $variations, $cart_item_data ){
    wcac_mark( 'after WC_Cart::add_to_cart' );
}, 10, 6 );

// Момент ajax-хука WooCommerce (перед формированием фрагментов)
add_action( 'woocommerce_ajax_added_to_cart', function( $product_id ){
    wcac_mark( 'woocommerce_ajax_added_to_cart' );
}, 10, 1 );

// Перед сборкой фрагментов (низкий приоритет)
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ){
    wcac_mark( 'before fragments' );
    return $fragments;
}, 1 );

// После сборки фрагментов — формируем наш HTML и подменяем футер
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ){
    wcac_mark( 'after fragments' );

    // Сюда же удобно добавить «итоговый» чекпоинт
    wcac_mark( 'prepare footer html' );

    $fragments['#wc-ajax-metrics'] = wcac_render_table();
    return $fragments;
}, 999 );

// ===== Примечания ============================================================
// • При ошибке валидации/добавления фрагменты не вернутся — футер не обновится.
//   Для fail-кейсов замеряйте «конец-в-конец» на клиенте через события
//   `adding_to_cart` / `ajaxError`.
// • Если хотите видеть ещё шаги (например, перерасчёт тоталов/доставки),
//   можно дополнительно отметить:
//   - add_action( 'woocommerce_before_calculate_totals', fn() => wcac_mark('before calculate_totals'), 1 );
//   - add_action( 'woocommerce_after_calculate_totals', fn() => wcac_mark('after calculate_totals'), 999 );
