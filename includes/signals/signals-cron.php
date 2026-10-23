<?php
/**
 * Plantis Signals — импорт истории заказов и запуск расчёта серверным cron.
 * Длинная работа выполняется пачками в Action Scheduler (часть WooCommerce).
 * Только расписание ночного запуска задаётся в ISPmanager.
 */

defined( 'ABSPATH' ) || exit;

const PLNT_SIGNALS_WORKER_HOOK = 'plnt_signals_process_order_batch';
const PLNT_SIGNALS_NIGHT_HOOK  = 'plnt_signals_nightly_update';

/**
 * При переходе на серверный cron однократно удаляем старые
 * расписания ТОЛЬКО трёх наших ночных процессов.
 *
 * Не затрагиваем системный WP-Cron, Action Scheduler, показатели
 * экспозиции, таблицы статистики и ручные обработчики.
 */
function plnt_signals_clear_old_nightly_schedules() {
    $version = 'server-cron-v1';
    if ( get_option( 'plnt_server_cron_migration_version', '' ) === $version ) {
        return;
    }

    $hooks = array(
        'plnt_daily_expo_days_update_hook',
        PLNT_SIGNALS_NIGHT_HOOK,
        'plnt_generate_feed_daily',
        'plnt_generate_yml_daily', // Старый хук XML (если остался в базе).
    );

    foreach ( $hooks as $hook ) {
        $result = wp_clear_scheduled_hook( $hook, array(), true );
        if ( is_wp_error( $result ) || false === $result ) {
            if ( function_exists( 'wc_get_logger' ) ) {
                wc_get_logger()->error(
                    'Не удалось очистить устаревшее расписание: ' . $hook,
                    array( 'source' => 'plantis-signals' )
                );
            }
            return; // Повторим на следующей загрузке WordPress.
        }
    }

    update_option( 'plnt_server_cron_migration_version', $version, false );
}
add_action( 'init', 'plnt_signals_clear_old_nightly_schedules', 30 );

add_action( 'admin_init', function () {
    if ( ! class_exists( 'WooCommerce' ) || ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }
    if ( ! plnt_signals_install_tables() ) {
        return;
    }

    $state = get_option( 'plnt_signals_backfill_state', array() );
    if ( ! is_array( $state ) || empty( $state['status'] ) ) {
        plnt_signals_start_backfill( false );
    } else {
        // Если процесс прервался между пакетами, восстанавливаем очередь.
        plnt_signals_recover_stalled_worker();
    }
}, 20 );

/** Ночной обработчик вызывается серверным cron; расписания WP-Cron нет. */
add_action( PLNT_SIGNALS_NIGHT_HOOK, function () {

    if ( ! plnt_signals_install_tables() ) {
        return;
    }
    $backfill = get_option( 'plnt_signals_backfill_state', array() );
    if ( ! is_array( $backfill ) || ( $backfill['status'] ?? '' ) !== 'complete' ) {
        return;
    }
    $daily = get_option( 'plnt_signals_daily_state', array() );
    if ( is_array( $daily ) && ( $daily['status'] ?? '' ) === 'running' ) {
        return; // Продолжается предыдущий пакетный расчёт.
    }

    $last_indexed = (int) get_option( 'plnt_signals_indexed_until', 0 );
    $to           = time();
    $from         = $last_indexed ? max( 0, $last_indexed - DAY_IN_SECONDS ) : max( 0, $to - 2 * DAY_IN_SECONDS );

    update_option( 'plnt_signals_daily_state', array(
        'status'    => 'running',
        'page'      => 1,
        'processed' => 0,
        'from'      => $from,
        'to'        => $to,
        'started'   => $to,
        'updated'   => $to,
    ), false );
    plnt_signals_enqueue_worker();
} );

function plnt_signals_enqueue_worker() {
    if ( function_exists( 'as_enqueue_async_action' ) ) {
        return (int) as_enqueue_async_action( PLNT_SIGNALS_WORKER_HOOK, array(), 'plantis-signals' );
    }
    // Запасной путь, если Action Scheduler временно недоступен.
    return wp_schedule_single_event( time() + 30, PLNT_SIGNALS_WORKER_HOOK );
}

