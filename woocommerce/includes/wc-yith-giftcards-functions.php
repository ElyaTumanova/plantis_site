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
#CARD BALANCE CHECK
#PRICE FIX
--------------------------------------------------------------*/

/*--------------------------------------------------------------
#GENERAL DEV
--------------------------------------------------------------*/

// регистрируем гет парамтер для подарочной карты
add_filter('query_vars', function ($vars) {
    $vars[] = 'gcnum';
    return $vars;
});

add_action( 'init', function() {

    // Наш кастомный сабмит
    if ( empty( $_POST['giftcard_pay_submit'] ) ) {
        return;
    }

    // --- 1. Сумма подарочной карты ---

    $amount_raw = $_POST['giftcard_amount'] ?? '';

    // защита от массива, чтобы не ловить Array to string conversion
    if ( is_array( $amount_raw ) ) {
        $amount_raw = reset( $amount_raw );
    }

    $amount = floatval( $amount_raw );
    if ( $amount <= 0 ) {
        return;
    }

    // --- 2. ID товара gift-card ---

    $product_id_raw = $_POST['giftcard_product_id'] ?? 0;

    if ( is_array( $product_id_raw ) ) {
        $product_id_raw = reset( $product_id_raw );
    }

    $product_id = absint( $product_id_raw );
    if ( ! $product_id ) {
        return;
    }

    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        return;
    }

    // --- 3. Платёжный метод Т-банка ---

    // именно ID шлюза, обычно совпадает с тем, что в классе WC_Gateway_TBank::$id
    $payment_method_id = 'tbank';


    // --- 4. Создаём заказ ---

    $order = wc_create_order( [
        'status'      => 'pending',
        'created_via' => 'instant_giftcard',
    ] );

    if ( is_wp_error( $order ) ) {
        return;
    }

    // --- 5. Добавляем товар Подарочная карта с нужной суммой ---

    // $amount тут в валюте магазина (рубли и т.п.), Woo сам потом посчитает копейки
    $item_id = $order->add_product( $product, 1, [
        'subtotal' => $amount,
        'total'    => $amount,
    ] );

    // на всякий случай проверим, что строка добавилась
    if ( ! $item_id ) {
        return;
    }

    // --- 6. Проставляем контакты (важно не передать массив!) ---

    // E-mail
    if ( isset( $_POST['billing_email'] ) ) {
        $email = $_POST['billing_email'];

        if ( is_array( $email ) ) {
            $email = reset( $email );
        }

        $email = sanitize_email( $email );
        if ( ! empty( $email ) ) {
            $order->set_billing_email( $email );
        }
    }

    // Телефон
    if ( isset( $_POST['billing_phone'] ) ) {
        $phone = $_POST['billing_phone'];

        if ( is_array( $phone ) ) {
            $phone = reset( $phone );
        }

        $phone = sanitize_text_field( $phone );
        if ( ! empty( $phone ) ) {
            $order->set_billing_phone( $phone );
        }
    }

    // --- 7. Привязываем способ оплаты Т-банк ---

    $order->set_payment_method( $payment_method_id );
    // При желании можно подписать:
    // $order->set_payment_method_title( 'Оплата через Т-банк' );

    // --- 8. Пересчитываем суммы ---

    // если у подарочной карты нет налогов и доставки — этого достаточно
    $order->calculate_totals( false ); // без перерасчёта налогов
    $order->set_total( $amount );      // на всякий случай жёстко выставляем тотал

    $order->save();

    // --- 9. Редиректим на страницу оплаты этого заказа ---

    $payment_url = $order->get_checkout_payment_url();
    wp_safe_redirect( $payment_url );
    exit;
} );




/*--------------------------------------------------------------
#EMAILS
--------------------------------------------------------------*/
//изменили текст в письме
add_action('ywgc_gift_cards_email_before_preview', function ($introductory_text, $gift_card){
  ?>
  <p style="margin-bottom:15px;text-align:left;margin:0 0 16px">
    Спасибо за покупку подарочного сертификата! Вот как его подарить. <br> <br> 
    Он доступен в цифровом формате. Просто отправьте ссылку человеку, которому вы его дарите. Все детали сертификата можно найти по той же ссылке.
  </p>
  <?php
}, 10, 2);

add_filter('ywgc_gift_cards_email_before_preview_text', function (){
  return '';
});
/**
 * Вывод изображения, номера и суммы подарочной карты в письме
 */
