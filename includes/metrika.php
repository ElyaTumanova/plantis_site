<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# METRIKA E-COMMERCE 
--------------------------------------------------------------*/

// YM action detail

add_action('woocommerce_after_main_content','plnt_metrika_card', 30);

function plnt_metrika_card () {
    global $product;
    if ($product) {
        $productName = $product->get_title();
        $price = $product->get_price();
        $parentCatId = check_category($product);
        $catName = get_the_category_by_ID($parentCatId);
    
            if (is_product()) {
    
                ?> 
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        window.dataLayer.push(
                            {
                                "ecommerce": {
                                    "currencyCode": "RUB",
                                    "detail": {
                                        "products" : [
                                            {
                                                "name":'<?php echo $productName?>',
                                                "price":'<?php echo $price?>',
                                                "category":'<?php echo $catName?>'
                                            }
                                        ]
                                    }
                                }
                            }
                        )
                        console.log(JSON.stringify(window.dataLayer));
                        
                        return true; 
                    })
                </script>
                <?php
            }
    }
    }

// YM action add
// добавляем доп атрибуты для кнопки Добавить в корзину для использования в Yandex Metrika e-commerce
add_filter( 'woocommerce_loop_add_to_cart_args', 'filter_woocommerce_loop_add_to_cart_args', 10, 2 );
function filter_woocommerce_loop_add_to_cart_args( $args, $product ) {
    $productName = $product->get_title();
    $price = $product->get_price();
    $parentCatId = check_category($product);
    $catName = get_the_category_by_ID($parentCatId);
    $quantity =  $product->get_stock_quantity();
    $args['attributes']['data-product-name'] = $productName;
    $args['attributes']['data-product-price'] = $price;
    $args['attributes']['data-category-name'] = $catName;
    $args['attributes']['data-stock-quantity'] = $quantity;

    //for Add/Remove from cart

    $cart_item_key = WC()->cart->generate_cart_id( $product->get_id() );
	  $remove_cart_url = wc_get_cart_remove_url( $cart_item_key );
    $args['attributes']['data-remove_link'] = $remove_cart_url;
    $args['attributes']['data-cart_item_key'] = $cart_item_key;
    $args['attributes']['rel'] = 'nofollow';                        //#nofollow

    return $args;
}