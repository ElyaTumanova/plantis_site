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
                    'value'     => 'outofstock',
                    'compare'   => 'NOT IN'
                )
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => 'peresadka'
                )
            )
        );

        $products = new WP_Query( $args );
        if ( $products->have_posts() ) : $products->the_post(); 
        
        //echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
        //echo $post->ID;
        ?>
            <div class="card__peresadka">
            <!-- <a href="<?php// echo get_site_url()?>/?add-to-cart=<?php //echo $post->ID?>" data-quantity="1" class="button"><?php //echo get_the_title() ?></a> -->
            <a href="?add-to-cart=8495" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="8495" data-product_sku="P00194" aria-label="Добавить в корзину “Сансевиерия Трёхполосная Лауренти 17/60”" aria-describedby="" rel="nofollow" data-product-name="Сансевиерия Трёхполосная Лауренти 17/60" data-product-price="3810" data-category-name="Комнатные растения" data-remove_link="http://new.plantis.shop/cart/?remove_item=3488330ba18d83e3d0ab177178ca66eb&amp;_wpnonce=94d13f3c9b" data-cart_item_key="3488330ba18d83e3d0ab177178ca66eb">В корзину</a>
            </div>

        <?php endif;

    }

    wp_reset_query();
    wp_reset_postdata();

}

