<?php
/** Отдельная страница настройки горшков-апсейлов в меню «Товары». */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'plnt_plant_upsell_register_settings_page' );

function plnt_plant_upsell_register_settings_page(): void {
    $GLOBALS['plnt_plant_upsell_page_hook'] = add_submenu_page(
        'plnt-product-links',
        'Апсейлы растений',
        'Апсейлы растений',
        'manage_woocommerce',
        'plnt-product-upsells',
        'plnt_plant_upsell_render_settings_page'
    );
}

add_action( 'admin_enqueue_scripts', 'plnt_plant_upsell_enqueue_admin_assets' );

function plnt_plant_upsell_enqueue_admin_assets( string $hook ): void {
    if ( $hook !== ( $GLOBALS['plnt_plant_upsell_page_hook'] ?? '' ) ) {
        return;
    }

    $script = 'assets/js/product-upsells-settings.js';
    $style  = 'assets/css/product-upsells-settings.css';

    wp_enqueue_script(
        'plnt-plant-upsells-settings',
        get_template_directory_uri() . '/' . $script,
        array( 'jquery' ),
        filemtime( get_template_directory() . '/' . $script ),
        true
    );

    wp_localize_script( 'plnt-plant-upsells-settings', 'plntPlantUpsellAdmin', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'plnt_plant_upsell_admin' ),
    ) );

    wp_enqueue_style(
        'plnt-plant-upsells-settings',
        get_template_directory_uri() . '/' . $style,
        array( 'plnt-product-links-settings' ),
        filemtime( get_template_directory() . '/' . $style )
    );
}

/** Разметка одного значения атрибута и его списка горшков. */
function plnt_plant_upsell_render_group( array $group, $index, array $terms ): void {
    $term_id  = absint( $group['term_id'] ?? 0 );
    $products = array_values( array_unique( array_filter( array_map( 'absint', (array) ( $group['products'] ?? array() ) ) ) ) );
    ?>
    <div class="plnt-upsell-group">
        <div class="plnt-upsell-group__heading">
            <strong class="plnt-upsell-group__title"><?php
                $current = $term_id ? get_term( $term_id, plnt_plant_upsell_attribute_taxonomy() ) : null;
                echo esc_html( $current && ! is_wp_error( $current ) ? $current->name : 'Новая группа' );
            ?></strong>
            <button class="button-link-delete plnt-upsell-group__delete" type="button">Удалить группу</button>
        </div>

        <label class="plnt-upsell-group__field">
            <span>Значение атрибута «Для пересадки»</span>
            <select class="plnt-upsell-group__term" name="upsell_groups[<?php echo esc_attr( $index ); ?>][term_id]" required>
                <option value="">Выберите значение атрибута</option>
                <?php foreach ( $terms as $term ) : ?>
                    <option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $term_id, (int) $term->term_id ); ?>>
                        <?php echo esc_html( $term->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="plnt-upsell-group__picker">
            <label for="plnt-upsell-skus-<?php echo esc_attr( $index ); ?>">Артикулы горшков и кашпо</label>
            <textarea class="plnt-upsell-group__skus" id="plnt-upsell-skus-<?php echo esc_attr( $index ); ?>"
                      rows="3" placeholder="Артикул-1, Артикул-2, Артикул-3"></textarea>
            <div class="plnt-upsell-group__apply-row">
                <button type="button" class="button plnt-upsell-group__apply">Применить</button>
                <span class="plnt-upsell-group__apply-result" role="status" aria-live="polite"></span>
            </div>
            <ul class="plnt-upsell-group__sku-errors" hidden></ul>
            <p class="description">Укажите артикулы через запятую. «Применить» добавит найденные товары в список ниже.
                После этого сохраните группы. Доступны горшки из категорий «Горшки и кашпо», Treez и Lechuza.</p>
        </div>

        <div class="plnt-upsell-group__selected">
            <div class="plnt-upsell-group__selected-header">
                <strong>Выбранные товары: <span class="plnt-upsell-group__total"><?php echo esc_html( (string) count( $products ) ); ?></span></strong>
                <input type="search" class="plnt-upsell-group__selected-filter" placeholder="Найти среди выбранных…" aria-label="Поиск среди выбранных горшков">
            </div>

            <ul class="plnt-upsell-group__list">
                <?php foreach ( $products as $product_id ) :
                    $product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;
                    $name    = $product ? $product->get_name() : 'Товар #' . $product_id . ' не найден';
                    $sku     = $product ? $product->get_sku() : '';
                    ?>
                    <li class="plnt-upsell-group__item" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                        <input type="hidden" name="upsell_groups[<?php echo esc_attr( $index ); ?>][products][]" value="<?php echo esc_attr( $product_id ); ?>">
                        <span class="plnt-upsell-group__item-title">
                            <a href="<?php echo esc_url( get_edit_post_link( $product_id ) ?: '#' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $name ); ?></a>
                            <?php if ( $sku ) : ?><small>Арт. <?php echo esc_html( $sku ); ?></small><?php endif; ?>
                        </span>
                        <button type="button" class="plnt-upsell-group__item-remove" aria-label="Убрать <?php echo esc_attr( $name ); ?>">×</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="plnt-upsell-group__empty" <?php echo $products ? 'hidden' : ''; ?>>Горшки пока не выбраны.</p>
            <button type="button" class="button-link plnt-upsell-group__show-all" hidden>Показать все</button>
        </div>
    </div>
    <?php
}

