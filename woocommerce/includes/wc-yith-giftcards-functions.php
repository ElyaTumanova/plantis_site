<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
Contents
#GENERAL DEV
#EMAILS
#CHECKOUT PAGE
#PRODUCT PAGE
--------------------------------------------------------------*/


/*--------------------------------------------------------------
#GENERAL DEV
--------------------------------------------------------------*/

// создаем запись со станицей с подарочной картой
function create_gift_card_page($gift_card) {
    $post_data = array(
    'post_title'    => wp_strip_all_tags( 'Подарочная карта' ),
    'post_content'  => $gift_card->gift_card_number ,
    'post_name' => 'gift-card-'.$gift_card->gift_card_number,
    'post_status'   => 'publish',
    'post_author'   => 1,
  );

  // Вставляем запись в базу данных
  $post_id = wp_insert_post( $post_data );

}

//add_action('yith_ywgc_after_gift_card_generation_save', 'create_gift_card_page');

// регистрируем гет парамтер для подарочной карты
add_filter('query_vars', function ($vars) {
    $vars[] = 'gcnum';
    return $vars;
});

/*--------------------------------------------------------------
#EMAILS
--------------------------------------------------------------*/

/**
 * Вывод изображения, номера и суммы подарочной карты в письме
 */
add_action( 'ywgc_gift_cards_email_before_preview_gift_card_param', function( $gift_card ) {

    if ( ! is_object( $gift_card ) ) {
        return;
    }

    // --- Изображение подарочной карты ---
    // Получаем картинку, выбранную в плагине. Может быть ID или URL.
    $image_url = '';
   if ( isset( $gift_card->product_id ) ) {
        $product_id = (int) $gift_card->product_id;
    } elseif ( method_exists( $gift_card, 'get_product_id' ) ) {
        $product_id = (int) $gift_card->get_product_id();
    }

    if ( $product_id ) {
        $thumb = get_the_post_thumbnail_url( $product_id, 'full' );
        if ( $thumb ) {
            $image_url = $thumb;
        }
    }

    // --- Номер подарочной карты ---
    $code = ! empty( $gift_card->gift_card_number )
        ? $gift_card->gift_card_number
        : ( method_exists( $gift_card, 'get_code' ) ? $gift_card->get_code() : '' );

    // --- Сумма подарочной карты ---
    $amount = '';
    if ( isset( $gift_card->amount ) ) {
        $amount = wc_price( $gift_card->amount );
    } elseif ( method_exists( $gift_card, 'get_balance' ) ) {
        $amount = wc_price( $gift_card->get_balance() );
    }

    // --- Вывод разметки ---
    ?>
    <div class="giftcard-preview" style="text-align:left; margin:20px 0;">
        <?php if ( $image_url ) : ?>
            <img src="<?php echo esc_url( $image_url ); ?>"
                 alt="<?php esc_attr_e( 'Gift Card', 'your-textdomain' ); ?>"
                 style="max-width:100%; height:auto; margin-bottom:15px;" />
        <?php endif; ?>

        <?php if ( $code ) : ?>
            <p style="font-size:18px; font-weight:bold; margin:0 0 10px;">
                <?php echo esc_html__( 'Номер сертификата:', 'your-textdomain' ) . ' ' . esc_html( $code ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $amount ) : ?>
            <p style="font-size:16px; margin:0;">
                <?php echo esc_html__( 'Сумма сертификата:', 'your-textdomain' ) . ' ' . wp_kses_post( $amount ); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php

}, 10, 1 );


//
add_action ('plnt_gift_card_email_after_preview', 'add_email_gift_card_link', 10);

function add_email_gift_card_link($gift_card) {
  $giftcard_link = 'http://dev.plantis-shop.ru/gift-card?gcnum='.$gift_card->gift_card_number;
  echo $giftcard_link;
}


// дата окончания действия
add_action( 'plnt_gift_card_email_after_preview', function( $gift_card ) {

    if ( ! is_object( $gift_card ) ) {
        return;
    }

    // Определяем дату окончания действия карты
    $expiration_date = ! is_numeric( $gift_card->expiration )
        ? strtotime( $gift_card->expiration )
        : $gift_card->expiration;

    if ( ! $expiration_date ) {
        return;
    }

    // Формат даты (можно менять)
    $date_format = apply_filters( 'yith_wcgc_date_format', 'd.m.Y' );

    // Выводим HTML с датой
    echo '<div class="giftcard-expiration" style="margin:20px 0;">';
    echo esc_html( sprintf(
        'Подарочный сертификат действует до %s',
        date_i18n( $date_format, $expiration_date )
    ) );
    echo '</div>';

}, 20, 1 );


