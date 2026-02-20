<?php
if (!defined('ABSPATH')) exit;

/**
 * Define fields in one place
 */
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
        '_plnt_sales_total' => [
            'label'       => 'Кол-во продаж товара — всего',
            'description' => 'Суммарное кол-во продаж товара — всего.',
            'csv_key'     => 'plnt_sales_total',
            'csv_label'   => 'Кол-во продаж товара — всего',
        ],
        '_plnt_sales_reset' => [
            'label'       => 'Кол-во продаж после обнуления',
            'description' => 'Кол-во продаж после последнего обнуления.',
            'csv_key'     => 'plnt_sales_reset',
            'csv_label'   => 'Кол-во продаж после обнуления',
        ],
    ];
}

/**
 * Admin: add fields to product edit screen (General tab)
 */
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
 * Admin: save fields (only if present in POST)
 * Если поля будут заполняться ТОЛЬКО программно — этот блок можно удалить,
 * или сделать readonly-поля и оставить без сохранения.
 */
// add_action('woocommerce_admin_process_product_object', function ($product) {
//     foreach (plnt_product_custom_fields_def() as $meta_key => $f) {
//         if (!isset($_POST[$meta_key])) continue;

//         $raw = wp_unslash($_POST[$meta_key]);
//         $val = ($raw === '') ? '' : (string) max(0, (int) $raw);

//         if ($val === '') {
//             $product->delete_meta_data($meta_key);
//         } else {
//             $product->update_meta_data($meta_key, $val);
//         }
//     }

//     // Чтобы точно сохранилось
//     $product->save();
// });

/**
 * CSV Export: add columns
 */
add_filter('woocommerce_product_export_column_names', function ($columns) {
    foreach (plnt_product_custom_fields_def() as $meta_key => $f) {
        $columns[$f['csv_key']] = $f['csv_label'];
    }
    return $columns;
});

add_filter('woocommerce_product_export_product_default_columns', function ($columns) {
    foreach (plnt_product_custom_fields_def() as $meta_key => $f) {
        $columns[$f['csv_key']] = $f['csv_label'];
    }
    return $columns;
});

/**
 * CSV Export: provide values for each column
 */
