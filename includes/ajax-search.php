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

    if ( ! check_ajax_referer('search-nonce', 'nonce', false) ) {
      wp_send_json_error(['message' => 'Bad nonce'], 403);
    }

    $raw = isset($_POST['s']) ? wp_unslash($_POST['s']) : '';
    $query = trim( sanitize_text_field($raw) );

    // не ищем мусор
    if (mb_strlen($query) < 3) {
      wp_send_json(['out' => '', 'found' => 0]);
    }

    $result = plnt_get_search_query($query);
    $q_page = $result['query'];
    $found = (int) ($q_page->found_posts ?? 0);
    plnt_log_search_query($query, $found);
    ob_start();
    ?> <div class='search-result__items'> <?php

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
    wp_reset_postdata();

    $out = ob_get_clean();

    wp_send_json([
      'out'   => $out,
      'found' => $found,
      '$query' => $query
    ]);
}

function render_search_result($product) {
  if (!$product instanceof WC_Product) return;

  $id    = (int) $product->get_id();
  $url   = $product->get_permalink();
  $title = $product->get_title();

  $thumb = get_the_post_thumbnail_url($id, 'thumbnail');
  if (!$thumb) {
    // запасной вариант (можно убрать, если не нужно)
    $thumb = wc_placeholder_img_src('thumbnail');
  }

  $sale    = get_post_meta($id, '_sale_price', true);
  $regular = get_post_meta($id, '_regular_price', true);
  $price   = get_post_meta($id, '_price', true);

  // Форматирование цен
  $price_html = ($price !== '' && $price !== null) ? wc_price((float) $price) : '';
  $reg_html   = ($regular !== '' && $regular !== null) ? wc_price((float) $regular) : '';
  $sale_html  = ($sale !== '' && $sale !== null) ? wc_price((float) $sale) : '';
  ?>
  <div class="search-result__item">
    <a href="<?php echo esc_url($url); ?>"
       class="search-result__link"
       target="_blank" rel="noopener noreferrer">

      <img src="<?php echo esc_url($thumb); ?>"
           class="search-result__image"
           alt="<?php echo esc_attr($title); ?>">

      <div class="search-result__info">
        <div class="search-result__row">
          <span class="search-result__title"><?php echo esc_html($title); ?></span>
          <?php plnt_check_stock_status(); ?>
        </div>

        <div class="search-result__row search-result__row_price">
          <?php if ($sale_html && $reg_html): ?>
            <span class="search-result__reg-price"><?php echo wp_kses_post($reg_html); ?></span>
            <span class="search-result__price"><?php echo wp_kses_post($sale_html); ?></span>
          <?php else: ?>
            <span class="search-result__price"><?php echo wp_kses_post($price_html); ?></span>
          <?php endif; ?>
        </div>
      </div>
    </a>
  </div>
  <?php
}


function plnt_get_search_query($search_init, $ordering_args=null, $per_page=null, $paged=null) {
  $search_init = (string)$search_init;

  // убираем все виды тире/дефисов и кавычек
  $search = preg_replace(
      '/[\p{Pd}\x{2212}\x{2043}\p{Pi}\p{Pf}"\'`]+/u',
      '',
      $search_init
  );

  // дополнительно чистим лишние пробелы
  $search = preg_replace('/\s+/u', ' ', $search);
  $search = trim($search);

  // (опционально) SKU — если хотите, чтобы он был самым первым:
  $sku_id = wc_get_product_id_by_sku($search_init);
  $ids_title   = plnt_collect_ids_by_text($search, 'title');
  $ids_excerpt = plnt_collect_ids_by_text($search, 'excerpt');

  // 2) synonyms (как у вас, почти без изменений)
  $ids_by_cat_synonyms = [];
  $matched_cats = get_terms([
      'taxonomy'   => 'product_cat',
      'hide_empty' => false,
      'meta_query' => [[
          'key'     => '_synonyms',
          'value'   => $search,
          'compare' => 'LIKE',
      ]],
      'fields' => 'ids',
  ]);

  if (!is_wp_error($matched_cats) && !empty($matched_cats)) {
    $ids_by_cat_synonyms = get_posts([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'orderby'        => 'meta_value',
        'meta_key'       => '_stock_status',
        'order'          => 'ASC',
        'posts_per_page' => -1,
        'no_found_rows'  => true,
        'tax_query'      => [[
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => $matched_cats,
            'include_children' => true,
        ]],
    ]);
  }

  $ids_by_product_synonyms = get_posts([
      'post_type'      => 'product',
      'post_status'    => 'publish',
      'fields'         => 'ids',
      'orderby'        => 'meta_value',
      'meta_key'       => '_stock_status',
      'order'          => 'ASC',
      'posts_per_page' => -1,
      'no_found_rows'  => true,
      'meta_query'     => [[
          'key'     => '_synonyms',
          'value'   => $search,
          'compare' => 'LIKE',
      ]],
  ]);

  $ids_syn = array_values(array_unique(array_merge(
      array_map('intval', (array)$ids_by_cat_synonyms),
      array_map('intval', (array)$ids_by_product_synonyms)
  )));

  // 3) content (описание)
  $ids_content = plnt_collect_ids_by_text($search, 'content');

  // Склейка с приоритетом групп + без дублей (порядок сохраняется)
  $all_ids = [];
  if ($sku_id) $all_ids[] = (int)$sku_id;

  foreach ([$ids_title, $ids_excerpt, $ids_syn, $ids_content] as $chunk) {
    foreach ((array)$chunk as $id) {
      $id = (int)$id;
      if ($id && !in_array($id, $all_ids, true)) $all_ids[] = $id;
    }
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
    'sku' => $sku_id
  ];
  return $result;
}

