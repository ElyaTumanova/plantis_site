<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = array(
    'post_type' => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows' => 1,
    'posts_per_page' => 8,
    'orderby' => 'rand',
    'meta_query' => array( 
        array(
            'key'       => '_stock_status',
            'value'     => 'outofstock',
            'compare'   => 'NOT IN'
        )
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN'
        )
    )
);

$products = new WP_Query( $args );
if ( $products->have_posts() ) : ?>   
        <div class="product-slider-wrap popular-products-wrap">
            <h2 class="my_header__title">Популярные товары</h2>
            <div class="popular-slider-swiper swiper">
                <ul class="products columns-3 swiper-wrapper"> 
                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                    <?php endwhile; // end of the loop. ?>
                </ul>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <!-- <div class="swiper-scrollbar"></div> -->
            </div>
        </div>

<?php endif;


wp_reset_query();





