<?php
/**
 * Plantis — правила ACF и рекомендации по скидкам.
 * Данные сохраняются в plnt_signals_stats. Цены товаров НЕ меняем.
 */

defined( 'ABSPATH' ) || exit;

/**
 * ACF options page можно создать самостоятельно с собственным post_id.
 * Сначала используем post_id из предыдущего ТЗ, затем стандартный ACF 'option'.
 * Каждая строка репитера задаёт нижнюю границу остатка:
 * 1 = [1,2), 2 = [2,3), 3 = [3,∞) и т.д.
 */
function plnt_signals_discount_rules() {
    static $cache = null;
    if ( null !== $cache ) {
        return $cache;
    }

    $cache = array();
    if ( ! function_exists( 'get_field' ) ) {
        return $cache;
    }

    $rows = get_field( 'plnt_signals_discount_rules', 'option' );

    if ( ! is_array( $rows ) || ! $rows ) {
        return $cache;
    }

    foreach ( $rows as $row ) {
        $min      = isset( $row['min_stock'] ) ? absint( $row['min_stock'] ) : 0;
        $days     = isset( $row['days_without_sales'] ) ? absint( $row['days_without_sales'] ) : 0;
        $discount = isset( $row['discount_percent'] ) ? (float) $row['discount_percent'] : 0;
        if ( $min < 1 || $days < 1 || $discount <= 0 || $discount >= 100 ) {
            continue;
        }

        // При дублировании нижней границы сохраняем первое подходящее правило.
        if ( ! isset( $cache[ $min ] ) ) {
            $cache[ $min ] = array(
                'min_stock'        => $min,
                'days'             => $days,
                'discount_percent' => $discount,
            );
        }
    }

    ksort( $cache, SORT_NUMERIC );
    return array_values( $cache );
}

/** Находим строку с максимальной нижней границей, не превышающей остаток. */
function plnt_signals_rule_for_stock( $stock ) {
    $matched = null;
    foreach ( plnt_signals_discount_rules() as $rule ) {
        if ( $stock < $rule['min_stock'] ) {
            break;
        }
        $matched = $rule;
    }
    return $matched;
}

/**
 * Запоминаем начало действия акционной цены только при наблюдаемом изменении поля.
 * Если скидка существовала до установки этого модуля, дату её начала узнать нельзя:
 * при первом наблюдении считаем текущий момент началом наблюдения.
 */
function plnt_signals_track_sale_price( WC_Product $product ) {
    if ( ! $product->is_type( 'simple' ) ) {
        return;
    }

    $id       = $product->get_id();
    $sale     = (string) $product->get_sale_price( 'edit' );
    $meta_key = '_plnt_signals_observed_sale_price';
    $has_old  = metadata_exists( 'post', $id, $meta_key );
    $old      = (string) get_post_meta( $id, $meta_key, true );

    if ( $has_old && $sale === $old ) {
        return;
    }

    update_post_meta( $id, $meta_key, $sale );
    if ( '' === $sale ) {
        delete_post_meta( $id, '_plnt_signals_sale_started_gmt' );
    } else {
        update_post_meta( $id, '_plnt_signals_sale_started_gmt', gmdate( 'Y-m-d H:i:s' ) );
    }
}

/** Разность именно календарных дней в часовом поясе магазина. */
function plnt_signals_days_since_sale( $product_id, $stats ) {
    $expo = get_post_meta( $product_id, '_plnt_expo_days_reset', true );
    if ( '' === $expo || ! is_numeric( $expo ) ) {
        return null;
    }
    $days = max( 0, (int) $expo );

    if ( $stats && ! empty( $stats->last_sale_date_gmt ) ) {
        $timestamp = strtotime( $stats->last_sale_date_gmt . ' UTC' );
        if ( false !== $timestamp ) {
            $timezone = wp_timezone();
            $sale_day = ( new DateTimeImmutable( '@' . $timestamp ) )
                ->setTimezone( $timezone )->setTime( 0, 0 );
            $today = new DateTimeImmutable( 'today', $timezone );
            $since = $sale_day > $today ? 0 : (int) $sale_day->diff( $today )->days;
            $days = min( $days, $since );
        }
    }
    return $days;
}

