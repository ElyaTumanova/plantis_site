<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// вывод товаров в результатх теста
add_action('wp_ajax_get_test_upsells', 'plnt_get_test_upsells_action_callback');
add_action('wp_ajax_nopriv_get_test_upsells', 'plnt_get_test_upsells_action_callback');

function plnt_get_test_upsells_action_callback() {
    $cat_slug = $_POST['cat_slug'];
    ob_start();
    get_template_part('template-parts/products/products-plants-test',null,
        array( // массив с параметрами
            'cat_slug' => $cat_slug,
        ));
        
    $response['test_upsells'] = ob_get_clean();

	wp_send_json($response);
	die();
    
}

// вывод слайдеров товаров на главной
add_action('wp_ajax_get_main_cats_term', 'plnt_main_cats_slider_action_callback');
add_action('wp_ajax_nopriv_get_main_cats_term', 'plnt_main_cats_slider_action_callback');

function plnt_main_cats_slider_action_callback() {
  $start_main = microtime(true);
	$term_slug = $_POST['term'];
	$term_type = $_POST['type'];
    //   WC()->session->set('term_slug', $_POST['term'] );
    //   WC()->session->set('term_type', $_POST['type'] );

    $args = array(
        'post_type' => 'product',
        'ignore_sticky_posts' => 1,
        'no_found_rows' => 1,
        'posts_per_page' => 8,
        'orderby' => 'rand',
        'meta_query' => array( 
            array(
                'key'       => '_stock_status',
                'value'     => array('outofstock','onbackorder'),
                'compare'   => 'NOT IN'
            )
        ),
        'tax_query' => array(
          array(
            'taxonomy' => $term_type,
            'field' => 'slug',
            'terms' => $term_slug,
          )
        ),
    );
    
    $products = new WP_Query( $args );
	  $json_data['out'] = ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
    if ( $products->have_posts() ) : ?>  
	
		<div class="product-slider-wrap product-slider-swiper swiper">
			<ul class="products columns-3 swiper-wrapper">
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?> 
			</ul>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
		<a class="main__cats-all" href="<?php echo get_term_link( $term_slug, $term_type );?>">Все товары категории</a>
    
    <?php endif;
    ?>
    <!-- <div><?php //echo 'hello '.$term_slug.' '.$term_type ;?></div> -->
    <?php
    $main_timing = round((microtime(true) - $start_main) * 1000, 2);
    $json_data['out'] = ob_get_clean();
    $json_data['main_timing'] = $main_timing;
    wp_send_json($json_data);
    wp_die();
};