<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Старт замера: самый ранний хук внутри ajax-запроса wc-ajax=add_to_cart
add_action( 'init', function () {
    if ( wp_doing_ajax()
      && isset($_GET['wc-ajax'])
      && $_GET['wc-ajax'] === 'add_to_cart' ) {
        $GLOBALS['wc_add_to_cart_t0'] = microtime( true );
    }
}, 0 );

// Стоп замера и отдача времени в заголовке (видно в DevTools → Network → Headers).
// Хук срабатывает ПЕРЕД формированием JSON-ответа и отправкой.
add_action( 'woocommerce_ajax_added_to_cart', function( $product_id ){
    if ( isset( $GLOBALS['wc_add_to_cart_t0'] ) && ! headers_sent() ) {
        $ms = (int) round( ( microtime(true) - $GLOBALS['wc_add_to_cart_t0'] ) * 1000 );
        header( 'Server-Timing: app;desc="wc add_to_cart";dur=' . $ms );
        header( 'X-Response-Time: ' . $ms . 'ms' );
    }
}, 999 );

// (Опционально) если хотите прочитать время на клиенте из JSON-ответа,
// добавим «скрытый» фрагмент с числом миллисекунд:
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ){
    if ( isset( $GLOBALS['wc_add_to_cart_t0'] ) ) {
        $ms = (int) round( ( microtime(true) - $GLOBALS['wc_add_to_cart_t0'] ) * 1000 );
        $fragments['wc_add_to_cart_server_ms'] =
            '<div id="wc-add-to-cart-server-ms" data-ms="' . esc_attr( $ms ) . '"></div>';
    }
    return $fragments;
}, 999 );


// анализ производиетльности серевера


//см wp-config.php


global $timing_points;
$timing_points = [];

function plnt_timing_mark($key) {
    global $timing_points;
    $timing_points[$key] = microtime(true);
}

$timing_points['wp_start'] = WP_START;

add_action('muplugins_loaded', function () {
    plnt_timing_mark('muplugins_loaded');
}, 999);

add_action('plugins_loaded', function () {
    plnt_timing_mark('plugins_loaded');
}, 999);

add_action('after_setup_theme', function () {
    plnt_timing_mark('after_setup_theme_start');
}, 0);

add_action('after_setup_theme', function () {
    plnt_timing_mark('after_setup_theme_end');
}, 999);

add_action('init', function () {
    plnt_timing_mark('init_start');
}, 0);

add_action('init', function () {
    plnt_timing_mark('init_end');
}, 999);

add_action('wp_loaded', function () {
    plnt_timing_mark('wp_loaded');
}, 999);

add_action('parse_request', function () {
    plnt_timing_mark('parse_request');
}, 999);

add_action('send_headers', function () {
    plnt_timing_mark('send_headers');
}, 999);

add_action('wp', function () {
    plnt_timing_mark('wp');
}, 999);

add_action('template_redirect', function () {
    plnt_timing_mark('template_redirect');
}, 999);

add_filter('template_include', function ($template) {
    plnt_timing_mark('template_include');
    return $template;
}, 999);

add_action('get_header', function () {
    plnt_timing_mark('get_header');
}, 0);

add_action('wp_head', function () {
    plnt_timing_mark('wp_head');
}, 999);

add_action('wp_footer', function () {
    plnt_timing_mark('wp_footer');
}, 999);

