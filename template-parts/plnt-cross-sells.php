<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$is_out_of_stock = $product->get_stock_status() ==='outofstock';


$crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' );

if( !empty ($crosssell_ids) ){

    $crosssell_ids = $crosssell_ids[0];
    

    if(count($crosssell_ids)>0){

        $args = array(
            'post_type' => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows' => 1,
            'posts_per_page' => 8,
            'orderby' => 'rand',
            'post__in' => $crosssell_ids,
            'meta_query' => array( 
                array(
                    'key'       => '_stock_status',
                    'value'     => array('outofstock', 'onbackorder'),
                    'compare'   => 'NOT IN'
                )
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => 'peresadka',
                    'operator' => 'NOT IN'
                )
            )
        );

        $products = new WP_Query( $args );
        if ( $products->have_posts() ) : ?>

            <div class="card__sliders-wrap cross-sells">

                <?php 
                if ( $is_out_of_stock) {
                    ?>
                    <h2 class="heading-2"><?php _e( 'Похожие растения в наличии', 'woocommerce' ) ?></h2>       
                    <?php
                } else {
                    ?>
                    <h2 class="heading-2"><?php _e( 'Похожие растения', 'woocommerce' ) ?></h2>
                    <?php       
                }
                ?>         
                <div class="cross-upsells-swiper swiper">
                    <ul class="products columns-3 swiper-wrapper"> 
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                        <?php endwhile; // end of the loop. ?>
                    </ul>
                    <div class="swiper-pagination"></div>
	                <div class="swiper-button-prev"></div>
	                <div class="swiper-button-next"></div>
                </div>
            </div>

        <?php endif;

    }

    wp_reset_query();

}

