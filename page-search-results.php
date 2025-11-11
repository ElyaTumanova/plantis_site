<?php 
/**
 * Template Name: Product Search
 */
$search = get_query_var('search'); 
$paged = max(1, (int) get_query_var('paged'));
$per_page = 24;

/** 1) Читаем orderby и получаем правильные аргументы сортировки от Woo */
$orderby_value = isset($_GET['orderby'])
    ? wc_clean( wp_unslash( $_GET['orderby'] ) )
    : 'post__in';

$ordering_args = WC()->query->get_catalog_ordering_args( $orderby_value );

$added_filters = [];
if ( $orderby_value === 'price' ) {
    add_filter( 'posts_clauses', [ WC()->query, 'order_by_price_asc_post_clauses' ] );
    $added_filters[] = [ 'posts_clauses', [ WC()->query, 'order_by_price_asc_post_clauses' ] ];
} elseif ( $orderby_value === 'price-desc' ) {
    add_filter( 'posts_clauses', [ WC()->query, 'order_by_price_desc_post_clauses' ] );
    $added_filters[] = [ 'posts_clauses', [ WC()->query, 'order_by_price_desc_post_clauses' ] ];
} elseif ( $orderby_value === 'popularity' ) {
    add_filter( 'posts_clauses', [ WC()->query, 'order_by_popularity_post_clauses' ] );
    $added_filters[] = [ 'posts_clauses', [ WC()->query, 'order_by_popularity_post_clauses' ] ];
} elseif ( $orderby_value === 'rating' ) {
    add_filter( 'posts_clauses', [ WC()->query, 'order_by_rating_post_clauses' ] );
    $added_filters[] = [ 'posts_clauses', [ WC()->query, 'order_by_rating_post_clauses' ] ];
}

$q_page = get_search_query($search);


get_header( 'shop' );
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
  <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
    <h1 class="woocommerce-products-header__title page-title">Результаты поиска: <?php if ($product_sku_id){echo('Артикул ');}; echo esc_html($search) ?></h1>
  <?php endif; ?>

  <?php	do_action( 'woocommerce_archive_description' );?>
</header>
<?php

if ( empty( $q_page->have_posts() ) ) {
    do_action( 'woocommerce_no_products_found' );
    get_template_part('template-parts/products/products-popular');
} else {

  if ( ! empty( $ordering_args['meta_key'] ) ) {
      $q_args['meta_key']  = $ordering_args['meta_key'];      // '_price' или 'total_sales'
      if ( $orderby_value === 'price' || $orderby_value === 'price-desc' ) {
          $q_args['meta_type'] = 'DECIMAL';                   // чтобы точно числовая сортировка
      }
  }

  foreach ( $added_filters as $af ) {
      remove_filter( $af[0], $af[1] );
  }

  wc_set_loop_prop( 'is_paginated', $q_page->max_num_pages > 1 );
  wc_set_loop_prop( 'page', $paged );
  wc_set_loop_prop( 'per_page', $per_page );
  wc_set_loop_prop( 'total', (int) $q_page->found_posts );

  if ($q_page->have_posts()) {

      // Используем Woo-компоненты, чтобы сохранить верстку/сетки
      do_action('woocommerce_before_shop_loop');
      woocommerce_product_loop_start();
    
        while ($q_page->have_posts()) {
            $q_page->the_post();
              do_action( 'woocommerce_shop_loop' );
              wc_get_template_part( 'content', 'product' );
        }

      do_action( 'woocommerce_before_product_loop_end' );   //plnt new action 
      woocommerce_product_loop_end();

      // ПРАВИЛЬНАЯ ПАГИНАЦИЯ
      $big = 999999999; // need an unlikely integer
      
      // Создаем правильную базовую ссылку для пагинации
      $pagination_args = array(
          'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format' => '?paged=%#%',
          'current' => $paged,
          'total' => $q_page->max_num_pages,
          'prev_text' => '&larr;',
          'next_text' => '&rarr;',
          'type' => 'list',
          'end_size' => 2,
          'mid_size' => 1,
          // 'add_args'  => array(
          //   'orderby' => $orderby_value, // ← сохраняем выбранную сортировку
          //   'search'  => $search,        // ← и строку поиска, если вы храните её в query_var 'search'
          // ),
      );
      
      echo '<nav class="woocommerce-pagination">';
      echo paginate_links($pagination_args);
      echo '</nav>';

      do_action( 'woocommerce_after_shop_loop' );

      
      wp_reset_postdata();
  } 
}

do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
?>
