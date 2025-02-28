<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//СПОСОБЫ ДОСТАВКИ

//задаем по умолчанию срочную доставку
//add_action('wp_head','plnt_set_initials');

function plnt_set_initials() {
    date_default_timezone_set('Europe/Moscow');
    $hour = date("H");
   
    if ($hour >= 18 && $hour <20) {
        WC()->session->set('isUrgent', '0' ); //0
    } else {
        WC()->session->set('isUrgent', '1' ); //1
    }

    WC()->session->set('isLate', '0' );

};

//for dev

add_action('woocommerce_review_order_before_shipping','plnt_check');
//add_action('wp_head','plnt_check');

function plnt_check() {
  
    global $local_pickup;
    // echo '<br>';
    // $packages = WC()->shipping()->get_packages();
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
    // print_r( $packages);
    // echo '<br>';
    // echo $chosen_methods[0];
    // echo '<br>';

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
    //echo 'isLate '.(WC()->session->get('isLate' )).'  ';
    echo '<br>';
    // date_default_timezone_set('Europe/Moscow');
    // $hour = date("H");
    // if ( is_checkout() && ($hour<18 || $hour>=20)) {
    //     echo $hour;
    // }

}

// срочная доставка
add_action( 'wp_ajax_get_urgent_shipping', 'plnt_get_urgent_shipping' );
add_action( 'wp_ajax_nopriv_get_urgent_shipping', 'plnt_get_urgent_shipping' );
function plnt_get_urgent_shipping() {

    // if(WC()->session->get('isBackorder') === '1') {
    //     WC()->session->set('isUrgent', '0' );
    //     WC()->session->set('hiAjax', 'hiAjax' );
    // } 
    // else {
        // WC()->session->set('hiAjax', 'hiAjaxElse' );
        if ( $_POST['isUrgent'] === '1'){
            WC()->session->set('isUrgent', '1' ); //1
        } else {
            WC()->session->set('isUrgent', '0' ); //0
        }
    // }
    
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

// поздняя доставка
add_action( 'wp_ajax_get_late_shipping', 'plnt_get_late_shipping' );
add_action( 'wp_ajax_nopriv_get_late_shipping', 'plnt_get_late_shipping' );
function plnt_get_late_shipping() {
    if ( $_POST['isLate'] === '1'){
        WC()->session->set('isLate', '1' );
    } else {
        WC()->session->set('isLate', '0' );
    }
    die(); // (required)
}

add_action( 'woocommerce_checkout_update_order_review', 'plnt_refresh_shipping_methods_for_late', 10, 1 );
function plnt_refresh_shipping_methods_for_late( $post_data ){
    $bool = true;

    if ( WC()->session->get('isLate' ) === '1')
        $bool = false;

    // Mandatory to make it work with shipping methods
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

    global $delivery_courier;
    global $delivery_long_dist;

    global $delivery_inMKAD_small;
	global $delivery_outMKAD_small;
	global $delivery_inMKAD_large;
	global $delivery_outMKAD_large;
    global $delivery_inMKAD_medium;
	global $delivery_outMKAD_medium;

    global $urgent_delivery_inMKAD; 
	global $urgent_delivery_outMKAD; 
	global $urgent_delivery_inMKAD_small; 
	global $urgent_delivery_outMKAD_small;
	global $urgent_delivery_inMKAD_large; 
	global $urgent_delivery_outMKAD_large;
    global $urgent_delivery_inMKAD_medium;
	global $urgent_delivery_outMKAD_medium;

    $late_markup_delivery = carbon_get_theme_option('late_markup_delivery');

    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    
    /*СРОЧНАЯ ДОСТАВКА*/

    if (WC()->session->get('isUrgent' ) === '0') {
        unset( $rates[ $urgent_delivery_inMKAD ] );
        unset( $rates[ $urgent_delivery_outMKAD ] );
        unset( $rates[ $urgent_delivery_inMKAD_small ] );
        unset( $rates[ $urgent_delivery_outMKAD_small ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );
        unset( $rates[ $urgent_delivery_inMKAD_medium ] );
        unset( $rates[ $urgent_delivery_outMKAD_medium ] );
    }  else {
        unset( $rates[ $delivery_inMKAD ] );
        unset( $rates[ $delivery_outMKAD ] );
        unset( $rates[ $delivery_inMKAD_small ] );
        unset( $rates[ $delivery_outMKAD_small ] );
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
        unset( $rates[ $delivery_inMKAD_medium ] );
        unset( $rates[ $delivery_outMKAD_medium ] );
        WC()->session->set('isLate', '0' );
    }

    // if (WC()->session->get('isUrgent' ) === '1') {
    //     unset( $rates[ $delivery_inMKAD ] );
    //     unset( $rates[ $delivery_outMKAD ] );
    //     unset( $rates[ $delivery_inMKAD_small ] );
    //     unset( $rates[ $delivery_outMKAD_small ] );
    //     unset( $rates[ $delivery_inMKAD_large ] );
    //     unset( $rates[ $delivery_outMKAD_large ] );
    //     unset( $rates[ $delivery_inMKAD_medium ] );
    //     unset( $rates[ $delivery_outMKAD_medium ] );
    //     WC()->session->set('isLate', '0' );
    // }
 

    /*СТОИМОСТЬ ДОСТАВКИ ПО СУММЕ*/

    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');

    if ( WC()->cart->subtotal < $min_small_delivery ) {
        unset( $rates[ $delivery_inMKAD ] );
        unset( $rates[ $delivery_outMKAD ] );
        unset( $rates[ $urgent_delivery_inMKAD ] );
        unset( $rates[ $urgent_delivery_outMKAD ] );
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );
        unset( $rates[ $delivery_inMKAD_medium ] );
        unset( $rates[ $delivery_outMKAD_medium ] );
        unset( $rates[ $urgent_delivery_inMKAD_medium ] );
        unset( $rates[ $urgent_delivery_outMKAD_medium ] );
        if(isset($rates[ $delivery_courier ])) {
            unset( $rates[ $delivery_inMKAD_small ] );
            unset( $rates[ $delivery_outMKAD_small ] );
            unset( $rates[ $urgent_delivery_inMKAD_small ] );
            unset( $rates[ $urgent_delivery_outMKAD_small ] );
            unset( $rates[ $delivery_inMKAD_medium ] );
            unset( $rates[ $delivery_outMKAD_medium ] );
            unset( $rates[ $urgent_delivery_inMKAD_medium ] );
            unset( $rates[ $urgent_delivery_outMKAD_medium ] );
            unset( $rates[ $delivery_long_dist ] );
        }
    } else if ( WC()->cart->subtotal < $min_medium_delivery ) {
        unset( $rates[ $delivery_inMKAD ] );
        unset( $rates[ $delivery_outMKAD ] );
        unset( $rates[ $urgent_delivery_inMKAD ] );
        unset( $rates[ $urgent_delivery_outMKAD ] );
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );
        unset( $rates[ $delivery_inMKAD_small ] );
        unset( $rates[ $delivery_outMKAD_small ] );
        unset( $rates[ $urgent_delivery_inMKAD_small ] );
        unset( $rates[ $urgent_delivery_outMKAD_small ] );
        if(isset($rates[ $delivery_courier ])) {
            unset( $rates[ $delivery_inMKAD_small ] );
            unset( $rates[ $delivery_outMKAD_small ] );
            unset( $rates[ $urgent_delivery_inMKAD_small ] );
            unset( $rates[ $urgent_delivery_outMKAD_small ] );
            unset( $rates[ $delivery_inMKAD_medium ] );
            unset( $rates[ $delivery_outMKAD_medium ] );
            unset( $rates[ $urgent_delivery_inMKAD_medium ] );
            unset( $rates[ $urgent_delivery_outMKAD_medium ] );
            unset( $rates[ $delivery_long_dist ] );
        }
            
    } else {
    unset( $rates[ $delivery_inMKAD_small ] );
    unset( $rates[ $delivery_outMKAD_small ] );
    unset( $rates[ $urgent_delivery_inMKAD_small ] );
    unset( $rates[ $urgent_delivery_outMKAD_small ] );
    unset( $rates[ $delivery_inMKAD_medium ] );
    unset( $rates[ $delivery_outMKAD_medium ] );
    unset( $rates[ $urgent_delivery_inMKAD_medium ] );
    unset( $rates[ $urgent_delivery_outMKAD_medium ] );
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
        unset( $rates[ $delivery_inMKAD_medium ] );
        unset( $rates[ $delivery_outMKAD_medium ] );
        unset( $rates[ $urgent_delivery_inMKAD_medium ] );
        unset( $rates[ $urgent_delivery_outMKAD_medium ] );
    } else {
        unset( $rates[ $delivery_inMKAD_large ] );
        unset( $rates[ $delivery_outMKAD_large ] );
        unset( $rates[ $urgent_delivery_inMKAD_large ] );
        unset( $rates[ $urgent_delivery_outMKAD_large ] );

    }

    //поздняя доставка
    if (isset($chosen_methods)) {
        if($local_pickup == $chosen_methods[0] || $delivery_courier == $chosen_methods[0] || $delivery_long_dist == $chosen_methods[0]) {
            WC()->session->set('isLate', '0' );  
        }
    }

    if (WC()->session->get('isLate' ) === '1') {
        foreach( $rates as $rate) {
            if ( 'local_pickup' !== $rate->method_id) {
                if('free_shipping' !== $rate->method_id) {
                    $rate->cost = $rate->cost + $late_markup_delivery;
                }
            }	
        }
    }

	return $rates;
}

