<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('wp_ajax_get_main_cats_term', 'plnt_main_cats_slider_action_callback');
add_action('wp_ajax_nopriv_get_main_cats_term', 'plnt_main_cats_slider_action_callback');

function plnt_main_cats_slider_action_callback() {
    $args = array(
        'post_type' => 'product',
        'ignore_sticky_posts' => 1,
        'no_found_rows' => 1,
        'posts_per_page' => 8,
        'orderby' => 'rand',
        'meta_query' => array( 
            array(
                'key'       => '_stock_status',
                'value'     => array('outofstock','onbackorder'),
                'compare'   => 'NOT IN'
            )
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'dekorativno-listvennye'
            )
        )
    );
    
    //$products = new WP_Query( $args );
    echo $args;
  
    wp_die();
}