//Хук, который умеет искать (title OR excerpt) или только content

add_filter('posts_search', function ($search, $wp_query) {
    if (is_admin() && !wp_doing_ajax()) return $search;

    if (! $wp_query->is_search()) return $search;


    $mode = $wp_query->get('plnt_search_in');
    if (!$mode || $mode === 'all') return $search;

    $post_type = $wp_query->get('post_type');
    $is_product = ($post_type === 'product') || (is_array($post_type) && in_array('product', $post_type, true));
    if (!$is_product) return $search;

    $q = $wp_query->query_vars;
    if (empty($q['search_terms']) || !is_array($q['search_terms'])) return $search;

    global $wpdb;

    $n = !empty($q['exact']) ? '' : '%';
    $clauses = [];

    foreach ($q['search_terms'] as $term) {
        $like = $n . $wpdb->esc_like($term) . $n;

        if ($mode === 'title') {
            $clauses[] = $wpdb->prepare("{$wpdb->posts}.post_title LIKE %s", $like);
        } elseif ($mode === 'excerpt') {
            $clauses[] = $wpdb->prepare("{$wpdb->posts}.post_excerpt LIKE %s", $like);
        } elseif ($mode === 'content') {
            $clauses[] = $wpdb->prepare("{$wpdb->posts}.post_content LIKE %s", $like);
        } elseif ($mode === 'title_excerpt') {
            $clauses[] = $wpdb->prepare(
                "({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s)",
                $like, $like
            );
        } else {
            return $search;
        }
    }

    $search = " AND (" . implode(" AND ", $clauses) . ")";

    if (!is_user_logged_in()) {
        $search .= " AND ({$wpdb->posts}.post_password = '')";
    }

    return $search;
}, 10, 2);



//Хелпер: получить IDs товаров по строке и режиму (title_excerpt / content)
function plnt_collect_ids_by_text($search, $mode) {
    global $plants_treez_cat_id, $peresadka_cat_id, $plants_cat_id;

    $common = [
        'post_type'        => 'product',
        'post_status'      => 'publish',
        's'                => $search,
        'plnt_search_in'   => $mode,      // <-- ключевое
        'fields'           => 'ids',
        'posts_per_page'   => -1,
        'no_found_rows'    => true,
        'orderby'          => 'meta_value',
        'meta_key'         => '_stock_status',
        'order'            => 'ASC',
    ];

    $argPlants = $common + [
        'tax_query' => [[
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'operator' => 'IN',
            'terms' => [$plants_cat_id],
            'include_children' => 1,
        ]]
    ];

    $argPlantsTreez = $common + [
        'meta_query' => [[
            'key'     => '_stock_status',
            'value'   => 'outofstock',
            'compare' => 'NOT IN',
        ]],
        'tax_query' => [[
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'operator' => 'IN',
            'terms' => [$plants_treez_cat_id],
            'include_children' => 1,
        ]]
    ];
    
    $argOther = $common + [
        'meta_query' => [[
            'key'     => '_stock_status',
            'value'   => 'outofstock',
            'compare' => 'NOT IN',
        ]],
        'tax_query' => [[
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'operator' => 'NOT IN',
            'terms' => [$plants_treez_cat_id, $peresadka_cat_id, $plants_cat_id],
            'include_children' => 1,
        ]]
    ];

    $q1 = new WP_Query($argPlants);
    $q2 = new WP_Query($argPlantsTreez);
    $q3 = new WP_Query($argOther);

    $ids1 = array_map('intval', (array)$q1->posts);
    $ids2 = array_map('intval', (array)$q2->posts);
    $ids3 = array_map('intval', (array)$q3->posts);

    return array_values(array_unique(array_merge($ids1, $ids2, $ids3)));
}

