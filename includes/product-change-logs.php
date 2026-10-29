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

/* ======================================================
 * ВЫВОД ЛОГОВ В АДМИНКЕ
 * ====================================================== */

add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'Логи товаров',
        'Логи товаров',
        'manage_woocommerce',
        'wc-product-change-logs',
        'wc_product_change_logs_admin_page'
    );
});


function wc_get_product_log_files() {

    $log_dir = WP_CONTENT_DIR . '/product-logs';

    if (!is_dir($log_dir)) {
        return [];
    }

    $files = glob($log_dir . '/product-change-log-*.txt');

    if (!$files) {
        return [];
    }

    rsort($files, SORT_NATURAL);

    return $files;
}


/**
 * Поиск логов по названию товара
 * во всех файлах.
 */
function wc_get_product_logs_by_name($search) {

    $search = trim((string) $search);

    if ($search === '') {
        return [];
    }

    $result = [];

    foreach (wc_get_product_log_files() as $file) {

        $lines = file(
            $file,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        if (!$lines) {
            continue;
        }

        $lines = array_reverse($lines);

        foreach ($lines as $line) {

            /*
             * Ищем название без учета регистра.
             */
            if (mb_stripos($line, $search) !== false) {
                $result[] = $line;
            }
        }
    }

    return $result;
}


function wc_product_change_logs_admin_page() {

    if (!current_user_can('manage_woocommerce')) {
        wp_die('Недостаточно прав.');
    }

    $log_dir = WP_CONTENT_DIR . '/product-logs';

    $date = isset($_GET['log_date'])
        ? sanitize_text_field(wp_unslash($_GET['log_date']))
        : wp_date('Y-m-d');

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $date = wp_date('Y-m-d');
    }

    $product_search = isset($_GET['product_search'])
        ? sanitize_text_field(wp_unslash($_GET['product_search']))
        : '';

    ?>
    <div class="wrap">

        <h1>Логи изменений товаров</h1>

        <form
            method="get"
            style="display:flex; align-items:flex-end; gap:20px; margin-bottom:20px;"
        >

            <input
                type="hidden"
                name="page"
                value="wc-product-change-logs"
            >

            <div>
                <label
                    for="wc-log-date"
                    style="display:block; margin-bottom:5px;"
                >
                    Дата
                </label>

                <input
                    id="wc-log-date"
                    type="date"
                    name="log_date"
                    value="<?php echo esc_attr($date); ?>"
                >
            </div>

            <div>
                <label
                    for="wc-log-product-search"
                    style="display:block; margin-bottom:5px;"
                >
                    Товар
                </label>

                <input
                    id="wc-log-product-search"
                    type="search"
                    name="product_search"
                    value="<?php echo esc_attr($product_search); ?>"
                    placeholder="Например, Монстера"
                    style="width:320px;"
                >
            </div>

            <div>
                <?php
                submit_button(
                    'Показать',
                    'secondary',
                    '',
                    false
                );
                ?>
            </div>

            <?php if ($product_search !== '') : ?>

                <div>
                    <a
                        href="<?php echo esc_url(
                            admin_url('admin.php?page=wc-product-change-logs')
                        ); ?>"
                        class="button"
                    >
                        Сбросить поиск
                    </a>
                </div>

            <?php endif; ?>

        </form>


        <?php

        if ($product_search !== '') {

            /*
             * Есть поиск по товару —
             * ищем по всем дням.
             */
            $lines = wc_get_product_logs_by_name($product_search);

            echo '<p>';
            echo 'Поиск по товару: <strong>' . esc_html($product_search) . '</strong>';
            echo ' — за все дни';
            echo '</p>';

        } else {

            /*
             * Нет поиска —
             * показываем выбранный день.
             */
            $log_file = $log_dir . '/product-change-log-' . $date . '.txt';

            if (!file_exists($log_file)) {
                echo '<p>За эту дату логов нет.</p>';
                echo '</div>';
                return;
            }

            $lines = file(
                $log_file,
                FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
            );

            if ($lines) {
                $lines = array_reverse($lines);
            }

            echo '<p>';
            echo 'Логи за <strong>' . esc_html($date) . '</strong>';
            echo '</p>';
        }


        if (!$lines) {

            echo '<p>';

            if ($product_search !== '') {
                echo 'По этому названию ничего не найдено.';
            } else {
                echo 'Лог пуст.';
            }

            echo '</p>';
            echo '</div>';

            return;
        }

        ?>


        <table class="widefat striped">

            <thead>
                <tr>
                    <th style="width:170px;">
                        Время
                    </th>

                    <th>
                        Запись
                    </th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($lines as $line) : ?>

                <?php

                $time = '';
                $text = $line;

                if (
                    preg_match(
                        '/^\[([^\]]+)\]\s*(.*)$/',
                        $line,
                        $matches
                    )
                ) {
                    $time = $matches[1];
                    $text = $matches[2];
                }

                ?>

                <tr>

                    <td>
                        <?php echo esc_html($time); ?>
                    </td>

                    <td>
                        <?php echo esc_html($text); ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>
    <?php
}

