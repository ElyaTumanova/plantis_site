<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$cat_slug = $args[ 'cat_slug' ];

global $plants_cat_id;
$lowest_cats = get_lowest_level_product_categories($plants_cat_id);
$cat_exist = false;
foreach ( $lowest_cats as $cat ) {
    $slug = $cat ->slug;
    if( $slug == $cat_slug) {
        $cat_exist = true;
        break;
    }
}
if ($cat_exist) {
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
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $cat_slug,
            )
        )
    );

    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>  
            <?php 
            function plnt_add_class_loop_test_swiper($clasess, $product){
                $clasess[] = 'swiper-slide2';
                return $clasess;
            }; ?> 
            <div class="test-plants-wrap product-slider-wrap">
                <h2 class="my_header__title">Это мог бы быть ты</h2>
                <div class="product-slider-swiper swiper">
                    <ul class="products columns-3 swiper-wrapper"> 
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                        <?php add_filter('woocommerce_post_class', 'plnt_add_class_loop_test_swiper', 10, 3);
                            // global $product;
                            ?>
                        <?php wc_get_template_part( 'content', 'product' ); ?>
                        <?php endwhile; // end of the loop. ?>
                    </ul>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                <a class="button test-plants-btn" href="<?php echo get_term_link( $cat_slug, 'product_cat' );?>">Все растения категории</a>
            </div>

    <?php endif;


    wp_reset_query();
} else {
    echo('no such cat');
}





