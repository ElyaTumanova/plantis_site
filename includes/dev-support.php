<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// FOR DEV

//add_action( 'wp_footer', 'echo_hi' );


function plnt_echo_smth() {


  $product = wc_get_product( 15419 );
  echo $product->get_price_html();
  echo $product->get_price();

  $code = 'B138-0994-F82D-9637';

  $query = new WP_Query( array(
        'post_type'      => 'gift_card',     // тип поста для YITH карт
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        's' => $code,
        'fields' => 'ids',
    ) );

  echo '<pre>';
  //print_r($product);
  //print_r($query);
  $post_obj = get_post( (int) $query->posts[0] );

  $post_id = $query->posts[0];
  $title   = get_the_title( $post_id );


  print_r($title);
  echo('<br>');
  print_r($query);
  echo('<br>');
  print_r(get_post_meta( $post_id ));
  


  echo '</pre>';
}


function echo_hi() {
  if (is_page('gift-card')) {
    echo ('hihi');
  }
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

add_action( 'woocommerce_before_checkout_form', 'my_show_cart_items_on_checkout', 5 );
function my_show_cart_items_on_checkout() {
    if ( WC()->cart->is_empty() ) {
        return;
    }

    echo '<div class="my-checkout-items">';
    echo '<h3>Состав заказа</h3>';

    echo '<ul>';

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product  = $cart_item['data'];
        $type    = $product->get_type();
        $name     = $product->get_name();
        $qty      = $cart_item['quantity'];
        $price    = wc_get_price_to_display( $product );
        $subtotal = $price * $qty;

        echo '<li>';
        echo '<strong>' . esc_html( $name ) . '</strong><br>';
        echo 'Кол-во: ' . esc_html( $qty ) . '<br>';
        echo 'Цена за шт.: ' . wc_price( $price ) . '<br>';
        echo 'Сумма: ' . wc_price( $subtotal );
        echo 'Тип: ' . $type;
        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
}


//add_action( 'woocommerce_before_checkout_form', 'my_set_price_by_product_id_on_checkout', 5 );
function my_set_price_by_product_id_on_checkout() {

    // чтобы не срабатывало в админке и при пустой корзине
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    if ( WC()->cart->is_empty() ) {
        return;
    }

    // ID товара, которому задаём цену
    $target_id = 15419;   // ← поменяй на свой ID

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];

        $new_price = wc_get_price_to_display( $product );   // ← нужная цена

        // для простых товаров
        if ( $product->get_id() == $target_id ) {
            $product->set_price( $new_price );
        }
    }

    // пересчитать итоги после изменения цен
    WC()->cart->calculate_totals();
}


// Простой вариант: одна цена для одного товара по его ID.
add_filter( 'woocommerce_product_get_price', 'my_force_product_price_by_id', 10, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'my_force_product_price_by_id', 10, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'my_force_product_price_by_id', 10, 2 );
add_filter( 'woocommerce_product_variation_get_regular_price', 'my_force_product_price_by_id', 10, 2 );

function my_force_product_price_by_id( $price, $product ) {

    $target_id = 15419;  // ID товара, для которого задаём новую цену

    // Для простого товара
    if ( $product->get_id() == $target_id ) {
        $new_price = wc_get_price_to_display( $product ); 
        return $new_price;
    }


    return $price;
}


