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
 * TEST: wc_get_orders filter by date_completed for fixed date: 2025-12-23
 * URL: /wp-admin/?plnt_test_date_completed=1
 */
function plnt_test_date_completed_query_fixed_date(): void {
    if (!is_admin()) return;
    if (!isset($_GET['plnt_test_date_completed'])) return;

    if (!current_user_can('manage_woocommerce')) {
        wp_die('No permission');
    }

    $tz = wp_timezone();

    // Day range in site timezone
    $start_dt = new DateTime('2025-12-23 00:00:00', $tz);
    $end_dt   = new DateTime('2025-12-23 23:59:59', $tz);

    $start_str = $start_dt->format('Y-m-d H:i:s');
    $end_str   = $end_dt->format('Y-m-d H:i:s');

    // timestamps (local -> unix)
    $start_ts = $start_dt->getTimestamp();
    $end_ts   = $end_dt->getTimestamp();


    $result = wc_get_orders([
        'status'       => ['completed'],
        'limit'        => 50,
        'orderby'      => 'date',
        'order'        => 'ASC',
        'return'       => 'objects',
        'paginate'     => true,
        'meta_key'     => '_date_completed',
        'meta_compare' => 'BETWEEN',
        'meta_value'   => [$start_ts, $end_ts],
        'type'         => 'shop_order',
    ]);

    $orders = $result->orders ?? [];
    $total  = (int) ($result->total ?? count($orders));
    $pages  = (int) ($result->max_num_pages ?? 1);

    $out  = '<h2>TEST: wc_get_orders(date_completed) for 2025-12-23</h2>';
    $out .= '<p><strong>Timezone:</strong> ' . esc_html($range['tz']) . '</p>';
    $out .= '<p><strong>Range:</strong> ' . esc_html($range['start']) . ' ... ' . esc_html($range['end']) . '</p>';
    $out .= '<p><strong>Found:</strong> ' . esc_html((string)$total) . ' orders (pages: ' . esc_html((string)$pages) . ')</p>';

    if (empty($orders)) {
        $out .= '<p><em>No completed orders in this range (or filter not working / none were completed that day).</em></p>';
        wp_die($out);
    }

    $out .= '<table class="widefat striped" style="max-width: 980px;">';
    $out .= '<thead><tr><th>Order ID</th><th>Status</th><th>Date completed</th><th>Date created</th></tr></thead><tbody>';

    foreach ($orders as $order) {
        if (!$order instanceof WC_Order) continue;

        $dc = $order->get_date_completed();
        $dc_str = $dc ? $dc->date('Y-m-d H:i:s') : '(null)';

        $created = $order->get_date_created();
        $created_str = $created ? $created->date('Y-m-d H:i:s') : '(null)';

        $out .= '<tr>';
        $out .= '<td>' . esc_html((string)$order->get_id()) . '</td>';
        $out .= '<td>' . esc_html($order->get_status()) . '</td>';
        $out .= '<td>' . esc_html($dc_str) . '</td>';
        $out .= '<td>' . esc_html($created_str) . '</td>';
        $out .= '</tr>';
    }

    $out .= '</tbody></table>';

    wp_die($out);
}
add_action('admin_init', 'plnt_test_date_completed_query_fixed_date');