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
        if ( $products->have_posts() ) : $products->the_post(); 
            woocommerce_template_loop_add_to_cart();
            woocommerce_quantity_input();
        endif;
    }
    wp_reset_query();
    wp_reset_postdata();

}

