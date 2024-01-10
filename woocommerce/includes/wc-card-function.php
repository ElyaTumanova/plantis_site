<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// табы
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );


// фото товара, бейдж распродажа
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

add_action('woocommerce_before_single_product_summary', 'plnt_product_image_wrap', 10);

function plnt_product_image_wrap () {
    ?>
    <div class="product__image-wrap">
    <?php
        woocommerce_show_product_sale_flash();
        woocommerce_show_product_images();
    ?>
    </div>
    <?php 
}

//цена и кнопка в корзину
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

add_action('woocommerce_after_single_product_summary', 'plnt_price_wrap', 10);

function plnt_price_wrap(){
    ?>
    <div class="product__price-wrap">
    <?php
        woocommerce_template_single_price();
        woocommerce_template_single_add_to_cart();
    ?>
    </div>
    <?php 
}

//кнопки изменения количетсва
add_action( 'woocommerce_before_quantity_input_field', 'truemisha_quantity_plus', 25 );
add_action( 'woocommerce_after_quantity_input_field', 'truemisha_quantity_minus', 25 );
 
function truemisha_quantity_plus() {
	echo '<button type="button" class="plus">+</button>';
}
 
function truemisha_quantity_minus() {
	echo '<button type="button" class="minus">-</button>';
}