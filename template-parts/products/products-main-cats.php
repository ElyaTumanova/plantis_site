<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args = array(
    'post_type' => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows' => 1,
    'posts_per_page' => 12,
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
        'taxonomy' => 'product_tag',
        'field' => 'slug',
        'terms' => 'skidki',
      )
    ),
);

$products = new WP_Query( $args );
if ( $products->have_posts() ) : ?>  

<?php 
  get_template_part('template-parts/products/product-slider', null, [
    'products' => $products,
  ]);
?>

<a class="front__products-all icon icon--arrow-right" href="<?php echo get_term_link( 'skidki', 'product_tag' );?>">Все товары категории</a>

<?php endif;
 