<?php
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

// add_action( 'plnt_generate_yml_daily', 'plnt_generate_yml_callback' );

// function plnt_generate_yml_callback() {
//     include get_theme_file_path('/includes/xml/create_yandex_xml.php');
//     include get_theme_file_path('/includes/xml/create_google_xml.php');
// }


// // В functions.php или в инициализирующем файле темы/плагина
// add_action( 'init', 'plnt_schedule_custom_cron' );

// function plnt_schedule_custom_cron() {
//     if ( ! wp_next_scheduled( 'plnt_generate_yml_daily' ) ) {
//         $hour = 19;
//         $minute = 00;
        
//         $timestamp = mktime( $hour, $minute, 0 );

//         // Если время уже прошло сегодня — планируем на завтра
//         if ( $timestamp < time() ) {
//             $timestamp = strtotime('+1 day', $timestamp);
//         }

//         wp_schedule_event( $timestamp, 'daily', 'plnt_generate_yml_daily' );
//     }
// }

wp_clear_scheduled_hook( 'plnt_generate_yml_daily' );


add_action('init', function() {
    wp_clear_scheduled_hook( 'plnt_generate_yml_daily' );
});

