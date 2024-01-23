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

// табы и описание
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

add_action('woocommerce_after_single_product_summary', 'plnt_price_wrap', 10);

function plnt_price_wrap(){
    ?>
    <div class="card__price-wrap">
        <?php
        // plnt_check_stock_status();
        woocommerce_template_single_price();
        woocommerce_template_single_add_to_cart();
        //plnt_wish_wrap(); //кнопка в избранное для be rocket wishlist
        plnt_outofstock_info();
        ?>
    </div>
    <?php 
};

function plnt_wish_wrap(){
    global $product;
    $id = $product->get_id();
    echo '
    <div class="br_wish_wait_block br_wish_wait_'. $id .'" data-id='. $id .'><span class="br_ww_button br_wish_button br_wish_add button" data-type="wish" href="#add_to_wish_list"><i class="fa fa-heart-o"></i></span></div>
    ';
};

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
    $crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' );

    if( !empty ($crosssell_ids) ){

        $crosssell_ids = $crosssell_ids[0];

        if(count($crosssell_ids)>0){

            $args = array(
                'post_type' => 'product',
                'ignore_sticky_posts' => 1,
                'no_found_rows' => 1,
                'posts_per_page' => 8,
                'orderby' => $rand,
                'post__in' => $crosssell_ids,
                'meta_query' => array( 
                    array(
                        'key'       => '_stock_status',
                        'value'     => 'outofstock',
                        'compare'   => 'NOT IN'
                    )
                )
            );

            $products = new WP_Query( $args );

            if ( $products->have_posts() ) : ?>

                <div class="cross-sells">

                <h2><?php _e( 'Похожие растения', 'woocommerce' ) ?></h2>

                <ul id="flexisel-cross-sells" class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">

                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                </ul>

                <script type="text/javascript">
                    jQuery(window).load(function() {
                        jQuery("#flexisel-cross-sells").flexisel({
                            visibleItems:3,
                            animationSpeed: 1000,
                            autoPlay: false,
                            autoPlaySpeed: 3000,
                            pauseOnHover: true,
                            enableResponsiveBreakpoints: true,
                            responsiveBreakpoints: {
                                portrait: {
                                    changePoint:480,
                                    visibleItems: 1
                                },
                                landscape: {
                                    changePoint:640,
                                    visibleItems:2
                                },
                                tablet: {
                                    changePoint:768,
                                    visibleItems: 3
                                }
                            }
                        });
                    });
                </script>

                </div>

            <?php endif;

        }

        wp_reset_query();

    }
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
	<span>&#10094; </span>
	<a href="' . $link . '">'.$text.'</a>
    </div>';
}
