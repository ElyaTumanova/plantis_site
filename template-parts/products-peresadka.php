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
        echo $post->ID;
        ?>
            <div class="card__peresadka">
            <a href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart"><?php echo get_the_title() ?></a>
            </div>

        <?php endif;

    }

    wp_reset_query();
    wp_reset_postdata();

}

