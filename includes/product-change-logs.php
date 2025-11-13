<?php
/**
 * ЛОГИРОВАНИЕ ИЗМЕНЕНИЙ ТОВАРОВ WOOCOOMMERCE
 * ----------------------------------------------------------
 * Отслеживает: создание, обновление, удаление товаров.
 * Логирует: название, цены, остатки, категории, метки, контекст (вручную / импорт / заказ).
 * Подавляет дубли после заказов.
 * ----------------------------------------------------------
 * Новое:
 *  - Время через wp_date()
 *  - Ежедневная ротация логов
 *  - Включатель через константу WC_PRODUCT_CHANGE_LOG_ENABLED
 */

if (!defined('ABSPATH')) exit;

// --- включатель логирования ---
if (!defined('WC_PRODUCT_CHANGE_LOG_ENABLED')) {
    define('WC_PRODUCT_CHANGE_LOG_ENABLED', true);
}
if (!WC_PRODUCT_CHANGE_LOG_ENABLED) {
    return; // мгновенно выходим, если выключено
}

// --- глобальные переменные ---
$GLOBALS['wc_product_snapshots']   = [];
$GLOBALS['wc_change_context']      = 'manual';
$GLOBALS['wc_context_per_id']      = [];
$GLOBALS['wc_suppress_after_save'] = [];

// --- инициализация ---
add_action('init', function () {
    if (!class_exists('WooCommerce')) return;

    // Снимки и сравнение
    add_action('woocommerce_before_product_object_save', 'wc_log_snapshot_before_save', 10, 2);
    add_action('woocommerce_after_product_object_save',  'wc_log_compare_after_save',   10, 2);

    // // Отслеживаем ручное изменение количества/статуса через мета
    // add_filter('pre_update_post_meta', 'wc_log_capture_old_stock_meta', 10, 4);
    // add_action('updated_post_meta',    'wc_log_maybe_log_stock_meta_change', 10, 4);
    // add_action('added_post_meta',      'wc_log_maybe_log_stock_meta_added', 10, 4);

    // Удаление
    add_action('before_delete_post', 'log_wc_product_delete');

    // Импорт (CSV)
    add_action('woocommerce_product_import_pre_insert_product_object', function($product){
        if ($product && $product->get_id()) {
            $GLOBALS['wc_context_per_id'][$product->get_id()] = 'import';
        }
        $GLOBALS['wc_change_context'] = 'import';
        return $product;
    }, 5, 1);

    add_action('woocommerce_product_import_inserted_product_object', function($product){
        if ($product && $product->get_id()) {
            $GLOBALS['wc_context_per_id'][$product->get_id()] = 'import';
        }
    }, 5, 1);

    // WP All Import
    add_action('pmxi_saved_post', function($post_id){
        if (get_post_type($post_id) === 'product') {
            $GLOBALS['wc_context_per_id'][$post_id] = 'import';
            $GLOBALS['wc_change_context'] = 'import';
        }
    }, 10, 1);

    // Заказы
    add_action('woocommerce_reduce_order_stock', function($order){
        $GLOBALS['wc_change_context'] = 'order';
        wc_log_order_stock_movement($order, 'reduce');
        $GLOBALS['wc_change_context'] = 'manual';
    }, 10, 1);

    add_action('woocommerce_restore_order_stock', function($order){
        $GLOBALS['wc_change_context'] = 'order';
        wc_log_order_stock_movement($order, 'restore');
        $GLOBALS['wc_change_context'] = 'manual';
    }, 10, 1);

    // Очистка контекста
    add_action('shutdown', function(){
        $GLOBALS['wc_change_context']      = 'manual';
        $GLOBALS['wc_context_per_id']      = [];
        $GLOBALS['wc_suppress_after_save'] = [];
    });
});

add_action('post_updated', 'wc_log_title_change', 10, 3);