/* ======================================================
 * ИСТОРИЯ ИЗМЕНЕНИЙ НА СТРАНИЦЕ РЕДАКТИРОВАНИЯ ТОВАРА
 * ====================================================== */

add_action('add_meta_boxes_product', 'wc_product_change_logs_add_metabox');

function wc_product_change_logs_add_metabox() {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }

    add_meta_box(
        'wc-product-change-history',
        'История изменений товара',
        'wc_product_change_logs_render_metabox',
        'product',
        'normal',
        'default'
    );
}

/**
 * Читает логи по ID товара за все даты, начиная с новых.
 * ID извлекается из служебной части записи, а не из названия товара.
 * Возвращает не более $limit + 1 записей для постраничного вывода.
 */
function wc_get_product_logs_by_id($product_id, $offset = 0, $limit = 25) {
    $product_id = absint($product_id);
    $offset     = max(0, (int) $offset);
    $limit      = max(1, min(100, (int) $limit));

    if (!$product_id) {
        return [];
    }

    // Форматы, которые записываются в этом файле:
    // "Обновление товара #123 (...)" и
    // "Движение остатков (Заказ #456): товар #123 (...)".
    $pattern = '~^\[[^\]]+\]\s*(?:'
        . '(?:Создание|Обновление|Удаление)\s+товара'
        . '|Движение остатков\s*\(Заказ\s*#\d+\):\s*товар'
        . ')\s*#' . $product_id . '\s*\(~u';

    $result  = [];
    $skipped = 0;

    foreach (wc_get_product_log_files() as $file) {
        if (!is_readable($file)) {
            continue;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            continue;
        }

        // Внутри каждого файла записи идут от старых к новым.
        for ($i = count($lines) - 1; $i >= 0; $i--) {
            if (!preg_match($pattern, $lines[$i])) {
                continue;
            }

            if ($skipped < $offset) {
                $skipped++;
                continue;
            }

            $result[] = $lines[$i];

            if (count($result) > $limit) {
                break 2;
            }
        }
    }

    return $result;
}

function wc_product_change_logs_render_metabox($post) {
    if (!current_user_can('manage_woocommerce') || !current_user_can('edit_post', $post->ID)) {
        return;
    }

    $per_page = 25;
    $page     = isset($_GET['wc_history_page'])
        ? max(1, absint(wp_unslash($_GET['wc_history_page'])))
        : 1;

    $lines    = wc_get_product_logs_by_id($post->ID, ($page - 1) * $per_page, $per_page);
    $has_next = count($lines) > $per_page;

    if ($has_next) {
        array_pop($lines);
    }

    if (!$lines) {
        echo '<p>Записей об изменениях этого товара нет.</p>';
        return;
    }

    echo '<p>Записи по ID товара за все даты, включая изменения названия и движения остатков по заказам.</p>';
    echo '<table class="widefat striped">';
    echo '<thead><tr><th style="width: 160px;">Дата и время</th><th>Запись</th></tr></thead>';
    echo '<tbody>';

    foreach ($lines as $line) {
        $time = '';
        $text = $line;

        if (preg_match('/^\[([^\]]+)\]\s*(.*)$/u', $line, $matches)) {
            $time = $matches[1];
            $text = $matches[2];
        }

        echo '<tr>';
        echo '<td>' . esc_html($time) . '</td>';
        echo '<td>' . esc_html($text) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';

    if ($page > 1 || $has_next) {
        $edit_url = get_edit_post_link($post->ID, 'raw');

        echo '<p style="display: flex; align-items: center; gap: 12px; margin-top: 12px;">';

        if ($page > 1) {
            $prev_url = add_query_arg('wc_history_page', $page - 1, $edit_url);
            echo '<a class="button" href="' . esc_url($prev_url . '#wc-product-change-history') . '">← Новые записи</a>';
        }

        echo '<span>Страница ' . esc_html($page) . '</span>';

        if ($has_next) {
            $next_url = add_query_arg('wc_history_page', $page + 1, $edit_url);
            echo '<a class="button" href="' . esc_url($next_url . '#wc-product-change-history') . '">Старые записи →</a>';
        }

        echo '</p>';
    }
}
