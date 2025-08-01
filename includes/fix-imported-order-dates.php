<?php
/**
 * Фикс дат заказов, созданных через импорт
 */

add_action('init', function () {
    global $wpdb;

    // Получаем все заказы, где есть флаг _need_fix_dates
    $orders = $wpdb->get_col("
        SELECT post_id FROM {$wpdb->postmeta} 
        WHERE meta_key = '_need_fix_dates' AND meta_value = '1'
    ");

    if (empty($orders)) return;

    foreach ($orders as $order_id) {
        $dates = get_post_meta($order_id, '_imported_dates', true);
        if (empty($dates)) continue;

        // ✅ Обновляем дату создания
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

        // ✅ Дата оплаты
        if (!empty($dates['paid'])) {
            update_post_meta($order_id, '_date_paid', $dates['paid']);
            if (!empty($dates['paid_gmt'])) update_post_meta($order_id, '_date_paid_gmt', $dates['paid_gmt']);
        }

        // ✅ Дата завершения
        if (!empty($dates['completed'])) {
            update_post_meta($order_id, '_date_completed', $dates['completed']);
            if (!empty($dates['completed_gmt'])) update_post_meta($order_id, '_date_completed_gmt', $dates['completed_gmt']);
        }

        // ✅ Убираем флаг и служебные данные
        delete_post_meta($order_id, '_need_fix_dates');
        delete_post_meta($order_id, '_imported_dates');

        error_log("✅ Даты заказа #$order_id исправлены SQL-обновлением");
    }
});
