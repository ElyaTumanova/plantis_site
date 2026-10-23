<?php
/**
 * Plantis — административная страница «Сигналы».
 * Таблица комнатных растений и сокращённая форма редактирования «Свойства».
 * Аналитические значения берутся из существующего product-expo.php.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/signals-stats.php';
require_once __DIR__ . '/signals-cron.php';

const PLNT_SIGNALS_CATEGORY_SLUG = 'komnatnye-rasteniya';
const PLNT_SIGNALS_TABLE_COLUMNS = 9;

/** Отдельный пункт главного меню непосредственно после «Товары». */
add_action( 'admin_menu', function () {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    $hook = add_menu_page(
        'Сигналы',
        'Сигналы',
        'edit_products',
        'plantis-signals',
        'plnt_signals_render_page',
        'dashicons-chart-line',
        57
    );

    add_action( 'admin_enqueue_scripts', function ( $current_hook ) use ( $hook ) {
        if ( $current_hook !== $hook ) {
            return;
        }

        foreach ( array( 'css', 'js' ) as $extension ) {
            $relative = '/includes/signals/signals.' . $extension;
            $path     = get_template_directory() . $relative;
            $url      = get_template_directory_uri() . $relative;
            $version  = file_exists( $path ) ? (string) filemtime( $path ) : null;

            if ( 'css' === $extension ) {
                wp_enqueue_style( 'plnt-signals-admin', $url, array(), $version );
            } else {
                wp_enqueue_script( 'plnt-signals-admin', $url, array(), $version, true );
                wp_localize_script( 'plnt-signals-admin', 'plntSignalsAdmin', array(
                    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                    'nonce'   => wp_create_nonce( 'plnt_signals_product' ),
                    'columns' => PLNT_SIGNALS_TABLE_COLUMNS,
                ) );
            }
        }
    } );
}, 99 );

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', function ( $menu_order ) {
    if ( ! is_array( $menu_order ) || ! in_array( 'plantis-signals', $menu_order, true ) ) {
        return $menu_order;
    }

    $menu_order = array_values( array_diff( $menu_order, array( 'plantis-signals' ) ) );
    $position   = array_search( 'edit.php?post_type=product', $menu_order, true );

    if ( false === $position ) {
        $menu_order[] = 'plantis-signals';
        return $menu_order;
    }

    array_splice( $menu_order, $position + 1, 0, array( 'plantis-signals' ) );
    return $menu_order;
}, 999 );

/** ID корневой категории и ID всех её дочерних категорий. */
function plnt_signals_category_ids() {
    $category = get_term_by( 'slug', PLNT_SIGNALS_CATEGORY_SLUG, 'product_cat' );
    if ( ! $category || is_wp_error( $category ) ) {
        return array();
    }

    $children = get_term_children( (int) $category->term_id, 'product_cat' );
    if ( is_wp_error( $children ) ) {
        $children = array();
    }

    return array_merge( array( (int) $category->term_id ), array_map( 'intval', $children ) );
}

function plnt_signals_is_plant( $product_id ) {
    $category_ids = plnt_signals_category_ids();
    return $category_ids && has_term( $category_ids, 'product_cat', $product_id );
}

/** Числовое значение из product-expo; нуль и отсутствие данных различаются. */
function plnt_signals_expo_value( $product_id, $meta_key ) {
    $raw = get_post_meta( $product_id, $meta_key, true );
    return '' === $raw || null === $raw
        ? '—'
        : number_format_i18n( max( 0, (int) $raw ) );
}

