<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//обертки для card grid
add_action('woocommerce_before_single_product_summary','plnt_card_grid_start',5);

function plnt_card_grid_start () {
    global $product;
    global $plants_cat_id;
    global $treez_cat_id;
    $parentCatId = check_category ($product);
    if ($parentCatId === $plants_cat_id) {
        if ( $product->get_stock_status() ==='outofstock') {
            ?>
            <div class="card__grid card__grid_outofstock">
            <?php
        } else {
            ?>
            <div class="card__grid">
            <?php
        }
    } else {
        if ($parentCatId === $treez_cat_id) {
            ?>
            <div class="card__grid card__grid_not-plant card__grid_treez ">
            <?php
        } else {
            ?>
            <div class="card__grid card__grid_not-plant">
            <?php
        }
    } 
};

add_action('woocommerce_after_single_product_summary','plnt_card_grid_end',50);

function plnt_card_grid_end () {
    ?>
	</div>
    <?php 
};

// табы и описание

// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

add_filter( 'woocommerce_product_tabs', 'truemisha_new_product_tab', 25 );
 
function truemisha_new_product_tab( $tabs ) {
 
	$tabs[ 'new_super_tab' ] = array(
		'title' 	=> 'Доставка',
		'priority' 	=> 25,
		'callback' 	=> 'truemisha_new_tab_content'
	);
 
	return $tabs;
 
}
function truemisha_new_tab_content() {
 
	get_template_part('template-parts/delivery-info'); // delivery info for card
 
}

add_filter( 'woocommerce_product_tabs', 'truemisha_reorder_tabs', 25 );
 
function truemisha_reorder_tabs( $tabs ) {
 
	$tabs[ 'new_super_tab' ][ 'priority' ] = 5;
	return $tabs;
 
}


add_action('woocommerce_before_single_product_summary', 'plnt_product_description', 10);

function plnt_product_description () {

    ?>
    <div class="card__description">
    <?php
    if( '' !== get_post()->post_content ) {
        ?>
            <h2 class="heading-2">Описание</h2>
        <?php
        }
    the_content();
    ?>
    </div>
    <?php 

};


// фото товара, бейдж распродажа
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

add_action('woocommerce_before_single_product_summary', 'plnt_product_image_wrap', 10);

function plnt_product_image_wrap () {
    ?>
    <div class="card__image-wrap">
    <?php
        truemisha_sale_badge();
        plnt_check_stock_status();
        woocommerce_show_product_images();
    ?>
    </div>
    <?php 
};

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
};

//слайдер фото товара

add_filter( 'woocommerce_single_product_carousel_options', 'plnt_product_gallery' );
 
function plnt_product_gallery( $options ) {
 
	$options[ 'directionNav' ] = true;
	$options[ 'controlNav' ] = true;
	$options[ 'animationLoop' ] = true;
	return $options;
};

//цена и кнопка в корзину, кнопка в избранное
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action('woocommerce_before_single_product','woocommerce_output_all_notices', 10); /* убрать уведомления woocommerce*/

add_filter( 'woocommerce_cart_redirect_after_error', '__return_false' );  //остановка перезагрузки страницы (перадресации) при ошибке добаления товара в корзину
add_filter( 'wc_add_to_cart_message_html', '__return_false' ); //Удалить сообщение «Товар добавлен в корзину..»


add_action('woocommerce_after_single_product_summary', 'plnt_price_wrap', 5);

function plnt_price_wrap(){
    ?>
    <div class="card__price-wrap">
        <div class = "card__add-to-cart-wrap">
            <?php
            woocommerce_template_single_price();
            ?> 
            <div class="card__price-btns-wrap">
                <?php
                global $product;
                if ( $product->get_stock_status() ==='outofstock') {
                    plnt_outofstock_btn();
                } else {
                    plnt_get_add_to_card();
                }
                ?>
            </div>
        </div>
        <?php
        plnt_outofstock_info();
        get_template_part('template-parts/delivery-info'); // delivery info for card
        ?>
    </div>
    <?php 
};

