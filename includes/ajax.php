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
  // get_template_part('template-parts/products/products-plants-test',null,
  //     array( // массив с параметрами
  //         'cat_slug' => $cat_slug,
  //     ));

  get_template_part('template-parts/products/product-slider', null, [
    'queryArgs' => [
      'tax_query' => [
        [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $cat_slug,
        ],
      ],
    ],
  ]);
      
  $response['test_upsells'] = ob_get_clean();

	wp_send_json($response);
	die();
}

// вывод слайдеров товаров на главной
add_action('wp_ajax_get_main_cats_term', 'plnt_main_cats_slider_action_callback');
add_action('wp_ajax_nopriv_get_main_cats_term', 'plnt_main_cats_slider_action_callback');

function plnt_main_cats_slider_action_callback() {
  $start_main = microtime(true);

  $term_slug = isset($_POST['term']) ? sanitize_title(wp_unslash($_POST['term'])) : '';
  $term_type = isset($_POST['type']) ? sanitize_key(wp_unslash($_POST['type'])) : '';

  if (!$term_slug || !$term_type || !taxonomy_exists($term_type)) {
      wp_send_json_error([
          'message' => 'Invalid term or taxonomy',
      ]);
  }

  $term = get_term_by('slug', $term_slug, $term_type);

  if (!$term || is_wp_error($term)) {
      wp_send_json_error([
          'message' => 'Term not found',
      ]);
  }

  ob_start();

  get_template_part('template-parts/products/product-slider', null, [
    'queryArgs' => [
      'tax_query' => [
        [
            'taxonomy' => $term_type,
            'field'    => 'slug',
            'terms'    => $term_slug,
        ],
      ],
    ],
    'isSwiperOver' => true
  ]);

  $slider_html = ob_get_clean();

  $link_html = sprintf(
    '<a class="front__products-all icon icon--arrow-right" href="%s">Все товары категории</a>',
    esc_url(get_term_link($term))
  );

  wp_send_json_success([
    'out'         => $slider_html,
    'out_link'    => $link_html,
    'main_timing' => round((microtime(true) - $start_main) * 1000, 2),
  ]);
}