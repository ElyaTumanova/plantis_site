<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// поиск
add_action('wp_ajax_search-ajax', 'plnt_search_ajax_action_callback');
add_action('wp_ajax_nopriv_search-ajax', 'plnt_search_ajax_action_callback');

function plnt_search_ajax_action_callback (){

  // $received = isset($_POST['nonce']) ? wp_unslash($_POST['nonce']) : '';
  // $expected = wp_create_nonce('search-nonce');
  // $verify   = wp_verify_nonce($received, 'search-nonce');

  // wp_send_json([
  //   'received' => $received,
  //   'expected' => $expected,
  //   'verify'   => $verify,              // 0 / 1 / 2
  //   'uid'      => get_current_user_id(),
  //   'token'    => wp_get_session_token(),
  //   'tick'     => wp_nonce_tick(),
  // ]);


    global $plants_treez_cat_id;
    global $peresadka_cat_id;
    global $plants_cat_id;
    if ( ! check_ajax_referer('search-nonce', 'nonce', false) ) {
      wp_send_json_error(['message' => 'Bad nonce'], 403);
    }


    $result = plnt_get_search_query($_POST['s']);
    $q_page = $result['query'];
    $json_data['out'] = ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
    ?> <div class='serach-result__items'> <?php

    if($q_page->have_posts()) {
      while ($q_page->have_posts()){
        $q_page->the_post();
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
            <div class="search-result__row search-result__row_price">
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

function plnt_get_search_query($search, $ordering_args=null, $per_page=null, $paged=null) {
  global $plants_treez_cat_id, $peresadka_cat_id, $plants_cat_id;
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
    $ids_by_cat_synonyms = [];

    $matched_cats = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'meta_query' => [
            [
                'key'     => '_synonyms',
                'value'   => $search,
                'compare' => 'LIKE',
            ],
        ],
        'fields' => 'ids',
    ]);

    if ( ! is_wp_error( $matched_cats ) && ! empty( $matched_cats ) ) {
      $ids_by_cat_synonyms = get_posts([
          'post_type'      => 'product',
          'post_status'    => 'publish',
          'fields'         => 'ids',
          'orderby' => 'meta_value',
          'meta_key' => '_stock_status',
          'order' => 'ASC',
          'posts_per_page' => -1,
          'no_found_rows'  => true,
          'tax_query'      => [
              [
                  'taxonomy'         => 'product_cat',
                  'field'            => 'term_id',
                  'terms'            => $matched_cats,
                  'include_children' => true,
              ],
          ],
      ]);
    }

    $ids_by_product_synonyms = [];
    $ids_by_product_synonyms = get_posts([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'orderby' => 'meta_value',
	      'meta_key' => '_stock_status',
        'order' => 'ASC',
        'posts_per_page' => -1,
        'no_found_rows'  => true,
        'meta_query'     => [
            [
                'key'     => '_synonyms',
                'value'   => $search,
                'compare' => 'LIKE',
            ],
        ],
    ]);


    $ids_plants = array_map('intval', (array) $query_ajax_plants->posts);
    $ids_others = array_map('intval', (array) $query_ajax_other->posts);
    $all_ids = array_values(array_unique(array_merge($ids_plants, $ids_others, $ids_by_cat_synonyms, $ids_by_product_synonyms)));
  }

  $q_args = [
      'post_type'      => 'product',
      'post_status'    => 'publish',
      'post__in'       => !empty($all_ids) ? $all_ids : [0],
      'ignore_sticky_posts' => true,
      'no_found_rows'  => false,
  ];

  if ($ordering_args) {
    $q_args['orderby'] = $ordering_args['orderby'];
    $q_args['order'] = $ordering_args['order'];
  } else {
    $q_args['orderby'] = 'post__in';
  }
  if ($per_page) {
    $q_args['posts_per_page'] = $per_page;
  }
  if ($paged) {
    $q_args['paged'] = $paged;
  }


  $q_page = new WP_Query( $q_args );
  $result = [
    'query' => $q_page,
    'sku' => $product_sku_id
  ];
  return $result;
}

add_action('wp_ajax_get_search_nonce', 'plnt_get_search_nonce');
add_action('wp_ajax_nopriv_get_search_nonce', 'plnt_get_search_nonce');
function plnt_get_search_nonce() {
  wp_send_json_success(['nonce' => wp_create_nonce('search-nonce')]);
}