/** Самостоятельная страница: отдельно от формы кросс-сейлов. */
function plnt_plant_upsell_render_settings_page(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }
    ?>
    <div class="wrap plnt-links-settings">
        <h1>Автоматические связи товаров</h1>
        <nav class="plnt-links-settings__sections" aria-label="Разделы настроек">
            <a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=plnt-product-links' ) ); ?>">Кросс-сейлы растений</a>
            <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=plnt-product-upsells' ) ); ?>">Апсейлы растений</a>
        </nav>
        <?php plnt_plant_upsell_render_settings(); ?>
    </div>
    <?php
}

function plnt_plant_upsell_render_settings(): void {
    $taxonomy = plnt_plant_upsell_attribute_taxonomy();
    $terms    = $taxonomy ? get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) ) : array();
    $terms    = is_wp_error( $terms ) ? array() : $terms;
    $groups   = plnt_plant_upsell_groups();
    ?>
    <section class="plnt-upsells" id="plnt-upsells">
        <h2>Апсейлы растений — горшки для пересадки</h2>
        <p>Для каждого значения атрибута «Для пересадки» укажите конкретные горшки. При нескольких значениях
            у растения списки горшков объединяются без повторов. Апсейлы меняются только у комнатных растений.</p>

        <?php if ( isset( $_GET['upsells_updated'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Группы апсейлов сохранены. Для обновления связей у существующих растений запустите пересчёт отдельной кнопкой.</p></div>
        <?php endif; ?>

        <?php if ( ! $taxonomy ) : ?>
            <div class="notice notice-error"><p>Не найден глобальный атрибут WooCommerce «Для пересадки». Создание групп недоступно.</p></div>
        <?php endif; ?>

        <?php if ( count( plnt_plant_upsell_pot_category_ids() ) !== 3 ) : ?>
            <div class="notice notice-warning"><p>Не все категории горшков найдены. Проверьте ярлыки: <code>gorshki_i_kashpo</code>, <code>kashpo-treez</code>, <code>kashpo-lechuza</code>.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="plnt-upsell-form">
            <input type="hidden" name="action" value="plnt_save_plant_upsell_groups">
            <?php wp_nonce_field( 'plnt_save_plant_upsell_groups' ); ?>

            <div class="plnt-links-settings__savebar">
                <span class="plnt-links-settings__save-status" id="plnt-upsell-save-status">Все изменения сохранены</span>
                <button type="submit" class="button button-primary" <?php disabled( ! $taxonomy ); ?>>Сохранить группы апсейлов</button>
            </div>

            <div id="plnt-upsell-groups">
                <?php foreach ( $groups as $index => $group ) : ?>
                    <?php plnt_plant_upsell_render_group( $group, $index, $terms ); ?>
                <?php endforeach; ?>
            </div>

            <p><button type="button" class="button" id="plnt-upsell-add-group" <?php disabled( ! $taxonomy ); ?>>Добавить группу апсейлов</button></p>
        </form>

        <details class="plnt-upsells__maintenance">
            <summary>Дополнительные действия: массовое обновление</summary>
            <p>Кнопка отдельно пересчитывает апсейлы всех комнатных растений, включая личные,
                по последней сохранённой настройке групп. Если ни одно значение атрибута растения
                не входит в настроенные группы, его текущие апсейлы сохраняются.</p>
            <button type="button" class="button" id="plnt-upsell-sync-all"
                    <?php disabled( ! $taxonomy ); ?>>Обновить апсейлы у всех комнатных растений</button>
            <span id="plnt-upsell-sync-result" role="status" aria-live="polite"></span>
        </details>

        <template id="plnt-upsell-group-template">
            <?php plnt_plant_upsell_render_group( array(), '__INDEX__', $terms ); ?>
        </template>
    </section>
    <?php
}

/** Сохранение только данных групп апсейлов с серверной проверкой категорий и термина. */
add_action( 'admin_post_plnt_save_plant_upsell_groups', 'plnt_plant_upsell_save_groups' );

function plnt_plant_upsell_save_groups(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'Недостаточно прав.' );
    }

    check_admin_referer( 'plnt_save_plant_upsell_groups' );

    $taxonomy = plnt_plant_upsell_attribute_taxonomy();

    if ( ! $taxonomy ) {
        wp_die( 'Атрибут «Для пересадки» не найден.' );
    }

    $rows = isset( $_POST['upsell_groups'] ) && is_array( $_POST['upsell_groups'] )
        ? wp_unslash( $_POST['upsell_groups'] )
        : array();
    $used_term_ids = array();
    $groups        = array();

    foreach ( $rows as $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }

        $term_id = absint( $row['term_id'] ?? 0 );
        $term    = $term_id ? get_term( $term_id, $taxonomy ) : null;

        if ( ! $term || is_wp_error( $term ) ) {
            wp_die( 'Выберите существующее значение атрибута «Для пересадки».' );
        }

        if ( isset( $used_term_ids[ $term_id ] ) ) {
            wp_die( 'Для значения «' . esc_html( $term->name ) . '» уже создана группа.' );
        }

        $used_term_ids[ $term_id ] = true;
        $products                 = array();

        foreach ( (array) ( $row['products'] ?? array() ) as $product_id ) {
            $product_id = absint( $product_id );

            if ( ! plnt_plant_upsell_is_allowed_pot( $product_id ) ) {
                wp_die( 'Товар #' . esc_html( (string) $product_id ) . ' недоступен или больше не относится к разрешённым категориям горшков.' );
            }

            $products[ $product_id ] = $product_id;
        }

        $groups[] = array(
            'term_id'  => $term_id,
            'products' => array_values( $products ),
        );
    }

    update_option( 'plnt_plant_upsell_groups', $groups, false );

    $redirect_args = array( 'page' => 'plnt-product-upsells', 'upsells_updated' => 1 );

    wp_safe_redirect( add_query_arg( $redirect_args, admin_url( 'admin.php' ) ) . '#plnt-upsells' );
    exit;
}

