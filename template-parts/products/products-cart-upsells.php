<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$product_id = $args[ 'product_id' ];

$product = wc_get_product( $product_id );

$upsells_ids = $product->get_upsell_ids();

if( !empty ($upsells_ids) ){


    if(count($upsells_ids)>0){

        $args = array(
            'post_type' => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows' => 1,
            'posts_per_page' => 8,
            'orderby' => 'rand',
            'post__in' => $upsells_ids,
            'meta_query' => array( 
                array(
                    'key'       => '_stock_status',
                    'value'     => array('outofstock', 'onbackorder'),
                    'compare'   => 'NOT IN'
                )
            )
        );

        $products = new WP_Query( $args );
        if ( $products->have_posts() ) : ?>

            <div class="cart-upsells__preview-wrap">
                <div class="cart-upsells__preview-title">Этому растению подойдет</div>
   
                <div class="cart-upsells__preview-down"><span class="cart-upsells__preview-down-open">Развернуть</span><span class="cart-upsells__preview-down-close">Свернуть</span><span class="cart-upsells__preview-arrow">next</span></div>
            </div>

            <div class="cart-upsells__sliders-wrap">    
                    
                <div class="cart_upsells-swiper swiper">
                    <ul class="products columns-3 swiper-wrapper"> 
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                       <?php $prod_id = get_the_ID(); ?>
                        <?php $sale = get_post_meta( get_the_ID(), '_sale_price', true);?>
                        <li class="swiper-slide product">
                            <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'medium' );?>" class="cart_upsells__img attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="<?php echo get_the_title();?>">
                            <a href="<?php echo get_permalink();?>" class="cart_upsells__link" target="blank">
                                <div class="cart_upsells__title woocommerce-loop-product__title"><?php echo get_the_title();?></div>
                            </a>
                            <div class="price">
                            <?php if ($sale) {
                                ?>
                                <span class="cart_upsells__reg-price"><?php echo get_post_meta( get_the_ID(), '_regular_price', true);?>&#8381;</span>
                                <span class="cart_upsells__price"><?php echo get_post_meta( get_the_ID(), '_sale_price', true);?>&#8381;</span>
                                <?php
                            } else {
                                ?>
                                <span class="cart_upsells__price"><?php echo get_post_meta( get_the_ID(), '_price', true);?>&#8381;</span>
                                <?php 
                            }
                            ?>
                            </div>
                            <a 
                                href="?add-to-cart=<?php echo $prod_id;?>" 
                                data-quantity="1" 
                                class="button product_type_simple add_to_cart_button ajax_add_to_cart product-cart-upsells_btn" 
                                data-product_id="<?php echo $prod_id;?>" 
                                rel="noindex, nofollow" 
                                data-product-name="<?php echo get_the_title();?>" 
                                data-product-price="<?php if ($sale) { 
                                    echo $sale;
                                    } else {
                                    echo get_post_meta( get_the_ID(), '_price', true);}?>" 
                                    data-category-name="Горшки и кашпо"
                                    data-stock-quantity="<?php echo get_post_meta( get_the_ID(), '_stock', true);?>" 
                                    data-remove_link="<?php
                                    $cart_item_key = WC()->cart->generate_cart_id( $prod_id );
                                    $remove_cart_url = wc_get_cart_remove_url( $cart_item_key );
                                    echo $remove_cart_url;
                                    ?>" 
                                    data-cart_item_key="<?php echo $cart_item_key;?>"
                                >
                                В корзину
                            </a>
                        </li>

                        <?php endwhile; // end of the loop. ?>
                    </ul>
                    <div class="swiper-scrollbar"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

        <?php endif;

    }

    wp_reset_query();

}


