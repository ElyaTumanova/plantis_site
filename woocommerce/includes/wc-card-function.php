<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// табы и описание
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action('woocommerce_before_single_product_summary', 'plnt_product_description', 10);

function plnt_product_description () {
    ?>
    <div class="product__description">
        <h2 class="header-second">Описание</h2>
    <?php
    the_content();
    ?>
    </div>
    <?php 
}


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
		echo '<div class="sale_badge"><span class="sale_badge-skidka">Скидка </span>' . round( $percentage ) . '%</div>';
	}
}

//слайдер фото товара

add_filter( 'woocommerce_single_product_carousel_options', 'plnt_product_gallery' );
 
function plnt_product_gallery( $options ) {
 
	$options[ 'directionNav' ] = true;
	$options[ 'controlNav' ] = true;
	$options[ 'animationLoop' ] = true;
	return $options;
}

// отключаем зум для фото товара
// add_action( 'after_setup_theme', function() {
// 	remove_theme_support( 'wc-product-gallery-zoom' );
// });

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

// мета данные товара и атрибуты
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'plnt_product_artikul', 40 );
add_action( 'woocommerce_single_product_summary', 'plnt_product_attributes', 50 );

function plnt_product_artikul() {
    global $product;
	$sku = $product->get_sku();
 
	if( $sku ) { // если заполнен, то выводим
		echo '<p class="product__artikul">Артикул: ' . $sku . '</p>';
	}
}

function check_category () {
	global $post;
	$idCats=[];
	$terms_post = get_the_terms( $post->cat_ID , 'product_cat' );
	foreach ($terms_post as $term_cat) {
    $term_cat_id = $term_cat->term_id;
	$idCats[$term_cat_id]=$term_cat_id;
	};	
	return $idCats;
}

function plnt_product_attributes(){
    global $product;
    global $plants_cat_id;
    ?>
    <div class="product__attributes">
    <?php
        $idCats = check_category ();
        if( in_array( $plants_cat_id ,$idCats ) )
            {
            echo  '<h2 class="header-second">Уход и характеристики</h2>';
            } else {
            echo '<h2 class="header-second">Характеристики</h2>';
            }
        wc_display_product_attributes($product);
    ?>
    </div>
    <?php 
}

//upsells & cross sells

remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product_summary','plnt_get_cross_sells', 20);

add_filter('woocommerce_upsell_display_args', function ($args) {
    $args['posts_per_page'] = 8;
    return $args;
});

add_filter( 'woocommerce_product_upsells_products_heading' , 'plnt_upsells_heading' );

function plnt_upsells_heading () {
    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
    echo'<script>console.log('.$plants_cat_id.')</script>';
    $idCats = check_category ();
    foreach ($idCats as $cat){
        switch ($cat) {
            case $plants_cat_id:				//category ID for plants
                return 'Этому растению подойдет';
                break;
            case $gorshki_cat_id:				//category ID for gorshki
                return 'Другие цвета';
                break;
            case $treez_cat_id:				//category ID for treez
                return 'Другие цвета и сопутствующие';
                break;
            default:
                return 'Вас также заитересует';
                break;
        }
    }
};
        
function plnt_get_cross_sells(){
    woocommerce_cross_sell_display();
}

add_filter( 'woocommerce_product_cross_sells_products_heading' , 'plnt_cross_sells_heading' );

function plnt_cross_sells_heading() {
    return 'Похожие растения';
};

