<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

add_action('yith_ywgc_after_gift_card_generation_save', 'create_gift_card_page');

//
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

//
add_filter( 'ywgc_gift_card_code_form_checkout_hook', function (){
  return 'plnt_woocommerce_checkout_gift_card';
});

//remove_action( 'woocommerce_review_order_before_order_total', 'show_gift_card_amount_on_cart_totals');

// add_action('woocommerce_checkout_order_review','show_gift_card_amount_on_cart_totals',25);

add_filter('ywgc_remove_gift_card_text', function (){
  return '×';
});


add_filter('yith_ywgc_cart_totals_gift_card_label',function (){
  return 'Подарочный сертификат';
});