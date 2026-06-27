<?php
if (!defined('ABSPATH')) exit;



function plnt_account_button($classes = '') {
    $is_logged_in = is_user_logged_in();
    $is_account   = function_exists( 'is_account_page' ) && is_account_page();

    $classes = $classes;

    $icon = plnt_icon('account');

    if ( $is_logged_in ) {
        $href = home_url( '/my-account/orders' );
        $classes .= ' logged-in';
    } elseif ( $is_account ) {
        $href = home_url( '/my-account' );
    } else {
        $href = '';
        $classes .= ' login-btn login-popup-open-btn button';
    }


    echo ('<div class ="header__actions header__actions--account">');
    if ( $href ) : ?>
      <a href="<?php echo esc_url( $href ); ?>" class="<?php echo esc_attr( $classes ); ?>">
          <?php echo $icon; ?>
      </a>
    <?php else : ?>
      <button type="button" class="<?php echo esc_attr( $classes ); ?>">
          <?php echo $icon; ?>
      </button>
    <?php endif;
    echo ('</div>');
}

function plnt_wishlist_button() {
    if ( ! defined( 'YITH_WCWL' ) || ! function_exists( 'yith_wcwl_count_all_products' ) ) {
        return;
    }

    $count     = yith_wcwl_count_all_products();
    $is_active = $count > 0;

    $count_classes = 'header__actions-count';
    $link_classes  = '';

    if ( $is_active ) {
        $count_classes .= ' header__actions-count--active';
        $link_classes  .= ' ';
    }

    $url  = home_url( '/wishlist' );
    $icon = plnt_icon('wish');
    ?>

    <div class="header__actions header__actions--wishlist">
      <div class="<?php echo esc_attr( $count_classes ); ?>">
          <span class="yith-wcwl-items-count">
              <i><?php echo esc_html( $count ); ?></i>
          </span>
      </div>
      <a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $link_classes ); ?>">
          <?php echo $icon; ?>
      </a>
    </div>

    <?php
}

function plnt_adress_link() {
  ?>
  <a href="https://yandex.ru/maps/-/CPWKiHNG" target="_blank">
    Москва, ул. Мещерякова, д.3
  </a>
  <?php
}

function plnt_phones_link() {
  ?>
  <a href="tel:+78002015790">8 800 201 57 90</a> <span>•</span> <a href="tel:+79995527944">8 999 552-79-44</a>
  <?php
}

function plnt_email_link() {
  ?>
  <a href="mailto:INFO@PLANTIS.SHOP">INFO@PLANTIS.SHOP</a>
  <?php
}


function plnt_search_form( $id = 'searchform' ) {
  ?>
  <form role="search" method="get" id="<?php echo esc_attr( $id ); ?>" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="<?php echo esc_attr( $id ); ?>-s">Найти:</label>
    <input type="search" class="search-field" placeholder="Поиск…" value="<?php echo get_search_query(); ?>" name="s" id="<?php echo esc_attr( $id ); ?>-s">
    <input type="hidden" value="product" name="post_type">
  </form>
  <?php
}