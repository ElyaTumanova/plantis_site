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
 * 2) Генерация фидов ежедневно в 04:30 по часовому поясу WP.
 * Используем одноразовое событие, чтобы сохранять локальное время
 * при смене часового пояса и переходах на летнее время.
 */
function plnt_feed_next_night_timestamp(): int {
    $now  = new DateTimeImmutable('now', wp_timezone());
    $next = $now->setTime(4, 30);
    if ($next <= $now) {
        $next = $next->modify('+1 day');
    }
    return $next->getTimestamp();
}

function plnt_schedule_daily_feed_cron(): void {
    $hook = 'plnt_generate_feed_daily';
    $version = '04-30-single-v1';

    if (get_option('plnt_feed_cron_schedule_version', '') !== $version) {
        // Однократная миграция со старого запуска «через 60 секунд + daily».
        wp_clear_scheduled_hook($hook);
        wp_clear_scheduled_hook('plnt_generate_yml_daily');
        update_option('plnt_feed_cron_schedule_version', $version, false);
    }

    if (!wp_next_scheduled($hook)) {
        wp_schedule_single_event(plnt_feed_next_night_timestamp(), $hook);
    }
}
add_action('init', 'plnt_schedule_daily_feed_cron');

// После штатной генерации назначаем следующий ночной запуск.
// Функция при ручном do_action() не создаёт дубли уже существующего задания.
add_action('plnt_generate_feed_daily', 'plnt_schedule_daily_feed_cron', 20);

/**
 * 3) Снимаем задачу при смене темы (если код живёт в теме)
 * Если это плагин — лучше использовать register_deactivation_hook.
 */
// add_action('switch_theme', function () {
//     wp_clear_scheduled_hook('plnt_generate_feed_daily');
// });

/**
 * 4) AJAX: посмотреть статус последнего запуска и следующее выполнение
 *
 * Вызов из админки, например:
 * /wp-admin/admin-ajax.php?action=plnt_feed_daily_status
 */
add_action('wp_ajax_plnt_feed_daily_status', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['error' => 'forbidden'], 403);
    }

    $hook      = 'plnt_generate_feed_daily';
    $next_ts   = wp_next_scheduled($hook);
    $status    = get_option('plnt_generate_feed_daily_status', null);

    wp_send_json_success([
        'hook'     => $hook,
        'next_run' => $next_ts ? [
            'timestamp' => (int)$next_ts,
            'local'     => wp_date('Y-m-d H:i:s', (int)$next_ts),
            'relative'  => human_time_diff(time(), (int)$next_ts) . ' from now',
        ] : null,
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
 * Ручная генерация XML-фидов.
 *
 * /wp-admin/admin-ajax.php?action=plnt_feed_daily_schedule_now
 */
add_action('wp_ajax_plnt_feed_daily_schedule_now', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['error' => 'forbidden'], 403);
    }

    $hook = 'plnt_generate_feed_daily';

    // Запускаем генерацию сразу.
    do_action($hook);

    $next_ts = wp_next_scheduled($hook);

    wp_send_json_success([
        'hook'     => $hook,
        'next_run' => $next_ts ? [
            'timestamp' => (int) $next_ts,
            'local'     => wp_date('Y-m-d H:i:s', (int) $next_ts),
            'relative'  => human_time_diff(time(), (int) $next_ts) . ' from now',
        ] : null,
    ]);
});

