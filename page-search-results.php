<?php 
$search = get_search_query(); // строка поиска из ?s=
$paged = max(1, (int) get_query_var('paged'));
$per_page = 24;

global $plants_treez_cat_id;
global $peresadka_cat_id;
global $plants_cat_id;

$argPlants = array(
  'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
  'post_status' => 'publish',
  's' => 'замио',
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
  's' => 'замио',
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

$ids_plants = array_map('intval', (array) $query_ajax_plants->posts);
$ids_others = array_map('intval', (array) $query_ajax_other->posts);
$all_ids = array_values(array_unique(array_merge($ids_plants, $ids_others)));

$q_page = new WP_Query([
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'post__in'       => $all_ids,
    'orderby'        => 'post__in',
    'posts_per_page' => 12,
    'paged' => $paged,
    'no_found_rows'  => false,
]);

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
		<h1 class="woocommerce-products-header__title page-title">Результаты поиска: <?php echo esc_html($search) ?></h1>
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
        'end_size' => 3,
        'mid_size' => 3
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
