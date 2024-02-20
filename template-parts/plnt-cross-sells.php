<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

            <h2 class="heading-2"><?php _e( 'Похожие растения', 'woocommerce' ) ?></h2>

            <!-- <ul id="flexisel-cross-sells" class="products columns-<?php //echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>"> -->
            <?php 
                // if (wp_is_mobile()) {
                //      ?> 
                <!-- <ul class="products__slider_mob products columns-3">  -->
                    <?php
                // } else {
                    ?> 
                <div class="cross-upsells-swiper ">
                            <ul class="products columns-3 "> <?php
                        // }
                    ?>
                    <!-- <ul id="flexisel-cross-sells" class="products columns-3"> -->

                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                        <?php endwhile; // end of the loop. ?>

                    </ul>
                    <!-- <div class="swiper-pagination"></div>
	                <div class="swiper-button-prev"></div>
	                <div class="swiper-button-next"></div> -->
                </div>


            <?php 
            // if (wp_is_mobile()) {
            //     $num = 2;
            // } else {
            //     $num = 3;
            // }
            // if (count($products->posts) >3) {?> 
                <!-- <script type="text/javascript">
                    jQuery(window).load(function() {
                        jQuery("#flexisel-cross-sells").flexisel({
                            visibleItems:3,
                            columnGaps: 30,
                            animationSpeed: 1000,
                            autoPlay: false,
                            autoPlaySpeed: 3000,
                            pauseOnHover: true,
                            enableResponsiveBreakpoints: true,
                            responsiveBreakpoints: {
                                portrait: {
                                    changePoint:480,
                                    visibleItems: 2,
                                    columnGaps: 20
                                },
                                landscape: {
                                    changePoint:640,
                                    visibleItems:2,
                                    columnGaps: 20
                                },
                                tablet: {
                                    changePoint:768,
                                    visibleItems: 3,
                                    columnGaps: 30
                                }
                            }
                        });
                    });
                </script> -->
       

            </div>

        <?php endif;

    }

    wp_reset_query();

}

