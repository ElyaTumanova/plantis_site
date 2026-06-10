<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* wish list button in card */
function plnt_card_wishlist_btn() {
    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
};

/* wish list layout */
add_action('plnt_wish_before_shop_loop','get_wishlist_top', 32);
function get_wishlist_top($wishlist) {
  $total = count( $wishlist->get_items() );

	if ( ! $total ) {
		return;
	}

  echo ('<div class="catalog__top">');
  plnt_wishlist_total_count($wishlist);
  plnt_catalog_grid_columns();
  echo ('</div>');
}

function plnt_wishlist_total_count($wishlist) {
	$total = count( $wishlist->get_items() );

	if ( ! $total ) {
		return;
	}

	echo '<span class="woocommerce-result-count" aria-hidden="false">';
	echo esc_html( plnt_product_count_text( $total ) );
	echo '</span>';
}

add_action('plnt_wish_after_header','plnt_wish_header_image', 10);

function plnt_wish_header_image() {
  ?>
  <div class="catalog__header-image-wrap darken">
    <img
      class="catalog__header-image"
      src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/wish-header-img.png' ); ?>" 
      alt=""
      whidth="800"
      height="800">
  </div>
  <?
}

function plnt_wishlist_share() {
	if ( ! function_exists( 'yith_wcwl_get_template' ) || ! class_exists( 'YITH_WCWL_Wishlist_Factory' ) ) {
		return;
	}

	$wishlist = YITH_WCWL_Wishlist_Factory::get_current_wishlist();

	if ( ! $wishlist ) {
		return;
	}

	$wishlist_token = $wishlist->get_token();

	$share_link_url = trailingslashit( YITH_WCWL()->get_wishlist_url() )
		. 'view/'
		. $wishlist_token
		. '/';

	$share_link_title = 'My wishlist on Plantis';

	yith_wcwl_get_template(
		'share.php',
		array(
			'wishlist'            => $wishlist,
			'share_link_url'      => $share_link_url,
			'share_link_title'    => $share_link_title,
			'share_email_icon'    => '',
			'share_whatsapp_icon' => '',
			'share_whatsapp_url'  => '',
		)
	);
}

plnt_add_wrapper('container', 'yith_wcwl_wishlist_after_wishlist_content', 40, 'yith_wcwl_wishlist_after_wishlist_content', 42);

add_action('yith_wcwl_wishlist_after_wishlist_content','plnt_cart_popular', 41);

// add_action('yith_wcwl_wishlist_after_wishlist_content','plnt_img_gallery_swiper_init', 100);


/* wishlist ajax */
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
              let wishListBtns = document.querySelectorAll ('.header__actions--wishlist');
              wishListBtns.forEach((el)=>{
                  if (data.count > 0) {
                      el.querySelector('.header__actions-count').classList.add('header__actions-count--active');
                  } else {
                      el.querySelector('.header__actions-count').classList.remove('header__actions-count--active');
                  }
              });
            } );
          } );
        } );
      "
    );
  }

  add_action( 'wp_enqueue_scripts', 'yith_wcwl_enqueue_custom_script', 20 );
}


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


  