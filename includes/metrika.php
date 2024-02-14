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
    $productName = $product->get_title();
    $price = $product->get_price();
    $parentCatId = check_category($product);
    $catName = get_the_category_by_ID($parentCatId);

        if (is_product()) {

            ?> 
            <script>
                console.log('hi product');
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

// YM action add
// добавляем доп атрибуты для кнопки Добавить в корзину для использования в Yandex Metrika e-commerce
add_filter( 'woocommerce_loop_add_to_cart_args', 'filter_woocommerce_loop_add_to_cart_args', 10, 2 );
function filter_woocommerce_loop_add_to_cart_args( $args, $product ) {
    $productName = $product->get_title();
    $price = $product->get_price();
    $parentCatId = check_category($product);
    $catName = get_the_category_by_ID($parentCatId);
    $args['attributes']['data-product-name'] = $productName;
    $args['attributes']['data-product-price'] = $price;
    $args['attributes']['data-category-name'] = $catName;

    return $args;
}





// dataLayer.push({
//     "ecommerce": {
//         "currencyCode": "RUB",
//         "purchase": {
//             "actionField": {
//                 "id" : "TRX987"
//             },
//             "products": [
//                 {
//                     "id": "25341",
//                     "name": "Толстовка Яндекс мужская",
//                     "price": 1345.26,
//                     "brand": "Яндекс / Яndex",
//                     "category": "Одежда/Мужская одежда/Толстовки и свитшоты",
//                     "variant": "Оранжевый цвет",
//                     "quantity": 1,
//                     "list": "Одежда",
//                     "position": 1
//                 },
//                 {
//                     "id": "25314",
//                     "name": "Толстовка Яндекс женская",
//                     "price": 1543.62,
//                     "brand": "Яндекс / Яndex",
//                     "category": "Одежда/Женская одежда/Толстовки и свитшоты",
//                     "variant": "Белый цвет",
//                     "quantity": 3,
//                     "list": "Толстовки",
//                     "position": 2
//                 }
//             ]
//         }
//     }
// });