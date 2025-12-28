<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//СПОСОБЫ ДОСТАВКИ

//задаем по умолчанию срочную доставку
add_action('wp_head','plnt_set_initials');

function plnt_set_initials() {
    date_default_timezone_set('Europe/Moscow');
    $hour = date("H");

    if ($hour >= 18 && $hour <20) {
        WC()->session->set('isUrgent', '0' ); //0
    } else {
        WC()->session->set('isUrgent', '1' ); //1
    }

    WC()->session->set('isLate', '0' );
    WC()->session->set('is_courier_deliv_flag', '0' );

    if ( plnt_is_backorder() || plnt_is_treez_backorder()) {
      WC()->session->set('isUrgent', '0' );
      WC()->session->set('isLate', '0' );
    }
};

//for dev

//add_action('woocommerce_review_order_before_shipping','plnt_check');
//add_action('wp_head','plnt_check');

function plnt_check() {
  
    global $local_pickup;
    global $delivery_pochta;
    // echo '<br>';
    $packages = WC()->shipping()->get_packages();
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
    //print_r( $packages);
    // echo '<br>';
    //echo $delivery_pochta;
    echo $chosen_methods[0];
    echo '<br>';

    // if($local_pickup === $chosen_methods[0]) {
    //     echo 'hi';
    //     WC()->session->set('isLate', '0' );  
    // }
    // $isbackorders = plnt_is_backorder();
    // echo 'isback '.$isbackorders.'  ';
    echo 'isUrgent '.(WC()->session->get('isUrgent' )).'  ';
    
    // echo 'hiAjax '.(WC()->session->get('hiAjax' )).'  ';
    // echo 'hiInit '.(WC()->session->get('hiInit' )).'  ';
    // echo 'isback2 '.(WC()->session->get('isBackorder' )).'  ';
    echo 'isLate '.(WC()->session->get('isLate' )).'  ';
    echo '<br>';
    // date_default_timezone_set('Europe/Moscow');
    // $hour = date("H");
    // if ( is_checkout() && ($hour<18 || $hour>=20)) {
    //     echo $hour;
    // }

    $delivery_murkup = get_delivery_markup();
    //print_r ($delivery_murkup);

    //echo 'isCourier '.(WC()->session->get('is_courier_deliv_flag' )).'  ';

}

// срочная доставка
add_action( 'wp_ajax_get_urgent_shipping', 'plnt_get_urgent_shipping' );
add_action( 'wp_ajax_nopriv_get_urgent_shipping', 'plnt_get_urgent_shipping' );
function plnt_get_urgent_shipping() {
  // Безопасная обработка значений
    $is_urgent = sanitize_text_field( $_POST['isUrgent'] ?? '' );
    $is_late   = sanitize_text_field( $_POST['isLate'] ?? '' );

    // Запись в сессию WooCommerce
    WC()->session->set( 'isUrgent', $is_urgent );
    WC()->session->set( 'isLate', $is_late );

    // Можно вернуть успех для отладки
    wp_send_json_success([
        'isUrgent' => $is_urgent,
        'isLate' => $is_late,
        'message' => 'Флаги обновлены'
    ]);

  //wp_die(); // (required)
}

add_action( 'woocommerce_checkout_update_order_review', 'plnt_refresh_shipping_methods', 10, 1 );
function plnt_refresh_shipping_methods( $post_data ){
  // Если хотя бы один из флагов равен '1', сбрасываем кэш способов доставки
  $has_flag = ( WC()->session->get('isUrgent') === '1' ) || ( WC()->session->get('isLate') === '1' );

  $bool = ! $has_flag;

  // Обязательно для корректной перерасчётки способов доставки
  foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
      WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
  }
  WC()->cart->calculate_shipping();
}


/* выбираем способ доставки в зависимости от условий*/

add_filter( 'woocommerce_package_rates', 'plnt_shipping_conditions', 25, 2 );
 