function plnt_cart_notice() {   // уведомление о том, что в корзину добавили максимальное кол-во товара, добавляется аяксом в add-to-cart.js
    ?>
        <div class='cart-notice'></div> 
    <?php
}

function plnt_get_add_to_card() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        ?><div class="add-to-cart-wrap"> <?php
        if ($product->get_stock_status() ==='instock') {
            woocommerce_template_loop_add_to_cart(); //заменили обычную не яакс кнопку на аякс кнопку из каталога
        }
        if ($quantity > 1 || !$product->get_manage_stock()) {
            woocommerce_quantity_input(array(
                'min_value' => 1,
                'max_value'    => $quantity,    // почему-то пришлось передавать заново, проверить на PLANTIS #TODO
            ),);           // добавили поля ввода. чтобы кнопка "в корзину" работала я полем ввода и кнопками +- см скрипт quantity-buttons.js
        }
        if ($product->get_stock_status() ==='instock') {
            plnt_card_wishlist_btn();
        }
        // plnt_cart_notice();
        ?></div>
        <?php
    } 
};

// function plnt_wish_wrap() {
//     global $product;
//     $id = $product->get_id();
//     echo '
//     <div class="br_wish_wait_block br_wish_wait_'. $id .'" data-id='. $id .'><span class="br_ww_button br_wish_button br_wish_add button" data-type="wish" href="#add_to_wish_list"><i class="fa fa-heart-o"></i></span></div>
//     ';
// };

function plnt_check_stock_status() {
    global $product;
    global $plants_cat_id;
    $parentCatId = check_category ($product);
    if ($parentCatId === $plants_cat_id) {
        if ( $product->get_stock_status() ==='instock') {
            ?>
            <div class="card__stockstatus card__stockstatus_in">В наличии</div>
            <?php
        } else {
            ?>
            <div class="card__stockstatus card__stockstatus_out">Под заказ</div>
            <?php
        }
    }
}

function plnt_outofstock_info() {
    global $product;
    if ( $product->get_stock_status() ==='outofstock') {
        ?>
        <div class="card__outofstock-info">К сожалению, данный товар закончился!<br>Свяжитесь с нами удобным способом, и мы привезём его под заказ.</div>
        <?php
    }
}
function plnt_outofstock_btn() {
    global $product;
    if ( $product->get_stock_status() ==='outofstock') {
        ?>
        <div class="card__outofstock-btn-wrap">
            <button class="button card__preorder-btn page-popup-open-btn">Предзаказ</button>
            <?php
            plnt_card_wishlist_btn(); //кнопка в избранное для yith
            ?>
        </div>
        <?php
    }
}

// баннеры в карточке товара

add_action('woocommerce_after_single_product_summary', 'plnt_card_banners_wrap', 5);

function plnt_card_banners_wrap() {
    get_template_part('template-parts/card-banners'); // info cards for card
}


//кнопки изменения количества
add_action( 'woocommerce_before_quantity_input_field', 'truemisha_quantity_minus', 25 );
add_action( 'woocommerce_after_quantity_input_field', 'truemisha_quantity_plus', 25 );
 
function truemisha_quantity_plus() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        if ($quantity > 1 || !$product->get_manage_stock()) {
            echo '<div class="plus">&#43;</div>';
        }
    } 
    else {
        echo '<div class="plus">&#43;</div>';
    }
};
 
function truemisha_quantity_minus() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        if ($quantity > 1 || !$product->get_manage_stock()) {
            echo '<div class="minus">&#8722;</div>';
        }
    } 
    else {
        echo '<div class="minus">&#8722;</div>';
    }
};

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
};

function plnt_product_attributes(){
    global $product;
    global $plants_cat_id;
    ?>
    <div class="product__attributes">
    <?php
        $attributes = $product->get_attributes();
        if(!empty($attributes)){
            $parentCatId = check_category($product);
            if( $parentCatId === $plants_cat_id )
                {
                echo  '<h2 class="heading-2">Уход и характеристики</h2>';
                } else {
                echo '<h2 class="heading-2">Характеристики</h2>';
                }
        }
        wc_display_product_attributes($product);
    ?>
    </div>
    <?php 
};