/** $restart=true — переиндексация всех заказов с первой страницы. */
function plnt_signals_start_backfill( $restart = false ) {
    if ( ! plnt_signals_install_tables() ) {
        return false;
    }
    $current = get_option( 'plnt_signals_backfill_state', array() );
    if ( ! $restart && is_array( $current ) && ! empty( $current['status'] ) ) {
        return false;
    }
    if ( $restart && is_array( $current ) && ( $current['status'] ?? '' ) === 'running' ) {
        return false;
    }
    $daily = get_option( 'plnt_signals_daily_state', array() );
    if ( $restart && is_array( $daily ) && ( $daily['status'] ?? '' ) === 'running' ) {
        return false;
    }

    // При повторной полной индексации очищаем и снимки удалённых заказов.
    if ( $restart ) {
        global $wpdb;
        $wpdb->query( 'DELETE FROM ' . plnt_signals_items_table() );
    }
    update_option( 'plnt_signals_backfill_state', array(
        'status'    => 'running',
        'page'      => 1,
        'processed' => 0,
        'started'   => time(),
        'updated'   => time(),
        'error'     => '',
    ), false );
    return plnt_signals_enqueue_worker();
}

/** Восстанавливаем зависший пакет без дублирования работающих заданий. */
function plnt_signals_recover_stalled_worker() {
    $backfill = get_option( 'plnt_signals_backfill_state', array() );
    $daily    = get_option( 'plnt_signals_daily_state', array() );
    $active   = is_array( $backfill ) && ( $backfill['status'] ?? '' ) === 'running'
        ? $backfill
        : ( is_array( $daily ) && ( $daily['status'] ?? '' ) === 'running' ? $daily : array() );

    if ( ! $active || time() - (int) ( $active['updated'] ?? $active['started'] ?? 0 ) < 20 * MINUTE_IN_SECONDS ) {
        return;
    }
    $locked = (int) get_option( 'plnt_signals_worker_lock', 0 );
    if ( $locked && time() - $locked < 10 * MINUTE_IN_SECONDS ) {
        return;
    }
    if ( function_exists( 'as_has_scheduled_action' )
        && as_has_scheduled_action( PLNT_SIGNALS_WORKER_HOOK, array(), 'plantis-signals' ) ) {
        return;
    }
    plnt_signals_enqueue_worker();
}

function plnt_signals_worker_lock() {
    $name = 'plnt_signals_worker_lock';
    if ( add_option( $name, (string) time(), '', false ) ) {
        return true;
    }
    // На случай прерванного PHP-процесса.
    if ( ( time() - (int) get_option( $name, 0 ) ) > 10 * MINUTE_IN_SECONDS ) {
        delete_option( $name );
        return add_option( $name, (string) time(), '', false );
    }
    return false;
}

function plnt_signals_order_batch_args( $page, $state, $initial ) {
    $args = array(
        'type'     => 'shop_order',
        'status'   => array_keys( wc_get_order_statuses() ),
        'return'   => 'ids',
        'limit'    => PLNT_SIGNALS_BATCH_SIZE,
        'paged'    => $page,
        'orderby'  => 'ID',
        'order'    => 'ASC',
    );
    if ( ! $initial ) {
        // Две UTC UNIX-метки: поддерживаются WooCommerce как date_modified range.
        $args['date_modified'] = (int) $state['from'] . '...' . (int) $state['to'];
    }
    return $args;
}

add_action( PLNT_SIGNALS_WORKER_HOOK, 'plnt_signals_run_worker' );

