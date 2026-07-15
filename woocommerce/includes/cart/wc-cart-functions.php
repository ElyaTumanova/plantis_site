<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* AJAX CART UPDATE */
  add_action( 'wp_ajax_plnt_cart_update', 'plnt_cart_update' );
  add_action( 'wp_ajax_nopriv_plnt_cart_update', 'plnt_cart_update' );

  function plnt_cart_update() {
    $cart_item_key = isset( $_POST['cart_item_key'] )
      ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) )
      : '';

    $quantity = isset( $_POST['qty'] )
      ? absint( $_POST['qty'] )
      : 0;

    if ( ! $cart_item_key ) {
      wp_send_json_error(
        [
          'message' => 'Не передан cart_item_key',
        ],
        400
      );
    }

    if ( ! function_exists( 'WC' ) ) {
      wp_send_json_error(
        [
          'message' => 'WooCommerce недоступен',
        ],
        500
      );
    }

    if ( ! WC()->cart ) {
      wc_load_cart();
    }

    $cart_item = WC()->cart->get_cart_item( $cart_item_key );

    if ( ! $cart_item ) {
      wp_send_json_error(
        [
          'message' => 'Товар не найден в корзине',
          'cart_item_key' => $cart_item_key,
        ],
        404
      );
    }

    if ( 0 === $quantity ) {
      $result = WC()->cart->remove_cart_item(
        $cart_item_key
      );
    } else {
      $result = WC()->cart->set_quantity(
        $cart_item_key,
        $quantity,
        true
      );
    }

    if ( false === $result ) {
      wp_send_json_error(
        [
          'message' => 'Не удалось изменить количество',
        ],
        400
      );
    }

    plnt_send_cart_update_response();
  }

  function plnt_send_cart_update_response($extra_data = []) {
    $response = [
        'fragments'  => plnt_woocommerce_cart_fragments( array() ),
        'cart_count' => WC()->cart->get_cart_contents_count(),
      ];
      
    wp_send_json_success( array_merge( $response, $extra_data ) );
  }
/* 
*/
/* CART BACKORDER */
  add_action( 'wp_ajax_replace_backorder_product', 'plnt_replace_backorder_product' );
  add_action( 'wp_ajax_nopriv_replace_backorder_product', 'plnt_replace_backorder_product' );
  function plnt_replace_backorder_product() {
    $product_id = isset( $_POST['backorder_replace_prodId'] )
      ? absint( $_POST['backorder_replace_prodId'] )
      : 0;

    $cart_item_key = isset( $_POST['backorder_replace_cart_item'] )
      ? sanitize_text_field(
        wp_unslash(
          $_POST['backorder_replace_cart_item']
        )
      )
      : '';

    if ( ! $product_id || ! $cart_item_key ) {
      wp_send_json_error( [ 'message' => 'Не переданы данные для замены товара' ], 400 );
    }

    if ( ! function_exists( 'WC' ) ) {
      wp_send_json_error( [ 'message' => 'WooCommerce недоступен' ], 500 );
    }

    if ( ! WC()->cart ) {
      wc_load_cart();
    }

    $cart_item = WC()->cart->get_cart_item($cart_item_key);

    if ( ! $cart_item ) {
      wp_send_json_error( [ 'message' => 'Исходный товар не найден в корзине' ], 404 );
    }

    /*
    * Данные Метрики сохраняем до изменения корзины.
    */

    $removed_product = isset( $cart_item['data'] ) && $cart_item['data'] instanceof WC_Product ? $cart_item['data'] : null;
    $removed_quantity = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 1;
    $added_product = wc_get_product( $product_id );

    if ( ! $removed_product || ! $added_product ) {
      wp_send_json_error( [ 'message' => 'Не удалось получить данные товаров' ], 400 );
    }

    $metrika_remove_product = plnt_get_metrika_product_data( $removed_product, $removed_quantity );
    $metrika_add_product = plnt_get_metrika_product_data( $added_product, 1 );

    $added_cart_item_key = WC()->cart->add_to_cart($product_id);

    if ( ! $added_cart_item_key ) {
      wp_send_json_error(
        [
          'message' => 'Не удалось добавить новый товар',
        ],
        400
      );
    }

    $removed = WC()->cart->remove_cart_item(
      $cart_item_key
    );

    if ( ! $removed ) {
      /*
      * Откатываем добавление, чтобы в корзине
      * не остался лишний товар.
      */
      WC()->cart->remove_cart_item(
        $added_cart_item_key
      );

      wp_send_json_error(
        [
          'message' => 'Не удалось удалить заменяемый товар',
        ],
        400
      );
    }

    plnt_send_cart_update_response(
      [
        'metrika' => [
          'remove' => [ $metrika_remove_product ],
          'add'    => [ $metrika_add_product ],
        ],
      ]
    );
  }

  function plnt_get_metrika_product_data( $product, $quantity = 1 ) {
    if ( ! $product instanceof WC_Product ) {
      return [];
    }

    $parentCatId = check_category($product);
    $catName = get_the_category_by_ID($parentCatId);

    return [
      'id'       => (string) $product->get_id(),
      'name'     => wp_strip_all_tags( $product->get_name() ),
      'price'    => (float) wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
      'category' => $catName,
      'quantity' => max( 1, (int) $quantity ),
    ];
  }
/* 
 */
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
    $current_total = 0;
    $discount = 0;


    foreach ( $cart->get_cart() as $cart_item ) {
      if ( empty( $cart_item['data'] ) ) {
        continue;
      }

      $product  = $cart_item['data'];
      $quantity = (int) $cart_item['quantity'];

      $regular_price = (float) $product->get_regular_price();
      $current_price = (float) $product->get_price();

      $regular_total += $regular_price * $quantity;
      $current_total += $current_price * $quantity;
    }

    $discount = max( 0, $regular_total - $current_total );


    return array(
      'total'         => $total,
      'count'         => $count,
      'regular_total' => $regular_total,
      'discount'      => $discount,
    );
  }
/*  
*/
/* PERESADKA */
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
/* CART & WISHLIST UPDATE */
  add_action( 'wp_ajax_nopriv_plnt_get_cart_wish', 'plnt_get_cart_wish' );
  add_action( 'wp_ajax_plnt_get_cart_wish', 'plnt_get_cart_wish' );
  //обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования
  function plnt_get_cart_wish() {
    if ( ! WC()->cart ) {
      wc_load_cart();
    }

    wp_send_json(
      array(
        'fragments'  => plnt_woocommerce_cart_fragments( array() ),
        'wish_ids'   => plnt_get_wish_list_ids(),
        'wish_count' => (int) yith_wcwl_count_all_products(),
      )
    );
  }
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
/* 
 */