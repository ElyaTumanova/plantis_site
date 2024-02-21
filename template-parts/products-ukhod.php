<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$args = array(
    'post_type' => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows' => 1,
    'posts_per_page' => 8,
    'orderby' => $rand,
    // 'post__in' => $product_ids_on_sale,
    'meta_query' => array( 
        array(
            'key'       => '_stock_status',
            'value'     => 'outofstock',
            'compare'   => 'NOT IN'
        )
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => 'ukhod'
        )
    )
);

$products = new WP_Query( $args );
if ( $products->have_posts() ) : ?>   
        <div class="card__ukhod-loop">
            <h2 class="heading-2">Товары для ухода за растениями</h2>
            <ul class="products columns-3"> 
                <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                <div>hello</div>
                <?php wc_get_template_part( 'content', 'product' ); ?>

                <?php endwhile; // end of the loop. ?>
            </ul>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

<?php endif;


wp_reset_query();





