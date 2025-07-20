<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package estore
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php
					/* translators: %s: search query. */
					printf( 'Результаты поиска: %s', '<span>' . get_search_query() . '</span>' );
				?></h1>
			</header><!-- .page-header -->

            <?php
            // global $wp_query;
            // $arg = array(
            //     'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
            //     'post_status' => 'publish',
            //     's' => get_search_query(),
            //     'posts_per_page' => -1,
            //     'meta_query' => array( 
            //         array(
            //             'key'       => '_stock_status',
            //             'value'     => 'outofstock',
            //             'compare'   => 'NOT IN'
            //         )
            //     )
            // );
            // $search_query = new WP_Query($arg);
            // $wp_query = $search_query;
            ?>

            <div class="catalog__grid">
                <!-- <div class="catalog__sidebar">
                    <?php //plnt_catalog_menu() ?>
                </div> -->
                <div class="catalog__products-wrap">
                    <ul class="products columns-2"> 
                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();
                        $product = wc_get_product( get_the_ID() );
                        // if ($product->is_in_stock()) {
                            wc_get_template_part( 'content', 'product' );
                        // }                      
                        endwhile;
                        ?>
                    </ul> 
                    
                    <?php

                    // the_posts_pagination();

                    the_posts_pagination( array(
                        'class' => 'woocommerce-pagination',
                        'prev_text'    => '←',
	                    'next_text'    => '→',
                    ));

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif; ?>
                     
                </div>
            </div>
			

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();