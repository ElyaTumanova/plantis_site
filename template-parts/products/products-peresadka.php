<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//global $product;
$product_id = $args[ 'product_id' ];
$product = wc_get_product( $product_id );

//$crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' );
$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids' );


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
            ?>
            <svg class="peresadka_plus_icon" viewBox="0 0 24 24" width="18" height="18" fill="none" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M12 6V18" stroke="#000000" stroke-width="1.3"></path><path d="M6 12L18 12" stroke="#000000" stroke-width="1.3"></path></svg>
            <?php
            woocommerce_template_loop_add_to_cart();
            woocommerce_quantity_input();
        endif;
    }
    wp_reset_query();
    wp_reset_postdata();

}

