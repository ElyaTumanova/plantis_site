<?php
/**
 * Автоматическое исправление дат заказов после импорта
 * Этот файл подключается в functions.php
 */

add_action('woocommerce_admin_order_data_after_order_details', function($order){
    // для наглядности можно вывести сообщение в админке
    if (get_post_meta($order->get_id(), '_imported_dates', true)) {
        echo '<div style="padding:10px;background:#fffbcc;border:1px solid #ccc;">⏳ Дата заказа будет обновлена автоматически.</div>';
    }
});

add_action('woocommerce_checkout_update_order_meta', 'fix_imported_dates_sql', 100);
add_action('woocommerce_after_order_object_save', 'fix_imported_dates_sql', 100);

function fix_imported_dates_sql($order) {
    if (is_numeric($order)) {
        $order_id = $order;
    } elseif (is_object($order) && method_exists($order, 'get_id')) {
        $order_id = $order->get_id();
    } else {
        return;
    }

    $dates = get_post_meta($order_id, '_imported_dates', true);
    if (empty($dates) || !is_array($dates)) {
        return;
    }

    global $wpdb;

    // Обновляем дату создания
    if (!empty($dates['created'])) {
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_date'     => $dates['created'],
                'post_date_gmt' => !empty($dates['created_gmt']) ? $dates['created_gmt'] : gmdate('Y-m-d H:i:s', strtotime($dates['created']))
            ),
            array('ID' => $order_id)
        );
    }

    // Обновляем дату оплаты
    if (!empty($dates['paid'])) {
        update_post_meta($order_id, '_date_paid', $dates['paid']);
        if (!empty($dates['paid_gmt'])) {
            update_post_meta($order_id, '_date_paid_gmt', $dates['paid_gmt']);
        }
    }

    // Обновляем дату завершения
    if (!empty($dates['completed'])) {
        update_post_meta($order_id, '_date_completed', $dates['completed']);
        if (!empty($dates['completed_gmt'])) {
            update_post_meta($order_id, '_date_completed_gmt', $dates['completed_gmt']);
        }
    }

    // Чистим служебное поле, чтобы не мешалось
    delete_post_meta($order_id, '_imported_dates');
}
