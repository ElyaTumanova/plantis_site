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
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => 'komnatnye-rasteniya',
                    'operator' => 'IN'
                )
            )
        );

        $products = new WP_Query( $args );
        $cart_item_ids = [];
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            array_push($cart_item_ids, $product_id);
        }
        print_r($cart_item_ids);
    
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

                        <?php $prod_id = get_the_ID(); ?>
                        <?php if (!in_array($prod_id, $cart_item_ids))?>
                        <li class="swiper-slide product">
                            <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'medium' );?>" class="backorder-crossells__img attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="<?php echo get_the_title();?>">
                            <a href="<?php echo get_permalink();?>" class="backorder-crossells__link" target="blank">
                                <div class="backorder-crossells__title woocommerce-loop-product__title"><?php echo get_the_title();?></div>
                            </a>
                            <span class="price backorder-crossells__price"><?php echo get_post_meta( get_the_ID(), '_price', true);?>&#8381;</span>
                            <button class='backorder_replace_btn' data-product_id="<?php echo $prod_id; ?>" data-cart_item="<?php echo $cart_item; ?>">Заменить</button>
                        </li>
                        <?php endif;?>
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

