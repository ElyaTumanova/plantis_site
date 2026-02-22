<?php
/**
 * Plugin Name: Plantis Product Counters (Expo + Sales)
 * Description: Custom product meta fields + daily counters for exposition days and sales, with WooCommerce CSV export and logging.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

/* =========================================================
 *  Logger (WooCommerce -> Status -> Logs -> plnt-counters-*)
 * ========================================================= */

function plnt_logger(): ?WC_Logger {
    return function_exists('wc_get_logger') ? wc_get_logger() : null;
}

function plnt_log_info(string $message, array $context = []): void {
    $logger = plnt_logger();
    if (!$logger) return;
    $logger->info($message, array_merge(['source' => 'plnt-counters'], $context));
}

function plnt_log_error(string $message, array $context = []): void {
    $logger = plnt_logger();
    if (!$logger) return;
    $logger->error($message, array_merge(['source' => 'plnt-counters'], $context));
}

/* =========================================
 *  Fields definition (single source of truth)
 * ========================================= */

function plnt_product_custom_fields_def(): array {
    return [
        '_plnt_expo_days_total' => [
            'label'       => 'Кол-во дней в экспозиции — всего',
            'description' => 'Кол-во дней, когда товар был в наличии (кол-во товара >= 1) — всего.',
            'csv_key'     => 'plnt_expo_days_total',
            'csv_label'   => 'Кол-во дней в экспозиции — всего',
        ],
        '_plnt_expo_days_reset' => [
            'label'       => 'Кол-во дней в экспозиции после обнуления',
            'description' => 'Кол-во дней в экспозиции после последнего обнуления.',
            'csv_key'     => 'plnt_expo_days_reset',
            'csv_label'   => 'Кол-во дней в экспозиции после обнуления',
        ],
        '_plnt_expo_sales_total' => [
            'label'       => 'Кол-во продаж товара — всего',
            'description' => 'Суммарное кол-во продаж товара — всего.',
            'csv_key'     => 'plnt_expo_sales_total',
            'csv_label'   => 'Кол-во продаж товара — всего',
        ],
        '_plnt_expo_sales_reset' => [
            'label'       => 'Кол-во продаж после обнуления',
            'description' => 'Кол-во продаж после последнего обнуления.',
            'csv_key'     => 'plnt_expo_sales_reset',
            'csv_label'   => 'Кол-во продаж после обнуления',
        ],
    ];
}

/* =========================
 *  Admin fields (General tab)
 * ========================= */

add_action('woocommerce_product_options_general_product_data', function () {
    foreach (plnt_product_custom_fields_def() as $meta_key => $f) {
        woocommerce_wp_text_input([
            'id'                => $meta_key,
            'label'             => $f['label'],
            'desc_tip'          => true,
            'description'       => $f['description'],
            'type'              => 'number',
            'custom_attributes' => [
                'min'  => '0',
                'step' => '1',
            ],
        ]);
    }
});

/**
 * Admin save (оставь, если допускаешь ручное редактирование).
 * Если поля ТОЛЬКО программно — удали этот блок или сделай readonly.
 */
add_action('woocommerce_admin_process_product_object', function ($product) {
    foreach (plnt_product_custom_fields_def() as $meta_key => $f) {
        if (!isset($_POST[$meta_key])) continue;

        $raw = wp_unslash($_POST[$meta_key]);
        $val = ($raw === '') ? '' : (string) max(0, (int) $raw);

        if ($val === '') {
            $product->delete_meta_data($meta_key);
        } else {
            $product->update_meta_data($meta_key, $val);
        }
    }
    $product->save();
});

/* =========================
 *  WooCommerce CSV Export
 * ========================= */

add_filter('woocommerce_product_export_column_names', function ($columns) {
    foreach (plnt_product_custom_fields_def() as $f) {
        $columns[$f['csv_key']] = $f['csv_label'];
    }
    return $columns;
});

add_filter('woocommerce_product_export_product_default_columns', function ($columns) {
    foreach (plnt_product_custom_fields_def() as $f) {
        $columns[$f['csv_key']] = $f['csv_label'];
    }
    return $columns;
});