add_filter('woocommerce_product_export_product_column_plnt_expo_days_total', function ($value, $product) {
    $v = $product->get_meta('_plnt_expo_days_total', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

add_filter('woocommerce_product_export_product_column_plnt_expo_days_reset', function ($value, $product) {
    $v = $product->get_meta('_plnt_expo_days_reset', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

add_filter('woocommerce_product_export_product_column_plnt_sales_total', function ($value, $product) {
    $v = $product->get_meta('_plnt_sales_total', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);

add_filter('woocommerce_product_export_product_column_plnt_sales_reset', function ($value, $product) {
    $v = $product->get_meta('_plnt_sales_reset', true);
    return ($v === '' || $v === null) ? '' : (string) (int) $v;
}, 10, 2);


/**
 * Schedule daily cron (once)
 */
function plnt_schedule_daily_expo_counter_cron(): void {
    if (!wp_next_scheduled('plnt_daily_expo_days_update_hook')) {
        wp_schedule_event(time() + 600, 'daily', 'plnt_daily_expo_days_update_hook');
    }
}
add_action('init', 'plnt_schedule_daily_expo_counter_cron');

/**
 * Qualify rule:
 * - published
 * - manage_stock enabled
 * - stock_quantity >= 1
 *
 * Backorders НЕ учитываем (разрешены/не разрешены — не важно)
 */
function plnt_product_is_in_expo_today(WC_Product $product): bool {
    if ($product->get_status() !== 'publish') return false;
    if (!$product->managing_stock()) return false;

    $qty = $product->get_stock_quantity();
    if ($qty === null || (int)$qty < 1) return false;

    return true;
}

/**
 * Daily job:
 * - _plnt_expo_days_total: +1 if qualifies
 * - _plnt_expo_days_reset: +1 if qualifies, else reset to 0
 */
function plnt_daily_expo_days_update(): void {

    $today = (new DateTime('now', wp_timezone()))->format('Y-m-d');
    $last  = get_option('plnt_expo_days_last_run', '');
    if ($last === $today) return;

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

            $product_id = $product->get_id();
            $qualifies  = plnt_product_is_in_expo_today($product);

            // TOTAL
            if ($qualifies) {
                $total = (int) get_post_meta($product_id, '_plnt_expo_days_total', true);
                update_post_meta($product_id, '_plnt_expo_days_total', (string) ($total + 1));
            }

            // RESET expo + RESET sales (синхронно)
            $expo_reset  = (int) get_post_meta($product_id, '_plnt_expo_days_reset', true);
            $sales_reset = (int) get_post_meta($product_id, '_plnt_sales_reset', true);

            if ($qualifies) {
                update_post_meta($product_id, '_plnt_expo_days_reset', (string) ($expo_reset + 1));
            } else {
                if ($expo_reset !== 0) {
                    update_post_meta($product_id, '_plnt_expo_days_reset', '0');
                }
                if ($sales_reset !== 0) {
                    update_post_meta($product_id, '_plnt_sales_reset', '0');
                }
            }
        }

        $page++;
    } while (count($products) === $limit);

    update_option('plnt_expo_days_last_run', $today, false);
}
add_action('plnt_daily_expo_days_update_hook', 'plnt_daily_expo_days_update', 10);



/**
 * Return day range timestamps for a specific date in site timezone
 */
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

/**
 * Daily job: add "yesterday completed" quantities to product counters
 * Uses _date_completed BETWEEN start_ts/end_ts
 */
function plnt_daily_sales_update(): void {

    $today = (new DateTime('now', wp_timezone()))->format('Y-m-d');
    $last  = get_option('plnt_sales_last_run', '');
    if ($last === $today) return;

    $yesterday = (new DateTime('yesterday', wp_timezone()))->format('Y-m-d');
    $range = plnt_get_day_timestamps($yesterday);

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

            // надежный фильтр по _date_completed
            'meta_query' => [
                [
                    'key'     => '_date_completed',
                    'value'   => [$range['start_ts'], $range['end_ts']],
                    'compare' => 'BETWEEN',
                    'type'    => 'NUMERIC',
                ],
            ],
        ]);

        $orders = $result->orders ?? [];

        foreach ($orders as $order) {
            if (!$order instanceof WC_Order) continue;

            foreach ($order->get_items('line_item') as $item) {
                if (!$item instanceof WC_Order_Item_Product) continue;

                $product_id = (int) $item->get_product_id();
                if ($product_id <= 0) continue;

                $qty = (int) $item->get_quantity();
                if ($qty <= 0) continue;

                $sold[$product_id] = ($sold[$product_id] ?? 0) + $qty;
            }
        }

        $total_pages = (int) ($result->max_num_pages ?? 0);
        $page++;
    } while ($total_pages && $page <= $total_pages);

    foreach ($sold as $product_id => $qty_sold) {
        $total = (int) get_post_meta($product_id, '_plnt_sales_total', true);
        $reset = (int) get_post_meta($product_id, '_plnt_sales_reset', true);

        update_post_meta($product_id, '_plnt_sales_total', (string) ($total + $qty_sold));
        update_post_meta($product_id, '_plnt_sales_reset', (string) ($reset + $qty_sold));
    }

    update_option('plnt_sales_last_run', $today, false);

    update_option('plnt_sales_last_stats', [
        'counted_day' => $yesterday,
        'range'       => [$range['start'], $range['end']],
        'orders_pages'=> $total_pages ?? 0,
        'products'    => count($sold),
        'qty_total'   => array_sum($sold),
        'timestamp'   => (new DateTime('now', wp_timezone()))->format('Y-m-d H:i:s'),
    ], false);
}

add_action('plnt_daily_expo_days_update_hook', 'plnt_daily_sales_update', 20);

/**
 * Manual run via URL (SAFE):
 * /wp-admin/?plnt_run_expo=1
 * Запускает весь ежедневный сценарий (expo + sales), НО без обхода защиты от повторного запуска в тот же день.
 */
function plnt_manual_run_expo_days_update(): void {

    if (!is_admin()) return;
    if (!isset($_GET['plnt_run_expo'])) return;

    if (!current_user_can('manage_woocommerce')) {
        wp_die('No permission');
    }

    // Запускаем общий daily hook (expo + sales)
    do_action('plnt_daily_expo_days_update_hook');

    wp_die('OK: Daily hook executed (expo + sales). If it already ran today — nothing changed.');
}
add_action('admin_init', 'plnt_manual_run_expo_days_update');