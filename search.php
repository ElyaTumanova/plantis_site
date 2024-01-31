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
					printf( esc_html__( 'Search Results for: %s', 'estore' ), '<span>' . get_search_query() . '</span>' );
				?></h1>
			</header><!-- .page-header -->
            <?php
            // if ( is_search() ) {
            //     echo "Search page";
            // }
            global $wp_query;

            $arg = array(
                'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
                'post_status' => 'publish',
                's' => get_search_query(),
                'meta_query' => array( 
                    array(
                        'key'       => '_stock_status',
                        'value'     => 'outofstock',
                        'compare'   => 'NOT IN'
                    )
                )
            );
            $query = new WP_Query($arg);

            $wp_query = $query;

            echo '<pre>';
            print_r( $wp_query );
            echo '</pre>';     
            // ?>
            <div class="catalog__grid">
                <div class="catalog__sidebar">
                    <?php plnt_catalog_menu() ?>
                </div>
                <div class="catalog__products-wrap">
                    <ul class="products columns-2"> 
                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) : the_post();
                        // global $post;
                        $product = wc_get_product( get_the_ID() );
                        // echo '<pre>';
                        // print_r( $product );
                        // echo '</pre>';   
                        if ($product->is_in_stock()) {
                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
                            // get_template_part( 'template-parts/content', 'search' );
                            wc_get_template_part( 'content', 'product' );
                        }                      
                        endwhile;
                        ?>
                    </ul> 
                    
                    <?php

                    // the_posts_navigation();
                    the_posts_pagination();

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