<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$queryArgs = $args['queryArgs'] ?? null;
$isSwiperOver = $args['isSwiperOver'] ?? false;

$slides_mobile  = $args['slidesMobile'] ?? null;
$slides_tablet  = $args['slidesTablet'] ?? null;
$slides_desktop = $args['slidesDesktop'] ?? null;

$space_mobile   = $args['spaceMobile'] ?? null;
$space_tablet   = $args['spaceTablet'] ?? null;
$space_desktop  = $args['spaceDesktop'] ?? null;


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

<div class="<?php echo $isSwiperOver ? 'swiper--over' : ''?>" 
  data-js-product-slider
  <?php echo $slides_mobile ? 'data-slides-mobile="' . esc_attr( $slides_mobile ) . '"' : ''; ?>
  <?php echo $slides_tablet ? 'data-slides-tablet="' . esc_attr( $slides_tablet ) . '"' : ''; ?>
  <?php echo $slides_desktop ? 'data-slides-desktop="' . esc_attr( $slides_desktop ) . '"' : ''; ?>

  <?php echo $space_mobile ? 'data-space-mobile="' . esc_attr( $space_mobile ) . '"' : ''; ?>
  <?php echo $space_tablet ? 'data-space-tablet="' . esc_attr( $space_tablet ) . '"' : ''; ?>
  <?php echo $space_desktop ? 'data-space-desktop="' . esc_attr( $space_desktop ) . '"' : ''; ?>
  
>
  <div class="product-slider-wrap product-slider-swiper swiper">
    <ul class="products columns-2-mob columns-3 swiper-wrapper">
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