add_action('wp_ajax_get_search_nonce', 'plnt_get_search_nonce');
add_action('wp_ajax_nopriv_get_search_nonce', 'plnt_get_search_nonce');
function plnt_get_search_nonce() {
  wp_send_json_success(['nonce' => wp_create_nonce('search-nonce')]);
}

//TELEMETRY

//Создание таблицы (сразу “по дням”)
function plnt_search_telemetry_install() {
  global $wpdb;
  $table = $wpdb->prefix . 'plnt_search_queries';
  $charset = $wpdb->get_charset_collate();

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';

  $sql = "CREATE TABLE $table (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    day DATE NOT NULL,
    query_norm VARCHAR(120) NOT NULL,
    total_count INT UNSIGNED NOT NULL DEFAULT 0,
    empty_count INT UNSIGNED NOT NULL DEFAULT 0,
    first_seen DATETIME NOT NULL,
    last_seen DATETIME NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY day_query_uq (day, query_norm),
    KEY day_idx (day),
    KEY query_idx (query_norm),
    KEY last_seen_idx (last_seen)
  ) $charset;";

  dbDelta($sql);
}

add_action('init', function () {
  $ver = '1.0.0'; // если будете менять схему — увеличивайте
  if (get_option('plnt_search_telemetry_ver') !== $ver) {
    plnt_search_telemetry_install();
    update_option('plnt_search_telemetry_ver', $ver);
  }
});

function plnt_norm_query($q) {
  $q = wp_strip_all_tags((string) $q);
  $q = trim($q);

  // lowercase (mb if available)
  if (function_exists('mb_strtolower')) {
    $q = mb_strtolower($q, 'UTF-8');
  } else {
    $q = strtolower($q);
  }

  // ё -> е
  $q = str_replace('ё', 'е', $q);

  // collapse whitespace (unicode if possible)
  $q = preg_replace('/\s+/u', ' ', $q);
  if ($q === null) { // in case PCRE unicode fails for some reason
    $q = preg_replace('/\s+/', ' ', (string) $q);
  }

  // limit length to 120 chars (mb if available)
  if (function_exists('mb_strlen') && function_exists('mb_substr')) {
    if (mb_strlen($q, 'UTF-8') > 120) {
      $q = mb_substr($q, 0, 120, 'UTF-8');
    }
  } else {
    if (strlen($q) > 120) {
      $q = substr($q, 0, 120);
    }
  }

  return trim($q);
}


//Нормализация + логирование “день+запрос”
function plnt_log_search_query($q_raw, $found_posts) {
  global $wpdb;

  $q = plnt_norm_query(wp_unslash($q_raw));
  if (mb_strlen($q) < 3) return;

  // по желанию: отсечь явные email/телефоны
  if (preg_match('/[\w.+-]+@[\w.-]+\.[a-z]{2,}/i', $q)) return;
  if (preg_match('/(\+?\d[\d\s().-]{8,}\d)/', $q)) return;

  $table = $wpdb->prefix . 'plnt_search_queries';
  $day = current_time('Y-m-d');
  $now = current_time('mysql');
  $empty_inc = ((int)$found_posts === 0) ? 1 : 0;

  $sql = $wpdb->prepare(
    "INSERT INTO $table (day, query_norm, total_count, empty_count, first_seen, last_seen)
     VALUES (%s, %s, 1, %d, %s, %s)
     ON DUPLICATE KEY UPDATE
       total_count = total_count + 1,
       empty_count = empty_count + VALUES(empty_count),
       last_seen   = VALUES(last_seen)",
    $day, $q, $empty_inc, $now, $now
  );

  $wpdb->query($sql);
}

