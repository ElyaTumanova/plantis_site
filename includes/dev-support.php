<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// FOR DEV

//add_action( 'wp_footer', 'plnt_echo_smth' );


function plnt_echo_smth() {
    $isHolidayCourierTariff = carbon_get_theme_option('is_holiday_courier_tariff') == '1';
    $isSmallHolidayCart = WC()->cart->subtotal <= 5000;
    $isSmallHolidayTariffOn = $isHolidayCourierTariff && $isSmallHolidayCart;
    echo $isSmallHolidayTariffOn;
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


// Footer: кнопка + JS
add_action('wp_footer', 'reazy_wc_notices_debug_button', 9999);
function reazy_wc_notices_debug_button() {
	// Можно ограничить только каталогом:
	// if ( ! ( function_exists('is_shop') && (is_shop() || is_product_taxonomy()) ) ) return;

	$nonce = wp_create_nonce('reazy_only_wc_notices');
	?>
	<div style="position:fixed;right:20px;bottom:20px;z-index:99999;">
		<button type="button" id="reazy-show-only-notices"
			style="padding:10px 14px;border-radius:8px;border:1px solid #ddd;background:#fff;cursor:pointer;">
			Показать только notices
		</button>
	</div>

	<script>
	(function () {
		var btn = document.getElementById('reazy-show-only-notices');
		if (!btn) return;

		function getOrCreateWrapperBeforeShopLoop() {
			var w = document.querySelector('.woocommerce-notices-wrapper');
			if (w) return w;

			// Если решишь создавать wrapper:
			// var anchor =
			//   document.querySelector('.woocommerce-products-header') ||
			//   document.querySelector('.woocommerce-result-count') ||
			//   document.querySelector('.woocommerce-ordering') ||
			//   document.querySelector('ul.products') ||
			//   document.querySelector('.products') ||
			//   document.querySelector('.woocommerce') ||
			//   document.querySelector('main') ||
			//   document.querySelector('body');
			//
			// w = document.createElement('div');
			// w.className = 'woocommerce-notices-wrapper';
			// anchor.parentNode.insertBefore(w, anchor);
			// return w;
		}

		btn.addEventListener('click', function () {
			var wrapper = getOrCreateWrapperBeforeShopLoop();

			var data = new FormData();
      data.append('action', 'reazy_only_wc_notices');
      data.append('nonce', '<?php echo esc_js($nonce); ?>');

			fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
      })
			.then(function (r) { return r.json(); })
			.then(function (res) {
				if (!res || !res.success) {
					console.warn('AJAX error:', res);
					return;
				}
				if (!wrapper) {
					console.warn('No .woocommerce-notices-wrapper found on page');
					return;
				}
				wrapper.innerHTML = res.data.html || '';
			})
			.catch(console.error);
		});
	})();
	</script>
	<?php
}