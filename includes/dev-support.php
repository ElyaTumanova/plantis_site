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


/**
 * 1) Кнопка + JS в футере
 * 2) AJAX-хендлер (и для залогиненных, и для гостей)
 */

/** =========================
 *  UI + JS
 *  ========================= */
//add_action('wp_footer', 'reazy_wc_notices_debug_button', 9999);
function reazy_wc_notices_debug_button() {
	// Можно ограничить только каталогом:
	// if ( ! ( function_exists('is_shop') && (is_shop() || is_product_taxonomy()) ) ) return;

	$nonce = wp_create_nonce('reazy_only_wc_notices');
	$ajax_url = admin_url('admin-ajax.php');
	?>
	<div style="position:fixed;right:20px;bottom:20px;z-index:99999;">
		<button type="button" id="reazy-show-only-notices"
			style="padding:10px 14px;border-radius:8px;border:1px solid #ddd;background:#fff;cursor:pointer;">
			Показать только notices
		</button>
	</div>

	<script>
	(function () {
		const btn = document.getElementById('reazy-show-only-notices');
		if (!btn) return;

		const ajaxUrl = <?php echo wp_json_encode($ajax_url); ?>;
		const nonce   = <?php echo wp_json_encode($nonce); ?>;

		function getOrCreateWrapper() {
			let w = document.querySelector('.woocommerce-notices-wrapper');
			if (w) return w;

			// Создаём wrapper, если его нет (иначе нечего обновлять)
			const anchor =
				document.querySelector('.woocommerce-products-header') ||
				document.querySelector('.woocommerce-result-count') ||
				document.querySelector('.woocommerce-ordering') ||
				document.querySelector('ul.products') ||
				document.querySelector('.products') ||
				document.querySelector('.woocommerce') ||
				document.querySelector('main') ||
				document.body;

			w = document.createElement('div');
			w.className = 'woocommerce-notices-wrapper';

			if (anchor && anchor.parentNode) anchor.parentNode.insertBefore(w, anchor);
			else document.body.insertBefore(w, document.body.firstChild);

			return w;
		}

		async function postNotices() {
			// как в вашем ajax-search.js: FormData + credentials + X-Requested-With :contentReference[oaicite:1]{index=1}
			const fd = new FormData();
			fd.append('action', 'reazy_only_wc_notices');
			fd.append('nonce', nonce);

			const res = await fetch(ajaxUrl, {
				method: 'POST',
				body: fd,
				credentials: 'same-origin',
				headers: { 'X-Requested-With': 'XMLHttpRequest' }
			});

			// admin-ajax может вернуть не-JSON (0/HTML). Поэтому читаем безопасно:
			const text = await res.text();
			let data = null;
			try { data = JSON.parse(text); } catch (e) {}

			return { res, data, text };
		}

		btn.addEventListener('click', async function () {
			const wrapper = getOrCreateWrapper();

			try {
				const { res, data, text } = await postNotices();

				// Если не JSON — покажем сырой ответ (для отладки)
				if (!data) {
					console.warn('AJAX returned non-JSON:', res.status, text);
					return;
				}

				if (!res.ok) {
					console.warn('AJAX HTTP error:', res.status, data);
					return;
				}

				if (!data.success) {
					console.warn('AJAX app error:', data);
					return;
				}

				wrapper.innerHTML = (data.data && data.data.html) ? data.data.html : '';
			} catch (err) {
				console.error('AJAX failed:', err);
			}
		});
	})();
	</script>
	<?php
}

/** =========================
 *  AJAX handler
 *  ========================= */
add_action('wp_ajax_reazy_only_wc_notices', 'reazy_only_wc_notices_ajax');
add_action('wp_ajax_nopriv_reazy_only_wc_notices', 'reazy_only_wc_notices_ajax');

function reazy_only_wc_notices_ajax() {
	if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'reazy_only_wc_notices')) {
		wp_send_json_error(['message' => 'Bad nonce'], 403);
	}

	if (!function_exists('wc_print_notices')) {
		wp_send_json_error(['message' => 'WooCommerce not available'], 500);
	}

  $notices = function_exists('wc_get_notices') ? wc_get_notices() : [];
  if (empty($notices)) {
      wp_send_json_success(['html' => '<div class="woocommerce-error">Notices сейчас нет</div>']);
  }

	ob_start();
	wc_print_notices();
	$html = ob_get_clean();

	wp_send_json_success(['html' => $html]);
}

function plnt_debug_product_page(): void {
    if (!is_admin()) return;
    if (!isset($_GET['plnt_debug_product'])) return;
    if (!current_user_can('manage_woocommerce')) wp_die('No permission');

    $product_id = (int) $_GET['plnt_debug_product'];
    if ($product_id <= 0) wp_die('Bad product id');

    $product = wc_get_product($product_id);
    if (!$product) wp_die('Product not found');

    $data = [
        'product_id'            => $product_id,
        'title'                 => $product->get_name(),
        'status'                => $product->get_status(),
        'managing_stock'        => $product->managing_stock() ? 1 : 0,
        'stock_quantity'        => $product->get_stock_quantity(),
        'qualifies_now'         => plnt_product_is_in_expo_today($product) ? 1 : 0,

        '_plnt_expo_days_total' => (int) get_post_meta($product_id, '_plnt_expo_days_total', true),
        '_plnt_expo_days_reset' => (int) get_post_meta($product_id, '_plnt_expo_days_reset', true),
        '_plnt_expo_paused'     => (int) get_post_meta($product_id, '_plnt_expo_paused', true),

        '_plnt_expo_sales_total'=> (int) get_post_meta($product_id, '_plnt_expo_sales_total', true),
        '_plnt_expo_sales_reset'=> (int) get_post_meta($product_id, '_plnt_expo_sales_reset', true),
    ];

    $out  = '<h2>Plantis debug product: ' . esc_html((string)$product_id) . '</h2>';
    $out .= '<p><a href="' . esc_url(admin_url('post.php?post=' . $product_id . '&action=edit')) . '">Open product edit</a></p>';
    $out .= '<table class="widefat striped" style="max-width: 1000px;"><tbody>';

    foreach ($data as $k => $v) {
        $out .= '<tr><th style="width:320px;">' . esc_html($k) . '</th><td><code>' . esc_html(is_null($v) ? 'NULL' : (string)$v) . '</code></td></tr>';
    }

    $out .= '</tbody></table>';

    $out .= '<hr><p>Tip: to enable before/after logging for this product on next runs, open:</p>';
    $out .= '<p><code>/wp-admin/?plnt_debug_watch=8438</code></p>';

    wp_die($out);
}
add_action('admin_init', 'plnt_debug_product_page');