/** Строка товара. Обновляется AJAX после сохранения свойств. */
function plnt_signals_product_row( WC_Product $product, $stats = null ) {
    $id         = $product->get_id();
    $sku        = $product->get_sku();
    $regular    = $product->get_regular_price( 'edit' );
    $sale       = $product->get_sale_price( 'edit' );
    $stock      = $product->get_stock_quantity();
    $manage     = $product->managing_stock();
    $edit_url   = get_edit_post_link( $id );
    if ( null === $stats ) {
        $all_stats = plnt_signals_get_stats_for_ids( array( $id ) );
        $stats = $all_stats[ $id ] ?? null;
    }
    $last_sale = $stats && $stats->last_sale_date_gmt
        ? get_date_from_gmt( $stats->last_sale_date_gmt, 'd.m.Y' ) : '—';
    $avg_price = $stats && null !== $stats->avg_sale_price_6m
        ? wc_price( $stats->avg_sale_price_6m ) : '—';

    ob_start();
    ?>
    <tr class="plnt-signals-row" data-product-id="<?php echo esc_attr( $id ); ?>">
        <td class="plnt-signals-cell-product">
            <div class="plnt-signals-product">
                <a class="plnt-signals-photo" href="<?php echo esc_url( $edit_url ); ?>" aria-label="Открыть карточку товара">
                    <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
                </a>
                <div class="plnt-signals-details">
                    <a class="plnt-signals-product-title" href="<?php echo esc_url( $edit_url ); ?>">
                        <?php echo esc_html( $product->get_name() ); ?>
                    </a>
                    <div class="plnt-signals-sku">Артикул: <?php echo '' !== $sku ? esc_html( $sku ) : '—'; ?></div>
                    <?php if ( current_user_can( 'edit_post', $id ) ) : ?>
                        <button type="button" class="button button-small plnt-signals-edit" data-product-id="<?php echo esc_attr( $id ); ?>" aria-expanded="false">Свойства</button>
                    <?php endif; ?>
                </div>
            </div>
        </td>
        <td class="plnt-signals-cell-prices">
            <div class="plnt-signals-price-line"><span>Обычная:</span> <strong><?php echo '' !== $regular ? wp_kses_post( wc_price( $regular ) ) : '—'; ?></strong></div>
            <div class="plnt-signals-price-line"><span>Акционная:</span> <strong class="plnt-signals-sale-price"><?php echo '' !== $sale ? wp_kses_post( wc_price( $sale ) ) : '—'; ?></strong></div>
        </td>
        <td class="plnt-signals-cell-stock">
            <?php if ( $manage ) : ?>
                <strong><?php echo null !== $stock ? esc_html( number_format_i18n( $stock ) ) : '—'; ?></strong>
            <?php else : ?>
                <span class="plnt-signals-muted">Учёт выключен</span>
            <?php endif; ?>
        </td>
        <td class="plnt-signals-cell-number"><?php echo esc_html( plnt_signals_expo_value( $id, '_plnt_expo_days_reset' ) ); ?></td>
        <td class="plnt-signals-cell-number"><?php echo esc_html( plnt_signals_expo_value( $id, '_plnt_expo_sales_reset' ) ); ?></td>
        <td class="plnt-signals-cell-number"><?php echo esc_html( plnt_signals_expo_value( $id, '_plnt_expo_days_total' ) ); ?></td>
        <td class="plnt-signals-cell-number"><?php echo esc_html( plnt_signals_expo_value( $id, '_plnt_expo_sales_total' ) ); ?></td>
        <td class="plnt-signals-cell-number"><?php echo esc_html( $last_sale ); ?></td>
        <td class="plnt-signals-cell-number"><?php echo wp_kses_post( $avg_price ); ?></td>
    </tr>
    <?php
    return ob_get_clean();
}

