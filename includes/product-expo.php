<?php
if (!defined('ABSPATH')) exit;

/**
 * Expo days: add custom field to product edit screen
 */
add_action('woocommerce_product_options_general_product_data', function () {
    woocommerce_wp_text_input([
        'id'                => '_plnt_expo_days',
        'label'             => 'Кол-во дней в экспозиции',
        'desc_tip'          => true,
        'description'       => 'Целое число (например: 7).',
        'type'              => 'number',
        'custom_attributes' => [
            'min'  => '0',
            'step' => '1',
        ],
    ]);
});

/**
 * Expo days: save custom field
 */
add_action('woocommerce_admin_process_product_object', function ($product) {
    if (!isset($_POST['_plnt_expo_days'])) return;

    $raw = wp_unslash($_POST['_plnt_expo_days']);
    $val = ($raw === '') ? '' : (string) max(0, (int) $raw);

    if ($val === '') {
        $product->delete_meta_data('_plnt_expo_days');
    } else {
        $product->update_meta_data('_plnt_expo_days', $val);
    }
});


/**
 * Add column to WooCommerce Product CSV Export
 */
add_filter('woocommerce_product_export_column_names', function ($columns) {
    $columns['plnt_expo_days'] = 'Кол-во дней в экспозиции';
    return $columns;
});

add_filter('woocommerce_product_export_product_default_columns', function ($columns) {
    $columns['plnt_expo_days'] = 'Кол-во дней в экспозиции';
    return $columns;
});

/**
 * Provide data for the export column
 */
add_filter('woocommerce_product_export_product_column_plnt_expo_days', function ($value, $product) {
    $days = $product->get_meta('_plnt_expo_days', true);
    return ($days === '' || $days === null) ? '' : (string) (int) $days;
}, 10, 2);

