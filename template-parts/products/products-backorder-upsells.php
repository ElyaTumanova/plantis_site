<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//global $product;
$product_id = $args[ 'product_id' ];
echo $product_id;
$product = wc_get_product( $product_id );
echo $product;

//$crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' );
//$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids' );
$upsells_ids = $product->get_upsell_ids();


if( !empty ($upsells_ids) ){

   // $crosssell_ids = $crosssell_ids[0];

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
        $products = new WP_Query( $args );
        if ( $products->have_posts() ) : ?>

            <div class="card__sliders-wrap up-sells upsells">

                <?php
                $heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) );

                if ( $heading ) :
                    ?>
                    <h2 class="heading-2"><?php echo esc_html( $heading ); ?></h2>
                <?php endif; ?>    
                    
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
    wp_reset_postdata();

}

