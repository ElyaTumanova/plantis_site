<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// поиск
add_action('wp_ajax_search-ajax', 'plnt_search_ajax_action_callback');
add_action('wp_ajax_nopriv_search-ajax', 'plnt_search_ajax_action_callback');

function plnt_search_ajax_action_callback (){
    global $plants_treez_cat_id;
    global $peresadka_cat_id;
    global $plants_cat_id;
    if(!wp_verify_nonce($_POST['nonce'], 'search-nonce')){
        wp_die('Данные отправлены не с того адреса');
    }

    $argPlants = array(
      'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
      'post_status' => 'publish',
      's' => $_POST['s'],
      'orderby' => 'meta_value',
      'meta_key' => '_stock_status',
      'order' => 'ASC',
      // 'posts_per_page' => -1,
      // 'meta_query' => array( 
      //     array(
      //         'key'       => '_stock_status',
      //         'value'     => 'outofstock',
      //         'compare'   => 'NOT IN',
      //         )
              
      // ),
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
      's' => $_POST['s'],
      'orderby' => 'meta_value',
      'meta_key' => '_stock_status',
      'order' => 'ASC',
      // 'posts_per_page' => -1,
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
    $query_ajax = array_merge($query_ajax_plants, $query_ajax_other);

    

    $product_sku_id = wc_get_product_id_by_sku( $query_ajax->query_vars[ 's' ] );
    // print_r($product_sku_id);
    $json_data['out'] = ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
    ?> <div class='serach-result__items'> <?php

    if ($product_sku_id) { 
      $product = wc_get_product( $product_sku_id );
      // print_r($product);
      // print_r($product->get_id());
      $short_descr = $product->get_short_description();
      $title = $product->get_title();
      $sale = get_post_meta( $product_sku_id, '_sale_price', true);
      render_search_result($product);
      ?>
        </div>
        <input class="search-result__btn button" type="submit" form ="searchform" value="Посмотреть все" />
      <?php
    } else {
      if ($query_ajax->have_posts()) {
        // echo '<pre>';
        // print_r( $query_ajax );
        // echo '</pre>';
        while ($query_ajax->have_posts()){
          $query_ajax->the_post();
          $product = wc_get_product( get_the_ID() );
          render_search_result($product);
        }
        ?>
        </div>
        <input class="search-result__btn button" type="submit" form ="searchform" value="Посмотреть все" />
        <?php
      } else {
        ?>
        <div class="search-result__text">Ничего не найдено</div>
        </div>
        <?php
      }
    } 
    $json_data['out'] = ob_get_clean();
    wp_send_json($json_data);
    wp_die();
}

function render_search_result($product) {
  $id = $product->get_id();
  $sale = get_post_meta( $id, '_sale_price', true);
  ?>
    <div class="search-result__item">
        <a href="<?php echo $product->get_permalink();?>" class="search-result__link" target="blank">
          <img src="<?php echo get_the_post_thumbnail_url( $id, 'thumbnail' );?>" class="search-result__image" alt="<?php echo $product->get_title();?>">
          <div class="search-result__info">
            <div class="search-result__row">
              <span class="search-result__title"><?php echo $product->get_title();?></span>
              <!-- <span class="search-result__descr"><?php //echo $product->get_short_description();?></span> -->
              <?php plnt_check_stock_status();?>
            </div>
            <div class="search-result__row">
              <?php if ($sale) {
                ?>
                      <span class="search-result__reg-price"><?php echo get_post_meta( $id, '_regular_price', true);?>&#8381;</span>
                      <span class="search-result__price"><?php echo get_post_meta( $id, '_sale_price', true);?>&#8381;</span>
                      <?php
                  } else {
                    ?>
                      <span class="search-result__price"><?php echo get_post_meta( $id, '_price', true);?>&#8381;</span>
                      <?php 
                  }
                  ?>
                </div>
              </div>
        </a>  
    </div>
  <?php
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