function wc_log_title_change($post_ID, $post_after, $post_before) {
    // только для товаров
    if ($post_after->post_type !== 'product') return;

    $old = (string) $post_before->post_title;
    $new = (string) $post_after->post_title;
    if ($old === $new) return;

    // контекст и пользователь
    $ctx      = wc_detect_context_for_product($post_ID);
    $user     = wp_get_current_user();
    $username = ($user && $user->exists()) ? $user->user_login : 'system';

    $log_entry = sprintf(
        "[%s] Обновление товара #%d (%s) пользователем %s | Способ: %s | Изменения: Название: \"%s\" → \"%s\"\n",
        wp_date('Y-m-d H:i:s'),
        $post_ID,
        $new !== '' ? $new : '(без названия)',
        $username,
        wc_context_label($ctx),
        wc_stringify($old),
        wc_stringify($new)
    );
    wc_write_product_log($log_entry);
}


// ======================================================
//  ЛОГИКА СОХРАНЕНИЙ
// ======================================================

function wc_log_snapshot_before_save($product) {
    if ( ! $product || ! $product->get_id() ) {
        return;
    }

    $id = $product->get_id();

    // Берём "старое" состояние ИЗ БД, а не из объекта продукта
    $post = get_post($id);

    // Название до сохранения
    $old_name = $post ? $post->post_title : '';

    // Старые мета-значения
    $old_regular_price = get_post_meta($id, '_regular_price', true);
    $old_sale_price    = get_post_meta($id, '_sale_price', true);
    $old_price         = get_post_meta($id, '_price', true);
    $old_stock_qty     = get_post_meta($id, '_stock', true);
    $old_stock_status  = get_post_meta($id, '_stock_status', true);

    // Старые категории и метки по термам
    $old_cat_names = [];
    $terms = get_the_terms($id, 'product_cat');
    if ( ! is_wp_error($terms) && ! empty($terms) ) {
        foreach ($terms as $t) {
            $old_cat_names[] = $t->name;
        }
        sort($old_cat_names, SORT_NATURAL | SORT_FLAG_CASE);
    }

    $old_tag_names = [];
    $terms = get_the_terms($id, 'product_tag');
    if ( ! is_wp_error($terms) && ! empty($terms) ) {
        foreach ($terms as $t) {
            $old_tag_names[] = $t->name;
        }
        sort($old_tag_names, SORT_NATURAL | SORT_FLAG_CASE);
    }

    $GLOBALS['wc_product_snapshots'][$id] = [
        'name'           => $old_name,
        'regular_price'  => $old_regular_price,
        'sale_price'     => $old_sale_price,
        'price'          => $old_price,
        'stock_status'   => $old_stock_status,
        'stock_quantity' => $old_stock_qty,
        'categories'     => $old_cat_names,
        'tags'           => $old_tag_names,
    ];
}


function wc_log_compare_after_save($product) {
    if (!$product || !$product->get_id()) return;

    $id = $product->get_id();
    $before = $GLOBALS['wc_product_snapshots'][$id] ?? null;
    $after  = wc_collect_product_state($product);
    unset($GLOBALS['wc_product_snapshots'][$id]);

    $user = wp_get_current_user();
    $username = ($user && $user->exists()) ? $user->user_login : 'system';
    $action = $before ? 'Обновление' : 'Создание';

    $changes = wc_diff_product_state($before, $after);

    // Подавление дублей складских изменений
    $context = wc_detect_context_for_product($id);
    if ($context === 'order' || !empty($GLOBALS['wc_suppress_after_save'][$id])) {
        $changes = wc_filter_out_stock_changes($changes);
    }
    if ($action === 'Обновление' && empty($changes)) return;

    $lines = [];
    foreach ($changes as $label => $pair) {
        [$old, $new] = $pair;
        $lines[] = sprintf('%s: "%s" → "%s"', $label, wc_stringify($old), wc_stringify($new));
    }
    $summary = $lines ? ' | Изменения: ' . implode('; ', $lines) : '';

    $log_entry = sprintf(
        "[%s] %s товара #%d (%s) пользователем %s | Способ: %s%s\n",
        wp_date("Y-m-d H:i:s"),
        $action,
        $id,
        $after['name'],
        $username,
        wc_context_label($context),
        $summary
    );

    wc_write_product_log($log_entry);
}

// ======================================================
//  УДАЛЕНИЕ
// ======================================================

