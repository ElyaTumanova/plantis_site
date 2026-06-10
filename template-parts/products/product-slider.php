<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$queryArgs = $args['queryArgs'] ?? null;
$isSwiperOver = $args['isSwiperOver'] ?? false;

$queryArgs = wp_parse_args( $queryArgs, 
  array(
      'post_type' => 'product',
      'ignore_sticky_posts' => 1,
      'no_found_rows' => false,
      'posts_per_page' => 12,
      'orderby' => 'rand',
      'meta_query' => array( 
          array(
              'key'       => '_stock_status',
              'value'     => array('outofstock','onbackorder'),
              'compare'   => 'NOT IN'
          )
      ),
  )
);

$products = new WP_Query( $queryArgs );

if (!$products) {
    return;
}

?>

<div class="<?php echo $isSwiperOver ? 'swiper--over' : ''?>" data-js-product-slider>
  <div class="product-slider-wrap product-slider-swiper swiper">
    <ul class="products columns-3 swiper-wrapper">
      <?php while ( $products->have_posts() ) : $products->the_post(); ?>
  
      <?php wc_get_template_part( 'content', 'product' ); ?>
  
      <?php endwhile; // end of the loop. ?>
    </ul>
    <div class="swiper-scrollbar"></div>
  </div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>

<?php wp_reset_postdata(); ?>

