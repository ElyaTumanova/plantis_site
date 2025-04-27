<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//обертки для card grid
add_action('woocommerce_before_single_product_summary','plnt_card_grid_start',5);

function plnt_card_grid_start () {
    global $product;
    global $plants_cat_id;
    $parentCatId = check_category ($product);
    $isTreez = check_is_treez($product);
    $isLechuza = check_is_lechuza($product);
    if ($parentCatId === $plants_cat_id) {
        if ( $product->get_stock_status() ==='onbackorder' && $product->backorders_allowed()) {
            ?>
            <div class="card__grid card__grid_backorder">
            <?php
        } elseif ($product->get_stock_status() ==='outofstock') {
            ?>
            <div class="card__grid card__grid_outofstock">
            <?php
        } else {
            ?>
            <div class="card__grid">
            <?php
        }
    } else {
        if ($isTreez || $isLechuza) {
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

add_action('woocommerce_after_single_product_summary','plnt_card_grid_end',40);

function plnt_card_grid_end () {
    ?>
	</div>
    <?php 
};

// табы

// remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// // таб доставка
add_filter( 'woocommerce_product_tabs', 'truemisha_new_product_tab', 25 );
 
function truemisha_new_product_tab( $tabs ) {
 
	$tabs[ 'delivery' ] = array(
		'title' 	=> 'Доставка и самовывоз',
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
 
	$tabs[ 'delivery' ][ 'priority' ] = 20;
    $tabs[ 'additional_information' ][ 'priority' ] = 10;
    $tabs[ 'description' ][ 'priority' ] = 30;
	return $tabs;
 
}

// //редактируем стандартные табы
add_filter( 'woocommerce_product_tabs', 'truemisha_rename_tabs', 25 );
 
function truemisha_rename_tabs( $tabs ) {
    global $product;
    global $plants_cat_id;
    $parentCatId = check_category($product);
    if( $parentCatId === $plants_cat_id ) {
        $tabs[ 'additional_information' ][ 'title' ] = 'Уход и характеристики';
    } else {
        $tabs[ 'additional_information' ][ 'title' ] = 'Xарактеристики';
    }
	return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'truemisha_remove_product_tabs', 25 );
 
function truemisha_remove_product_tabs( $tabs ) {

    global $product;
    
    if(empty($product->get_attributes())) {

        unset( $tabs[ 'additional_information' ] ); // вкладка Описание
     
    }

    if('' === get_post()->post_content ) {

        unset( $tabs[ 'description' ] ); // вкладка Описание
     
    }

    return $tabs;
 
}

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
	$options[ 'animationLoop' ] = false;
	return $options;
};

//цена и кнопка в корзину, кнопка в избранное
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action('woocommerce_before_single_product','woocommerce_output_all_notices', 10); /* убрать уведомления woocommerce*/

add_filter( 'woocommerce_cart_redirect_after_error', '__return_false' );  //остановка перезагрузки страницы (перадресации) при ошибке добаления товара в корзину
add_filter( 'wc_add_to_cart_message_html', '__return_false' ); //Удалить сообщение «Товар добавлен в корзину..»


add_action('woocommerce_after_single_product_summary', 'plnt_price_wrap', 5);

function for_dev() {
    global $product;
    echo 'stock qty '.$product->get_stock_quantity();
    echo '<br>';
    echo 'parent cat '.check_category ($product);
    echo '<br>';
    //print_r($product);
    $isTreez = check_is_treez($product);
    echo 'is Treez '.$isTreez;
    echo '<br>';
}

function plnt_price_wrap(){
    ?>
    <div class="card__price-wrap">
        <div class = "card__add-to-cart-wrap">
            <?php
            echo for_dev();
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
            <span class = "backorder-info">В наличии <?php echo $product->get_stock_quantity();?> шт. Если вы хотите заказать большее количество, то ориентировочная дата доставки из Европы <?php echo plnt_set_backorders_date();?>. После оформления заказа наш менеджер свяжется с вами для уточнения деталей заказа.</span>
        </div>
        <?php
        // peresadka_init
        //plnt_get_peresadka_add_to_cart();
        ?>
    </div>
    <?php
};

// peresadka
function plnt_get_peresadka_add_to_cart() {
    global $product; 
    $product_id = $product->get_id();
    ?><div class="card__peresadka"><?php 
    get_template_part('template-parts/products/products-peresadka',null, 
        array( // массив с параметрами
            'product_id' => $product_id
        ));
    ?></div><?php
};

function plnt_get_add_to_card() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        ?><div class="add-to-cart-wrap"> <?php
        if ($product->get_stock_status() ==='instock' || $product->backorders_allowed()) {
            woocommerce_template_loop_add_to_cart(); //заменили обычную не яакс кнопку на аякс кнопку из каталога
        }
        if ($quantity > 1 && !$product->backorders_allowed()) {
            woocommerce_quantity_input(array(
                'min_value' => 1,
                'max_value'    => $quantity,    // почему-то пришлось передавать заново, проверить на PLANTIS #TODO
            ),);           // добавили поля ввода. чтобы кнопка "в корзину" работала я полем ввода и кнопками +- см скрипт quantity-buttons.js
        }
        if ($product->backorders_allowed() || !$product->get_manage_stock()) {
            woocommerce_quantity_input(array(
                'min_value' => 1,
            ),);           // добавили поля ввода. чтобы кнопка "в корзину" работала я полем ввода и кнопками +- см скрипт quantity-buttons.js
        }
        if ($product->get_stock_status() ==='instock' || $product->backorders_allowed()) {
            plnt_card_wishlist_btn();
        }
        ?></div>
        <?php
    } 
};

function plnt_check_stock_status() {
    global $product;
    global $plants_cat_id;
    $parentCatId = check_category ($product);
    if ($parentCatId === $plants_cat_id) {
        if ( $product->get_stock_status() ==='instock' ) {
            ?>
            <div class="card__stockstatus card__stockstatus_in">Доставка от 2-х часов</div>
            <?php
        } else if ($product->backorders_allowed() && $product->get_stock_quantity() <= 0) {
            ?>
            <div class="card__stockstatus card__stockstatus_in">Доставка 10 — 14 дней</div>
            <?php
        } else {
            ?>
            <div class="card__stockstatus card__stockstatus_out">Под заказ</div>
            <?php
        }
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
        if ($quantity > 1 || !$product->get_manage_stock() || $product->backorders_allowed()) {
            echo '<div class="plus">&#43;</div>';
        }
    } 
};
 
function truemisha_quantity_minus() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        if ($quantity > 1 || !$product->get_manage_stock() || $product->backorders_allowed()) {
            echo '<div class="minus">&#8722;</div>';
        }
    } 
};