//upsells & cross sells

// add_action('woocommerce_after_single_product_summary', 'plnt_card_crosssells_wrap_start', 6);

function plnt_card_crosssells_wrap_start(){
    ?>
    <div class="card__crosssell-wrap">
    
    <?php 
};

// add_action('woocommerce_after_single_product_summary', 'plnt_card_crosssells_wrap_end', 8);

function plnt_card_crosssells_wrap_end(){
    ?>
    </div>
    <?php 
};

add_action('woocommerce_after_single_product_summary','plnt_sliders_wrap_start', 10);

function plnt_sliders_wrap_start() {
    ?>
	<div class="card__sliders-wrap">
    <?php 
};

add_action('woocommerce_after_single_product_summary','plnt_sliders_wrap_end',30);

function plnt_sliders_wrap_end () {
    ?>
	</div>
    <?php 
};


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action('woocommerce_after_single_product_summary','plnt_get_upsells', 15);

function plnt_get_upsells(){
    get_template_part('template-parts/plnt-upsells');
}

// add_filter('woocommerce_upsell_display_args', function ($args) {
//     $args['posts_per_page'] = 8;
//     return $args;
// });

add_filter( 'woocommerce_product_upsells_products_heading' , 'plnt_upsells_heading' );

function plnt_upsells_heading () {
    global $product;
    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
    $parentCatId = check_category ($product);
    switch ($parentCatId) {
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
};
        
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product_summary','plnt_get_cross_sells', 20);

function plnt_get_cross_sells(){
    get_template_part('template-parts/plnt-cross-sells');
}

// tabs

// add_action('woocommerce_after_single_product_summary','plnt_get_card_tabs', 35);

// function plnt_get_card_tabs() {
//     get_template_part('template-parts/card-tabs');
// }

// товары для ухода

add_action('woocommerce_after_single_product_summary','plnt_card_ukhod_loop',40);

function plnt_card_ukhod_loop() {
    get_template_part('template-parts/products-ukhod');
}

// ссылка "назад" для карточки товара

add_action('woocommerce_before_single_product','plnt_category_link',20);

function plnt_category_link () {
    global $product;
    $parentCat = check_category ($product);
    $term = get_term($parentCat);
    $link = get_term_link( $parentCat, 'product_cat' );
    $name = $term->name;
	echo '<div class="card__toback-link">
	<span>prev</span>
	<a href="' . $link . '">Каталог: '.$name.'</a>
    </div>';
}

// поп-ап предзаказ preoprder popup
add_action('woocommerce_after_main_content','plnt_get_preorder_popup',20);

function plnt_get_preorder_popup () {
    global $product;
    if (is_product() && $product->get_stock_status() ==='outofstock') {
        wc_get_template_part('template-parts/popups/preorder-popup');
    }
}

/*--------------------------------------------------------------
# HELPERS 
--------------------------------------------------------------*/
// функция, определяет есть ли среди категорий товара "родительские"
function check_category ($product) {
    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
    global $treez_poliv_cat_id;
    global $ukhod_cat_id;
    global $misc_cat_id;
	$idCats = $product->get_category_ids();
    if (in_array($plants_cat_id, $idCats)) {
        return $parentCatId = $plants_cat_id;
    } else if (in_array($gorshki_cat_id, $idCats)) {
        return $parentCatId = $gorshki_cat_id;
    } else if (in_array($treez_cat_id, $idCats)) {
        return $parentCatId = $treez_cat_id;
    } else if (in_array($treez_poliv_cat_id, $idCats)) {
        return $parentCatId = $treez_poliv_cat_id;
    } else if (in_array($ukhod_cat_id, $idCats)) {
        return $parentCatId = $ukhod_cat_id;
    } else {
        return $parentCatId = $misc_cat_id;
    }
};