add_filter('woocommerce_product_export_product_column_plnt_expo_days_total', function ($value, $product) {
    $v = $product->get_meta('_plnt_expo_days_total', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

add_filter('woocommerce_product_export_product_column_plnt_expo_days_reset', function ($value, $product) {
    $v = $product->get_meta('_plnt_expo_days_reset', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

add_filter('woocommerce_product_export_product_column_plnt_expo_sales_total', function ($value, $product) {
    $v = $product->get_meta('_plnt_expo_sales_total', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

add_filter('woocommerce_product_export_product_column_plnt_expo_sales_reset', function ($value, $product) {
    $v = $product->get_meta('_plnt_expo_sales_reset', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

/* =========================
 *  Cron scheduling (daily)
 * ========================= */

function plnt_schedule_daily_expo_counter_cron(): void {
    if (!wp_next_scheduled('plnt_daily_expo_days_update_hook')) {
        // первый запуск примерно через 10 минут после первого init
        wp_schedule_event(time() + 600, 'daily', 'plnt_daily_expo_days_update_hook');
        plnt_log_info('cron scheduled', ['hook' => 'plnt_daily_expo_days_update_hook']);
    }
}
add_action('init', 'plnt_schedule_daily_expo_counter_cron');

/* =========================
 *  Expo qualification rule
 * ========================= */

function plnt_product_is_in_expo_today(WC_Product $product): bool {
    if ($product->get_status() !== 'publish') return false;
    if (!$product->managing_stock()) return false;

    $qty = $product->get_stock_quantity();
    if ($qty === null || (int) $qty < 1) return false;

    return true;
}

/* =========================================
 *  Expo daily job (FAST: update_post_meta)
 *  - _plnt_expo_days_total: +1 if qualifies
 *  - _plnt_expo_days_reset: +1 if qualifies, else -> 0
 *  - _plnt_expo_sales_reset:    -> 0 when expo resets
 * ========================================= */

function plnt_daily_expo_days_update(): void {

    $stats = [
        'checked'            => 0,
        'total_inc'          => 0,
        'expo_inc'           => 0,
        'expo_restarted'     => 0, // сколько раз стартовали заново после паузы
        'expo_paused_set'    => 0, // сколько раз поставили флаг паузы
        'expo_sales_zeroed'  => 0,
        'pages'              => 0,
    ];

    // meta flag key: 1 = paused (не qualifies), 0/empty = active
    $pause_flag_key = '_plnt_expo_paused';

    try {
        $today = (new DateTime('now', wp_timezone()))->format('Y-m-d');
        $last  = get_option('plnt_expo_days_last_run', '');
        if ($last === $today) {
            plnt_log_info('expo: skipped (already ran today)', ['date' => $today]);
            return;
        }

        $page  = 1;
        $limit = 200;

        do {
            $products = wc_get_products([
                'status' => ['publish', 'draft', 'pending', 'private'],
                'type'   => ['simple'], // только simple
                'limit'  => $limit,
                'page'   => $page,
                'return' => 'objects',
            ]);

            foreach ($products as $product) {
                if (!$product instanceof WC_Product) continue;

                $stats['checked']++;

                $product_id = $product->get_id();
                $qualifies  = plnt_product_is_in_expo_today($product);

                // TOTAL
                if ($qualifies) {
                    $total = (int) get_post_meta($product_id, '_plnt_expo_days_total', true);
                    update_post_meta($product_id, '_plnt_expo_days_total', (string) ($total + 1));
                    $stats['total_inc']++;
                }

                // текущие значения
                $expo_reset       = (int) get_post_meta($product_id, '_plnt_expo_days_reset', true);
                $expo_sales_reset = (int) get_post_meta($product_id, '_plnt_expo_sales_reset', true);
                $paused           = (int) get_post_meta($product_id, $pause_flag_key, true); // 1/0

                if ($qualifies) {
                    // Если до этого был "paused", значит началась новая серия => reset = 0 и стартуем заново
                    if ($paused === 1) {
                        update_post_meta($product_id, '_plnt_expo_days_reset', '1'); // 0 -> +1 за сегодня
                        update_post_meta($product_id, $pause_flag_key, '0');
                        $stats['expo_restarted']++;

                        // синхронно обнуляем продажи "после обнуления"
                        if ($expo_sales_reset !== 0) {
                            update_post_meta($product_id, '_plnt_expo_sales_reset', '0');
                            $stats['expo_sales_zeroed']++;
                        }
                    } else {
                        // обычное продолжение серии => +1
                        update_post_meta($product_id, '_plnt_expo_days_reset', (string) ($expo_reset + 1));
                        $stats['expo_inc']++;
                    }
                } else {
                    // не qualifies: ставим флаг паузы, reset НЕ трогаем и НЕ обнуляем
                    if ($paused !== 1) {
                        update_post_meta($product_id, $pause_flag_key, '1');
                        $stats['expo_paused_set']++;
                    }
                    // reset "заморожен" (ничего не делаем)
                    // sales_reset тоже НЕ трогаем (обнулится при рестарте серии)
                }
            }

            $stats['pages']++;
            $page++;
        } while (count($products) === $limit);

        update_option('plnt_expo_days_last_run', $today, false);

        plnt_log_info('expo: done', [
            'date'  => $today,
            'stats' => $stats,
        ]);

    } catch (Throwable $e) {
        plnt_log_error('expo: error ' . $e->getMessage(), [
            'stats' => $stats,
        ]);
    }
}
add_action('plnt_daily_expo_days_update_hook', 'plnt_daily_expo_days_update', 20);

/* =========================================
 *  Helpers: day timestamps (site timezone)
 * ========================================= */

function plnt_get_day_timestamps(string $ymd): array {
    $tz = wp_timezone();
    $start_dt = new DateTime($ymd . ' 00:00:00', $tz);
    $end_dt   = new DateTime($ymd . ' 23:59:59', $tz);

    return [
        'start_ts' => $start_dt->getTimestamp(),
        'end_ts'   => $end_dt->getTimestamp(),
        'start'    => $start_dt->format('Y-m-d H:i:s'),
        'end'      => $end_dt->format('Y-m-d H:i:s'),
        'tz'       => $tz->getName(),
    ];
}

/* =========================================
 *  Sales daily job (FAST: update_post_meta)
 *  Counts qty from orders that were moved to completed yesterday
 *  via _date_completed BETWEEN (NUMERIC)
 *  - _plnt_expo_sales_total += qty
 *  - _plnt_expo_sales_reset += qty (если expo не сбросился)
 * ========================================= */

function plnt_daily_sales_update(): void {

    $stats = [
        'counted_day'      => null,
        'range'            => null,
        'orders_scanned'   => 0,
        'items_scanned'    => 0,
        'products_touched' => 0,
        'qty_total'        => 0,
        'pages'            => 0,
    ];

    try {
        $today = (new DateTime('now', wp_timezone()))->format('Y-m-d');
        $last  = get_option('plnt_expo_sales_last_run', '');
        if ($last === $today) {
            plnt_log_info('sales: skipped (already ran today)', ['date' => $today]);
            return;
        }

        $yesterday = (new DateTime('yesterday', wp_timezone()))->format('Y-m-d');
        $range = plnt_get_day_timestamps($yesterday);

        $stats['counted_day'] = $yesterday;
        $stats['range'] = [$range['start'], $range['end']];

        $limit = 100;
        $page  = 1;
        $sold  = [];

        do {
            $result = wc_get_orders([
                'status'   => ['completed'],
                'limit'    => $limit,
                'paged'    => $page,
                'orderby'  => 'date',
                'order'    => 'ASC',
                'return'   => 'objects',
                'paginate' => true,
                'type'     => 'shop_order',

                // ✅ CPT datastore friendly (без meta_query)
                'meta_key'     => '_date_completed',
                'meta_compare' => 'BETWEEN',
                'meta_value'   => [$range['start_ts'], $range['end_ts']],
                'meta_type'    => 'NUMERIC',
            ]);

            $orders = $result->orders ?? [];
            $stats['orders_scanned'] += count($orders);

            foreach ($orders as $order) {
                if (!$order instanceof WC_Order) continue;

                foreach ($order->get_items('line_item') as $item) {
                    if (!$item instanceof WC_Order_Item_Product) continue;

                    $stats['items_scanned']++;

                    $product_id = (int) $item->get_product_id();
                    if ($product_id <= 0) continue;

                    $qty = (int) $item->get_quantity();
                    if ($qty <= 0) continue;

                    $sold[$product_id] = ($sold[$product_id] ?? 0) + $qty;
                }
            }

            $total_pages = (int) ($result->max_num_pages ?? 0);
            $stats['pages']++;
            $page++;
        } while ($total_pages && $page <= $total_pages);

        foreach ($sold as $product_id => $qty_sold) {
            $total = (int) get_post_meta($product_id, '_plnt_expo_sales_total', true);
            $reset = (int) get_post_meta($product_id, '_plnt_expo_sales_reset', true);

            update_post_meta($product_id, '_plnt_expo_sales_total', (string) ($total + $qty_sold));
            update_post_meta($product_id, '_plnt_expo_sales_reset', (string) ($reset + $qty_sold));
        }

        $stats['products_touched'] = count($sold);
        $stats['qty_total'] = array_sum($sold);

        update_option('plnt_expo_sales_last_run', $today, false);

        update_option('plnt_expo_sales_last_stats', [
            'counted_day' => $yesterday,
            'range'       => [$range['start'], $range['end']],
            'products'    => count($sold),
            'qty_total'   => array_sum($sold),
            'timestamp'   => (new DateTime('now', wp_timezone()))->format('Y-m-d H:i:s'),
        ], false);

        plnt_log_info('sales: done', [
            'date'  => $today,
            'stats' => $stats,
        ]);

    } catch (Throwable $e) {
        plnt_log_error('sales: error ' . $e->getMessage(), [
            'stats' => $stats,
        ]);
    }
}

add_action('plnt_daily_expo_days_update_hook', 'plnt_daily_sales_update', 10);

/* =========================
 *  Manual run (SAFE) via URL
 *  /wp-admin/?plnt_run_expo=1
 * ========================= */

function plnt_manual_run_expo_days_update(): void {

    if (!is_admin()) return;
    if (!isset($_GET['plnt_run_expo'])) return;

    if (!current_user_can('manage_woocommerce')) {
        wp_die('No permission');
    }

    do_action('plnt_daily_expo_days_update_hook');

    wp_die('OK: Daily hook executed (expo + sales). If it already ran today — nothing changed.');
}
add_action('admin_init', 'plnt_manual_run_expo_days_update');