/**
 * Подтверждённая продажа после начала наблюдения за акцией.
 * Проверяем ОБЕ даты: создание заказа и его завершение должны быть не раньше акции.
 */
function plnt_signals_completed_sale_after_discount( $product_id, $sale_started_gmt ) {
    global $wpdb;
    if ( ! $sale_started_gmt ) {
        return false;
    }
    $table = plnt_signals_items_table();
    return (bool) $wpdb->get_var( $wpdb->prepare(
        "SELECT 1 FROM {$table}
         WHERE product_id = %d AND order_status = 'completed'
           AND order_created_gmt >= %s
           AND order_completed_gmt >= %s
           AND qty > 0 LIMIT 1",
        $product_id, $sale_started_gmt, $sale_started_gmt
    ) );
}

/** Рассчитываем состояние одного товара на основании правила ACF. */
function plnt_signals_calculate_recommendation( WC_Product $product, $stats = null ) {
    $none = array( 'type' => 'none', 'priority' => 0, 'discount' => 0, 'days' => 0, 'rule_days' => 0 );
    if ( ! $product->is_type( 'simple' ) || ! $product->managing_stock() ) {
        return $none;
    }

    $stock = $product->get_stock_quantity();
    if ( null === $stock || $stock <= 0 || (float) $product->get_regular_price( 'edit' ) <= 0 ) {
        return $none;
    }

    $rule = plnt_signals_rule_for_stock( (int) $stock );
    $days = plnt_signals_days_since_sale( $product->get_id(), $stats );
    if ( ! $rule || null === $days ) {
        return $none;
    }

    $reached = $days >= $rule['days'];
    $sale    = (string) $product->get_sale_price( 'edit' );
    $type    = 'none';
    $priority = 0;

    if ( '' === $sale && $reached ) {
        $type = 'add';
        $priority = 3;
    } elseif ( '' !== $sale ) {
        if ( ! $reached && plnt_signals_completed_sale_after_discount(
            $product->get_id(),
            get_post_meta( $product->get_id(), '_plnt_signals_sale_started_gmt', true )
        ) ) {
            $type = 'remove';
            $priority = 2;
        } else {
            $type = 'active';
            $priority = 1;
        }
    }

    return array(
        'type'      => $type,
        'priority'  => $priority,
        'discount'  => (float) $rule['discount_percent'],
        'days'      => $days,
        'rule_days' => (int) $rule['days'],
    );
}

/** Сохраняем только поля рекомендации, не стирая среднюю цену и дату продажи. */
function plnt_signals_save_recommendation( $product_id, $rec ) {
    global $wpdb;
    $table = plnt_signals_stats_table();
    $now   = gmdate( 'Y-m-d H:i:s' );
    return false !== $wpdb->query( $wpdb->prepare(
        "INSERT INTO {$table}
            (product_id, recommendation_type, recommendation_priority,
             recommendation_discount, recommendation_days, recommendation_updated_gmt)
         VALUES (%d, %s, %d, %f, %d, %s)
         ON DUPLICATE KEY UPDATE
             recommendation_type = VALUES(recommendation_type),
             recommendation_priority = VALUES(recommendation_priority),
             recommendation_discount = VALUES(recommendation_discount),
             recommendation_days = VALUES(recommendation_days),
             recommendation_updated_gmt = VALUES(recommendation_updated_gmt)",
        $product_id, $rec['type'], $rec['priority'], $rec['discount'], $rec['days'], $now
    ) );
}

