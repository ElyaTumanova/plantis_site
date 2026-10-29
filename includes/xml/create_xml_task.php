<?php
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

/**
 * 1) Основной callback: что делать по крону
 */
add_action('plnt_generate_feed_daily', 'plnt_generate_feed_callback');

function plnt_generate_feed_callback() {
    $status_key = 'plnt_generate_feed_daily_status';

    // Старт — сохраняем, что запуск начался
    update_option($status_key, [
        'last_started_at'    => time(),
        'last_finished_at'   => null,
        'status'             => 'running', // running / ok / failed
        'result'             => null,
        'error'              => null,
    ], false);

    try {
        // здесь можно добавить любые проверки окружения, если нужно

        // Путь к файлам генерации
        $yandex_file = get_theme_file_path('/includes/xml/create_yandex_xml.php');
        $google_file = get_theme_file_path('/includes/xml/create_google_xml.php');

        // На всякий случай проверим, что файлы существуют
        if (!file_exists($yandex_file)) {
            throw new Exception('Yandex XML generator file not found: ' . $yandex_file);
        }
        if (!file_exists($google_file)) {
            throw new Exception('Google XML generator file not found: ' . $google_file);
        }

        // Запускаем генерацию
        include $yandex_file;
        include $google_file;

        // Финиш — всё прошло ок
        $data = get_option($status_key, []);
        $data['status']           = 'ok';
        $data['result']           = [
            'message' => 'YML files generated successfully',
        ];
        $data['error']            = null;
        $data['last_finished_at'] = time();
        update_option($status_key, $data, false);

    } catch (Throwable $e) {
        // Любая ошибка — сохраняем как failed
        $data = get_option($status_key, []);
        $data['status']           = 'failed';
        $data['error']            = $e->getMessage();
        $data['last_finished_at'] = time();
        update_option($status_key, $data, false);
    }
}

/**
 * Автоматическую генерацию ежедневно в 04:30 запускает ISPmanager.
 * Расписание WP-Cron здесь не создаётся.
 * Старые события очищаются однократно при загрузке signals-cron.php.
 */

/**
 * AJAX: посмотреть статус последнего запуска XML
 *
 * Вызов из админки, например:
 * /wp-admin/admin-ajax.php?action=plnt_feed_daily_status
 */
add_action('wp_ajax_plnt_feed_daily_status', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['error' => 'forbidden'], 403);
    }

    $hook      = 'plnt_generate_feed_daily';
    $status    = get_option('plnt_generate_feed_daily_status', null);

    wp_send_json_success([
        'hook'     => $hook,
        'next_run' => null, // За расписание отвечает ISPmanager, не WP-Cron.
        'last' => $status ? [
            'last_started_at'      => $status['last_started_at'] ?? null,
            'last_started_local'   => !empty($status['last_started_at'])
                ? wp_date('Y-m-d H:i:s', (int)$status['last_started_at'])
                : null,
            'last_finished_at'     => $status['last_finished_at'] ?? null,
            'last_finished_local'  => !empty($status['last_finished_at'])
                ? wp_date('Y-m-d H:i:s', (int)$status['last_finished_at'])
                : null,
            'status'               => $status['status'] ?? null,   // running / ok / failed
            'result'               => $status['result'] ?? null,   // наш message
            'error'                => $status['error'] ?? null,
        ] : null,
    ]);
});

/**
 * AJAX: исходная ссылка для немедленной ручной генерации
 *
 * /wp-admin/admin-ajax.php?action=plnt_feed_daily_schedule_now
 */
add_action('wp_ajax_plnt_feed_daily_schedule_now', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['error' => 'forbidden'], 403);
    }

    $hook = 'plnt_generate_feed_daily';

    // Запускаем СЕЙЧАС в этом же запросе
    do_action($hook);


    wp_send_json_success([
        'hook'     => $hook,
        'next_run' => null, // Ночной запуск настроен отдельно через ISPmanager.
    ]);
});