function log_wc_product_delete($post_id) {
    if (get_post_type($post_id) !== 'product') return;
    $user = wp_get_current_user();
    $username = ($user && $user->exists()) ? $user->user_login : 'system';
    $title = get_the_title($post_id) ?: '(без названия)';
    $context = wc_detect_context_for_product($post_id);

    $log_entry = sprintf(
        "[%s] Удаление товара #%d (%s) пользователем %s | Способ: %s\n",
        wp_date("Y-m-d H:i:s"),
        $post_id,
        $title,
        $username,
        wc_context_label($context)
    );

    wc_write_product_log($log_entry);
}

// ======================================================
//  ЗАКАЗЫ
// ======================================================

function wc_log_order_stock_movement($order, $mode) {
    if (!$order instanceof WC_Order) return;

    $order_id = $order->get_id();
    $user = wp_get_current_user();
    $username = ($user && $user->exists()) ? $user->user_login : 'system';

    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if (!$product) continue;

        $pid = $product->get_id();
        $name = $product->get_name();
        $qty  = (float) $item->get_quantity();
        $delta = ($mode === 'restore') ? +$qty : -$qty;

        $GLOBALS['wc_suppress_after_save'][$pid] = true;

        $log_entry = sprintf(
            "[%s] Движение остатков (Заказ #%d): товар #%d (%s) %+g шт. пользователем %s | Способ: %s\n",
            wp_date("Y-m-d H:i:s"),
            $order_id,
            $pid,
            $name,
            $delta,
            $username,
            wc_context_label('order')
        );
        wc_write_product_log($log_entry);
    }
}

// ======================================================
//  ВСПОМОГАТЕЛЬНЫЕ
// ======================================================

function wc_collect_product_state(WC_Product $product) {
    static $term_cache = [];

    $get_terms_cached = function($ids) use (&$term_cache) {
        $key = implode(',', (array)$ids);
        if (isset($term_cache[$key])) return $term_cache[$key];
        $names = [];
        foreach ((array)$ids as $tid) {
            $term = get_term($tid);
            if ($term && !is_wp_error($term)) $names[] = $term->name;
        }
        sort($names, SORT_NATURAL | SORT_FLAG_CASE);
        return $term_cache[$key] = $names;
    };

    return [
        'name'           => $product->get_name(),
        'regular_price'  => $product->get_regular_price(),
        'sale_price'     => $product->get_sale_price(),
        'price'          => $product->get_price(),
        'stock_status'   => $product->get_stock_status(),
        'stock_quantity' => $product->get_stock_quantity(),
        'categories'     => $get_terms_cached($product->get_category_ids()),
        'tags'           => $get_terms_cached($product->get_tag_ids()),
    ];
}

function wc_diff_product_state($before, $after) {
    $diff = [];
    if (!$before) {
        foreach ($after as $k => $v) {
            if (wc_has_meaningful_value($v)) $diff[wc_label($k)] = ['', $v];
        }
        return $diff;
    }
    foreach ($after as $k => $new) {
        $old = $before[$k] ?? null;
        if (is_array($new) || is_array($old)) {
            $oldStr = implode(', ', (array)$old);
            $newStr = implode(', ', (array)$new);
            if ($oldStr !== $newStr) $diff[wc_label($k)] = [$oldStr, $newStr];
        } elseif ((string)$old !== (string)$new) {
            $diff[wc_label($k)] = [$old, $new];
        }
    }
    return $diff;
}

function wc_filter_out_stock_changes($changes) {
    $stock = ['Статус наличия', 'Остаток на складе'];
    foreach ($stock as $s) unset($changes[$s]);
    return $changes;
}

function wc_label($key) {
    return [
        'name'           => 'Название',
        'regular_price'  => 'Базовая цена (regular)',
        'sale_price'     => 'Цена со скидкой (sale)',
        'price'          => 'Текущая цена (price)',
        'stock_status'   => 'Статус наличия',
        'stock_quantity' => 'Остаток на складе',
        'categories'     => 'Категории',
        'tags'           => 'Метки',
    ][$key] ?? $key;
}

function wc_stringify($v) {
    if (is_array($v)) return implode(', ', $v);
    if ($v === null || $v === '') return '—';
    return (string)$v;
}

function wc_has_meaningful_value($v) {
    return is_array($v) ? !empty($v) : !($v === null || $v === '');
}

