<?php 
/**
 * Template Name: Product Search (Custom)
 */

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
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
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
<div  class="content-area">
<?php

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
  's' => 'кала',
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
  's' => 'кала',
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

if ($query_ajax_plants->have_posts() || $query_ajax_other->have_posts()) {
    // Используем Woo-компоненты, чтобы сохранить верстку/сетки
    do_action('woocommerce_before_shop_loop');
    woocommerce_product_loop_start();
    while ($query_ajax_plants->have_posts()) {
        $query_ajax_plants->the_post();
        wc_get_template_part('content', 'product');
    }
    while ($query_ajax_other->have_posts()) {
        $query_ajax_other->the_post();
        wc_get_template_part('content', 'product');
    }
    do_action( 'woocommerce_before_product_loop_end' );   //plnt new action 
    woocommerce_product_loop_end();

    // Пагинация
    // $total_pages = (int) ceil($total / $per_page);
    // echo paginate_links([
    //     'total'   => $total_pages,
    //     'current' => $paged,
    // ]);
    wp_reset_postdata();
} else {
    wc_get_template('loop/no-products-found.php');
}
?>

</div>




<?php //get_footer(); ?>