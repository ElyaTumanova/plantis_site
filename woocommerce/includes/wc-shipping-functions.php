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
            unset( $rates[ $delivery_inMKAD ] )
            unset( $rates[ $delivery_outMKAD ] )
            unset( $rates[ $delivery_inMKAD_small ] )
            unset( $rates[ $delivery_outMKAD_small ] )
            unset( $rates[ $delivery_inMKAD_large ] )
            unset( $rates[ $delivery_outMKAD_large ] )
        }
    }

    return $rates;
}