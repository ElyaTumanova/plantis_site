<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// анализ производиетльности серевера


//см wp-config.php


    global $timing_points;
    $timing_points = [];

    // === INIT ===
    add_action('init', function () {
        global $timing_points;
        $timing_points['init_start'] = microtime(true);
    }, 0);

    add_action('init', function () {
        global $timing_points;
        $timing_points['init_end'] = microtime(true);
    }, 999);

    // === plugins_loaded (после плагинов) ===
    add_action('plugins_loaded', function () {
        global $timing_points;
        $timing_points['plugins_loaded'] = microtime(true);
    }, 0);

    // === after_setup_theme (в начале темы) ===
    add_action('after_setup_theme', function () {
        global $timing_points;
        $timing_points['theme_start'] = microtime(true);
    }, 0);

    // === after_setup_theme (в конце темы) ===
    add_action('after_setup_theme', function () {
        global $timing_points;
        $timing_points['theme_end'] = microtime(true);
    }, 999);

    // === wp_loaded ===
    add_action('wp_loaded', function () {
        global $timing_points;
        $timing_points['wp_loaded'] = microtime(true);
    });

    // === template_include ===
    add_filter('template_include', function ($template) {
        global $timing_points;
        $timing_points['template_include'] = microtime(true);
        return $template;
    }, 0);

    // === get_header ===
    add_action('get_header', function () {
        global $timing_points;
        $timing_points['get_header'] = microtime(true);
    }, 0);

    // === template_redirect ===
    add_action('template_redirect', function () {
        global $timing_points;
        $timing_points['template_start'] = microtime(true);
    }, 0);

    // === shutdown — финальный расчет ===
    add_action('shutdown', function () {
        global $timing_points, $wpdb;

        $now = microtime(true);
        $php_total = ($now - WP_START) * 1000;

        $timing = [];

        // Этапы
        $timing['init'] = ($timing_points['init_end'] - $timing_points['init_start']) * 1000;
        $timing['plugins'] = isset($timing_points['plugins_loaded']) ? ($timing_points['plugins_loaded'] - WP_START) * 1000 : 0;
        $timing['theme'] = isset($timing_points['theme_start'], $timing_points['theme_end']) ? ($timing_points['theme_end'] - $timing_points['theme_start']) * 1000 : 0;
        $timing['wp_loaded'] = ($timing_points['wp_loaded'] - $timing_points['init_end']) * 1000;
        $timing['template_include'] = ($timing_points['template_include'] - $timing_points['wp_loaded']) * 1000;
        $timing['get_header'] = ($timing_points['get_header'] - $timing_points['template_include']) * 1000;
        $timing['template'] = isset($timing_points['template_start']) ? ($now - $timing_points['template_start']) * 1000 : 0;

        // SQL

        // $db_time = 0;
        // if (!empty($wpdb->queries)) {
        //     foreach ($wpdb->queries as $query) {
        //         $db_time += $query[1];
        //     }
        // }
        // $timing['db'] = $db_time * 1000;

        // $timing['php'] = $php_total;
        $timing['total'] = $php_total;

        // Header
        // $server_timing = [];
        // foreach ($timing as $label => $ms) {
        //     $server_timing[] = sprintf('%s;dur=%.1f', $label, $ms);
        // }

        // if (!headers_sent()) {
        //     header('Server-Timing: ' . implode(', ', $server_timing));
        // }
    });

    //вывод server timing в html
    add_action('wp_footer', function () {
        global $timing_points, $wpdb;

        if (empty($timing_points)) {
            echo '<!-- Server-Timing: no data collected -->';
            return;
        }

        $now = microtime(true);
        $php_total = ($now - WP_START) * 1000;

        $timing = [];

        $timing['init'] = ($timing_points['init_end'] - $timing_points['init_start']) * 1000;
        $timing['plugins'] = isset($timing_points['plugins_loaded']) ? ($timing_points['plugins_loaded'] - WP_START) * 1000 : 0;
        $timing['theme'] = isset($timing_points['theme_start'], $timing_points['theme_end']) ? ($timing_points['theme_end'] - $timing_points['theme_start']) * 1000 : 0;
        $timing['wp_loaded'] = ($timing_points['wp_loaded'] - $timing_points['init_end']) * 1000;
        $timing['template_include'] = ($timing_points['template_include'] - $timing_points['wp_loaded']) * 1000;
        $timing['get_header'] = ($timing_points['get_header'] - $timing_points['template_include']) * 1000;
        $timing['template'] = isset($timing_points['template_start']) ? ($now - $timing_points['template_start']) * 1000 : 0;

        $db_time = 0;
        if (!empty($wpdb->queries)) {
            foreach ($wpdb->queries as $query) {
                $db_time += $query[1];
            }
        }

        $timing['db'] = $db_time * 1000;
        $timing['php'] = $php_total;
        $timing['total'] = $php_total;

        echo "\n<!-- Server-Timing Debug:\n";
        foreach ($timing as $label => $dur) {
            printf("%s: %.2fms\n", $label, $dur);
        }
        echo "-->\n";
    });


    // TOP 10 SLOWEST SQL QUERIES
    add_action('shutdown', function () {

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


