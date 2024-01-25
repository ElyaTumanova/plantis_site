<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//обертки для card grid
add_action('woocommerce_before_single_product_summary','plnt_card_grid_start',5);

function plnt_card_grid_start () {
    ?>
	<div class="card__grid">
    <?php 
};

add_action('woocommerce_after_single_product_summary','plnt_card_grid_end',30);

function plnt_card_grid_end () {
    ?>
	</div>
    <?php 
};

// табы(убираем) и описание
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action('woocommerce_before_single_product_summary', 'plnt_product_description', 10);

function plnt_product_description () {
    ?>
    <div class="card__description">
        <h2 class="heading-2">Описание</h2>
    <?php
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
//remove_action('woocommerce_before_single_product','woocommerce_output_all_notices', 10); /* уведомления woocommerce*/

add_filter( 'woocommerce_cart_redirect_after_error', '__return_false' );  //остановка перезагрузки страницы (перадресации) при ошибке добаления товара в корзину
add_filter( 'wc_add_to_cart_message_html', '__return_false' );

add_action('woocommerce_after_single_product_summary', 'plnt_price_wrap', 5);

function plnt_price_wrap(){
    ?>
    <div class="card__price-wrap">
        <?php
        // plnt_check_stock_status();
        //plnt_card_qty();
        woocommerce_template_single_price();
        ?><div class="cart"> <?php
            woocommerce_template_loop_add_to_cart();    //заменили обычную не яакс кнопку на аякс кнопку из каталога
            woocommerce_quantity_input();               // добавили поля ввода. чтобы кнопка "в корзину" работала я полем ввода и кнопками +- см скрипт quantity-buttons.js
            plnt_card_wishlist_btn();
        ?></div>
        <?php
        //plnt_wish_wrap(); //кнопка в избранное для be rocket wishlist
        plnt_outofstock_info();
        //woocommerce_output_all_notices();
        get_template_part('template-parts/delivery-info'); // delivery info for card
        ?>
    </div>
    <?php 
};

///////////////////////////////////////////
add_action( 'wp_footer', 'trigger_for_ajax_add_to_cart' );
function trigger_for_ajax_add_to_cart() { ?>
    <script type="text/javascript">
        (function($){
            $('body').on( 'added_to_cart', function(){
                // Your code here
                console.log('added_to_cart'); // Test output on browser console
            });
        })(jQuery);
    </script>
<?php }


function plnt_card_qty() {
	?>
	<span class="card-qty"><?php echo wp_kses_data(WC()->cart->get_cart_item('product_id'))?></span>
	<?php
}

function plnt_get_card_qty() {
    global $product;
    $product_id = $product->get_id();
    $cart_content = WC()->cart->cart_contents;

    $cnt_products = 0;
    if ( $cart_content ) {
        foreach ( $cart_content as $cart_item ) {
            if ( $cart_item['product_id'] == $product_id ) {
                $cnt_products += $cart_item['quantity'];
            }
        }
    }

    return $cnt_products;
}

function plnt_card_qty_fragment( $fragments ) {
 
	ob_start();
	plnt_card_qty();
 
	$fragments[ 'span.card-qty' ] = ob_get_clean();
	
	return $fragments;
 
}

//add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_card_qty_fragment', 25 );

////////////////////////////////////

// function plnt_wish_wrap() {
//     global $product;
//     $id = $product->get_id();
//     echo '
//     <div class="br_wish_wait_block br_wish_wait_'. $id .'" data-id='. $id .'><span class="br_ww_button br_wish_button br_wish_add button" data-type="wish" href="#add_to_wish_list"><i class="fa fa-heart-o"></i></span></div>
//     ';
// };

function plnt_check_stock_status() {
    global $product;
    echo $product->get_stock_status();
}

function plnt_outofstock_info() {
    global $product;
    if ( $product->get_stock_status() ==='outofstock') {
        ?>
        <div class="card__outofstock-info">К сожалению, данный товар закончился!<br>Свяжитесь с нами удобным способом, и мы привезём его под заказ.</div>
        <?php
        plnt_card_wishlist_btn(); //кнопка в избранное для yith
        ?>
        <button class="button card__preorder-btn">Предзаказ</button>
        <?php
    }
}

// баннеры в карточке товара

add_action('woocommerce_after_single_product_summary', 'plnt_card_banners_wrap', 5);

function plnt_card_banners_wrap() {
    get_template_part('template-parts/card-banners'); // delivery info for card
}


//кнопки изменения количества
add_action( 'woocommerce_before_quantity_input_field', 'truemisha_quantity_minus', 25 );
add_action( 'woocommerce_after_quantity_input_field', 'truemisha_quantity_plus', 25 );
 
function truemisha_quantity_plus() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        if ($quantity > 1) {
            echo '<button type="button" class="plus">+</button>';
        }
    } else {
        echo '<button type="button" class="plus">+</button>';
    }
};
 