add_action( 'ywgc_gift_cards_email_before_preview_gift_card_param', function( $gift_card ) {

    if ( ! is_object( $gift_card ) ) {
        return;
    }

    // --- Изображение подарочной карты ---
    // Получаем картинку, выбранную в плагине. Может быть ID или URL.
    // $image_url = '';
    // if ( isset( $gift_card->product_id ) ) {
    //     $product_id = (int) $gift_card->product_id;
    // } elseif ( method_exists( $gift_card, 'get_product_id' ) ) {
    //     $product_id = (int) $gift_card->get_product_id();
    // }

    // if ( $product_id ) {
    //     $thumb = get_the_post_thumbnail_url( $product_id, 'full' );
    //     if ( $thumb ) {
    //         $image_url = $thumb;
    //     }
    // }

    $image_url = get_template_directory_uri().'/images/gift-card/gc_soc.jpg';

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
  $giftcard_link = site_url().'/gift-card?gcnum='.$gift_card->gift_card_number;
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

add_action('woocommerce_email_footer', function($email) {
  ?>
  <p style="margin:30px 0 10px; font-weight: bold;">Наши контакты</p>
  <p style="margin: 0 0 10px;"><a style="color: inherit;font-weight:normal;text-decoration:none" href="tel:+78002015790">8 800 201 57 90</a></p>
  <p style="margin: 0 0 10px;"><a style="color: inherit;font-weight:normal;text-decoration:none" href="tel:+79647687944">8 964 768 79 44</a></p>
  <p style="margin: 0 0 10px;"><a style="color: inherit;font-weight:normal;text-decoration:none" href="mailto:INFO@PLANTIS.SHOP">INFO@PLANTIS.SHOP</a></p>
  <p style="margin:40px 0 10px; font-weight: bold; color: #146F41">Ваш Plantis</p>
  <?php
}, 10, 1);


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


//изменили плейсхолдер для поля кода карты
add_filter('ywgc_checkout_box_placeholder', function (){
  return 'Номер подарочного сертификата';
});

//изменили название для поля введенного кода карты
add_filter('yith_ywgc_cart_totals_gift_card_label', function (){
  return 'Сертификат';
});

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
  <p class="gift__note">Можно ввести любую сумму от 1500 до 30&nbsp;000&nbsp;₽</p>
  <div class="gift__amounts gift-swiper-wrap">
      <div class="swiper-wrapper">
        <p class="swiper-slide" data-amount="1500">1500<span>₽</span></p>
        <p class="swiper-slide" data-amount="2000">2000<span>₽</span></p>
        <p class="swiper-slide" data-amount="3000">3000<span>₽</span></p>
        <p class="swiper-slide" data-amount="4000">4000<span>₽</span></p>
        <p class="swiper-slide" data-amount="5000">5000<span>₽</span></p>
        <p class="swiper-slide" data-amount="10000">10000<span>₽</span></p>
        <p class="swiper-slide" data-amount="15000">15000<span>₽</span></p>
        <p class="swiper-slide" data-amount="20000">20000<span>₽</span></p>
        <p class="swiper-slide" data-amount="25000">25000<span>₽</span></p>
        <p class="swiper-slide" data-amount="30000">30000<span>₽</span></p>
      </div>
  </div>
  <?php
},10);


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
});


/*--------------------------------------------------------------
#GIFT CARD PAGE
--------------------------------------------------------------*/

// выносим фильтр в именованную функцию
function plnt_where_exact_title( $where, $query ) {
    global $wpdb;
    $exact_title = $query->get( 'plnt_exact_title' );
    if ( $exact_title ) {
        $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title = %s", $exact_title );
    }
    return $where;
}

function plnt_get_giftcard_by_code( $code ) {
    // строгая валидация
    $code = strtoupper( sanitize_text_field( (string) $code ) );
    if ( $code === '' || ! preg_match( '/^[A-Z0-9]{4}(?:-[A-Z0-9]{4}){3}$/', $code ) ) {
        return null;
    }

    add_filter( 'posts_where', 'plnt_where_exact_title', 10, 2 );

    $q = new WP_Query( [
        'post_type'       => 'gift_card',
        'post_status'     => 'publish',
        'posts_per_page'  => 1,
        'fields'          => 'ids',
        // передаём значение для фильтра БЕЗОПАСНО
        'plnt_exact_title'=> $code,
        'no_found_rows'   => true,
        'ignore_sticky_posts' => true,
    ] );

    remove_filter( 'posts_where', 'plnt_where_exact_title', 10 );

    return ! empty( $q->posts ) ? (int) $q->posts[0] : null;
}


/*--------------------------------------------------------------
#PRICE FIX
--------------------------------------------------------------*/
// в плагине Т банка
// plugins/tbank-woocommerce/tbank/SupportPaymentTBank.php
// заменить в двух местах
// $price = $item->get_product()->get_price();
// на 
//$price = $item->get_product()->get_price();
// if ( $item->get_product()->get_type() === 'gift-card' || $price === '' || $price === null ) {

//     $qty  = max(1, (float) $item->get_quantity());
//     $line_total = (float) $item->get_total();
//     $line_tax   = (float) $item->get_total_tax();

//     $price = ($line_total + $line_tax) / $qty;
// }


