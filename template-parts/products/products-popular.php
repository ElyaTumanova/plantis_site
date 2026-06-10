<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

echo ('<section class="section">');
echo('<h2 class="h2">Популярные товары </h2>');

  get_template_part( 'template-parts/products/product-slider', null, [
    'queryArgs' => [
      'posts_per_page' => 8,
      'tax_query' => array(
          array(
              'taxonomy' => 'product_visibility',
              'field'    => 'name',
              'terms'    => 'featured',
              'operator' => 'IN'
          )
      )
    ],
    'isSwiperOver' => true,
  ]);
echo('</section>');



