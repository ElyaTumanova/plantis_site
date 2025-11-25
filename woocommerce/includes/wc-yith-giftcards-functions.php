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


// создание ссылки на оплату
add_action( 'admin_post_nopriv_giftcard_pay', 'handle_giftcard_pay' );
add_action( 'admin_post_giftcard_pay',        'handle_giftcard_pay' );

function handle_giftcard_pay() {

    // 1. Сумма сертификата
    $amount_raw = $_POST['giftcard_amount'] ?? '';
    if ( is_array( $amount_raw ) ) {
        $amount_raw = reset( $amount_raw );
    }
    $amount = floatval( $amount_raw );
    if ( $amount <= 0 ) {
        wp_die( 'Некорректная сумма.' );
    }

    // 2. Товар gift-card
    $product_id_raw = $_POST['giftcard_product_id'] ?? 0;
    if ( is_array( $product_id_raw ) ) {
        $product_id_raw = reset( $product_id_raw );
    }
    $product_id = absint( $product_id_raw );
    if ( ! $product_id ) {
        wp_die( 'Не указан товар подарочной карты.' );
    }

    $product = wc_get_product( $product_id );
    if ( ! $product ) {
        wp_die( 'Товар подарочной карты не найден.' );
    }

    // 3. Данные из твоей формы YITH

    // 3.1 Email (и плательщика, и получателя – один и тот же)
    $recipient_emails = $_POST['ywgc-recipient-email'] ?? [];
    if ( is_array( $recipient_emails ) ) {
        $recipient_email = reset( $recipient_emails );
    } else {
        $recipient_email = $recipient_emails;
    }
    $recipient_email = sanitize_email( $recipient_email );

    if ( ! $recipient_email ) {
        wp_die( 'Укажите корректный e-mail.' );
    }

    // 3.2 Имя получателя
    $recipient_names = $_POST['ywgc-recipient-name'] ?? [];
    if ( is_array( $recipient_names ) ) {
        $recipient_name = reset( $recipient_names );
    } else {
        $recipient_name = $recipient_names;
    }
    $recipient_name = sanitize_text_field( $recipient_name );

    // 3.3 Сообщение
    $message_raw = $_POST['ywgc-edit-message'] ?? '';
    if ( is_array( $message_raw ) ) {
        $message_raw = reset( $message_raw );
    }
    $message = wp_kses_post( $message_raw );

    // 3.4 Имя отправителя
    $sender_raw = $_POST['ywgc-sender-name'] ?? '';
    if ( is_array( $sender_raw ) ) {
        $sender_raw = reset( $sender_raw );
    }
    $sender_name = sanitize_text_field( $sender_raw );

    // 4. Создаём заказ
    $order = wc_create_order( [
        'status'      => 'pending',
        'created_via' => 'giftcard_pay_button',
    ] );

    if ( is_wp_error( $order ) ) {
        wp_die( 'Не удалось создать заказ.' );
    }

    // 5. Добавляем товар с нужной суммой
    $item_id = $order->add_product( $product, 1, [
        'subtotal' => $amount,
        'total'    => $amount,
    ] );

    // 5.1. Мета YITH для строки заказа – чтобы в заказе выводились нужные поля

    if ( $item_id ) {

        // Сумма карты (Amount)
        wc_add_order_item_meta( $item_id, '_ywgc_amount', $amount );

        // "Ручная" сумма (Manual amount = 1)
        wc_add_order_item_meta( $item_id, '_ywgc_is_manual_amount', 1 );

        // Digital: 1 (виртуальная карта, отправляется по e-mail)
        wc_add_order_item_meta( $item_id, '_ywgc_is_digital', 1 );

        // Email получателя (хоть ты его не показывал в списке, но он нужен плагину)
        wc_add_order_item_meta( $item_id, '_ywgc_recipient_email', $recipient_email );

        // Recipient's name
        if ( $recipient_name ) {
            wc_add_order_item_meta( $item_id, '_ywgc_recipient_name', $recipient_name );
        }

        // Sender's name (от кого)
        if ( $sender_name ) {
            wc_add_order_item_meta( $item_id, '_ywgc_sender_name', $sender_name );
        }

        // Сообщение
        if ( $message ) {
            wc_add_order_item_meta( $item_id, '_ywgc_message', $message );
        }

        // Design type: default
        wc_add_order_item_meta( $item_id, '_ywgc_design_type', 'default' );

        // Has custom design: 1
        wc_add_order_item_meta( $item_id, '_ywgc_has_custom_design', 1 );

        // Delivery notification: off
        wc_add_order_item_meta( $item_id, '_ywgc_delivery_notification', 'off' );

        // Версия плагина (можно захардкодить, можно взять из константы, если есть)
        wc_add_order_item_meta( $item_id, '_ywgc_version', '4.26.0' );
    }

    // 6. Биллинг заказчика (email = email получателя)
    $order->set_billing_email( $recipient_email );

    // 7. Способ оплаты — tbank
    $order->set_payment_method( 'tbank' );

    // 8. Пересчитываем суммы
    $order->calculate_totals();
    $order->save();

    // 9. Редирект на страницу оплаты
    $url = $order->get_checkout_payment_url();
    if ( ! $url ) {
        wp_die( 'Не удалось получить URL оплаты.' );
    }

    wp_safe_redirect( $url );
    exit;
}

/**
 * Получить объект заказа по объекту gift card YITH.
 */
