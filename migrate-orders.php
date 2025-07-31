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

// === Проверка товара (по ID и SKU) ===
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
    return (strpos($date, 'T') === false) 
        ? date('Y-m-d\TH:i:s', strtotime($date)) 
        : $date;
}

function normalize_date_gmt($date) {
    if (!$date) return null;
    return gmdate('Y-m-d\TH:i:s', strtotime($date));
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

    // Подготовка meta_data (с удалением дублей)
    $new_meta = [];
    $added_keys = [];
    foreach ($old_order['meta_data'] as $meta) {
        $key = ($meta['key'] === 'billing_dontcallme' || $meta['key'] === '_billing_dontcallme')
            ? 'dontcallme'
            : $meta['key'];
        $key = ($meta['key'] === 'additional_peresadka')
            ? '_plnt_comment'
            : $meta['key'];
        if (in_array($key, $added_keys)) continue;
        $new_meta[] = ['key' => $key, 'value' => $meta['value']];
        $added_keys[] = $key;
    }

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
        // ✅ Перенос всех дат
        // 'date_created'         => normalize_date($old_order['date_created']),
        // 'date_created_gmt'     => normalize_date_gmt($old_order['date_created']),
        // 'date_paid'            => isset($old_order['date_paid']) ? normalize_date($old_order['date_paid']) : null,
        // 'date_paid_gmt'        => isset($old_order['date_paid']) ? normalize_date_gmt($old_order['date_paid']) : null,
        // 'date_completed'       => isset($old_order['date_completed']) ? normalize_date($old_order['date_completed']) : null,
        // 'date_completed_gmt'   => isset($old_order['date_completed']) ? normalize_date_gmt($old_order['date_completed']) : null
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

// === 4. Обновляем даты (PUT) ===
$update_dates = [
    'date_created'     => normalize_date($old_order['date_created']),
    'date_created_gmt' => normalize_date_gmt($old_order['date_created']),
    'date_paid'        => isset($old_order['date_paid']) ? normalize_date($old_order['date_paid']) : null,
    'date_paid_gmt'    => isset($old_order['date_paid']) ? normalize_date_gmt($old_order['date_paid']) : null,
    'date_completed'   => isset($old_order['date_completed']) ? normalize_date($old_order['date_completed']) : null,
    'date_completed_gmt'=> isset($old_order['date_completed']) ? normalize_date_gmt($old_order['date_completed']) : null
];

wc_api_request("$new_url/orders/$new_order_id", $new_key, $new_secret, 'PUT', $update_dates);
echo "✅ Даты заказа {$old_order['number']} обновлены.\n";
