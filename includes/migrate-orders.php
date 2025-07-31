<?php
// === Настройки API ===
$old_url    = "https://oldsite.com/wp-json/wc/v3";
$old_key    = "ck_OLD";
$old_secret = "cs_OLD";

$new_url    = "https://newsite.com/wp-json/wc/v3";
$new_key    = "ck_NEW";
$new_secret = "cs_NEW";

$log_file = __DIR__ . "/migration.log";
$page_state_file = __DIR__ . "/last_page.txt";

// === Логирование ===
function log_message($message) {
    global $log_file;
    $line = "[" . date("Y-m-d H:i:s") . "] " . $message . "\n";
    echo $line;
    file_put_contents($log_file, $line, FILE_APPEND);
}

// === REST API запрос ===
function wc_api_request($url, $key, $secret, $method = 'GET', $data = null) {
    $ch = curl_init();
    $headers = ["Content-Type: application/json"];
    $opts = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => "$key:$secret",
        CURLOPT_HTTPHEADER => $headers
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_POSTFIELDS] = json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    curl_setopt_array($ch, $opts);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// === Перенос клиентов ===
function migrate_customers($old_url, $old_key, $old_secret, $new_url, $new_key, $new_secret) {
    log_message("🚀 Начинаем перенос клиентов...");
    $page = 1;
    while (true) {
        $customers = wc_api_request("$old_url/customers?per_page=100&page=$page", $old_key, $old_secret);
        if (empty($customers)) break;

        foreach ($customers as $cust) {
            $exists = wc_api_request("$new_url/customers?email=" . urlencode($cust['email']), $new_key, $new_secret);
            if (!empty($exists)) {
                log_message("⚠️ Клиент {$cust['email']} уже есть, пропускаем.");
                continue;
            }
            $data = [
                'email'      => $cust['email'],
                'first_name' => $cust['first_name'],
                'last_name'  => $cust['last_name'],
                'username'   => $cust['username'],
                'billing'    => $cust['billing'],
                'shipping'   => $cust['shipping']
            ];
            $result = wc_api_request("$new_url/customers", $new_key, $new_secret, 'POST', $data);
            if (isset($result['id'])) {
                log_message("✅ Клиент {$cust['email']} перенесён (ID {$result['id']})");
            } else {
                log_message("❌ Ошибка переноса клиента {$cust['email']}");
            }
            sleep(1); // пауза между запросами
        }
        $page++;
    }
    log_message("✅ Перенос клиентов завершён.");
}

// === Проверка товара (по ID и SKU) ===
function get_product_id_by_id_or_sku($product_id, $sku, $api_url, $key, $secret) {
    $product = wc_api_request("$api_url/products/$product_id", $key, $secret);
    if (!isset($product['id'])) {
        $products = wc_api_request("$api_url/products?sku=" . urlencode($sku), $key, $secret);
        if (!empty($products) && isset($products[0]['id'])) {
            return $products[0]['id'];
        }
        return null;
    }
    return $product['id'];
}

// === Подготовка заказа для переноса ===
function prepare_order_for_import($old_order, $new_api_url, $new_key, $new_secret) {
    $existing = wc_api_request("$new_api_url/orders?search=" . $old_order['number'], $new_key, $new_secret);
    if (!empty($existing)) {
        log_message("⚠️ Заказ {$old_order['number']} уже существует, пропускаем.");
        return null;
    }

    $new_line_items = [];
    foreach ($old_order['line_items'] as $item) {
        $new_product_id = get_product_id_by_id_or_sku(
            $item['product_id'],
            $item['sku'],
            str_replace('/orders', '', $new_api_url),
            $new_key,
            $new_secret
        );
        if (!$new_product_id) {
            log_message("❌ Товар {$item['name']} (SKU {$item['sku']}) не найден — пропускаем его.");
            continue;
        }
        $new_line_items[] = [
            'product_id'   => $new_product_id,
            'variation_id' => $item['variation_id'],
            'quantity'     => $item['quantity'],
            'subtotal'     => $item['subtotal'],
            'total'        => $item['total'],
            'meta_data'    => array_map(function($m) {
                return ['key' => $m['key'], 'value' => $m['value']];
            }, $item['meta_data'])
        ];
    }

    $new_shipping_lines = [];
    foreach ($old_order['shipping_lines'] as $ship) {
        $new_shipping_lines[] = [
            'method_id'    => $ship['method_id'],
            'method_title' => $ship['method_title'],
            'total'        => $ship['total'],
            'meta_data'    => array_map(function($m) {
                return ['key' => $m['key'], 'value' => $m['value']];
            }, $ship['meta_data'])
        ];
    }

    $new_meta = [];
    $added_keys = []; // массив для проверки дублей

    foreach ($order['meta_data'] as $meta) {
        $key = ($meta['key'] === 'billing_dontcallme' || $meta['key'] === '_billing_dontcallme')
            ? 'dontcallme'
            : $meta['key'];

        // ✅ Если этот ключ уже добавлен — пропускаем
        if (in_array($key, $added_keys)) {
            continue;
        }

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
        'meta_data'            => $new_meta
    ];
}

// === Перенос заказов ===
function migrate_orders($old_url, $old_key, $old_secret, $new_url, $new_key, $new_secret, $page_state_file) {
    $page = file_exists($page_state_file) ? (int)file_get_contents($page_state_file) : 1;
    $total = 0;
    log_message("🚀 Начинаем перенос заказов, стартуем с страницы $page...");

    while (true) {
        $orders = wc_api_request("$old_url/orders?per_page=100&page=$page", $old_key, $old_secret);
        if (empty($orders)) break;

        foreach ($orders as $order) {
            $new_order = prepare_order_for_import($order, "$new_url/orders", $new_key, $new_secret);
            if (!$new_order) continue;

            $result = wc_api_request("$new_url/orders", $new_key, $new_secret, 'POST', $new_order);

            if (isset($result['id'])) {
                log_message("✅ Заказ {$order['number']} перенесён → Новый ID {$result['id']}");
                $total++;
            } else {
                log_message("❌ Ошибка переноса заказа {$order['number']}");
            }

            sleep(1); // пауза
        }

        file_put_contents($page_state_file, $page + 1);
        $page++;
    }

    log_message("✅ Перенос заказов завершён. Всего перенесено: $total");
}

// === Запуск ===
migrate_customers($old_url, $old_key, $old_secret, $new_url, $new_key, $new_secret);
migrate_orders($old_url, $old_key, $old_secret, $new_url, $new_key, $new_secret, $page_state_file);