/** Состояние фонового импорта и расчётов, без чтения истории заказов. */
function plnt_signals_render_import_status() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }
    $import = get_option( 'plnt_signals_backfill_state', array() );
    $daily  = get_option( 'plnt_signals_daily_state', array() );
    $status = is_array( $import ) ? ( $import['status'] ?? '' ) : '';
    $night  = wp_next_scheduled( PLNT_SIGNALS_NIGHT_HOOK );
    $next_run = $night ? wp_date( 'd.m.Y H:i', $night, wp_timezone() ) : 'не назначен';
    $last = get_option( 'plnt_signals_last_success', '' );
    $last_run = $last ? get_date_from_gmt( $last, 'd.m.Y H:i' ) : 'пока нет';
    ?>
    <div class="plnt-signals-job-status">
        <span>Последний пересчёт: <strong><?php echo esc_html( $last_run ); ?></strong></span>
        <span>Следующий ночной запуск: <strong><?php echo esc_html( $next_run ); ?></strong></span>
        <?php if ( 'running' === $status ) : ?>
            <span>Первоначальный сбор: <strong>обработано <?php echo esc_html( number_format_i18n( (int) ( $import['processed'] ?? 0 ) ) ); ?> заказов</strong></span>
        <?php elseif ( 'error' === $status ) : ?>
            <span class="plnt-signals-job-error">Ошибка импорта: <?php echo esc_html( $import['error'] ?? 'Неизвестная ошибка' ); ?></span>
        <?php elseif ( 'complete' === $status ) : ?>
            <span>Первоначальный сбор: <strong>завершён</strong></span>
        <?php else : ?>
            <span>Первоначальный сбор: <strong>не запущен</strong></span>
        <?php endif; ?>
        <?php if ( is_array( $daily ) && ( $daily['status'] ?? '' ) === 'running' ) : ?>
            <span>Обновление: <strong>обрабатываются заказы</strong></span>
        <?php elseif ( is_array( $daily ) && ( $daily['status'] ?? '' ) === 'error' ) : ?>
            <span class="plnt-signals-job-error">Ошибка обновления: <?php echo esc_html( $daily['error'] ?? 'Неизвестная ошибка' ); ?></span>
        <?php endif; ?>
        <?php if ( 'complete' === $status ) : ?>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="plnt_signals_refresh">
                <?php wp_nonce_field( 'plnt_signals_refresh' ); ?>
                <button type="submit" class="button" <?php disabled( is_array( $daily ) && ( $daily['status'] ?? '' ) === 'running' ); ?>>Обновить статистику</button>
            </form>
        <?php endif; ?>
        <?php if ( in_array( $status, array( 'complete', 'error' ), true ) ) : ?>
            <form class="plnt-signals-restart-backfill" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="plnt_signals_retry_backfill">
                <?php wp_nonce_field( 'plnt_signals_retry_backfill' ); ?>
                <button type="submit" class="button" <?php disabled( is_array( $daily ) && ( $daily['status'] ?? '' ) === 'running' ); ?>>Повторить первоначальный сбор</button>
            </form>
        <?php endif; ?>
    </div>
    <?php
}

