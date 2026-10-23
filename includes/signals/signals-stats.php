<?php
/**
 * Plantis Signals — аналитические таблицы и расчёт из фактических позиций заказов.
 * Не изменяет метаполя product-expo.
 */

defined( 'ABSPATH' ) || exit;

const PLNT_SIGNALS_DB_VERSION = '1.0.0';
const PLNT_SIGNALS_BATCH_SIZE = 75;

function plnt_signals_stats_table() {
    global $wpdb;
    return $wpdb->prefix . 'plnt_signals_stats';
}

function plnt_signals_items_table() {
    global $wpdb;
    return $wpdb->prefix . 'plnt_signals_order_items';
}

/** Обновляется только при изменении версии схемы; не запускаем dbDelta на каждом запросе. */
function plnt_signals_install_tables() {
    if ( get_option( 'plnt_signals_db_version' ) === PLNT_SIGNALS_DB_VERSION ) {
        return true;
    }

    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $stats = plnt_signals_stats_table();
    $items = plnt_signals_items_table();
    $collate = $wpdb->get_charset_collate();

    dbDelta( "CREATE TABLE {$stats} (
        product_id bigint(20) unsigned NOT NULL,
        last_sale_date_gmt datetime DEFAULT NULL,
        avg_sale_price_6m decimal(20,4) DEFAULT NULL,
        sales_qty_6m decimal(20,4) NOT NULL DEFAULT 0,
        sales_total_6m decimal(24,4) NOT NULL DEFAULT 0,
        calculated_at_gmt datetime DEFAULT NULL,
        PRIMARY KEY  (product_id),
        KEY last_sale_date_gmt (last_sale_date_gmt),
        KEY avg_sale_price_6m (avg_sale_price_6m)
    ) {$collate};" );

    dbDelta( "CREATE TABLE {$items} (
        order_item_id bigint(20) unsigned NOT NULL,
        order_id bigint(20) unsigned NOT NULL,
        product_id bigint(20) unsigned NOT NULL,
        order_status varchar(40) NOT NULL,
        order_created_gmt datetime DEFAULT NULL,
        order_completed_gmt datetime DEFAULT NULL,
        qty decimal(20,4) NOT NULL DEFAULT 0,
        line_total decimal(24,4) NOT NULL DEFAULT 0,
        PRIMARY KEY  (order_item_id),
        KEY order_id (order_id),
        KEY product_id (product_id),
        KEY order_completed_gmt (order_completed_gmt)
    ) {$collate};" );

    // Не записываем версию, пока обе таблицы не существуют.
    $stats_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $stats ) ) );
    $items_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $items ) ) );
    if ( $stats_exists !== $stats || $items_exists !== $items ) {
        return false;
    }

    update_option( 'plnt_signals_db_version', PLNT_SIGNALS_DB_VERSION, false );
    return true;
}

/** Все значения из источника заказов сохраняем в UTC, отображение в timezone магазина. */
function plnt_signals_order_date_gmt( $date ) {
    return $date instanceof WC_DateTime ? gmdate( 'Y-m-d H:i:s', $date->getTimestamp() ) : null;
}

/**
 * Заменяем снимок ВСЕХ позиций заказа атомарно.
 * Таким образом изменение/удаление позиции, статуса и отмена не создают дублей.
 */
function plnt_signals_index_order( $order_id ) {
    global $wpdb;
    $table = plnt_signals_items_table();
    $order = wc_get_order( $order_id );

    // Заказ мог быть удалён/перемещён в корзину; в этом случае удаляем его строки.
    $is_shop_order = $order instanceof WC_Order && 'shop_order' === $order->get_type();
    $rows = array();

    if ( $is_shop_order ) {
        $status    = $order->get_status();
        $created   = plnt_signals_order_date_gmt( $order->get_date_created() );
        $completed = plnt_signals_order_date_gmt( $order->get_date_completed() );

        foreach ( $order->get_items( 'line_item' ) as $item ) {
            if ( ! $item instanceof WC_Order_Item_Product ) {
                continue;
            }
            $product_id = (int) $item->get_product_id();
            if ( ! $product_id ) {
                continue;
            }
            $quantity = max( 0, (float) $item->get_quantity() );
            if ( $quantity <= 0 ) {
                continue;
            }
            $rows[] = array(
                'order_item_id'      => (int) $item->get_id(),
                'order_id'           => (int) $order_id,
                'product_id'         => $product_id,
                'order_status'       => $status,
                'order_created_gmt'  => $created,
                'order_completed_gmt' => $completed,
                'qty'                => wc_format_decimal( $quantity, 4 ),
                // Товарная стоимость в заказе, после купонов, с налогом (если он есть).
                'line_total'         => wc_format_decimal( (float) $item->get_total() + (float) $item->get_total_tax(), 4 ),
            );
        }
    }

    $wpdb->query( 'START TRANSACTION' );
    try {
        $deleted = $wpdb->delete( $table, array( 'order_id' => $order_id ), array( '%d' ) );
        if ( false === $deleted ) {
            throw new RuntimeException( 'Ошибка очистки индекса заказа ' . $order_id );
        }

        $formats = array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' );
        foreach ( $rows as $row ) {
            $saved = $wpdb->insert( $table, $row, $formats );
            if ( false === $saved ) {
                throw new RuntimeException( 'Ошибка сохранения позиции заказа ' . $order_id );
            }
        }
        $wpdb->query( 'COMMIT' );
    } catch ( Throwable $exception ) {
        $wpdb->query( 'ROLLBACK' );
        throw $exception;
    }
}

