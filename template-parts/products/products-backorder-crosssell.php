<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$product_id = $args[ 'product_id' ];
$cart_item = $args[ 'cart_item' ];
$product = wc_get_product( $product_id );
$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids' );

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

                        <?php //wc_get_template_part( 'content', 'product' ); ?>
                        <?php $prod_id = get_the_ID(); ?>
                        <li class="swiper-slide product">
                            <a href="<?php echo get_permalink();?>" class="backorder-crossells__link" target="blank">
                                <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );?>" class="backorder-crossells__preview-img" alt="<?php echo get_the_title();?>">
                                <div class="backorder-crossells__title woocommerce-loop-product__title"><?php echo get_the_title();?></div>
                                <span class="price backorder-crossells__price"><?php echo get_post_meta( get_the_ID(), '_price', true);?>&#8381;</span>
                                <button class='backorder_replace_btn' data-product_id="<?php echo $prod_id; ?>" data-cart_item="<?php echo $cart_item; ?>">lalala<?php echo $prod_id; ?></button>
                            </a>
                        </li>
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

