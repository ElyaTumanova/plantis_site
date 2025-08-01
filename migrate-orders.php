<?php

// === Данные API старого магазина ===
$old_url = "https://plantis.shop/wp-json/wc/v3";
$old_key = "ck_d0efbf184dd2bf49da53a4b8df98201faa5bcb8d";
$old_secret = "cs_3b711de00ebec91a2ce1bc0314dac8721c160c8a";

// === Данные API нового магазина ===
$new_url = "https://plantis-shop.ru/wp-json/wc/v3";
$new_key = "ck_771c883e1256823b9fa05f23e4f41b7b543aa311";
$new_secret = "cs_15ac1868a521fc7333c50c09f52901adaa524cd8";

// === Укажите ID заказа для переноса ===
$order_id_to_migrate = 65740;

// === Функция API ===
function wc_api_request($url, $key, $secret, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => "$key:$secret",
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// === Функция поиска товара по SKU ===
function get_product_id_by_sku($sku, $api_url, $key, $secret) {
    if (!$sku) {
        return null; // если SKU пустой — сразу возвращаем null
    }

    // Запрос к WooCommerce API по SKU
    $products = wc_api_request("$api_url/products?sku=" . urlencode($sku), $key, $secret);

    if (!empty($products) && isset($products[0]['id'])) {
        return $products[0]['id'];
    }

    return null; // товар не найден
}

function normalize_date($date) {
    if (!$date) return null;
    // Преобразуем в локальное ISO 8601 без смещения
    return date('Y-m-d\TH:i:s', strtotime($date));
}

function normalize_date_gmt($date) {
    if (!$date) return null;
    $timestamp = strtotime($date);

    // Определяем смещение сервера
    $server_offset = date('Z'); // смещение в секундах
    $utc_timestamp = $timestamp - $server_offset;

    return gmdate('Y-m-d\TH:i:s', $utc_timestamp);
}




// === Подготовка заказа ===
function prepare_order_for_import($old_order, $new_api_url, $new_key, $new_secret) {
    // Проверка существования заказа на новом сайте
    $existing = wc_api_request("$new_api_url/orders?search=" . $old_order['number'], $new_key, $new_secret);
    $order_exists = false;
    if (is_array($existing)) {
        foreach ($existing as $ex) {
            if (isset($ex['number']) && $ex['number'] == $old_order['number']) {
                $order_exists = true;
                break;
            }
        }
    }
    if ($order_exists) {
        echo "⚠️ Заказ {$old_order['number']} уже существует на новом сайте, пропускаем.\n";
        return null;
    }

    // 🔹 Подготовка товаров — поиск ТОЛЬКО по SKU
    $new_line_items = [];
    foreach ($old_order['line_items'] as $item) {

        // ✅ Ищем товар только по SKU
        $new_pid = get_product_id_by_sku(
            $item['sku'],
            str_replace('/orders', '', $new_api_url),
            $new_key,
            $new_secret
        );

        if (!$new_pid) {
            echo "❌ Товар {$item['name']} (SKU {$item['sku']}) не найден, пропускаем.\n";
            continue;
        }

        $new_line_items[] = [
            'product_id'   => $new_pid,
            'variation_id' => $item['variation_id'], // можно оставить, если вариации есть
            'quantity'     => $item['quantity'],
            'subtotal'     => $item['subtotal'],
            'total'        => $item['total'],
            'meta_data'    => array_map(function($m) {
                return ['key' => $m['key'], 'value' => $m['value']];
            }, $item['meta_data'])
        ];
    }
    

    // Подготовка доставки
    $new_shipping_lines = [];
    foreach ($old_order['shipping_lines'] as $ship) {
        $new_shipping_lines[] = [
            'method_id'    => $ship['method_id'],
            'method_title' => $ship['method_title'],
            'total'        => $ship['total'],
            'meta_data' => array_map(function($m) {
                return [
                    'key'   => $m['key'],
                    'value' => $m['value']
                ];
            }, $ship['meta_data'])
        ];
    }

    // Подготовка meta_data (удаляем дубли и переименовываем ключи)
    $new_meta = [];
    $added_keys = [];

    foreach ($old_order['meta_data'] as $meta) {
        $original_key = $meta['key'];

        // 🔹 Переименовываем ключи по правилам
        if ($original_key === 'billing_dontcallme' || $original_key === '_billing_dontcallme') {
            $key = 'dontcallme';
        } elseif ($original_key === 'additional_peresadka') {
            $key = '_plnt_comment';
        } else {
            $key = $original_key;
        }

        // 🔹 Пропускаем, если такой ключ уже добавлен
        if (in_array($key, $added_keys)) {
            continue;
        }

        $new_meta[] = [
            'key'   => $key,
            'value' => $meta['value']
        ];
        $added_keys[] = $key;
    }

    // 🔹 Добавляем служебные даты для обновления в базе
    $new_meta[] = [
        'key'   => '_imported_dates',
        'value' => [
            'created'         => $old_order['date_created'],
            'created_gmt'     => gmdate('Y-m-d H:i:s', strtotime($old_order['date_created'])),
            'paid'            => isset($old_order['date_paid']) ? $old_order['date_paid'] : '',
            'paid_gmt'        => isset($old_order['date_paid']) ? gmdate('Y-m-d H:i:s', strtotime($old_order['date_paid'])) : '',
            'completed'       => isset($old_order['date_completed']) ? $old_order['date_completed'] : '',
            'completed_gmt'   => isset($old_order['date_completed']) ? gmdate('Y-m-d H:i:s', strtotime($old_order['date_completed'])) : ''
        ]
    ];


    return [
        'customer_id'          => $old_order['customer_id'] ?: 0,
        'status'               => $old_order['status'],
        'currency'             => $old_order['currency'],
        'billing'              => $old_order['billing'],
        'shipping'             => $old_order['shipping'],
        'payment_method'       => $old_order['payment_method'],
        'payment_method_title' => $old_order['payment_method_title'],
        'customer_note'        => $old_order['customer_note'],
        'line_items'           => $new_line_items,
        'shipping_lines'       => $new_shipping_lines,
        'meta_data'            => $new_meta,
    ];
}

// === 1. Получаем заказ со старого сайта ===
$old_order = wc_api_request("$old_url/orders/$order_id_to_migrate", $old_key, $old_secret);
//var_dump($old_order); exit;
if (!$old_order || !isset($old_order['id'])) {
    exit("❌ Заказ с ID $order_id_to_migrate не найден на старом сайте.\n");
}

// === 2. Подготавливаем заказ ===
$new_order = prepare_order_for_import($old_order, "$new_url/orders", $new_key, $new_secret);

if (!$new_order) {
    exit("⚠️ Заказ {$old_order['number']} пропущен (уже существует или ошибка подготовки).\n");
}

// === 3. Отправляем заказ на новый сайт ===
$result = wc_api_request("$new_url/orders", $new_key, $new_secret, 'POST', $new_order);

if (!isset($result['id'])) {
    exit("❌ Ошибка при создании заказа {$old_order['number']}:\n" . print_r($result, true));
}

$new_order_id = $result['id'];
echo "✅ Заказ {$old_order['number']} создан на новом сайте (ID $new_order_id)\n";


// === 4. Добавляем SQL-хук в новый сайт ===
$wp_hook_file = '/var/www/u1478867/data/www/dev.plantis.shop/wp-content/themes/plantis_site/functions-import-dates.php';
if (!file_exists($wp_hook_file)) {
    file_put_contents($wp_hook_file, <<<PHP
<?php
add_action('woocommerce_new_order', function(\$order_id) {
    \$dates = get_post_meta(\$order_id, '_imported_dates', true);
    if (empty(\$dates) || !is_array(\$dates)) return;

    global \$wpdb;
    if (!empty(\$dates['created'])) {
        \$wpdb->update(
            \$wpdb->posts,
            ['post_date' => \$dates['created'], 'post_date_gmt' => \$dates['created_gmt']],
            ['ID' => \$order_id]
        );
    }
    if (!empty(\$dates['paid'])) {
        update_post_meta(\$order_id, '_date_paid', \$dates['paid']);
        if (!empty(\$dates['paid_gmt'])) update_post_meta(\$order_id, '_date_paid_gmt', \$dates['paid_gmt']);
    }
    if (!empty(\$dates['completed'])) {
        update_post_meta(\$order_id, '_date_completed', \$dates['completed']);
        if (!empty(\$dates['completed_gmt'])) update_post_meta(\$order_id, '_date_completed_gmt', \$dates['completed_gmt']);
    }
    delete_post_meta(\$order_id, '_imported_dates'); // очистка служебных данных
});
PHP
    );
    echo "✅ Хук для обновления дат добавлен в functions-import-dates.php\n";
}