// мета данные товара
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'plnt_product_artikul', 40 );

function plnt_product_artikul() {
    global $product;
	$sku = $product->get_sku();
 
	if( $sku ) { // если заполнен, то выводим
		echo '<p class="product__artikul">Артикул: ' . $sku . '</p>';
	}
};

//upsells & cross sells

//add_action('woocommerce_after_single_product_summary','plnt_sliders_wrap_start', 10);

function plnt_sliders_wrap_start() {
    ?>
	<div class="card__sliders-wrap">
    <?php 
};

//add_action('woocommerce_after_single_product_summary','plnt_sliders_wrap_end',30);

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
    global $lechuza_cat_id;
    $parentCatId = check_category ($product);
    switch ($parentCatId) {
        case $plants_cat_id:				//category ID for plants
            return 'Этому растению подойдет';
            break;
        case $gorshki_cat_id || $lechuza_cat_id:				//category ID for gorshki
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

// товары для ухода

add_action('woocommerce_after_single_product_summary','plnt_card_ukhod_loop',50);

function plnt_card_ukhod_loop() {
    get_template_part('template-parts/products/products-ukhod');
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

    //$parentCatId = check_category ($product);
    
    //$isTreez = $parentCatId === $treez_cat_id || $parentCatId === $plants_treez_cat_id || $parentCatId === $treez_poliv_cat_id || ($product->get_stock_status() ==='onbackorder' && in_array($treez_cat_id, $idCats));
    $isTreez = (!$product->get_manage_stock() && in_array($treez_cat_id, $idCats)) || in_array($plants_treez_cat_id, $idCats) || in_array($treez_poliv_cat_id, $idCats);
    return $isTreez;
}

function check_is_lechuza($product) {
    global $lechuza_cat_id;

    $idCats = $product->get_category_ids();

    //$parentCatId = check_category ($product);
    
    //$isLechuza = $parentCatId === $lechuza_cat_id || ($product->get_stock_status() ==='onbackorder' && in_array($lechuza_cat_id, $idCats));
    $isLechuza = (!$product->get_manage_stock() && in_array($lechuza_cat_id, $idCats)) ;
    return $isLechuza;
}

function plnt_set_backorders_date() {
	$backorderdate = date( "d.m", strtotime('next wednesday +2 week') );

	return $backorderdate;
}

