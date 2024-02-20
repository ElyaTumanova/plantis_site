<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
// global $wp_query;
$product_ids_on_sale = wc_get_product_ids_on_sale();
$arg = array(
    'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
    'post__in' => array_merge( array( 0 ), $product_ids_on_sale ),
    'post_status' => 'publish',
    // 's' => get_search_query(),
    'tax_query' => array(
		array(
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => 'komnatnye-rasteniya'
		)
    ),
    'posts_per_page' => -1,
    'meta_query' => array( 
        array(
            'key'       => '_stock_status',
            'value'     => 'outofstock',
            'compare'   => 'NOT IN'
        )
    ),
    'orderby' => 'rand',
);
$on_sale_query = new WP_Query($arg);
// $wp_query = $on_sale_query;
echo '<pre>';
print_r( $on_sale_query );
echo '</pre>';


if( $on_sale_query->have_posts() ) :

    ?><ul class="products columns-2"><?php
        // затем запускаем цикл
        while( $on_sale_query->have_posts() ) : $on_sale_query->the_post();

            $product = wc_get_product( get_the_ID() );
            if ($product->is_in_stock()) {
                wc_get_template_part( 'content', 'product' );
            } 
        endwhile;
    ?></ul><?php
endif;

// восстанавливаем глобальную переменную $post
wp_reset_postdata();
 