function truemisha_quantity_minus() {
    global $product;
    if(is_product()) {
        $quantity =  $product->get_stock_quantity();
        if ($quantity > 1) {
            echo '<button type="button" class="minus">-</button>';
        }
    } else {
        echo '<button type="button" class="minus">-</button>';
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

function check_category () {
	global $post;
	$idCats=[];
	$terms_post = get_the_terms( $post->cat_ID , 'product_cat' );
	foreach ($terms_post as $term_cat) {
    $term_cat_id = $term_cat->term_id;
	$idCats[$term_cat_id]=$term_cat_id;
	};	
	return $idCats;
};

function plnt_product_attributes(){
    global $product;
    global $plants_cat_id;
    ?>
    <div class="product__attributes">
    <?php
        $idCats = check_category ();
        if( in_array( $plants_cat_id ,$idCats ) )
            {
            echo  '<h2 class="heading-2">Уход и характеристики</h2>';
            } else {
            echo '<h2 class="heading-2">Характеристики</h2>';
            }
        wc_display_product_attributes($product);
    ?>
    </div>
    <?php 
};

//upsells & cross sells

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


add_filter('woocommerce_upsell_display_args', function ($args) {
    $args['posts_per_page'] = 8;
    return $args;
});

add_filter( 'woocommerce_product_upsells_products_heading' , 'plnt_upsells_heading' );

function plnt_upsells_heading () {
    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
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
        
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product_summary','plnt_get_cross_sells', 20);

function plnt_get_cross_sells(){
    get_template_part('template-parts/plnt-cross-sells');
}


// ссылка "назад" для карточки товара

add_action('woocommerce_before_single_product','plnt_category_link',20);

function plnt_category_link () {
    global $plants_cat_id;
    global $gorshki_cat_id;
    global $treez_cat_id;
	$idCats = check_category ();
	foreach ($idCats as $cat){
		switch ($cat)  {
			case $plants_cat_id:				//category ID for plants
				$link = get_term_link( $cat, 'product_cat' );
				$text = 'Каталог растений';
				break;
			case $gorshki_cat_id:				//category ID for gorshki
				$link = get_term_link( $cat, 'product_cat' );
				$text = 'Каталог горшков';
				break;
			case 69:				//category ID for ukhod
				$link = get_term_link( $cat, 'product_cat' );
				$text = 'Каталог товаров для ухода';
				break;
			case $treez_cat_id:				//category ID for treez
				$link = get_term_link( $cat, 'product_cat' );
				$text = 'Каталог кашпо Treez';
				break;
			default:
				$link = get_permalink( wc_get_page_id( 'shop' ) );
				$text = 'Каталог товаров';
                break;
			}
	}	
	echo '<div class="card__toback-link">
	<span>&#10094;</span>
	<a href="' . $link . '">'.$text.'</a>
    </div>';
}