/**
 * Пересчёт по сохранённым позициям. Никаких запросов к заказам для каждой строки товара.
 * Дата последнего заказа = дата создания (все статусы, кроме cancelled).
 * Средняя цена = SUM(line_total)/SUM(qty) выполненных заказов за скользящие 6 мес.
 */
function plnt_signals_rebuild_aggregates() {
    global $wpdb;

    $stats = plnt_signals_stats_table();
    $items = plnt_signals_items_table();
    $now   = new DateTimeImmutable( 'now', wp_timezone() );
    $cutoff = $now->modify( '-6 months' )->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Y-m-d H:i:s' );
    $now_gmt = gmdate( 'Y-m-d H:i:s' );

    // Несуществующие в индексе товары должны терять устаревшие значения.
    $wpdb->query( 'START TRANSACTION' );
    try {
        $sql = $wpdb->prepare(
            "INSERT INTO {$stats}
                (product_id, last_sale_date_gmt, avg_sale_price_6m, sales_qty_6m, sales_total_6m, calculated_at_gmt)
             SELECT product_id,
                MAX(CASE WHEN order_status <> 'cancelled' AND order_status <> 'trash' THEN order_created_gmt ELSE NULL END),
                CASE WHEN SUM(CASE WHEN order_status = 'completed' AND order_completed_gmt >= %s AND order_completed_gmt <= %s THEN qty ELSE 0 END) > 0
                    THEN SUM(CASE WHEN order_status = 'completed' AND order_completed_gmt >= %s AND order_completed_gmt <= %s THEN line_total ELSE 0 END)
                      / SUM(CASE WHEN order_status = 'completed' AND order_completed_gmt >= %s AND order_completed_gmt <= %s THEN qty ELSE 0 END)
                    ELSE NULL END,
                SUM(CASE WHEN order_status = 'completed' AND order_completed_gmt >= %s AND order_completed_gmt <= %s THEN qty ELSE 0 END),
                SUM(CASE WHEN order_status = 'completed' AND order_completed_gmt >= %s AND order_completed_gmt <= %s THEN line_total ELSE 0 END),
                %s
             FROM {$items}
             GROUP BY product_id
             ON DUPLICATE KEY UPDATE
                last_sale_date_gmt = VALUES(last_sale_date_gmt),
                avg_sale_price_6m = VALUES(avg_sale_price_6m),
                sales_qty_6m = VALUES(sales_qty_6m),
                sales_total_6m = VALUES(sales_total_6m),
                calculated_at_gmt = VALUES(calculated_at_gmt)",
            $cutoff, $now_gmt, $cutoff, $now_gmt, $cutoff, $now_gmt,
            $cutoff, $now_gmt, $cutoff, $now_gmt, $now_gmt
        );
        if ( false === $wpdb->query( $sql ) ) {
            throw new RuntimeException( 'Ошибка группировки данных «Сигналов».' );
        }

        $missing = $wpdb->prepare(
            "UPDATE {$stats} s LEFT JOIN {$items} i ON i.product_id = s.product_id
             SET s.last_sale_date_gmt = NULL, s.avg_sale_price_6m = NULL,
                 s.sales_qty_6m = 0, s.sales_total_6m = 0,
                 s.calculated_at_gmt = %s
             WHERE i.product_id IS NULL",
            $now_gmt
        );
        if ( false === $wpdb->query( $missing ) ) {
            throw new RuntimeException( 'Ошибка очистки устаревших показателей «Сигналов».' );
        }
        $wpdb->query( 'COMMIT' );
        update_option( 'plnt_signals_last_success', $now_gmt, false );
    } catch ( Throwable $exception ) {
        $wpdb->query( 'ROLLBACK' );
        throw $exception;
    }
}

/** Один запрос ко всем видимым товарам в таблице вместо запроса на каждый товар. */
function plnt_signals_get_stats_for_ids( array $ids ) {
    global $wpdb;
    $ids = array_values( array_filter( array_unique( array_map( 'absint', $ids ) ) ) );
    if ( ! $ids || get_option( 'plnt_signals_db_version' ) !== PLNT_SIGNALS_DB_VERSION ) {
        return array();
    }
    $placeholders = implode( ', ', array_fill( 0, count( $ids ), '%d' ) );
    $sql = $wpdb->prepare(
        'SELECT * FROM ' . plnt_signals_stats_table() . ' WHERE product_id IN (' . $placeholders . ')',
        ...$ids
    );
    $results = $wpdb->get_results( $sql, OBJECT_K );
    return is_array( $results ) ? $results : array();
}
