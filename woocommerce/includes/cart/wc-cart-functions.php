<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* CART SUMMARY */

function plnt_get_cart_summary() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return array(
			'total'         => 0,
			'count'         => 0,
			'regular_total' => 0,
      'discount' => 0
		);
	}

	$cart          = WC()->cart;
	$total         = (float) $cart->get_total( 'edit' );
	$count         = (int) $cart->get_cart_contents_count();
	$regular_total = 0;
	$discount = 0;


	foreach ( $cart->get_cart() as $cart_item ) {
		if ( empty( $cart_item['data'] ) ) {
			continue;
		}

		$product  = $cart_item['data'];
		$quantity = (int) $cart_item['quantity'];

		$regular_price = (float) $product->get_regular_price();

		$regular_total += $regular_price * $quantity;
	}

  $discount = max( 0, $regular_total - $total );


	return array(
		'total'         => $total,
		'count'         => $count,
		'regular_total' => $regular_total,
    'discount'      => $discount,
	);
}

/* Peresadka */
  /* добавляем ключ родительского товара при доавлении пересадки в корзину */
  add_filter( 'woocommerce_add_cart_item_data', 'plnt_add_peresadka_parent_key', 10, 3 );

  function plnt_add_peresadka_parent_key( $cart_item_data, $product_id, $variation_id ) {

    if ( empty( $_REQUEST['parent_cart_item_key'] ) ) {
      return $cart_item_data;
    }

    $parent_cart_item_key = sanitize_text_field(
      wp_unslash( $_REQUEST['parent_cart_item_key'] )
    );

    if ( empty( WC()->cart->cart_contents[ $parent_cart_item_key ] ) ) {
      return $cart_item_data;
    }

    $cart_item_data['plnt_parent_cart_item_key'] = $parent_cart_item_key;

    // Уникальность именно для пары:
    // пересадка + конкретная строка исходного товара
    $cart_item_data['plnt_unique_key'] = md5( $product_id . '_' . $parent_cart_item_key );

    return $cart_item_data;
  }

  /* сортирует строки корзины, чтобы пересадки шли после родительских товаров */
  add_action( 'woocommerce_before_calculate_totals', 'plnt_sort_cart_items_with_peresadka', 20 );

  function plnt_sort_cart_items_with_peresadka( $cart ) {
    if ( is_admin() && ! wp_doing_ajax() ) {
      return;
    }

    if ( ! $cart || empty( $cart->cart_contents ) ) {
      return;
    }

    $cart_contents = $cart->cart_contents;

    $children_by_parent = array();
    $normal_items       = array();

    foreach ( $cart_contents as $cart_item_key => $cart_item ) {
      if ( ! empty( $cart_item['plnt_parent_cart_item_key'] ) ) {
        $parent_key = $cart_item['plnt_parent_cart_item_key'];

        if ( ! isset( $children_by_parent[ $parent_key ] ) ) {
          $children_by_parent[ $parent_key ] = array();
        }

        $children_by_parent[ $parent_key ][ $cart_item_key ] = $cart_item;
      } else {
        $normal_items[ $cart_item_key ] = $cart_item;
      }
    }

    $sorted_cart = array();

    foreach ( $normal_items as $cart_item_key => $cart_item ) {
      $sorted_cart[ $cart_item_key ] = $cart_item;

      if ( ! empty( $children_by_parent[ $cart_item_key ] ) ) {
        foreach ( $children_by_parent[ $cart_item_key ] as $child_key => $child_item ) {
          $sorted_cart[ $child_key ] = $child_item;
        }
      }
    }

    $cart->cart_contents = $sorted_cart;
  }

  /* добавдяем класс к строке пересадки в корзине */
  add_filter( 'woocommerce_cart_item_class', 'plnt_peresadka_cart_item_class', 10, 3 );
  add_filter( 'woocommerce_mini_cart_item_class', 'plnt_peresadka_cart_item_class', 10, 3 );
  
  

  function plnt_peresadka_cart_item_class( $class, $cart_item, $cart_item_key ) {
    if ( ! empty( $cart_item['plnt_parent_cart_item_key'] ) ) {
      $class .= ' cart_item--peresadka';
    }

    return $class;
  }
/* 
 */
/*--------------------------------------------------------------
# CART & WISHLIST UPDATE 
--------------------------------------------------------------*/

// получаем ИД товаров в wishlist для аякса

function plnt_get_wish_list_ids() {

    // Берём текущий wishlist (для гостя — по cookie/token, для юзера — по user_id)
    $wishlist = YITH_WCWL_Wishlist_Factory::get_current_wishlist();

    if ( ! $wishlist ) {
        return '';
    }

    $wish_list_items = array();

    foreach ( $wishlist->get_items() as $item ) {
        $wish_list_items[] = (int) $item->get_product_id();
    }

    return implode(',', array_unique($wish_list_items));
}


//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования
function plnt_update_mini_cart() {
  $response = array();
	ob_start();
  woocommerce_mini_cart();
  $response['mini_cart'] = ob_get_clean();
	$response['cart_count'] = WC()->cart->get_cart_contents_count();
	$response['wish'] = plnt_get_wish_list_ids();
	$response['count'] = yith_wcwl_count_all_products();
	ob_start();
	plnt_cart_totals();
	$response['cart_summary'] = ob_get_clean();

	wp_send_json($response);
	die();
}
add_action( 'wp_ajax_nopriv_plnt_update_mini_cart', 'plnt_update_mini_cart' );
add_action( 'wp_ajax_plnt_update_mini_cart', 'plnt_update_mini_cart' );


/*--------------------------------------------------------------
# CART FUNCTIONS 
--------------------------------------------------------------*/

// изменяем кнопку "в корзину" после добавления товара в корзину


//add_filter( 'woocommerce_product_single_add_to_cart_text', 'truemisha_single_product_btn_text' ); // текст для страницы самого товара
 
function truemisha_single_product_btn_text( $text ) {
	if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( get_the_ID() ) ) ) {
		$text = 'В корзине';
	}
 
	return $text;
}