/** Можно пересчитать все растения (ночью) или только переданные ID. */
function plnt_signals_rebuild_recommendations( $product_ids = null ) {
    if ( ! plnt_signals_install_tables() ) {
        return;
    }

    if ( is_array( $product_ids ) ) {
        $ids = array_values( array_filter( array_unique( array_map( 'absint', $product_ids ) ) ) );
        if ( $ids ) {
            plnt_signals_rebuild_recommendation_batch( $ids );
        }
        return;
    }

    $page = 1;
    do {
        $query = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => array( 'publish', 'private', 'draft', 'pending', 'future' ),
            'posts_per_page' => 150,
            'paged'          => $page,
            'fields'         => 'ids',
            'orderby'       => 'ID',
            'order'         => 'ASC',
            'no_found_rows'  => false,
        ) );
        $ids = $query->posts;
        if ( $ids ) {
            plnt_signals_rebuild_recommendation_batch( $ids );
        }
        $page++;
    } while ( $page <= $query->max_num_pages );
}

function plnt_signals_rebuild_recommendation_batch( array $ids ) {
    $stats_by_id = plnt_signals_get_stats_for_ids( $ids );
    foreach ( $ids as $id ) {
        $product = wc_get_product( $id );
        if ( ! $product || ! $product->is_type( 'simple' ) ) {
            continue;
        }
        plnt_signals_track_sale_price( $product );
        $rec = plnt_signals_calculate_recommendation( $product, $stats_by_id[ $id ] ?? null );
        plnt_signals_save_recommendation( $id, $rec );
    }
}

// После ночного обновления и после завершения отдельного заказа.
add_action( 'plnt_signals_aggregates_rebuilt', 'plnt_signals_rebuild_recommendations', 10, 1 );

// Если сотрудник изменил акционную цену или остаток — обновить рекомендацию сразу.
add_action( 'woocommerce_after_product_object_save', function ( $product ) {
    if ( ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
        return;
    }
    if ( ! plnt_signals_install_tables() ) {
        return;
    }
    plnt_signals_track_sale_price( $product );
    plnt_signals_rebuild_recommendations( array( $product->get_id() ) );
}, 30 );

// После изменения правил ACF — фоновый пересчёт. Страница настроек создаётся пользователем.
add_action( 'acf/save_post', function ( $post_id ) {
    if ( ! in_array( (string) $post_id, array( 'plnt_signals_settings', 'options', 'option' ), true ) ) {
        return;
    }
    if ( function_exists( 'as_enqueue_async_action' ) ) {
        as_enqueue_async_action( 'plnt_signals_rebuild_rules', array(), 'plantis-signals', true );
    } else {
        plnt_signals_rebuild_recommendations();
    }
}, 20 );
add_action( 'plnt_signals_rebuild_rules', 'plnt_signals_rebuild_recommendations' );

/**
 * После выполнения заказа индексируем его и обновляем дату последней продажи.
 * Не блокируем админку/checkout тяжёлым пересчётом — используем Action Scheduler.
 */
add_action( 'woocommerce_order_status_completed', function ( $order_id ) {
    if ( get_option( 'plnt_signals_db_version' ) !== PLNT_SIGNALS_DB_VERSION ) {
        return;
    }
    if ( function_exists( 'as_enqueue_async_action' ) ) {
        as_enqueue_async_action(
            'plnt_signals_completed_order_refresh',
            array( (int) $order_id ),
            'plantis-signals',
            true
        );
    } else {
        wp_schedule_single_event( time() + 30, 'plnt_signals_completed_order_refresh', array( (int) $order_id ) );
    }
}, 30 );

add_action( 'plnt_signals_completed_order_refresh', function ( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order || ! plnt_signals_install_tables() ) {
        return;
    }

    $ids = array();
    foreach ( $order->get_items( 'line_item' ) as $item ) {
        if ( $item instanceof WC_Order_Item_Product && $item->get_product_id() ) {
            $ids[] = (int) $item->get_product_id();
        }
    }

    // Не конфликтуем с пакетным импортом заказов.
    if ( get_option( 'plnt_signals_worker_lock', false ) ) {
        if ( function_exists( 'as_schedule_single_action' ) ) {
            as_schedule_single_action( time() + 90, 'plnt_signals_completed_order_refresh', array( (int) $order_id ), 'plantis-signals', true );
        }
        return;
    }

    plnt_signals_index_order( (int) $order_id );
    plnt_signals_rebuild_aggregates( array_unique( $ids ) );
}, 10, 1 );
