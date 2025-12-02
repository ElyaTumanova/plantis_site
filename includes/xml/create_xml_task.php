<?php
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

add_action( 'plnt_generate_yml_daily', 'plnt_generate_yml_callback' );

function plnt_generate_yml_callback() {

   // ВРЕМЕННО для теста:
    file_put_contents(
        WP_CONTENT_DIR . '/cron-test.txt',
        "Cron fired: " . date('Y-m-d H:i:s') . PHP_EOL,
        FILE_APPEND
    );
    // include get_theme_file_path('/includes/xml/create_yandex_xml.php');
    // include get_theme_file_path('/includes/xml/create_google_xml.php');
}


// В functions.php или в инициализирующем файле темы/плагина
add_action( 'init', 'plnt_schedule_custom_cron' );

function plnt_schedule_custom_cron() {
    if ( ! wp_next_scheduled( 'plnt_generate_yml_daily' ) ) {

        // Время первого запуска — "прямо сейчас" в UTC
        $timestamp = current_time( 'timestamp', true );

        // Событие раз в сутки, начиная с этого момента
        wp_schedule_event( $timestamp, 'daily', 'plnt_generate_yml_daily' );
    }
}


//добавить кнопку “запланировать сейчас”
add_action('wp_ajax_plnt_generate_yml_schedule_now', function () {
  if (!current_user_can('manage_options')) {
    wp_send_json_error(['error' => 'forbidden'], 403);
  }
  if (!wp_next_scheduled('plnt_generate_yml_daily')) {
    wp_schedule_event(time() + 60, 'daily', 'plnt_generate_yml_daily');
  }
  $next = wp_next_scheduled('plnt_generate_yml_daily');
  wp_send_json_success(['next_run' => $next ? wp_date('Y-m-d H:i:s', (int)$next) : null]);
});





