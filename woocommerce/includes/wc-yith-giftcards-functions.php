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

/*--------------------------------------------------------------
#EMAILS
--------------------------------------------------------------*/

add_filter( 'yith_ywgc_email_automatic_cart_discount_url', 'change_email_discount_link', 10, 3);

function change_email_discount_link($apply_discount_url, $args, $gift_card) {
    $apply_discount_url = 'http://new.plantis.shop/lalalal';
    return $apply_discount_url;
}


//
add_action ('plnt_gift_card_email_after_preview', 'add_email_gift_card_link');

function add_email_gift_card_link($gift_card) {
  $giftcard_link = 'http://new.plantis.shop/gift-card-'.$gift_card->gift_card_number;
  echo $giftcard_link;
}

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


