<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// 1. Получаем данные из ACF поля 'popular_cats'
$popular_cats_acf = get_field('popular_cats');
$popular_cats = [];

// Маппинг ключей из ACF в системные таксономии WooCommerce
$taxonomy_map = [
  'cat' => 'product_cat',
  'tag' => 'product_tag',
];

if ( ! empty( $popular_cats_acf ) && is_array( $popular_cats_acf ) ) {
  foreach ( $popular_cats_acf as $item ) {
    $type_key = $item['type'] ?? '';
    $slug     = trim( $item['name'] ?? '' );

    if ( isset( $taxonomy_map[ $type_key ] ) && ! empty( $slug ) ) {
      $taxonomy = $taxonomy_map[ $type_key ];

      // Проверяем существование термина в БД
      if ( term_exists( $slug, $taxonomy ) ) {
        $popular_cats[] = [
          'taxonomy' => $taxonomy,
          'slug'     => $slug,
        ];
      }
    }
  }
}

// Если категории заполнены и существуют — выводим секцию
if ( ! empty( $popular_cats ) ) : 
  // Берем первый элемент для начальной загрузки товаров и ссылки
  $first_cat     = $popular_cats[0];
  $first_taxonomy = $first_cat['taxonomy'];
  $first_slug     = $first_cat['slug'];
  $first_term_link = get_term_link( $first_slug, $first_taxonomy );
?>


<h2 class="h2">Популярные категории</h2>

<div class="swiper front__cats-swiper">
  <div class="swiper-wrapper front__cats-nav cats-nav">

    <?php foreach ( $popular_cats as $index => $tile ) : 
      $term = get_term_by( 'slug', $tile['slug'], $tile['taxonomy'] );
      if ( ! $term ) continue;

      $active_class = ( $index === 0 ) ? ' cats-nav__title--active' : '';
    ?>

      <button 
        class="swiper-slide button cats-nav__title<?php echo $active_class; ?>" 
        type="button" 
        data-type="<?php echo esc_attr( $tile['taxonomy'] ); ?>" 
        data-term="<?php echo esc_attr( $tile['slug'] ); ?>"
      >
        <?php echo esc_html( $term->name ); ?>
      </button>

    <?php endforeach; ?>

  </div>
</div>

<?php 
  get_template_part( 'template-parts/products/product-slider', null, [
    'queryArgs' => [
      'tax_query' => array(
        array(
          'taxonomy' => $first_taxonomy,
          'field'    => 'slug',
          'terms'    => $first_slug,
        )
      )
    ],
    'isSwiperOver' => true,
  ]);
?>

<a 
  class="front__products-all icon icon--arrow-right" 
  href="<?php echo is_wp_error( $first_term_link ) ? '#' : esc_url( $first_term_link ); ?>"
>
  Все товары категории
</a>

<?php endif; ?>