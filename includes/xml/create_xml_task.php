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
 * Avito: independent server cron at 00:00 and 12:00.
 * ISPmanager should call:
 * wp --path=/path/to/wordpress eval 'do_action("plnt_generate_avito_feed");'
 *
 * We do not register WP-Cron events: the real schedule is in ISPmanager.
 */
add_action( 'plnt_generate_avito_feed', 'plnt_generate_avito_feed_callback' );

function plnt_generate_avito_feed_callback(): void {
    $status_key = 'plnt_generate_avito_feed_status';

    update_option( $status_key, [
        'last_started_at'  => time(),
        'last_finished_at' => null,
        'status'           => 'running',
        'result'           => null,
        'error'            => null,
    ], false );

    try {
        $avito_file = get_theme_file_path( '/includes/xml/create_avito_xml.php' );

        if ( ! is_file( $avito_file ) ) {
            throw new RuntimeException( 'Avito XML generator file not found: ' . $avito_file );
        }

        require_once $avito_file;

        if ( ! function_exists( 'plnt_avito_generate_xml' ) ) {
            throw new RuntimeException( 'Avito XML generator function not found.' );
        }

        // Writes wp-content/avito-xml/feed-avito-0.xml, with no HTTP output.
        plnt_avito_generate_xml();

        $data = get_option( $status_key, [] );
        $data['status']           = 'ok';
        $data['result']           = [ 'message' => 'Avito XML generated successfully' ];
        $data['error']            = null;
        $data['last_finished_at'] = time();
        update_option( $status_key, $data, false );

    } catch ( Throwable $e ) {
        $data = get_option( $status_key, [] );
        $data['status']           = 'failed';
        $data['result']           = null;
        $data['error']            = $e->getMessage();
        $data['last_finished_at'] = time();
        update_option( $status_key, $data, false );

        // Let ISPmanager record a failed job rather than an apparently successful one.
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            throw $e;
        }
    }
}

/**
 * Пересобираем XML Авито, когда растение заканчивается или снова появляется.
 * Для нескольких изменений остатков за минуту создаётся одна фоновая задача.
 * Плановый cron в ISPmanager при этом продолжает работать без изменений.
 */
add_action( 'woocommerce_product_before_set_stock', 'plnt_avito_remember_stock_before_change' );
add_action( 'woocommerce_product_set_stock', 'plnt_avito_schedule_on_stock_change' );
add_action( 'woocommerce_product_set_stock_status', 'plnt_avito_schedule_on_stock_status_change', 10, 3 );

/** Запоминаем старое количество до записи нового значения WooCommerce. */
function plnt_avito_remember_stock_before_change( $product ): void {
    if ( ! $product instanceof WC_Product || 'product' !== get_post_type( $product->get_id() ) ) {
        return;
    }

    $old_stock = get_post_meta( $product->get_id(), '_stock', true );

    $GLOBALS['plnt_avito_stock_before'][ $product->get_id() ] = is_numeric( $old_stock )
        ? (float) $old_stock
        : null;
}

/** Срабатывает только при переходах > 0 → <= 0 или <= 0 → > 0. */
function plnt_avito_schedule_on_stock_change( $product ): void {
    if ( ! $product instanceof WC_Product ) {
        return;
    }

    $product_id = $product->get_id();
    $before     = $GLOBALS['plnt_avito_stock_before'][ $product_id ] ?? null;

    unset( $GLOBALS['plnt_avito_stock_before'][ $product_id ] );

    $after = $product->get_stock_quantity();

    if ( null === $before || null === $after || ( $before > 0 ) === ( (float) $after > 0 ) ) {
        return;
    }

    plnt_avito_queue_stock_refresh( $product_id );
}

/** Если наличие меняют вручную без изменения количества, обновляем фид тоже. */
function plnt_avito_schedule_on_stock_status_change( $product_id, $stock_status, $product ): void {
    plnt_avito_queue_stock_refresh( (int) $product_id );
}

/**
 * Только опубликованные комнатные растения с включённой публикацией Авито.
 * Action Scheduler выполняет задачу через минуту, не задерживая заказ/админку.
 */
function plnt_avito_queue_stock_refresh( int $product_id ): void {
    if (
        'product' !== get_post_type( $product_id )
        || 'publish' !== get_post_status( $product_id )
        || '1' !== (string) get_post_meta( $product_id, 'plnt_avito_publish', true )
        || ! function_exists( 'as_schedule_single_action' )
    ) {
        return;
    }

    $indoor_category = get_term_by( 'slug', 'komnatnye-rasteniya', 'product_cat' );
    $product_terms   = get_the_terms( $product_id, 'product_cat' );

    if ( ! $indoor_category || ! is_array( $product_terms ) ) {
        return;
    }

    $is_indoor_plant = false;

    foreach ( $product_terms as $term ) {
        if (
            (int) $term->term_id === (int) $indoor_category->term_id
            || in_array(
                (int) $indoor_category->term_id,
                array_map( 'intval', get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) ),
                true
            )
        ) {
            $is_indoor_plant = true;
            break;
        }
    }

    if ( ! $is_indoor_plant ) {
        return;
    }

    // $unique = true: одно отложенное обновление на все изменения за минуту.
    as_schedule_single_action(
        time() + MINUTE_IN_SECONDS,
        'plnt_generate_avito_feed',
        [],
        'plnt_avito',
        true
    );
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


