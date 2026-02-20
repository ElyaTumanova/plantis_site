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

    // Чтобы точно сохранилось
    $product->save();
});

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