/** Отдельная таблица: только комнатные растения, поиск и фильтрация. */
function plnt_signals_render_page() {
    if ( ! current_user_can( 'edit_products' ) ) {
        wp_die( esc_html__( 'У вас нет прав для просмотра этой страницы.' ) );
    }

    $root_ids = plnt_signals_category_ids();
    $search   = isset( $_GET['s'] ) && is_string( $_GET['s'] )
        ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
    $page     = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
    $category = isset( $_GET['product_cat_filter'] ) ? absint( $_GET['product_cat_filter'] ) : 0;
    $tag      = isset( $_GET['product_tag_filter'] ) ? absint( $_GET['product_tag_filter'] ) : 0;
    $stock_status = isset( $_GET['stock_status_filter'] ) && is_string( $_GET['stock_status_filter'] )
        ? sanitize_key( wp_unslash( $_GET['stock_status_filter'] ) ) : '';
    if ( ! in_array( $stock_status, array( '', 'instock', 'outofstock', 'onbackorder' ), true ) ) {
        $stock_status = '';
    }

    // Фильтровать можно только по корневой категории и её потомкам.
    if ( $category && ! in_array( $category, $root_ids, true ) ) {
        $category = 0;
    }
    if ( $tag && ! term_exists( $tag, 'product_tag' ) ) {
        $tag = 0;
    }

    $tax_query = array(
        array(
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => $root_ids ? $root_ids : array( 0 ),
            'include_children' => false,
            'operator'         => 'IN',
        ),
    );

    if ( $category ) {
        $tax_query[] = array(
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => array( $category ),
            'include_children' => true,
        );
    }
    if ( $tag ) {
        $tax_query[] = array(
            'taxonomy' => 'product_tag',
            'field'    => 'term_id',
            'terms'    => array( $tag ),
        );
    }

    $query_args = array(
        'post_type'           => 'product',
        'post_status'         => array( 'publish', 'private', 'draft', 'pending', 'future' ),
        'posts_per_page'      => 30,
        'paged'               => $page,
        's'                   => $search,
        'orderby'             => 'title',
        'order'               => 'ASC',
        'ignore_sticky_posts' => true,
        'tax_query'           => $tax_query,
    );
    if ( $stock_status ) {
        $query_args['meta_query'] = array(
            array(
                'key'     => '_stock_status',
                'value'   => $stock_status,
                'compare' => '=',
            ),
        );
    }
    $query = new WP_Query( $query_args );
    $stats_by_id = plnt_signals_get_stats_for_ids( wp_list_pluck( $query->posts, 'ID' ) );

    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'include'    => $root_ids,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );
    $tags = get_terms( array(
        'taxonomy'   => 'product_tag',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    $base_url  = admin_url( 'admin.php?page=plantis-signals' );
    $has_filter = '' !== $search || $category || $tag || $stock_status;
    ?>
    <div class="wrap plnt-signals-wrap">
        <h1 class="wp-heading-inline">Сигналы</h1>
        <p class="description">Комнатные растения — цены, остатки, показатели экспозиции и статистика заказов.</p>
        <?php plnt_signals_render_import_status(); ?>

        <form class="plnt-signals-filters" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
            <input type="hidden" name="page" value="plantis-signals">
            <label class="screen-reader-text" for="plnt-signals-search">Поиск товаров</label>
            <input type="search" id="plnt-signals-search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Название товара">

            <label class="screen-reader-text" for="plnt-signals-category">Категория</label>
            <select id="plnt-signals-category" name="product_cat_filter">
                <option value="0">Все категории</option>
                <?php if ( ! is_wp_error( $categories ) ) : ?>
                    <?php foreach ( $categories as $term ) : ?>
                        <?php
                        $depth = count( array_intersect( $root_ids, get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) ) );
                        ?>
                        <option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $category, $term->term_id ); ?>><?php echo esc_html( str_repeat( '— ', $depth ) . $term->name ); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label class="screen-reader-text" for="plnt-signals-tag">Метка</label>
            <select id="plnt-signals-tag" name="product_tag_filter">
                <option value="0">Все метки</option>
                <?php if ( ! is_wp_error( $tags ) ) : ?>
                    <?php foreach ( $tags as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $tag, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label class="screen-reader-text" for="plnt-signals-stock">Наличие</label>
            <select id="plnt-signals-stock" name="stock_status_filter">
                <option value="">Все статусы наличия</option>
                <option value="instock" <?php selected( $stock_status, 'instock' ); ?>>В наличии</option>
                <option value="outofstock" <?php selected( $stock_status, 'outofstock' ); ?>>Нет в наличии</option>
                <option value="onbackorder" <?php selected( $stock_status, 'onbackorder' ); ?>>Предзаказ</option>
            </select>

            <button type="submit" class="button button-primary">Фильтровать</button>
            <?php if ( $has_filter ) : ?>
                <a class="button" href="<?php echo esc_url( $base_url ); ?>">Сбросить</a>
            <?php endif; ?>
            <span class="plnt-signals-total">Найдено: <?php echo esc_html( number_format_i18n( $query->found_posts ) ); ?></span>
        </form>

        <div id="plnt-signals-notice" role="status" aria-live="polite"></div>
        <div class="plnt-signals-table-scroll">
            <table class="wp-list-table widefat fixed striped plnt-signals-table">
                <thead>
                    <tr>
                        <th scope="col" class="plnt-signals-heading-product">Товар</th>
                        <th scope="col" class="plnt-signals-heading-prices">Цены</th>
                        <th scope="col" class="plnt-signals-heading-stock">Запас</th>
                        <th scope="col" class="plnt-signals-heading-number" title="Дней экспозиции после последнего обнуления">Дней с обнуления</th>
                        <th scope="col" class="plnt-signals-heading-number" title="Проданных единиц после последнего обнуления">Продаж с обнуления</th>
                        <th scope="col" class="plnt-signals-heading-number" title="Дней экспозиции за всё время">Дней всего</th>
                        <th scope="col" class="plnt-signals-heading-number" title="Проданных единиц за всё время">Продаж всего</th>
                        <th scope="col" class="plnt-signals-heading-last-sale">Последняя продажа</th>
                        <th scope="col" class="plnt-signals-heading-average">Средняя цена за 6 мес.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( $query->have_posts() ) : ?>
                        <?php foreach ( $query->posts as $post ) : ?>
                            <?php
                            $product = wc_get_product( $post->ID );
                            if ( $product && $product->is_type( 'simple' ) ) {
                                echo plnt_signals_product_row( $product, $stats_by_id[ $post->ID ] ?? null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                            ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="<?php echo esc_attr( PLNT_SIGNALS_TABLE_COLUMNS ); ?>">Товары не найдены.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        if ( $query->max_num_pages > 1 ) {
            $pagination_base = add_query_arg( array(
                'page'               => 'plantis-signals',
                'paged'              => '%#%',
                's'                  => $search,
                'product_cat_filter' => $category,
                'product_tag_filter' => $tag,
                'stock_status_filter' => $stock_status,
            ), admin_url( 'admin.php' ) );
            echo '<div class="tablenav bottom"><div class="tablenav-pages">';
            echo wp_kses_post( paginate_links( array(
                'base'      => $pagination_base,
                'format'    => '',
                'current'   => $page,
                'total'     => $query->max_num_pages,
                'prev_text' => '« Назад',
                'next_text' => 'Вперёд »',
                'type'      => 'plain',
            ) ) );
            echo '</div></div>';
        }
        ?>
    </div>
    <?php
    wp_reset_postdata();
}

/** Проверка AJAX-запроса и доступа к товару. */
function plnt_signals_get_editable_product() {
    check_ajax_referer( 'plnt_signals_product', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    if ( ! $product_id || ! current_user_can( 'edit_post', $product_id ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    $product = wc_get_product( $product_id );
    if ( ! $product || ! $product->is_type( 'simple' ) || ! plnt_signals_is_plant( $product_id ) ) {
        wp_send_json_error( array( 'message' => 'Товар недоступен для редактирования в «Сигналах».' ), 400 );
    }

    return $product;
}

/** Только запрошенные поля быстрого редактирования. */
function plnt_signals_render_properties( WC_Product $product ) {
    $id     = $product->get_id();
    $status = $product->get_status();

    ob_start();
    ?>
    <form class="plnt-signals-properties" data-product-id="<?php echo esc_attr( $id ); ?>">
        <input type="hidden" name="product_id" value="<?php echo esc_attr( $id ); ?>">
        <div class="plnt-signals-editor-heading">
            <strong>Свойства: <?php echo esc_html( $product->get_name() ); ?></strong>
            <button type="button" class="button-link plnt-signals-close" aria-label="Закрыть форму">Закрыть ×</button>
        </div>

        <div class="plnt-signals-editor-grid">
            <label><span>Заголовок</span><input type="text" name="name" required value="<?php echo esc_attr( $product->get_name() ); ?>"></label>
            <label><span>Статус</span>
                <select name="status">
                    <option value="publish" <?php selected( $status, 'publish' ); ?>>Опубликовано</option>
                    <option value="draft" <?php selected( $status, 'draft' ); ?>>Черновик</option>
                    <option value="pending" <?php selected( $status, 'pending' ); ?>>На утверждении</option>
                    <option value="private" <?php selected( $status, 'private' ); ?>>Личное</option>
                    <option value="future" <?php selected( $status, 'future' ); ?>>Запланировано</option>
                </select>
            </label>
            <label><span>Артикул</span><input type="text" name="sku" value="<?php echo esc_attr( $product->get_sku() ); ?>"></label>
            <label><span>Цена</span><input type="number" name="regular_price" step="any" min="0" value="<?php echo esc_attr( $product->get_regular_price( 'edit' ) ); ?>"></label>
            <label><span>Распродажа</span><input type="number" name="sale_price" step="any" min="0" value="<?php echo esc_attr( $product->get_sale_price( 'edit' ) ); ?>" placeholder="Акционная цена"></label>
            <label><span>Запас</span><input type="number" name="stock_quantity" step="1" min="0" value="<?php echo esc_attr( $product->get_stock_quantity() ); ?>"></label>
            <label class="plnt-signals-checkbox"><input type="checkbox" name="manage_stock" value="1" <?php checked( $product->managing_stock() ); ?>> Управлять запасами?</label>
            <label><span>Предзаказы?</span>
                <select name="backorders">
                    <option value="no" <?php selected( $product->get_backorders(), 'no' ); ?>>Не разрешать</option>
                    <option value="notify" <?php selected( $product->get_backorders(), 'notify' ); ?>>Разрешить, но уведомлять клиента</option>
                    <option value="yes" <?php selected( $product->get_backorders(), 'yes' ); ?>>Разрешить</option>
                </select>
            </label>
        </div>

        <div class="plnt-signals-editor-actions">
            <button type="submit" class="button button-primary">Обновить</button>
            <button type="button" class="button plnt-signals-close">Отмена</button>
            <span class="spinner" aria-hidden="true"></span>
            <span class="plnt-signals-error" role="alert" aria-live="polite"></span>
        </div>
    </form>
    <?php
    return ob_get_clean();
}

add_action( 'wp_ajax_plnt_signals_get_properties', function () {
    $product = plnt_signals_get_editable_product();
    wp_send_json_success( array( 'html' => plnt_signals_render_properties( $product ) ) );
} );

function plnt_signals_post_string( $key ) {
    return isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] )
        ? trim( sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) )
        : '';
}

/** Цена может быть пустой, иначе проверяем неотрицательное число. */
function plnt_signals_decimal( $key ) {
    $raw = str_replace( ',', '.', plnt_signals_post_string( $key ) );
    if ( '' === $raw ) {
        return '';
    }
    if ( ! is_numeric( $raw ) || (float) $raw < 0 ) {
        throw new Exception( 'Некорректное значение цены.' );
    }
    return wc_format_decimal( $raw );
}

add_action( 'wp_ajax_plnt_signals_save_properties', function () {
    $product = plnt_signals_get_editable_product();
    $id      = $product->get_id();

    try {
        $name = plnt_signals_post_string( 'name' );
        if ( '' === $name ) {
            throw new Exception( 'Введите название товара.' );
        }

        $status = plnt_signals_post_string( 'status' );
        if ( ! in_array( $status, array( 'publish', 'private', 'draft', 'pending', 'future' ), true ) ) {
            throw new Exception( 'Недопустимый статус товара.' );
        }
        if ( in_array( $status, array( 'publish', 'future' ), true )
            && ! in_array( $product->get_status(), array( 'publish', 'future' ), true )
            && ! current_user_can( 'publish_products' ) ) {
            throw new Exception( 'Недостаточно прав для публикации товара.' );
        }

        $sku = plnt_signals_post_string( 'sku' );
        if ( '' !== $sku ) {
            $sku_owner = wc_get_product_id_by_sku( $sku );
            if ( $sku_owner && (int) $sku_owner !== $id ) {
                throw new Exception( 'Этот артикул уже назначен другому товару.' );
            }
        }

        $regular_price = plnt_signals_decimal( 'regular_price' );
        $sale_price    = plnt_signals_decimal( 'sale_price' );
        $manage_stock  = isset( $_POST['manage_stock'] );
        $quantity      = plnt_signals_post_string( 'stock_quantity' );
        $backorders    = plnt_signals_post_string( 'backorders' );

        if ( ! in_array( $backorders, array( 'no', 'notify', 'yes' ), true ) ) {
            throw new Exception( 'Недопустимое значение предзаказов.' );
        }

        if ( $manage_stock && ( '' === $quantity || ! ctype_digit( $quantity ) ) ) {
            throw new Exception( 'При включённом управлении запасами укажите целое неотрицательное количество.' );
        }

        $product->set_name( $name );
        $product->set_status( $status );
        $product->set_sku( $sku );
        $product->set_regular_price( $regular_price );
        $product->set_sale_price( $sale_price );
        $product->set_backorders( $backorders );
        $product->set_manage_stock( $manage_stock );

        if ( $manage_stock ) {
            $amount = (int) $quantity;
            $product->set_stock_quantity( $amount );
            $no_stock_threshold = absint( get_option( 'woocommerce_notify_no_stock_amount', 0 ) );
            $product->set_stock_status(
                $amount > $no_stock_threshold ? 'instock' : ( $product->get_backorders() !== 'no' ? 'onbackorder' : 'outofstock' )
            );
        }

        // При выключении учёта остатков статус наличия не меняем без отдельного поля.
        // WooCommerce CRUD вызывает хуки существующих интеграций, включая синхронизацию метки skidki.
        $product->save();

        clean_post_cache( $id );
        $product = wc_get_product( $id );
        wp_send_json_success( array(
            'message' => 'Товар обновлён.',
            'row'     => $product && plnt_signals_is_plant( $id ) ? plnt_signals_product_row( $product ) : '',
        ) );
    } catch ( Throwable $error ) {
        wp_send_json_error( array( 'message' => $error->getMessage() ), 400 );
    }
} );