/** Преобразовать список точных артикулов в товары разрешённых категорий. */
add_action( 'wp_ajax_plnt_plant_upsell_resolve_skus', 'plnt_plant_upsell_resolve_skus_ajax' );

function plnt_plant_upsell_resolve_skus_ajax(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    check_ajax_referer( 'plnt_plant_upsell_admin', 'nonce' );

    $raw = isset( $_POST['skus'] ) ? sanitize_textarea_field( wp_unslash( $_POST['skus'] ) ) : '';
    $skus = array_values( array_unique( array_filter( array_map(
        'trim',
        preg_split( '/[,;\r\n]+/u', $raw ) ?: array()
    ), 'strlen' ) ) );

    if ( count( $skus ) > 500 ) {
        wp_send_json_error( array( 'message' => 'За один раз можно указать не более 500 артикулов.' ), 400 );
    }

    $products   = array();
    $unresolved = array();

    foreach ( $skus as $sku ) {
        $product_id = (int) wc_get_product_id_by_sku( $sku );

        if ( ! $product_id ) {
            $unresolved[] = array( 'sku' => $sku, 'reason' => 'Товар не найден' );
            continue;
        }

        if ( ! plnt_plant_upsell_is_allowed_pot( $product_id ) ) {
            $unresolved[] = array( 'sku' => $sku, 'reason' => 'Товар не входит в разрешённые категории или недоступен' );
            continue;
        }

        $product = wc_get_product( $product_id );

        if ( ! $product ) {
            $unresolved[] = array( 'sku' => $sku, 'reason' => 'Товар не найден' );
            continue;
        }

        $products[ $product_id ] = array(
            'id'      => $product_id,
            'name'    => $product->get_name(),
            'sku'     => $product->get_sku(),
            'editUrl' => get_edit_post_link( $product_id ),
        );
    }

    wp_send_json_success( array(
        'products'   => array_values( $products ),
        'unresolved' => $unresolved,
    ) );
}

/** Полная пересборка апсейлов только у растений (пакеты по 20). */
add_action( 'wp_ajax_plnt_plant_upsell_sync_all', 'plnt_plant_upsell_sync_all_ajax' );

function plnt_plant_upsell_sync_all_ajax(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    check_ajax_referer( 'plnt_plant_upsell_admin', 'nonce' );

    $offset      = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;
    $product_ids = plnt_plant_upsell_all_plant_ids();
    $total       = count( $product_ids );
    $batch       = array_slice( $product_ids, $offset, 20 );
    $taxonomy    = plnt_plant_upsell_attribute_taxonomy();
    $group_terms = array();

    foreach ( plnt_plant_upsell_groups() as $group ) {
        $term_id = absint( $group['term_id'] ?? 0 );

        if ( $term_id ) {
            $group_terms[] = $term_id;
        }
    }

    $skipped = 0;

    foreach ( $batch as $product_id ) {
        // Если для значений атрибута растения нет группы, не трогаем его апсейлы.
        if ( ! $taxonomy || ! $group_terms || ! has_term( $group_terms, $taxonomy, $product_id ) ) {
            ++$skipped;
            continue;
        }

        plnt_plant_upsell_update_product( $product_id );
    }

    $processed = min( $offset + count( $batch ), $total );

    wp_send_json_success( array(
        'processed' => $processed,
        'total'     => $total,
        'done'      => $processed >= $total,
        'skipped'   => $skipped,
    ) );
}
