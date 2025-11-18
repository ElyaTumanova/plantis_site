<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => 'skidki',
          )
        ),
    );
    
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) : ?>  
	
		<div class="product-slider-wrap product-slider-swiper swiper">
			<ul class="products columns-3 swiper-wrapper">
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?> 
			</ul>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
		<a class="main__cats-all" href="<?php echo get_term_link( 'skidki', 'product_tag' );?>">Все товары категории</a>
    
    <?php endif;
 