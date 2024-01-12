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
        truemisha_sale_badge();
        woocommerce_show_product_images();
    ?>
    </div>
    <?php 
}

function truemisha_sale_badge() {
 
	// получаем объект текущего товара в цикле
	global $product;
 
	// есле не распродажа, ничего не делаем
	if ( ! $product->is_on_sale() ) {
		return;
	}
		// рассчитываем процент скидки
		$percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
 
	if ( $percentage > 0 ) {
		echo '<div class="onsale">Скидка ' . round( $percentage ) . '%</div>';
	}
}

//слайдер фото товара

add_filter( 'woocommerce_single_product_carousel_options', 'plnt_product_gallery' );
 
function plnt_product_gallery( $options ) {
 
	$options[ 'directionNav' ] = true;
	$options[ 'controlNav' ] = false;
	$options[ 'animationLoop' ] = true;
	return $options;
 
}

// отключаем зум для фото товара
add_action( 'after_setup_theme', function() {
	remove_theme_support( 'wc-product-gallery-zoom' );
});

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
add_action( 'woocommerce_before_quantity_input_field', 'truemisha_quantity_minus', 25 );
add_action( 'woocommerce_after_quantity_input_field', 'truemisha_quantity_plus', 25 );
 
function truemisha_quantity_plus() {
	echo '<button type="button" class="plus">+</button>';
}
 
function truemisha_quantity_minus() {
	echo '<button type="button" class="minus">-</button>';
}

// мета данные товара
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'plnt_product_artikul', 40 );

function plnt_product_artikul() {
    global $product;
	$sku = $product->get_sku();
 
	if( $sku ) { // если заполнен, то выводим
		echo '<p class="product__artikul">Артикул: ' . $sku . '</p>';
	}
 
}