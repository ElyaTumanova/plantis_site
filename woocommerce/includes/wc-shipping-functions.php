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
        //WC()->session->set('chosen_shipping_methods', $_POST['destination'] );
    } else {
        WC()->session->set('isUrgent', '0' );
        //WC()->session->set('chosen_shipping_methods', 'local_pickup:9' );
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

// получаем стоимость способов доставки по ИД
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
                    $shipping_id = $shipping_method->id.":".$shipping_method_id;
                    $shipping_costs[$shipping_id]=$shipping_method->cost;
				}
			}
        }
    }

	return $shipping_costs;
}

// задаем способ доставки по умолчанию для доставок внутри и за пределы МКАД

add_filter( 'woocommerce_shipping_chosen_method', 'wp_kama_woocommerce_shipping_chosen_method_filter', 10, 3 );

function wp_kama_woocommerce_shipping_chosen_method_filter( $default, $rates, $chosen_method ){

    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;

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

    if($chosen_method) {
        $default = $chosen_method;
    } else {
        $default = $local_pickup;
    }

    if( $chosen_method === $delivery_inMKAD) {
        $default = $urgent_delivery_inMKAD;
    }

    if( $chosen_method === $urgent_delivery_inMKAD) {
        $default = $delivery_inMKAD;
    }

    if( $chosen_method === $delivery_outMKAD) {
        $default = $urgent_delivery_outMKAD;
    }

    if( $chosen_method === $urgent_delivery_outMKAD) {
        $default = $delivery_outMKAD;
    }

    if( $chosen_method === $delivery_inMKAD_small) {
        $default = $urgent_delivery_inMKAD_small;
    }

    if( $chosen_method === $urgent_delivery_inMKAD_small) {
        $default = $delivery_inMKAD_small;
    }

    if( $chosen_method === $delivery_outMKAD_small) {
        $default = $urgent_delivery_outMKAD_small;
    }

    if( $chosen_method === $urgent_delivery_outMKAD_small) {
        $default = $delivery_outMKAD_small;
    }

    if( $chosen_method === $delivery_inMKAD_large) {
        $default = $urgent_delivery_inMKAD_large;
    }

    if( $chosen_method === $urgent_delivery_inMKAD_large) {
        $default = $delivery_inMKAD_large;
    }

    if( $chosen_method === $delivery_outMKAD_large) {
        $default = $urgent_delivery_outMKAD_large;
    }

    if( $chosen_method === $urgent_delivery_outMKAD_large) {
        $default = $delivery_outMKAD_large;
    } 

    return $default;
}

// новое поле для способов доставки в админке

//add_action('woocommerce_init', 'woocommerce_shipping_instances_form_fields_filters');
function woocommerce_shipping_instances_form_fields_filters(){
    foreach( WC()->shipping->get_shipping_methods() as $shipping_method ) {
        add_filter('woocommerce_shipping_instance_form_fields_' . $shipping_method->id, 'shipping_methods_additional_custom_field');
    }
}

function shipping_methods_additional_custom_field( $settings ) {
    $settings['shipping_comment'] = array(
        'title'         => __('Shipping Comment', 'woocommerce'),
        'type'          => 'text', 
        'placeholder'   => __( 'Enter any additional comments for this shipping method.', 'woocommerce' ),
    );
    return $settings;
} 
