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

            <div class="cart-upsells__sliders-wrap product-slider-wrap">    
                    
                <div class="cart_upsells-swiper swiper">
                    <ul class="products columns-3 swiper-wrapper"> 
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

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