add_action('shutdown', function () {
  if ( ! plnt_server_debug_enabled() ) {
    return;
  }
    global $timing_points, $wpdb;

    plnt_timing_mark('shutdown');

    $points_order = [
        'wp_start',
        'muplugins_loaded',
        'plugins_loaded',
        'after_setup_theme_start',
        'after_setup_theme_end',
        'init_start',
        'init_end',
        'wp_loaded',
        'parse_request',
        'send_headers',
        'wp',
        'template_redirect',
        'template_include',
        'get_header',
        'wp_head',
        'wp_footer',
        'shutdown',
    ];

    $total = (microtime(true) - WP_START) * 1000;

    $db_time = 0;

    if ( ! empty($wpdb->queries) ) {
        foreach ($wpdb->queries as $query) {
            $db_time += $query[1];
        }
    }

    echo "\n<!-- Server-Timing Debug Full:\n";

    $prev_key  = null;
    $prev_time = null;

    foreach ($points_order as $key) {
        if (empty($timing_points[$key])) {
            continue;
        }

        if ($prev_time !== null) {
            $ms = ($timing_points[$key] - $prev_time) * 1000;

            printf(
                "%s -> %s: %.2fms\n",
                $prev_key,
                $key,
                $ms
            );
        }

        $prev_key  = $key;
        $prev_time = $timing_points[$key];
    }

    echo "\nSummary:\n";
    printf("db: %.2fms\n", $db_time * 1000);
    printf("php_total: %.2fms\n", $total);
    printf("peak_memory: %.2fMB\n", memory_get_peak_usage(true) / 1024 / 1024);

    echo "-->\n";
}, 999);

    // TOP 10 SLOWEST SQL QUERIES
    add_action('shutdown', function () {

    if ( ! plnt_server_debug_enabled() ) {
      return;
    }

        if ( ! defined('SAVEQUERIES') || ! SAVEQUERIES || wp_doing_ajax() ) {
            return;
        }
        
        global $wpdb;

        if (defined('SAVEQUERIES') && SAVEQUERIES && !empty($wpdb->queries)) {
            usort($wpdb->queries, function ($a, $b) {
                return $b[1] <=> $a[1]; // сортировка по времени DESC
            });

            echo "<!-- TOP 10 SLOWEST SQL QUERIES -->\n";
            foreach (array_slice($wpdb->queries, 0, 10) as $i => $query) {
                list($sql, $time, $call) = $query;
                printf("<!-- #%d | %.4f sec | %s -->\n", $i + 1, $time, $sql);
            }
            echo "<!-- END SQL -->\n";
        }
    });

    // SQL DEBUG SUMMARY
    add_action('shutdown', function () {
        if ( ! plnt_server_debug_enabled() ) {
            return;
        }

        if ( ! defined('SAVEQUERIES') || ! SAVEQUERIES || wp_doing_ajax() ) {
            return;
        }

        global $wpdb;

        if ( empty($wpdb->queries) ) {
            return;
        }

        $queries = $wpdb->queries;

        $total_time = 0;
        foreach ( $queries as $query ) {
            $total_time += $query[1];
        }

        echo "\n<!-- SQL DEBUG SUMMARY\n";
        printf("Total queries: %d\n", count($queries));
        printf("Total SQL time: %.4f sec / %.2f ms\n", $total_time, $total_time * 1000);
        printf("Peak memory: %.2f MB\n", memory_get_peak_usage(true) / 1024 / 1024);
        echo "-->\n";


        // ALL QUERIES SLOWER THAN 2MS
        echo "\n<!-- SQL QUERIES SLOWER THAN 2MS -->\n";

        $slow_queries = array_filter($queries, function ($query) {
            return $query[1] >= 0.002;
        });

        usort($slow_queries, function ($a, $b) {
            return $b[1] <=> $a[1];
        });

        foreach ( $slow_queries as $i => $query ) {
            list($sql, $time, $call) = $query;

            printf(
                "<!-- #%d | %.4f sec | %s | CALL: %s -->\n",
                $i + 1,
                $time,
                trim(preg_replace('/\s+/', ' ', $sql)),
                $call
            );
        }

        echo "<!-- END SLOW SQL -->\n";


        // REPEATED SIMILAR QUERIES
        $groups = [];

        foreach ( $queries as $query ) {
            list($sql, $time, $call) = $query;

            $normalized = preg_replace('/\s+/', ' ', trim($sql));
            $normalized = preg_replace('/\b\d+\b/', '?', $normalized);
            $normalized = preg_replace("/'[^']*'/", "'?'", $normalized);

            if ( ! isset($groups[$normalized]) ) {
                $groups[$normalized] = [
                    'count' => 0,
                    'time'  => 0,
                    'sql'   => $normalized,
                    'call'  => $call,
                ];
            }

            $groups[$normalized]['count']++;
            $groups[$normalized]['time'] += $time;
        }

        usort($groups, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        echo "\n<!-- SQL REPEATED GROUPS TOP 20 -->\n";

        foreach ( array_slice($groups, 0, 20) as $i => $group ) {
            if ( $group['count'] < 2 ) {
                continue;
            }

            printf(
                "<!-- #%d | count: %d | total: %.4f sec / %.2f ms | SQL: %s | CALL: %s -->\n",
                $i + 1,
                $group['count'],
                $group['time'],
                $group['time'] * 1000,
                $group['sql'],
                $group['call']
            );
        }

        echo "<!-- END SQL GROUPS -->\n";

    });

function plnt_server_debug_enabled() {
    return (
        ! is_admin()
        && ! wp_doing_ajax()
        && ! wp_doing_cron()
        && ! wp_is_json_request()
        && ! defined('REST_REQUEST')
    );
}