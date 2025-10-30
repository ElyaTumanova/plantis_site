<?php 
$search = get_query_var('search'); 
$paged = max(1, (int) get_query_var('paged'));
$per_page = 24;

global $plants_treez_cat_id, $peresadka_cat_id, $plants_cat_id;

/** 1) Читаем orderby и получаем правильные аргументы сортировки от Woo */
$orderby_value = isset($_GET['orderby'])
    ? wc_clean( wp_unslash( $_GET['orderby'] ) )
    : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );

$ordering_args = WC()->query->get_catalog_ordering_args( $orderby_value );

$argPlants = array(
  'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
  'post_status' => 'publish',
  's' => $search,
  'fields'         => 'ids',
  'posts_per_page' => -1,
  'no_found_rows'  => true,
  'orderby' => 'meta_value',
  'meta_key' => '_stock_status',
  'order' => 'ASC',
  'tax_query' => array(
      array(
          'taxonomy' => 'product_cat',
          'field' => 'id',
          'operator' => 'IN',
          'terms' => [$plants_cat_id],
          'include_children' => 1,
      )
  )
);
$argOther = array(
  'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
  'post_status' => 'publish',
  's' => $search,
  'fields'         => 'ids',
  'posts_per_page' => -1,
  'no_found_rows'  => true,
  'orderby' => 'meta_value',
  'meta_key' => '_stock_status',
  'order' => 'ASC',
  'meta_query' => array( 
      array(
          'key'       => '_stock_status',
          'value'     => 'outofstock',
          'compare'   => 'NOT IN',
          )
          
  ),
  'tax_query' => array(
      array(
          'taxonomy' => 'product_cat',
          'field' => 'id',
          'operator' => 'NOT IN',
          'terms' => [$plants_treez_cat_id, $peresadka_cat_id, $plants_cat_id],
          'include_children' => 1,
      )
  )
);
$query_ajax_plants = new WP_Query($argPlants);
$query_ajax_other = new WP_Query($argOther);

$product_sku_id_plants = wc_get_product_id_by_sku( $query_ajax_plants->query_vars[ 's' ] );
$product_sku_id_other = wc_get_product_id_by_sku( $query_ajax_other->query_vars[ 's' ] );
$product_sku_id = $product_sku_id_plants ?: $product_sku_id_other ?: 0;

if ($product_sku_id) {
  $all_ids = [$product_sku_id];
} else {
  $ids_plants = array_map('intval', (array) $query_ajax_plants->posts);
  $ids_others = array_map('intval', (array) $query_ajax_other->posts);
  $all_ids = array_values(array_unique(array_merge($ids_plants, $ids_others)));
}

$q_args = [
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'post__in'       => $all_ids,
    'orderby'        => $ordering_args['orderby'],
    'order'          => $ordering_args['order'],
    'posts_per_page' => 12,
    'paged' => $paged,
    'ignore_sticky_posts' => true,
    'no_found_rows'  => false,
];

if ( ! empty( $ordering_args['meta_key'] ) ) {
  $q_args['meta_key'] = $ordering_args['meta_key']; // нужно для price/popularity/rating
}

$q_page = new WP_Query( $q_args );

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title">Результаты поиска: <?php if ($product_sku_id){echo('Артикул ');}; echo esc_html($search) ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<?php

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
        'add_args'  => array(
          'orderby' => $orderby_value, // ← сохраняем выбранную сортировку
          'search'  => $search,        // ← и строку поиска, если вы храните её в query_var 'search'
        ),
    );
    
    echo '<nav class="woocommerce-pagination">';
    echo paginate_links($pagination_args);
    echo '</nav>';

    do_action( 'woocommerce_after_shop_loop' );

    
    wp_reset_postdata();
} else {
   	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
?>
