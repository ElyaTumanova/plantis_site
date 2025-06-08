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

// function set_gift_card_hook() {
//   return  'plnt_woocommerce_checkout_gift_card';
// }

// remove_action( 'woocommerce_review_order_before_order_total', 'show_gift_card_amount_on_cart_totals');

// add_action('woocommerce_checkout_order_review','show_gift_card_amount_on_cart_totals',25);

// add_filter('ywgc_remove_gift_card_text', function (){
//   return 'Удалить';
// });
add_filter('ywgc_remove_gift_card_text', function (){
  return '<svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.07921 1.5H12.9125M1.24609 5H18.7461M16.8016 5L16.1196 16.3957C16.0173 17.9301 15.9662 18.6973 15.6348 19.279C15.343 19.7912 14.9029 20.2029 14.3725 20.46C13.77 20.752 13.0011 20.752 11.4633 20.752H8.52846C6.99065 20.752 6.22174 20.752 5.61926 20.46C5.08883 20.2029 4.64874 19.7912 4.35697 19.279C4.02557 18.6973 3.97442 17.9301 3.87213 16.3957L3.19054 5M8.05143 10.1389V15M11.9403 10.1389V15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
});

