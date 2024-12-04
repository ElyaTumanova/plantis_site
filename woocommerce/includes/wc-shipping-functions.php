<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//СПОСОБЫ ДОСТАВКИ
// СТОИМОСТЬ ДОСТАВКИ ПО ДАТЕ

add_action( 'wp_ajax_get_urgent_shipping', 'plnt_get_urgent_shipping' );
add_action( 'wp_ajax_nopriv_get_urgent_shipping', 'plnt_get_urgent_shipping' );
function plnt_get_urgent_shipping() {
    if ( $_POST['isUrgent'] === '1'){
        WC()->session->set('isUrgent', '1' );
    } else {
        WC()->session->set('isUrgent', '0' );
    }
    die(); // (required)
}

add_action( 'woocommerce_checkout_update_order_review', 'plnt_refresh_shipping_methods_for_urgent', 10, 1 );
function plnt_refresh_shipping_methods_for_urgent( $post_data ){
    $bool = true;

    if ( WC()->session->get('isUrgent' ) === '1' )
        $bool = false;

    // Mandatory to make it work with shipping methods
    foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
        WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
    }
    WC()->cart->calculate_shipping();
}


add_filter( 'woocommerce_package_rates', 'plnt_shipping_rates_for_urgent', 100, 2 );
function plnt_shipping_rates_for_urgent( $rates, $package ) {

    //переменные
    global $delivery_courier;
    $urgent_delivery_markup = carbon_get_theme_option('urgent_delivery_markup');

    if ( isset( $rates[ $delivery_courier ] ) ) { 
        return $rates;
    } else {
	    if (WC()->session->get('isUrgent' ) === '1') {
            foreach( $rates as $rate) {
                if ( 'local_pickup' === $rate->method_id ) {
                    $rate->cost = $rate->cost;
                }
                else if ('free_shipping' === $rate->method_id) {
                    $rate->cost = $rate->cost ;
                } else {
                    $rate->cost = $rate->cost + $urgent_delivery_markup;
                }
                
            }
        }
    }

    echo $rates;
    return $rates;
}

/*СТОИМОСТЬ ДОСТАВКИ ПО ВЕСУ*/

add_filter( 'woocommerce_package_rates', 'truemisha_shipping_by_weight', 30, 2 );
 
function truemisha_shipping_by_weight( $rates, $package ) {

    //переменные

    $large_delivery_markup_in_mkad = carbon_get_theme_option('large_delivery_markup_in_mkad');

    if ($large_delivery_markup_in_mkad) {
        // вес товаров в корзине
        $cart_weight = WC()->cart->cart_contents_weight;
    
        if ($cart_weight >= 11) {
           foreach( $rates as $rate) {
            
            if ( 'local_pickup' === $rate->method_id ) {
                $rate->cost = $rate->cost;
            }
            else if ('free_shipping' === $rate->method_id) {
                $rate->cost = $rate->cost ;
            } else {
                $rate->cost = $rate->cost + $large_delivery_markup_in_mkad;
            }
           }
        }
    }

	return $rates;
}

/* скрываем лишние способы доставки если доступна доставка бесплатная*/

add_filter( 'woocommerce_package_rates', 'new_truemisha_remove_shipping_method', 20, 2 );
 
function new_truemisha_remove_shipping_method( $rates, $package ) {

    //переменные
    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;

    global $delivery_free;
    global $delivery_courier;
 
	// удаляем способ доставки, если доступна бесплатная
	if ( isset( $rates[ $delivery_free ] ) ) { 
	    unset( $rates[ $delivery_inMKAD ] );
// 		unset( $rates[ $delivery_outMKAD ] );
		unset( $rates[ $delivery_courier ] );
	}
 
	return $rates;
}

/* стоимость доставки в зависимости от суммы заказа*/

//убираем способ онлайн-оплаты, если маленькая сумма заказа или далекая доставка
add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_small_order' );

function plnt_disable_payment_small_order( $available_gateways ) {
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    global $delivery_courier;
    global $delivery_long_dist;

    if( is_admin() ) {
		return $available_gateways;
	}

    if( is_wc_endpoint_url( 'order-pay' ) ) {
		return $available_gateways;
	}

    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    // стоимость товаров в корзине
    if (WC()->cart->subtotal < $min_small_delivery && $delivery_courier == $chosen_methods[0]) {
        unset( $available_gateways['tinkoff'] ); //to be updated - change to tinkoff
    }

    // дальняя доставка
    if ( $delivery_long_dist == $chosen_methods[0]) {
        unset( $available_gateways['tinkoff'] ); //to be updated - change to tinkoff
    }

    return $available_gateways;
}
	
add_filter( 'woocommerce_package_rates', 'new_truemisha_remove_shipping_on_price', 25, 2 );
 
function new_truemisha_remove_shipping_on_price( $rates, $package ) {

    //переменные
    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;
    global $delivery_courier;
    global $delivery_long_dist;

    $small_delivery_markup = carbon_get_theme_option('small_delivery_markup');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');

	// если сумма всех товаров в корзине меньше min_small_delivery, увеличиваем стоимость доставки
    if ($small_delivery_markup) { //если наценка не задана, то будет запущен второй вариант алгоритма с отключением способов доставки
        // отключаем опцию доставики по тарифам курьерской службы
        unset( $rates[ $delivery_courier ] );
        // стоимость товаров в корзине
        if (WC()->cart->subtotal < $min_small_delivery) {
           foreach( $rates as $rate) {
            
            if ( 'local_pickup' === $rate->method_id ) {
                $rate->cost = $rate->cost;
            }
            else if ('free_shipping' === $rate->method_id) {
                $rate->cost = $rate->cost ;
            } else {
                $rate->cost = $rate->cost + $small_delivery_markup;
            }
            
           }
        }
    } else {
        // если сумма всех товаров в корзине меньше min_small_delivery, отключаем способ доставки
        
            if ( WC()->cart->subtotal < $min_small_delivery ) {
                unset( $rates[ $delivery_inMKAD ] );
                unset( $rates[ $delivery_outMKAD ] );
                unset( $rates[ $delivery_long_dist ] );
                isset($rates[ $delivery_courier ]);		
            } 
            else {
                unset( $rates[ $delivery_courier ] );
            }
    }
 
	return $rates;
}