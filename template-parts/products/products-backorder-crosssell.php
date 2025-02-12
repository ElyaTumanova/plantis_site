<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//global $product;
$product_id = $args[ 'product_id' ];
//echo $product_id;
$product = wc_get_product( $product_id );
//echo $product;

//$crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' );
$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids' );
//$upsells_ids = $product->get_upsell_ids();


if( !empty ($crosssell_ids) ){

   $crosssell_ids = $crosssell_ids[0];

    if(count($crosssell_ids)>0){

        $args = array(
            'post_type' => 'product',
            'ignore_sticky_posts' => 1,
            'no_found_rows' => 1,
            'posts_per_page' => 6,
            'orderby' => 'rand',
            'post__in' => $crosssell_ids,
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

        <?php

            //$post_IDS = array();
            $i = 1;
            while ( $products->have_posts() ) : $products->the_post();
            $post_ID = get_the_ID();
            while($i <= 4) {
                echo $post_ID;
                //$my_product = wc_get_product($post_IDS[$i] );
                print_r($product);
                $i++;
            }
            //array_push($post_IDS, $post_ID);
            endwhile;

            //print_r($post_IDS);
          
            
        ?>

            <div class="backorder-crossells__sliders-wrap">
                <div class="backorder-crossells-swiper swiper">
                    <ul class="products columns-3 swiper-wrapper"> 
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                        <?php endwhile; // end of the loop. ?>
                    </ul>
                    <!-- <div class="swiper-pagination"></div> -->
                    <div class="swiper-scrollbar"></div>
	                <!-- <div class="swiper-button-prev"></div>
	                <div class="swiper-button-next"></div> -->
                </div>
            </div>

        <?php endif;
    }
    wp_reset_query();
    wp_reset_postdata();

}

