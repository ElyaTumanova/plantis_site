<?php 
/**
 * Template Name: Product Search (Custom)
 */

$s = get_search_query(); // строка поиска из ?s=
$paged = max(1, (int) get_query_var('paged'));
$per_page = 24;

echo($s);

global $plants_treez_cat_id;
global $peresadka_cat_id;
global $plants_cat_id;

$argPlants = array(
  'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
  'post_status' => 'publish',
  's' => 'замио',
  'orderby' => 'meta_value',
  'meta_key' => '_stock_status',
  'order' => 'ASC',
  'posts_per_page' => 12, // ← вот это определяет количество
  'paged' => $paged,
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
  'orderby' => 'meta_value',
  'meta_key' => '_stock_status',
  'order' => 'ASC',
  'posts_per_page' => 12, // ← вот это определяет количество
  'paged' => $paged,
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

// $total = count($query_ajax_plants->posts) + count($query_ajax_other->posts);
// $total = count($query_ajax_plants->posts);
// echo($total);
echo($query_ajax_plants->max_num_pages);
// echo($paged);

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
		<h1 class="woocommerce-products-header__title page-title">Результаты поиска: <?php echo($s) ?></h1>
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

if ($query_ajax_plants->have_posts() || $query_ajax_other->have_posts()) {

    // Используем Woo-компоненты, чтобы сохранить верстку/сетки
    do_action('woocommerce_before_shop_loop');
    woocommerce_product_loop_start();
  
      while ($query_ajax_plants->have_posts()) {
          $query_ajax_plants->the_post();
            do_action( 'woocommerce_shop_loop' );
            wc_get_template_part( 'content', 'product' );
      }
      // while ($query_ajax_other->have_posts()) {
      //     $query_ajax_other->the_post();
      //     do_action( 'woocommerce_shop_loop' );
      //     wc_get_template_part( 'content', 'product' );
      // }


    do_action( 'woocommerce_before_product_loop_end' );   //plnt new action 
    woocommerce_product_loop_end();

    do_action( 'woocommerce_after_shop_loop' );

    // Пагинация
    $pagination_base = add_query_arg('paged', '%#%');
    wc_get_template('loop/pagination.php', array(
        'total'   => $query_ajax_plants->max_num_pages,
        'current' => $paged,
        'base'    => $pagination_base,
    ));
    wp_reset_postdata();
} else {
   	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
?>