//убираем способ онлайн-оплаты, если маленькая сумма заказа или далекая доставка
add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_small_order' );

function plnt_disable_payment_small_order( $available_gateways ) {
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');
    global $delivery_courier;
    global $delivery_long_dist;

    if( is_admin() ) {
		return $available_gateways;
	}

    if( is_wc_endpoint_url( 'order-pay' ) ) {
		return $available_gateways;
	}

    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    if (isset($chosen_methods)) {
        // стоимость товаров в корзине
        if (WC()->cart->subtotal < $min_small_delivery && $delivery_courier == $chosen_methods[0]) {
            unset( $available_gateways['tinkoff'] ); //to be updated - change to tinkoff
        }
    
        
        if (WC()->cart->subtotal < $min_medium_delivery && $delivery_courier == $chosen_methods[0]) {
            unset( $available_gateways['tinkoff'] ); //to be updated - change to tinkoff
        }
        
    
        // дальняя доставка
        if ( $delivery_long_dist == $chosen_methods[0]) {
            unset( $available_gateways['tinkoff'] ); //to be updated - change to tinkoff
        }
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
                    if($shipping_method->id !== 'free_shipping') {
                        $shipping_id = $shipping_method->id.":".$shipping_method_id;
                        $shipping_costs[$shipping_id]=$shipping_method->cost;
                    } else {
                        $shipping_id = $shipping_method->id.":".$shipping_method_id;
                        $shipping_costs[$shipping_id]=0;
                    }
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
    global $delivery_inMKAD_medium;
	global $delivery_outMKAD_medium;

    global $urgent_delivery_inMKAD; 
	global $urgent_delivery_outMKAD; 
	global $urgent_delivery_inMKAD_small; 
	global $urgent_delivery_outMKAD_small;
	global $urgent_delivery_inMKAD_large; 
	global $urgent_delivery_outMKAD_large;
    global $urgent_delivery_inMKAD_medium;
	global $urgent_delivery_outMKAD_medium;

    $isUrgent = WC()->session->get('isUrgent' );

    if($chosen_method && in_array($chosen_method, $rates)) {
        $default = $chosen_method;
    } else {
        $default = $local_pickup;
    }

    if ( is_checkout() ) {
        if( $chosen_method === $delivery_inMKAD && $isUrgent === '1') {
            $default = $urgent_delivery_inMKAD;
        }
    
        if( $chosen_method === $urgent_delivery_inMKAD && $isUrgent === '0') {
            $default = $delivery_inMKAD;
        }
    
        if( $chosen_method === $delivery_outMKAD && $isUrgent === '1') {
            $default = $urgent_delivery_outMKAD;
        }
    
        if( $chosen_method === $urgent_delivery_outMKAD && $isUrgent === '0') {
            $default = $delivery_outMKAD;
        }
    
        if( $chosen_method === $delivery_inMKAD_small && $isUrgent === '1') {
            $default = $urgent_delivery_inMKAD_small;
        }
    
        if( $chosen_method === $urgent_delivery_inMKAD_small && $isUrgent === '0') {
            $default = $delivery_inMKAD_small;
        }
    
        if( $chosen_method === $delivery_outMKAD_small && $isUrgent === '1') {
            $default = $urgent_delivery_outMKAD_small;
        }
    
        if( $chosen_method === $urgent_delivery_outMKAD_small && $isUrgent === '0') {
            $default = $delivery_outMKAD_small;
        }
    
        if( $chosen_method === $delivery_inMKAD_large && $isUrgent === '1') {
            $default = $urgent_delivery_inMKAD_large;
        }
    
        if( $chosen_method === $urgent_delivery_inMKAD_large && $isUrgent === '0') {
            $default = $delivery_inMKAD_large;
        }
    
        if( $chosen_method === $delivery_outMKAD_large && $isUrgent === '1') {
            $default = $urgent_delivery_outMKAD_large;
        }
    
        if( $chosen_method === $urgent_delivery_outMKAD_large && $isUrgent === '0') {
            $default = $delivery_outMKAD_large;
        }

        if( $chosen_method === $delivery_inMKAD_medium && $isUrgent === '1') {
            $default = $urgent_delivery_inMKAD_medium;
        }
    
        if( $chosen_method === $urgent_delivery_inMKAD_medium && $isUrgent === '0') {
            $default = $delivery_inMKAD_medium;
        }
    
        if( $chosen_method === $delivery_outMKAD_medium && $isUrgent === '1') {
            $default = $urgent_delivery_outMKAD_medium;
        }
    
        if( $chosen_method === $urgent_delivery_outMKAD_medium && $isUrgent === '0') {
            $default = $delivery_outMKAD_medium;
        } 
    }

    return $default;
}

// новое поле для способов доставки в админке - вылезает ошибка!

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
