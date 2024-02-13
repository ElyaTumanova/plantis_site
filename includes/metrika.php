<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# METRIKA E-COMMERCE 
--------------------------------------------------------------*/
add_action('woocommerce_after_main_content','plnt_metrika_card', 30);

function plnt_metrika_card () {
    global $product;
    $productName = $product->get_title();
    $price = $product->get_price();
    $parentCatId = check_category();
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

// добавляем доп атрибуты для кнопки Добавить в корзину для использования в Yandex Metrika e-commerce
add_filter( 'woocommerce_loop_add_to_cart_args', 'filter_woocommerce_loop_add_to_cart_args', 10, 2 );
function filter_woocommerce_loop_add_to_cart_args( $args, $product ) {
    $productName = $product->get_title();
    $price = $product->get_price();
    $parentCatId = check_category();
    $catName = get_the_category_by_ID($parentCatId);
    $args['attributes']['data-product-name'] = $productName;
    $args['attributes']['data-product-price'] = $price;
    $args['attributes']['data-category-name'] = $catName;

    return $args;
}


add_action('wp_footer', 'plnt_metrika_cart_remove', 30);

function plnt_metrika_cart_remove () {
    // global $product;
    // $productName = $product->get_title();
    // $price = $product->get_price();
    // $parentCatId = check_category();
    // $catName = get_the_category_by_ID($parentCatId);

    if ( ! WC()->cart->is_empty() ) {
            ?> 
            <script>
                console.log('hi cart');
                document.addEventListener("DOMContentLoaded", function() {
                    removes = document.querySelectorAll('.remove');
                    console.log(removes);
                    removes.forEach(remove => {
                        remove.addEventListener('click', function (){
                            console.log(remove);
                            console.log(remove.getAttribute('data-product_id'));
                        })
                    });

                    // window.dataLayer.push(
                    //     {
                    //         "ecommerce": {
                    //             "currencyCode": "RUB",
                    //             "detail": {
                    //                 "products" : [
                    //                     {
                    //                         "name":'<?php echo $productName?>',
                    //                         "price":'<?php echo $price?>',
                    //                         "category":'<?php echo $catName?>'
                    //                     }
                    //                 ]
                    //             }
                    //         }
                    //     }
                    // )
                    // console.log(JSON.stringify(window.dataLayer));
                    
                    // return true; 
                })
            </script>
            <?php

    }

        
    }