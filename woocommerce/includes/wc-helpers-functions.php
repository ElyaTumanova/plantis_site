<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# HELPERS for categories
--------------------------------------------------------------*/
// функция, определяет есть ли среди категорий товара "родительские"
function check_category ($product) {
    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
    global $treez_poliv_cat_id;
    global $ukhod_cat_id;
    global $peresadka_cat_id;
    global $misc_cat_id;
    global $plants_treez_cat_id;
    global $lechuza_cat_id;
	$idCats = $product->get_category_ids();
    if (in_array($plants_cat_id, $idCats)) {
        return $parentCatId = $plants_cat_id;
    } else if (in_array($treez_cat_id, $idCats) && !$product->get_manage_stock()) {
        return $parentCatId = $treez_cat_id;
    } else if (in_array($treez_poliv_cat_id, $idCats)) {
        return $parentCatId = $treez_poliv_cat_id;
    } else if (in_array($ukhod_cat_id, $idCats)) {
        return $parentCatId = $ukhod_cat_id;
    } else if (in_array($peresadka_cat_id, $idCats)) {
        return $parentCatId = $peresadka_cat_id;
    } else if (in_array($plants_treez_cat_id, $idCats)) {
        return $parentCatId = $plants_treez_cat_id;
    } else if (in_array($lechuza_cat_id, $idCats) && !$product->get_manage_stock()) {
        return $parentCatId = $lechuza_cat_id;
    } else if (in_array($gorshki_cat_id, $idCats)) {
        return $parentCatId = $gorshki_cat_id;
    } else {
        return $parentCatId = $misc_cat_id;
    }
};

function check_is_treez($product) {
    global $treez_cat_id;
    global $treez_poliv_cat_id;
    global $plants_treez_cat_id;

    $idCats = $product->get_category_ids();
    
    //$isTreez = $parentCatId === $treez_cat_id || $parentCatId === $plants_treez_cat_id || $parentCatId === $treez_poliv_cat_id || ($product->get_stock_status() ==='onbackorder' && in_array($treez_cat_id, $idCats));
    $isTreez = (!$product->get_manage_stock() && in_array($treez_cat_id, $idCats)) || in_array($plants_treez_cat_id, $idCats) || in_array($treez_poliv_cat_id, $idCats);
    return $isTreez;
}

function check_is_lechuza($product) {
    global $lechuza_cat_id;

    $idCats = $product->get_category_ids();
    
    //$isLechuza = $parentCatId === $lechuza_cat_id || ($product->get_stock_status() ==='onbackorder' && in_array($lechuza_cat_id, $idCats));
    $isLechuza = (!$product->get_manage_stock() && in_array($lechuza_cat_id, $idCats)) ;
    return $isLechuza;
}

function plnt_set_backorders_date() {
	$backorderdate = date( "d.m", strtotime('next wednesday +2 week') );

	return $backorderdate;
}

/*--------------------------------------------------------------
# HELPERS for cart
--------------------------------------------------------------*/

 //Функция, возвращающая количество определённого товара в корзине
 function plnt_get_product_quantity_in_cart( $product_id ) {
 
	// по умолчанию количество товара равно 0
	$quantity = 0;
	// проходим циклом через все товары в корзине
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		// можно еще проверяет ID вариаций $cart_item[ 'variation_id' ]
		// если данный товар в цикле – наш товар, то записываем его количество в переменную
		if( $product_id == $cart_item[ 'product_id' ] ){
			$quantity = $cart_item[ 'quantity' ];
			break; // и прерываем цикл
		}
	}
 
	return $quantity;
 
}

// переписана стандартная функция wc_cart_totals_shipping_method_label, которая лежит в woocommerce/plugins/woocommerce/includes/wc-cart-functions.php, чтобы убрать : из названия метода доставки
function plnt_wc_cart_totals_shipping_method_label( $method ) {
	$label     = $method->get_label();
	$has_cost  = 0 < $method->cost;
	$hide_cost = ! $has_cost && in_array( $method->get_method_id(), array( 'free_shipping', 'local_pickup' ), true );

	if ( $has_cost && ! $hide_cost ) {
		if ( WC()->cart->display_prices_including_tax() ) {
			$label .= wc_price( $method->cost + $method->get_shipping_tax() );
			if ( $method->get_shipping_tax() > 0 && ! wc_prices_include_tax() ) {
				$label .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
			}
		} else {
			$label .= wc_price( $method->cost );
			if ( $method->get_shipping_tax() > 0 && wc_prices_include_tax() ) {
				$label .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
			}
		}
	}

	return apply_filters( 'woocommerce_cart_shipping_method_full_label', $label, $method );
}