function plantis_get_order_from_yith_gift_card( $gift_card ) {
    $order_id = 0;

    if ( ! $gift_card ) {
        return false;
    }

    // Часто у объекта есть метод get_order_id()
    if ( method_exists( $gift_card, 'get_order_id' ) ) {
        $order_id = $gift_card->get_order_id();
    } elseif ( isset( $gift_card->order_id ) ) { // или публичное свойство
        $order_id = $gift_card->order_id;
    }

    if ( ! $order_id ) {
        return false;
    }

    $order = wc_get_order( $order_id );
    return $order instanceof WC_Order ? $order : false;
}

add_filter( 'ywgc_send_gift_card_code_by_default', 'plantis_control_yith_gift_card_sending_default', 10, 2 );

function plantis_control_yith_gift_card_sending_default( $send, $gift_card ) {

    $order = plantis_get_order_from_yith_gift_card( $gift_card );
    if ( ! $order ) {
        return $send; // ничего не знаем — не трогаем
    }

    // ❗ Если хочешь это поведение только для наших "кнопочных" заказов:
    // if ( $order->get_created_via() !== 'giftcard_pay_button' ) {
    //     return $send;
    // }

    // Если заказ ещё НЕ выполнен — запрещаем отправку
    if ( ! $order->has_status( 'completed' ) ) {
        return false;
    }

    // Если заказ ВЫПОЛНЕН — оставляем как хочет плагин (обычно true)
    return $send;
}

add_filter( 'yith_wcgc_send_now_gift_card_to_custom_recipient', 'plantis_control_yith_gift_card_sending_custom', 10, 2 );

function plantis_control_yith_gift_card_sending_custom( $send, $gift_card ) {

    $order = plantis_get_order_from_yith_gift_card( $gift_card );
    if ( ! $order ) {
        return $send;
    }

    // Только наши заказы? Раскомментируй, если нужно ограничить:
    // if ( $order->get_created_via() !== 'giftcard_pay_button' ) {
    //     return $send;
    // }

    if ( ! $order->has_status( 'completed' ) ) {
        return false;
    }

    return $send;
}

/**
 * Отправляем все подарочные карты заказа при переходе в статус completed.
 */
add_action( 'woocommerce_order_status_completed', 'plantis_send_gift_cards_on_completed', 20 );

function plantis_send_gift_cards_on_completed( $order_id ) {

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    // Если нужно – можно ограничить только нашими "кнопочными" заказами:
    if ( $order->get_created_via() !== 'giftcard_pay_button' ) {
        return;
    }

    $order_items = $order->get_items();

    $gift_card_ids = [];

    // Собираем ID подарочных карт из позиций заказа
    foreach ( $order->get_items() as $item_id => $item ) {
        // // ID товара
        // $product_id = $item->get_product_id();

        // Объект товара
        $product = $item->get_product();

         // Если товара нет — сразу к следующему
        if ( ! $product ) {
            continue;
        }

        // Тип товара (simple, variable, gift-card и т.д.)
        $product_type = $product->get_type();

        // Нас интересуют только товары типа "gift-card"
        if ( 'gift-card' !== $product_type ) {
            continue;
        }

        $gift_card_post_ids = (array) $item->get_meta( '_ywgc_gift_card_post_id', true );
        $gift_card_post_id  = isset( $gift_card_post_ids[0] ) ? $gift_card_post_ids[0] : null;

        if ( $gift_card_post_id ) {
            $gift_card_ids[] = $gift_card_post_id;
        }
    }


    if ( empty( $gift_card_ids ) ) {
        return;
    }

    // Класс, который умеет send_gift_card_email()
    if ( ! class_exists( 'YITH_YWGC_Emails_Premium' ) ) {
        return;
    }

    // Берём singleton из твоего класса
    $emails = YITH_YWGC_Emails_Premium::get_instance();

    foreach ( $gift_card_ids as $gift_card_id ) {
        if ( method_exists( $emails, 'send_gift_card_email' ) ) {
            // $only_new = true — не отправит повторно, если карта уже была отослана
            $emails->send_gift_card_email( $gift_card_id, true );
        }
    }
}


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


//меняем описание способа оплаты при оплате заказа для подарочной карты

add_filter('woocommerce_gateway_description', function($description, $gateway_id) {

  if (! is_wc_endpoint_url('order-pay') || is_not_gift_card_order_pay()) {
    return $description;
  }

  if ( $gateway_id == 'tbank' ) {
    return '';
  } else if ($gateway_id == 'cheque') {
    return 'Мы можем выставить Вам счет для оплаты банковским переводом. Наш менеджер свяжется с Вами для выставления счета.';
  } else {
    return $description;
  }

}, 20, 2);

//меням сообщение "Вы платите за заказ гостя. Продолжайте оплату, только если вы знаете про этот заказ"

add_filter('woocommerce_add_error', function($message) {

    // только на странице оплаты заказа
    if ( ! is_wc_endpoint_url('order-pay') ) {
        return $message;
    }

    // ловим именно это предупреждение (на RU-сайте достаточно фразы-ключа)
    if ( is_string($message) && mb_stripos($message, 'Вы платите за заказ гостя') !== false ) {
        return 'Обратите внимание: e-mail, указанный в заказе, не совпадает с e-mail вашего аккаунта. Если всё верно, продолжайте оплату.';
    }

    return $message;

}, 20);





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


// add_filter('ywgc_recipient_name_label', function (){
//   return 'Для кого подарок';
// });
// add_filter('ywgc_sender_name_label', function (){
//   return 'От кого подарок';
// });

// add_filter('ywgc_edit_message_label', function (){
//   return 'Поздравление';
// });
// add_filter('yith_wcgc_manual_amount_option_text', function (){
//   return '';
// });
// add_filter('ywgc_add_to_cart_button_text', function (){
//   return 'Перейти к оплате';
// });

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


