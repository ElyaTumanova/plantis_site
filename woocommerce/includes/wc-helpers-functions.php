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

// находит все дочерние категории нижнего уровня
    function get_lowest_level_product_categories( $parent_id = 0 ) {
        $lowest_level_cats = [];

        // Получаем все подкатегории заданной родительской категории
        $categories = get_terms( [
            'taxonomy'   => 'product_cat',
            'parent'     => $parent_id,
            'hide_empty' => false,
        ] );

        foreach ( $categories as $category ) {
            // Проверяем, есть ли у категории дочерние
            $child_cats = get_terms( [
                'taxonomy'   => 'product_cat',
                'parent'     => $category->term_id,
                'hide_empty' => false,
            ] );

            if ( empty( $child_cats ) ) {
                // Нет дочерних — значит, это нижний уровень
                $lowest_level_cats[] = $category;
            } else {
                // Рекурсивно ищем в дочерних
                $lowest_level_cats = array_merge(
                    $lowest_level_cats,
                    get_lowest_level_product_categories( $category->term_id )
                );
            }
        }

        return $lowest_level_cats;
    }
/*--------------------------------------------------------------
# HELPERS for cart & checkout
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

function get_delivery_markup() {
  // define markup
	$delivery_murkup = ['in_mkad'=>0, 'out_mkad'=>0, 'urg'=>0 ];

  $min_small_delivery = carbon_get_theme_option('min_small_delivery');
  $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');


  $large_markup_delivery_in_mkad = carbon_get_theme_option('large_markup_delivery_in_mkad');
  $large_markup_delivery_out_mkad = carbon_get_theme_option('large_markup_delivery_out_mkad');

  $small_markup_delivery = carbon_get_theme_option('small_markup_delivery');
  $medium_markup_delivery = carbon_get_theme_option('medium_markup_delivery');

  $urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');
  $urgent_markup_delivery_large = carbon_get_theme_option('urgent_markup_delivery_large');

  $late_markup_delivery = carbon_get_theme_option('late_markup_delivery');



  // проверяем крупногабаритную доставку
  if (check_if_large_delivery()) {
    $delivery_murkup['in_mkad'] = $large_markup_delivery_in_mkad;
    $delivery_murkup['out_mkad'] = $large_markup_delivery_out_mkad;
  } 
  // проверяем маленькие суммы заказов
  else {
      if ( WC()->cart->subtotal < $min_small_delivery ) {
        $delivery_murkup['in_mkad'] = $small_markup_delivery;
        $delivery_murkup['out_mkad'] = $small_markup_delivery;
      } else if (WC()->cart->subtotal < $min_medium_delivery) {
        $delivery_murkup['in_mkad'] = $medium_markup_delivery;
        $delivery_murkup['out_mkad'] = $medium_markup_delivery;
      }
  }

  //проверяем срочную доставку и позднюю доставку

    // if (WC()->session->get('isLate' ) === '1') {
    //      $delivery_murkup['in_mkad'] =  $delivery_murkup['in_mkad'] + $late_markup_delivery;
    //      $delivery_murkup['out_mkad'] =  $delivery_murkup['out_mkad'] + $late_markup_delivery;
    // }

    // if (WC()->session->get('isUrgent' ) === '1') {
      if (check_if_large_delivery()) {
        // $delivery_murkup['in_mkad'] =  $delivery_murkup['in_mkad'] + $urgent_markup_delivery_large;
        // $delivery_murkup['out_mkad'] =  $delivery_murkup['out_mkad'] + $urgent_markup_delivery_large;
        $delivery_murkup['urg'] =  $urgent_markup_delivery_large;
      } else {
        // $delivery_murkup['in_mkad'] =  $delivery_murkup['in_mkad'] + $urgent_markup_delivery;
        // $delivery_murkup['out_mkad'] =  $delivery_murkup['out_mkad'] + $urgent_markup_delivery;
        $delivery_murkup['urg'] =  $urgent_markup_delivery;
      }
    // }

    // обнуляем СРОЧНУЮ надбавку для предзаказа
    if (plnt_is_backorder() || plnt_is_treez_backorder()) {
        // $delivery_murkup['in_mkad'] =  0;
        // $delivery_murkup['out_mkad'] =  0;
        $delivery_murkup['urg'] =  0;
    }


  return $delivery_murkup;
}

function check_if_large_delivery() {
  $cart_weight = WC()->cart->cart_contents_weight;

  $isLargeDelivery = false;

  if ($cart_weight >= 11) {
     $isLargeDelivery = true;
  }

  return $isLargeDelivery;
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


function get_backorder_info_snippet($_product, $qty) {
    global $plants_cat_id;
    global $peresadka_cat_id;
    $stock_qty = $_product->get_stock_quantity();
    if (check_category($_product) !== $peresadka_cat_id) {
        if ( check_is_treez($_product) || check_is_lechuza($_product) ) {
            ?><p class="backorder_date-info backorder_date-info_late">Доставка со склада 3 - 7 дней</p>
            <?php
        } else {
            if ( $_product->backorders_allowed() && $qty > $stock_qty ) {
                if (check_category($_product) === $plants_cat_id) {
                ?><p class="backorder_date-info backorder_date-info_late">Доставка после <?php echo plnt_set_backorders_date();?></p>
                <?php } else {
                    ?><p class="backorder_date-info backorder_date-info_late">Доставка со склада 3 - 7 дней</p>
                    <?php
                }
            } else {
                ?><p class="backorder_date-info">Доставка от 2-х часов</p>
                <?php
            }
        }
    }
}


