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


/* стоимость доставки в зависимости от суммы заказа*/

add_filter( 'woocommerce_package_rates', 'plnt_shipping_conditions', 25, 2 );
 
function plnt_shipping_conditions( $rates, $package ) {

    //переменные
    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;

    global $delivery_courier;
    global $delivery_long_dist;

    global $delivery_inMKAD_small;
	global $delivery_outMKAD_small;
	global $delivery_inMKAD_large;
	global $delivery_outMKAD_large;

    global $urgent_delivery_inMKAD; 
	global $urgent_delivery_outMKAD; 
	global $urgent_delivery_inMKAD_small; 
	global $urgent_delivery_outMKAD_small;
	global $urgent_delivery_inMKAD_large; 
	global $urgent_delivery_outMKAD_large;

    /*СТОИМОСТЬ ДОСТАВКИ ПО СУММЕ*/

    $min_small_delivery = carbon_get_theme_option('min_small_delivery');

    if ( WC()->cart->subtotal < $min_small_delivery ) {
        unset( $rates[ $delivery_inMKAD ] );
        unset( $rates[ $delivery_outMKAD ] );
        unset( $rates[ $urgent_delivery_inMKAD ] );
        unset( $rates[ $urgent_delivery_outMKAD ] );
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );
        if(isset($rates[ $delivery_courier ])) {
            unset( $rates[ $delivery_inMKAD_small ] );
            unset( $rates[ $delivery_outMKAD_small ] );
            unset( $rates[ $urgent_delivery_inMKAD_small ] );
            unset( $rates[ $urgent_delivery_outMKAD_small ] );
            unset( $rates[ $delivery_long_dist ] );
        }
    } else {
        unset( $rates[ $delivery_inMKAD_small ] );
        unset( $rates[ $delivery_outMKAD_small ] );
        unset( $rates[ $urgent_delivery_inMKAD_small ] );
        unset( $rates[ $urgent_delivery_outMKAD_small ] );
        unset( $rates[ $delivery_courier ] );
    }
 
    /*СТОИМОСТЬ ДОСТАВКИ ПО ВЕСУ*/
    
    $cart_weight = WC()->cart->cart_contents_weight; // вес товаров в корзине

    if ($cart_weight >= 11) {
        unset( $rates[ $delivery_inMKAD ] );
        unset( $rates[ $delivery_outMKAD ] );
        unset( $rates[ $urgent_delivery_inMKAD ] );
        unset( $rates[ $urgent_delivery_outMKAD ] );
        unset( $rates[ $delivery_inMKAD_small ] );
        unset( $rates[ $delivery_outMKAD_small ] );
        unset( $rates[ $urgent_delivery_inMKAD_small ] );
        unset( $rates[ $urgent_delivery_outMKAD_small ] );
    } else {
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );

    }

    /*СРОЧНАЯ ДОСТАВКА*/
    if (WC()->session->get('isUrgent' ) === '0') {
        unset( $rates[ $urgent_delivery_inMKAD ] );
        unset( $rates[ $urgent_delivery_outMKAD ] );
        unset( $rates[ $urgent_delivery_inMKAD_small ] );
        unset( $rates[ $urgent_delivery_outMKAD_small ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );
    }   

    if (WC()->session->get('isUrgent' ) === '1') {
        unset( $rates[ $delivery_inMKAD ] );
        unset( $rates[ $delivery_outMKAD ] );
        unset( $rates[ $delivery_inMKAD_small ] );
        unset( $rates[ $delivery_outMKAD_small ] );
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
    }

	return $rates;
}

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

add_action( 'wp_footer', 'plnt_get_shiping_costs' );

function plnt_get_shiping_costs() {
    $shipping_costs = [];
    $shipping_zones = WC_Shipping_Zones::get_zones();
 
	if( $shipping_zones ) {
 
		// для каждой зоны доставки
		foreach ( $shipping_zones as $shipping_zone_id => $shipping_zone ) {
 
			// получаем объект зоны доставки
			$shipping_zone = new WC_Shipping_Zone( $shipping_zone_id );
 
			// получаем доступные способы доставки для этой зоны
			$shipping_methods = $shipping_zone->get_shipping_methods( true, 'values' );
 
			if( $shipping_methods ) {
				foreach ( $shipping_methods as $shipping_method_id => $shipping_method ) {
                    print_r($shipping_method_id);
                    array_push($shipping_costs, array($shipping_method->id => $shipping_method->cost));
				}
			}
        }
    }
    //print_r($shipping_costs); 
}