function wc_detect_context_for_product($id) {
    if (!empty($GLOBALS['wc_context_per_id'][$id])) return $GLOBALS['wc_context_per_id'][$id];
    return $GLOBALS['wc_change_context'] ?? 'manual';
}

function wc_context_label($ctx) {
    return [
        'manual' => 'Вручную',
        'import' => 'Импорт',
        'order'  => 'Заказ',
    ][$ctx] ?? 'Вручную';
}

// Хранилище старых значений для _stock/_stock_status
// $GLOBALS['wc_prev_stock_meta'] = []; // [post_id => ['_stock' => old, '_stock_status' => old]]

// /**
//  * Сохраняем "старое" значение мета перед обновлением (чтобы потом сравнить).
//  */
// function wc_log_capture_old_stock_meta($check, $object_id, $meta_key, $meta_value) {
//     // интересуют товары и вариации
//     $ptype = get_post_type($object_id);
//     if ($ptype !== 'product' && $ptype !== 'product_variation') return $check;

//     if ($meta_key === '_stock' || $meta_key === '_stock_status') {
//         if (!isset($GLOBALS['wc_prev_stock_meta'][$object_id])) {
//             $GLOBALS['wc_prev_stock_meta'][$object_id] = [];
//         }
//         // читаем прежнее значение до апдейта
//         $old = get_post_meta($object_id, $meta_key, true);
//         $GLOBALS['wc_prev_stock_meta'][$object_id][$meta_key] = $old;
//     }
//     return $check; // не блокируем обновление
// }

// /**
//  * После обновления мета сравниваем и при необходимости пишем в лог.
//  */
// function wc_log_maybe_log_stock_meta_change($meta_id, $object_id, $meta_key, $meta_value) {
//     // интересуют только эти ключи и типы
//     $ptype = get_post_type($object_id);
//     if ($ptype !== 'product' && $ptype !== 'product_variation') return;
//     if ($meta_key !== '_stock' && $meta_key !== '_stock_status') return;

//     // подавляем записи, если это изменения из заказа (мы уже логируем wc_reduce/restore_order_stock)
//     $ctx = wc_detect_context_for_product($object_id);
//     if ($ctx === 'order' || !empty($GLOBALS['wc_suppress_after_save'][$object_id])) {
//         return;
//     }

//     $old = $GLOBALS['wc_prev_stock_meta'][$object_id][$meta_key] ?? null;
//     $new = get_post_meta($object_id, $meta_key, true);

//     // если реально не поменялось — выходим
//     if ((string)$old === (string)$new) return;

//     // Достаём продукт (для вариации — её объект)
//     $product = wc_get_product($object_id);
//     if (!$product) return;

//     // Имя/ID и пользователь
//     $name = $product->get_name();
//     $user = wp_get_current_user();
//     $username = ($user && $user->exists()) ? $user->user_login : 'system';

//     // Человечные лейблы
//     $label = ($meta_key === '_stock') ? 'Остаток на складе' : 'Статус наличия';

//     $log_entry = sprintf(
//         "[%s] Обновление товара #%d (%s) пользователем %s | Способ: %s | Изменения: %s: \"%s\" → \"%s\"\n",
//         wp_date("Y-m-d H:i:s"),
//         $object_id,
//         $name ?: '(без названия)',
//         $username,
//         wc_context_label($ctx),
//         $label,
//         wc_stringify($old),
//         wc_stringify($new)
//     );
//     wc_write_product_log($log_entry);

//     // чистка старых значений
//     unset($GLOBALS['wc_prev_stock_meta'][$object_id][$meta_key]);
//     if (empty($GLOBALS['wc_prev_stock_meta'][$object_id])) {
//         unset($GLOBALS['wc_prev_stock_meta'][$object_id]);
//     }
// }

// // Ловим кейс "удалили → добавили" (_stock/_stock_status впервые появляется)
// function wc_log_maybe_log_stock_meta_added($meta_id, $object_id, $meta_key, $meta_value) {
//     wc_log_handle_stock_meta_event($object_id, $meta_key, 'add');
// }