function plnt_signals_run_worker() {
    if ( ! class_exists( 'WooCommerce' ) || ! plnt_signals_worker_lock() ) {
        return;
    }

    $queue_next = false;
    $mode = '';
    try {
        $backfill = get_option( 'plnt_signals_backfill_state', array() );
        $initial  = is_array( $backfill ) && ( $backfill['status'] ?? '' ) === 'running';
        $state    = $initial ? $backfill : get_option( 'plnt_signals_daily_state', array() );
        if ( ! is_array( $state ) || ( $state['status'] ?? '' ) !== 'running' ) {
            return;
        }
        $mode  = $initial ? 'plnt_signals_backfill_state' : 'plnt_signals_daily_state';
        $page  = max( 1, (int) $state['page'] );
        $ids   = wc_get_orders( plnt_signals_order_batch_args( $page, $state, $initial ) );
        if ( ! is_array( $ids ) ) {
            throw new RuntimeException( 'WooCommerce не вернул массив идентификаторов заказов.' );
        }

        foreach ( $ids as $order_id ) {
            plnt_signals_index_order( (int) $order_id );
        }

        $state['processed'] = (int) $state['processed'] + count( $ids );
        $state['page']      = $page + 1;
        $state['updated']   = time();
        if ( count( $ids ) < PLNT_SIGNALS_BATCH_SIZE ) {
            plnt_signals_rebuild_aggregates();
            $state['status']   = 'complete';
            $state['finished'] = time();
            if ( ! $initial ) {
                update_option( 'plnt_signals_indexed_until', (int) $state['to'], false );
            } else {
                update_option( 'plnt_signals_indexed_until', time(), false );
            }
        } else {
            $queue_next = true;
        }
        update_option( $mode, $state, false );
    } catch ( Throwable $error ) {
        if ( $mode ) {
            $state['status'] = 'error';
            $state['error']  = $error->getMessage();
            update_option( $mode, $state, false );
        }
        if ( function_exists( 'wc_get_logger' ) ) {
            wc_get_logger()->error( $error->getMessage(), array( 'source' => 'plantis-signals' ) );
        }
    } finally {
        delete_option( 'plnt_signals_worker_lock' );
    }
    if ( $queue_next ) {
        plnt_signals_enqueue_worker();
    }
}

/** Кнопка повторного запуска/восстановления прерванного импорта. */
add_action( 'admin_post_plnt_signals_retry_backfill', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'Недостаточно прав.' );
    }
    check_admin_referer( 'plnt_signals_retry_backfill' );
    $state = get_option( 'plnt_signals_backfill_state', array() );
    $status = is_array( $state ) ? ( $state['status'] ?? '' ) : '';
    if ( 'running' !== $status ) {
        // Полный повторный проход: данные пересоздаются для существующих заказов.
        plnt_signals_start_backfill( true );
    }
    wp_safe_redirect( admin_url( 'admin.php?page=plantis-signals' ) );
    exit;
} );

/** Ручное обновление индексных данных с предыдущей отметки. */
add_action( 'admin_post_plnt_signals_refresh', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'Недостаточно прав.' );
    }
    check_admin_referer( 'plnt_signals_refresh' );
    // Используем тот же хук, что и ночной запуск; защита от параллельной задачи внутри.
    do_action( PLNT_SIGNALS_NIGHT_HOOK );
    wp_safe_redirect( admin_url( 'admin.php?page=plantis-signals' ) );
    exit;
} );

/**
 * В случае удаления заказа технический индекс тоже очищается. Обновление
 * опубликованного агрегата, как и остальные изменения, произойдёт ночью.
 */
function plnt_signals_clear_order_index( $order_id ) {
    global $wpdb;
    if ( get_option( 'plnt_signals_db_version' ) === PLNT_SIGNALS_DB_VERSION ) {
        $wpdb->delete( plnt_signals_items_table(), array( 'order_id' => (int) $order_id ), array( '%d' ) );
    }
}
add_action( 'woocommerce_trash_order', 'plnt_signals_clear_order_index' );
add_action( 'wp_trash_post', function ( $post_id ) {
    if ( 'shop_order' === get_post_type( $post_id ) ) {
        plnt_signals_clear_order_index( $post_id );
    }
} );
add_action( 'before_delete_post', function ( $post_id ) {
    if ( 'shop_order' === get_post_type( $post_id ) ) {
        plnt_signals_clear_order_index( $post_id );
    }
} );
add_action( 'woocommerce_before_delete_order', function ( $order_id ) {
    plnt_signals_clear_order_index( $order_id );
} );