function plnt_shipping_conditions( $rates, $package ) {

    //переменные
    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;

    global $delivery_long_dist;
    global $delivery_pochta;

    $late_markup_delivery = carbon_get_theme_option('late_markup_delivery');

    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    $delivery_murkup = get_delivery_markup();
    $delivery_markup_in_mkad = 0;
    $delivery_markup_out_mkad = 0;

    $isUrgentCourierTariff = carbon_get_theme_option('is_urgent_courier_tariff') == '1';
    $isHolidayCourierTariff = carbon_get_theme_option('is_holiday_courier_tariff') == '1';
    $isSmallHolidayCart = WC()->cart->subtotal < 5000;
    $isSmallHolidayTariffOn = $isHolidayCourierTariff && $isSmallHolidayCart;

    if ($delivery_murkup) {
      $delivery_markup_in_mkad = $delivery_murkup['in_mkad'];
      $delivery_markup_out_mkad = $delivery_murkup['out_mkad'];

      // //проверяем срочную доставку и позднюю доставку

      if (WC()->session->get('isLate' ) === '1') {
        $delivery_markup_in_mkad =  $delivery_markup_in_mkad + $late_markup_delivery;
        $delivery_markup_out_mkad =  $delivery_markup_out_mkad + $late_markup_delivery;
      }

      if (WC()->session->get('isUrgent' ) === '1') {
          $delivery_markup_in_mkad =  $delivery_markup_in_mkad + $delivery_murkup['urg'];
          $delivery_markup_out_mkad =  $delivery_markup_out_mkad + $delivery_murkup['urg'];
      }
    } 

    // set markup
    if ($delivery_murkup) {
      foreach ($rates as $rate) {
          if ($rate->id == $delivery_inMKAD){
              $rate->cost = $rate->cost + $delivery_markup_in_mkad;
          } else if ($rate->id == $delivery_outMKAD){
              $rate->cost = $rate->cost + $delivery_markup_out_mkad;
          }
      }
    }

    /* Срочная доставка по тарифу курьерской службы*/
    if ($isUrgentCourierTariff) {
      if (WC()->session->get('isUrgent' ) === '1') {
        foreach ($rates as $rate) {
          if ($rate->id == $delivery_inMKAD || $rate->id == $delivery_outMKAD){
              $rate->cost = 0;
          }
        }
      }
    }

    /* Доставка по тарифу курьерской службы для маленьких заказов на праздники*/
    if ($isSmallHolidayTariffOn) {
      if (WC()->session->get('isUrgent' ) === '0') {
        foreach ($rates as $rate) {
          if ($rate->id == $delivery_inMKAD || $rate->id == $delivery_outMKAD){
              $rate->cost = 0;
          }
        }
      }
    }

    /*ПОЧТА РОССИИ*/
    
    if (check_if_large_delivery()) {
        unset( $rates[ $delivery_pochta ] );
    }
 
    global $plants_cat_id;
    $plantsQty = 0;
    $notPlantsInCartQty = 0;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		    $parentCatId = check_category($_product);
        if ($parentCatId == $plants_cat_id ){
            $plantsQty =  $plantsQty + $cart_item['quantity'];
        } else {
            $notPlantsInCartQty++;
        }
    }

    foreach ($rates as $rate) {
        if ($rate->id == $delivery_pochta){
            $rate->cost = $rate->cost * $plantsQty;
        }
    }      

	return $rates;
}

//убираем способ онлайн-оплаты, если маленькая сумма заказа или далекая доставка или Срочная доставка по тарифу курьерской службы
add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_small_order' );

function plnt_disable_payment_small_order( $available_gateways ) {
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');
    // $isUrgentCourierTariff = true;
    $isUrgentCourierTariff = carbon_get_theme_option('is_urgent_courier_tariff') == '1';
    $isHolidayCourierTariff = carbon_get_theme_option('is_holiday_courier_tariff') == '1';
    $isSmallHolidayCart = WC()->cart->subtotal < 5000;
    $isSmallHolidayTariffOn = $isHolidayCourierTariff && $isSmallHolidayCart;

    global $delivery_long_dist;
    global $delivery_pochta;
    global $delivery_inMKAD;
    global $delivery_outMKAD;

    if( is_admin() ) {
		  return $available_gateways;
	  }

    if( is_wc_endpoint_url( 'order-pay' ) ) {
      return $available_gateways;
    }

    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
    WC()->session->set('is_courier_deliv_flag', '0' );

    if (isset($chosen_methods)) {
      // дальняя доставка
      if ( $delivery_long_dist == $chosen_methods[0]) {
          unset( $available_gateways['tbank'] ); //to be updated - change to tbank
          WC()->session->set('is_courier_deliv_flag', '1' );
      }

      // почта России
      if ( $delivery_pochta == $chosen_methods[0]) {
          unset( $available_gateways['tbank'] ); //to be updated - change to tbank
      }

      //Срочная доставка по тарифу курьерской службы

      if ($delivery_inMKAD == $chosen_methods[0] || $delivery_outMKAD == $chosen_methods[0]) {
        if ($isUrgentCourierTariff && WC()->session->get('isUrgent' ) === '1') {
            unset( $available_gateways['tbank'] ); //to be updated - change to tbank
            WC()->session->set('is_courier_deliv_flag', '1' );
        }
        if ($isSmallHolidayTariffOn && WC()->session->get('isUrgent' ) === '0') {
            unset( $available_gateways['tbank'] ); //to be updated - change to tbank
            WC()->session->set('is_courier_deliv_flag', '1' );
        }
      }
    }


    return $available_gateways;
}


//сохраняем в заказ флаг доставки по тарифу курьерской службы

add_action('woocommerce_checkout_create_order', function( $order, $data ) {

    // Берём из сессии (замени ключ на свой)
    $value = WC()->session ? WC()->session->get('is_courier_deliv_flag') : null;

    if ( $value !== null && $value !== '' ) {
        // Сохраняем в мета заказа
        $order->update_meta_data('_is_courier_deliv_flag', $value);
    }

}, 20, 2);