//Страница “Инструменты → Search queries”
add_action('admin_menu', function () {
  add_management_page(
    'Search queries',
    'Search queries',
    'manage_options',
    'plnt-search-queries',
    'plnt_render_search_queries_admin_page'
  );
});

function plnt_render_search_queries_admin_page() {
  if (!current_user_can('manage_options')) return;

  global $wpdb;
  $table = $wpdb->prefix . 'plnt_search_queries';

  $days  = isset($_GET['days']) ? max(1, min(3650, (int)$_GET['days'])) : 30;
  $limit = isset($_GET['limit']) ? max(10, min(2000, (int)$_GET['limit'])) : 200;

  $rows = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT query_norm,
              SUM(total_count) AS total_count,
              SUM(empty_count) AS empty_count,
              MIN(first_seen) AS first_seen,
              MAX(last_seen)  AS last_seen
       FROM $table
       WHERE day >= (CURRENT_DATE - INTERVAL %d DAY)
       GROUP BY query_norm
       ORDER BY total_count DESC
       LIMIT %d",
      $days, $limit
    ),
    ARRAY_A
  );

  $export_url = wp_nonce_url(
    admin_url('admin-post.php?action=plnt_export_search_csv&days=' . $days),
    'plnt_export_search_csv'
  );
  ?>
  <div class="wrap">
    <h1>Search queries</h1>

    <form method="get" style="margin: 12px 0;">
      <input type="hidden" name="page" value="plnt-search-queries" />
      <label>Период (дней):
        <input type="number" name="days" value="<?php echo esc_attr($days); ?>" min="1" max="3650" />
      </label>
      <label style="margin-left:12px;">Показать строк:
        <input type="number" name="limit" value="<?php echo esc_attr($limit); ?>" min="10" max="2000" />
      </label>
      <button class="button button-primary" type="submit" style="margin-left:12px;">Показать</button>

      <a class="button" style="margin-left:12px;" href="<?php echo esc_url($export_url); ?>">
        Выгрузить CSV (за <?php echo (int)$days; ?> дн.)
      </a>
    </form>

    <table class="widefat striped">
      <thead>
        <tr>
          <th>Запрос</th>
          <th style="width:120px;">Всего</th>
          <th style="width:140px;">Пустых</th>
          <th style="width:180px;">First seen</th>
          <th style="width:180px;">Last seen</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="5">Нет данных</td></tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td><?php echo esc_html($r['query_norm']); ?></td>
            <td><?php echo (int)$r['total_count']; ?></td>
            <td><?php echo (int)$r['empty_count']; ?></td>
            <td><?php echo esc_html($r['first_seen']); ?></td>
            <td><?php echo esc_html($r['last_seen']); ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php
}

//CSV экспорт
add_action('admin_post_plnt_export_search_csv', function () {
  if (!current_user_can('manage_options')) wp_die('Forbidden', 403);
  check_admin_referer('plnt_export_search_csv');

  global $wpdb;
  $table = $wpdb->prefix . 'plnt_search_queries';
  $days = isset($_GET['days']) ? max(1, min(3650, (int)$_GET['days'])) : 30;

  nocache_headers();
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=search-queries-' . date('Y-m-d') . '.csv');

  $out = fopen('php://output', 'w');
  fprintf($out, "\xEF\xBB\xBF"); // BOM для Excel
  fputcsv($out, ['query', 'total_count', 'empty_count', 'first_seen', 'last_seen']);

  $rows = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT query_norm,
              SUM(total_count) AS total_count,
              SUM(empty_count) AS empty_count,
              MIN(first_seen) AS first_seen,
              MAX(last_seen)  AS last_seen
       FROM $table
       WHERE day >= (CURRENT_DATE - INTERVAL %d DAY)
       GROUP BY query_norm
       ORDER BY total_count DESC",
      $days
    ),
    ARRAY_A
  );

  foreach ($rows as $r) {
    fputcsv($out, [
      $r['query_norm'],
      (int)$r['total_count'],
      (int)$r['empty_count'],
      $r['first_seen'],
      $r['last_seen'],
    ]);
  }

  fclose($out);
  exit;
});
