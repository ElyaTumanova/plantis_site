<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// wish list button in card

// add_action('woocommerce_after_add_to_cart_button', 'plnt_card_wishlist_btn', 10); //работает если использрвать в карточке не аякс кнопку добавления в корзину

function plnt_card_wishlist_btn() {
    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
};

// // wish list button in catalog
// add_action('woocommerce_before_shop_loop_item_title','plnt_wish_list_btn_catalog', 20);

// function plnt_wish_list_btn_catalog() {
// 	echo do_shortcode('[yith_wcwl_add_to_wishlist]');
// }


// шорткод для вывода ссылки и кол-ва + ajsx обновление
if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_get_items_count' ) ) {
    function yith_wcwl_get_items_count() {
      ob_start();
      ?>
        <div class="header__wishlist">
          <?php $whishlist_icon = carbon_get_theme_option('whishlist_icon')?>
          <?php if (yith_wcwl_count_all_products() == 0) :?>
            <div class="header__count">
          <?php else : ?>
            <div class="header__count header__count_active ">
          <?php endif;?>
              <span class="yith-wcwl-items-count">
                <i class="yith-wcwl-icon fa fa-heart-o"><?php echo esc_html( yith_wcwl_count_all_products() ); ?></i>
              </span>
            </div>

          <?php if (yith_wcwl_count_all_products() == 0) :?>
            <a href="<?php echo get_site_url()?>/wishlist" class="header-btn__wrap">	
          <?php else : ?>
            <a href="<?php echo get_site_url()?>/wishlist" class="header-btn__wrap header-btn__wrap_active">	
          <?php endif;?>
              <?php echo $whishlist_icon ?>		
              <span class="header-btn__label">Избранное</span>		
            </a>
        </div>
      <?php
      return ob_get_clean();
    }
  
    add_shortcode( 'yith_wcwl_items_count', 'yith_wcwl_get_items_count' );
  }
  
  if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ) {
    function yith_wcwl_ajax_update_count() {
      wp_send_json( array(
        'count' => yith_wcwl_count_all_products()
      ) );
    }
  
    add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
    add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
  }
  
  if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_enqueue_custom_script' ) ) {
    function yith_wcwl_enqueue_custom_script() {
      wp_add_inline_script(
        'jquery-yith-wcwl',
        "
          jQuery( function( $ ) {
            $( document ).on( 'added_to_wishlist removed_from_wishlist', function() {
              $.get( yith_wcwl_l10n.ajax_url, {
                action: 'yith_wcwl_update_wishlist_count'
              }, function( data ) {
                $('.yith-wcwl-items-count').children('i').html( data.count );
                test (data.count);
              } );
            } );
          } );
        "
      );
    }
  
    add_action( 'wp_enqueue_scripts', 'yith_wcwl_enqueue_custom_script', 20 );
  }

  function test (count) {
    print_r(count);
  }


// получаем ИД товаров в wishlist для аякса

function plnt_get_wish_list_ids() {
  global $user_id;
  $wishlist_ids = YITH_WCWL()->get_wishlists( array( 'user_id' => $user_id ) );

  foreach ($wishlist_ids as $wishlist_id) {
    $wish_id = $wishlist_id['id'];
    $wish_list_items = [];
                
    $wish_products = YITH_WCWL()->get_products( [ 'wishlist_id' => 'all' ] );
    foreach ($wish_products as $wish_product) {
      $product_id = $wish_product['prod_id'];
      array_push($wish_list_items, $product_id);
    }
    return $wish_list_items_string = implode(",", $wish_list_items);
  }
};

if ( defined( 'YITH_WCWL' ) && ! function_exists( 'plnt_yith_wcwl_ajax_get_wishlist' ) ) {
  function plnt_yith_wcwl_ajax_get_wishlist() {
    global $user_id;
    wp_send_json( array(
      'wish' =>  plnt_get_wish_list_ids()
    ));
  }

  add_action( 'wp_ajax_plnt_yith_wcwl_get_wishlist', 'plnt_yith_wcwl_ajax_get_wishlist' );
  add_action( 'wp_ajax_nopriv_plnt_yith_wcwl_get_wishlist', 'plnt_yith_wcwl_ajax_get_wishlist' );
}


add_action('yith_wcwl_wishlist_after_wishlist_content','plnt_cart_popular', 40);

// перевод текстов

function plnt_change_text_wish_1( $translated_text ) {
  if ( $translated_text == 'Add all to cart' ) {
    $translated_text = 'Добавить все в корзину';
  }
  return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_wish_1', 20 );

function plnt_change_text_wish_2( $translated_text ) {
  if ( $translated_text == 'Edit title' ) {
    $translated_text = 'Редактировать название';
  }
  return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_wish_2', 20 );

function plnt_change_text_wish_3( $translated_text ) {
  if ( $translated_text == 'Items correctly added to the cart' ) {
    $translated_text = 'Товары добавлены в корзину';
  }
  return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_wish_3', 20 );

function plnt_change_text_wish_4( $translated_text ) {
  if ( $translated_text == 'Product added to cart successfully' ) {
    $translated_text = 'Товары добавлены в корзину';
  }
  return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_wish_4', 20 );

function plnt_change_text_wish_5( $translated_text ) {
  if ( $translated_text == 'Product successfully removed.' ) {
    $translated_text = 'Товар удален из избранного';
  }
  return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_wish_5', 20 );

function plnt_change_text_wish_6( $translated_text ) {
  if ( $translated_text == 'No products added to the wishlist' ) {
    $translated_text = 'Здесь будут ваши избранные товары';
  }
  return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_wish_6', 20 );


  