/*--------------------------------------------------------------
#CHECKOUT PAGE
--------------------------------------------------------------*/

// поменяли хук, на котором висит поле ввода подарочной карты на странице Checkout
add_filter( 'ywgc_gift_card_code_form_checkout_hook', function (){
  return 'plnt_woocommerce_checkout_gift_card';
});

//изменили название поля для удаления кода карты
add_filter('ywgc_remove_gift_card_text', function (){
  return '×';
});


// add_filter('yith_ywgc_cart_totals_gift_card_label',function (){
//   return 'Подарочный сертификат';
// });

/*--------------------------------------------------------------
#PRODUCT PAGE
--------------------------------------------------------------*/

//add_action('woocommerce_before_single_product_summary','plnt_gift_card_info', 6);

function plnt_gift_card_info() {
  global $product;
  if ($product->get_type() == 'gift-card') {
    ?>
      <div class="card__gift-card-info"> 
        <?php get_template_part( 'template-parts/gift-card-info' );?>
        <button class='card__gift-card-buy button page-popup-open-btn'>Купить подарочную карту</button>
      </div>
    <?php
  }
}

//add_action('woocommerce_after_main_content','plnt_get_giftcard_popup',20);

function plnt_get_giftcard_popup() {
  global $product;
  if ($product->get_type() == 'gift-card') {
    ?>
      <div class="page-popup popup giftcard-popup">
        <div class="page-popup__popup-overlay popup-overlay"></div>
      </div>
    <?php
  }
}

add_action('yith_ywgc_show_gift_card_amount_selection', function(){
  ?> 
  <p class="gift__note">Можно ввести любую сумму от 1500 до 30 000 ₽</p>
  <div class="gift__amounts">
      <p>1500<span>₽</span></p>
      <p>2000<span>₽</span></p>
      <p>3000<span>₽</span></p>
      <p>4000<span>₽</span></p>
      <p>5000<span>₽</span></p>
      <p>10000<span>₽</span></p>
      <p>15000<span>₽</span></p>
      <p>20000<span>₽</span></p>
      <p>25000<span>₽</span></p>
      <p>30000<span>₽</span></p>
  </div>
  <?php
},10);

// add_action('yith_gift_cards_template_before_add_to_cart_form', function (){
//   echo '<div class="page-popup__container page-popup__container_giftcard d-none">
//   <span class="page-popup__close"><svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.00045 24L24 0.999999M23.9995 24L0.999999 1" stroke="currentColor" stroke-miterlimit="10"></path></svg></span>';
// }, 10);

// add_action('yith_gift_cards_template_after_add_to_cart_form', function (){
//   echo '</div>';
// }, 10);

add_filter('ywgc_recipient_name_label', function (){
  return 'Для кого подарок';
});
add_filter('ywgc_sender_name_label', function (){
  return 'От кого подарок';
});

add_filter('ywgc_edit_message_label', function (){
  return 'Поздравление';
});
add_filter('yith_wcgc_manual_amount_option_text', function (){
  return '';
});
add_filter('ywgc_add_to_cart_button_text', function (){
  return 'Перейти к оплате';
});

// add_filter('ywgc_minimal_amount_error_text',function (){
//   return 'Минимальная стоимость подарочного сертификата';
// });

add_filter( 'woocommerce_add_to_cart_redirect', function( $url ) {
    if ( isset( $_REQUEST['buy_now'] ) && '1' === $_REQUEST['buy_now'] ) {
        return wc_get_checkout_url();
    }
    return $url;
} );


/*--------------------------------------------------------------
#GIFT CARD PAGE
--------------------------------------------------------------*/

/**
 * Получить объект подарочной карты по её номеру
 *
 * @param string $code Номер подарочной карты
 * @return YWGC_Gift_Card_Premium|null
 */
function mytheme_get_giftcard_by_code( $code ) {
    if ( empty( $code ) ) {
        return null;
    }

    $query = new WP_Query( array(
        'post_type'      => 'gift_card',     // тип поста для YITH карт
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'post_title' => $code,
        'fields' => 'ids',
    ) );

    if ( ! empty( $query->posts ) ) {
        return $query->posts[0];
    }

    return null;
}