// // Общий обработчик для added/updated (чтобы не дублировать код)
// function wc_log_handle_stock_meta_event($object_id, $meta_key, $mode) {
//     $ptype = get_post_type($object_id);
//     if ($ptype !== 'product' && $ptype !== 'product_variation') return;
//     if ($meta_key !== '_stock' && $meta_key !== '_stock_status') return;

//     // подавляем, если это движение из заказа
//     $ctx = wc_detect_context_for_product($object_id);
//     if ($ctx === 'order' || !empty($GLOBALS['wc_suppress_after_save'][$object_id])) return;

//     $new = get_post_meta($object_id, $meta_key, true);

//     // 1) старое из pre_update кеша
//     $old = $GLOBALS['wc_prev_stock_meta'][$object_id][$meta_key] ?? null;

//     // 2) fallback — снапшот из before_save
//     if ($old === null) {
//         $snapshot = $GLOBALS['wc_product_snapshots'][$object_id] ?? null;
//         if ($snapshot) {
//             if ($meta_key === '_stock')        $old = $snapshot['stock_quantity'] ?? null;
//             if ($meta_key === '_stock_status') $old = $snapshot['stock_status']   ?? null;
//         }
//     }

//     // 3) если это именно add, считаем, что "раньше не было"
//     if ($old === null && $mode === 'add') $old = '';

//     if ((string)$old === (string)$new) return;

//     $product  = wc_get_product($object_id);
//     $name     = $product ? $product->get_name() : '(без названия)';
//     $user     = wp_get_current_user();
//     $username = ($user && $user->exists()) ? $user->user_login : 'system';
//     $label    = ($meta_key === '_stock') ? 'Остаток на складе' : 'Статус наличия';

//     $log_entry = sprintf(
//         "[%s] Обновление товара #%d (%s) пользователем %s | Способ: %s | Изменения: %s: \"%s\" → \"%s\"\n",
//         wp_date("Y-m-d H:i:s"),
//         $object_id,
//         $name,
//         $username,
//         wc_context_label($ctx),
//         $label,
//         wc_stringify($old),
//         wc_stringify($new)
//     );
//     wc_write_product_log($log_entry);

//     unset($GLOBALS['wc_prev_stock_meta'][$object_id][$meta_key]);
//     if (empty($GLOBALS['wc_prev_stock_meta'][$object_id])) unset($GLOBALS['wc_prev_stock_meta'][$object_id]);
// }


/**
 * Запись лога с ротацией по дате
 */
function wc_write_product_log($text) {
    $log_dir  = WP_CONTENT_DIR . '/product-logs';
    $log_file = $log_dir . '/product-change-log-' . wp_date('Y-m-d') . '.txt';

    if ( ! file_exists($log_dir) ) {
        if ( ! function_exists('wp_mkdir_p') ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( ! wp_mkdir_p($log_dir) ) {
            error_log('[WC Product Log] Не удалось создать каталог: ' . $log_dir);
            // фолбэк в uploads
            $upload = wp_get_upload_dir();
            if (!empty($upload['basedir'])) {
                $log_dir  = trailingslashit($upload['basedir']) . 'product-logs';
                $log_file = $log_dir . '/product-change-log-' . wp_date('Y-m-d') . '.txt';
                if ( ! file_exists($log_dir) && ! wp_mkdir_p($log_dir) ) {
                    error_log('[WC Product Log] Не удалось создать uploads каталог: ' . $log_dir);
                    if (function_exists('wc_get_logger')) {
                        $logger = wc_get_logger();
                        $logger->info($text, ['source' => 'product-change-log']);
                        return true;
                    }
                    return false;
                }
            }
        }
    }

    $htaccess = $log_dir . '/.htaccess';
    if ( ! file_exists($htaccess) ) {
        @file_put_contents($htaccess, "<FilesMatch \".*\">\n  Require all denied\n</FilesMatch>\n");
    }

    $ok = @file_put_contents($log_file, $text, FILE_APPEND | LOCK_EX);
    if ($ok === false) {
        error_log('[WC Product Log] Не удалось записать файл: ' . $log_file);
        if (function_exists('wc_get_logger')) {
            $logger = wc_get_logger();
            $logger->info($text, ['source' => 'product-change-log']);
            return true;
        }
        return false;
    }
    return true;
}

