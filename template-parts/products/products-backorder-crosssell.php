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
            $i = 1;
            ?>
            <div class="backorder-crossells__preview-wrap">
                <div class="backorder-crossells__preview-title">Похожие растения в наличии</div>
                <!-- <div class="backorder-crossells__preview"> -->
                    <?php
                        // while ( $products->have_posts() ) : $products->the_post();
                        //     if($i <= 4) {
                                ?>
                                <!-- <img src="<?php //echo get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );?>" class="backorder-crossells__preview-img" alt="<?php //echo get_the_title();?>"> -->
                                <?php
                        //    }
                        //     $i++;
                        // endwhile;
                    ?>
                <!-- </div> -->
                <div class="backorder-crossells__preview-down"><span class="backorder-crossells__preview-down-open">Развернуть</span><span class="backorder-crossells__preview-down-close">Свернуть</span><span class="backorder-crossells__preview-arrow">next</span></div>
            </div>
     
          <div class="backorder-crossells__sliders-wrap product-slider-wrap">
                  <div class="backorder-crossells-swiper swiper">
                    <ul class="products columns-3 swiper-wrapper"> 
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>
                        <div class='backorder_replace_btn'>lalala</div>

                        <?php endwhile; // end of the loop. ?>
                    </ul>
                    <div class="swiper-scrollbar"></div>
                </div>
            </div> 

        <?php endif;
    }
    wp_reset_query();
    wp_reset_postdata();

}

