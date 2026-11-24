<?php
/**
 * Страница настроек автоматических связей товаров.
 * Группы растений хранятся одной опцией plnt_plant_cross_sell_groups.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'plnt_plant_cross_sell_register_settings_page' );

function plnt_plant_cross_sell_register_settings_page(): void {
    add_menu_page(
        'Автоматические связи товаров',
        'Авто связи',
        'manage_woocommerce',
        'plnt-product-links',
        'plnt_plant_cross_sell_render_settings_page',
        'dashicons-randomize',
        57
    );

    $GLOBALS['plnt_plant_cross_sell_page_hook'] = add_submenu_page(
        'plnt-product-links',
        'Кросс-сейлы растений',
        'Кросс-сейлы растений',
        'manage_woocommerce',
        'plnt-product-links',
        'plnt_plant_cross_sell_render_settings_page'
    );
}

add_action( 'admin_enqueue_scripts', 'plnt_plant_cross_sell_enqueue_settings_assets' );

function plnt_plant_cross_sell_enqueue_settings_assets( string $hook ): void {
    $cross_sell_page = $GLOBALS['plnt_plant_cross_sell_page_hook'] ?? '';
    $upsell_page     = $GLOBALS['plnt_plant_upsell_page_hook'] ?? '';

    if ( $hook !== $cross_sell_page && $hook !== $upsell_page ) {
        return;
    }

    $style = 'assets/css/product-cross-sells-settings.css';

    wp_enqueue_style(
        'plnt-product-links-settings',
        get_template_directory_uri() . '/' . $style,
        array(),
        filemtime( get_template_directory() . '/' . $style )
    );

    if ( $hook !== $cross_sell_page ) {
        return;
    }

    $script = 'assets/js/product-cross-sells-settings.js';

    wp_enqueue_script( 'jquery-ui-sortable' );

    wp_enqueue_script(
        'plnt-product-links-settings',
        get_template_directory_uri() . '/' . $script,
        array( 'jquery', 'jquery-ui-sortable' ),
        filemtime( get_template_directory() . '/' . $script ),
        true
    );

    wp_localize_script( 'plnt-product-links-settings', 'plntProductLinksSettings', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'plnt_plant_cross_sell_sync' ),
    ) );
}

/** Список категорий растений, включая корневую и дочерние. */
function plnt_plant_cross_sell_admin_categories(): array {
    $root_id = plnt_plant_cross_sell_root_category();

    if ( ! $root_id ) {
        return array();
    }

    $terms = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ) );

    if ( is_wp_error( $terms ) ) {
        return array();
    }

    return array_values( array_filter( $terms, static function ( $term ) use ( $root_id ) {
        return (int) $term->term_id === $root_id
            || in_array( $root_id, array_map( 'intval', get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) ), true );
    } ) );
}

/** Полное название категории с родителями для удобства поиска. */
function plnt_plant_cross_sell_admin_category_label( WP_Term $term ): string {
    $ancestors = array_reverse( get_ancestors( $term->term_id, 'product_cat', 'taxonomy' ) );
    $names     = array();

    foreach ( $ancestors as $term_id ) {
        $parent = get_term( $term_id, 'product_cat' );

        if ( $parent && ! is_wp_error( $parent ) ) {
            $names[] = $parent->name;
        }
    }

    $names[] = $term->name;

    return implode( ' → ', $names );
}

/** Редактор одной группы. */
function plnt_plant_cross_sell_render_group( array $group, $index, array $categories, array $tags ): void {
    $id                = (string) ( $group['id'] ?? '' );
    $name              = (string) ( $group['name'] ?? '' );
    $selected_cats     = array_map( 'intval', (array) ( $group['categories'] ?? array() ) );
    $selected_tags     = array_map( 'intval', (array) ( $group['tags'] ?? array() ) );
    $count             = $id ? count( plnt_plant_cross_sell_group_product_ids( $id ) ) : 0;
    ?>
    <div class="plnt-link-group" data-group-id="<?php echo esc_attr( $id ); ?>">
        <div class="plnt-link-group__heading">
            <span class="plnt-link-group__handle dashicons dashicons-move" title="Перетащить группу" aria-hidden="true"></span>
            <strong class="plnt-link-group__heading-title"><?php echo esc_html( $name ?: 'Новая группа' ); ?></strong>
            <button class="button-link-delete plnt-link-group__remove" type="button">Удалить группу</button>
        </div>

        <input type="hidden" name="groups[<?php echo esc_attr( $index ); ?>][id]" value="<?php echo esc_attr( $id ); ?>">

        <div class="plnt-link-group__fields">
            <div class="plnt-link-group__field">
                <span>Название группы</span>
                <input type="text" class="regular-text plnt-link-group__name"
                       name="groups[<?php echo esc_attr( $index ); ?>][name]"
                       value="<?php echo esc_attr( $name ); ?>" required>
            </div>

            <div class="plnt-link-group__field">
                <span>Категории товаров</span>
                <select class="plnt-link-group__terms" data-taxonomy="categories"
                        name="groups[<?php echo esc_attr( $index ); ?>][categories][]"
                        multiple="multiple" data-placeholder="Поиск категорий...">
                    <?php foreach ( $categories as $term ) :
                        $label = plnt_plant_cross_sell_admin_category_label( $term );
                        ?>
                        <option value="<?php echo esc_attr( $term->term_id ); ?>"
                                data-label="<?php echo esc_attr( $label ); ?>"
                                <?php selected( in_array( (int) $term->term_id, $selected_cats, true ) ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="plnt-link-group__field">
                <span>Метки товаров</span>
                <select class="plnt-link-group__terms" data-taxonomy="tags"
                        name="groups[<?php echo esc_attr( $index ); ?>][tags][]"
                        multiple="multiple" data-placeholder="Поиск меток...">
                    <?php foreach ( $tags as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->term_id ); ?>"
                                data-label="<?php echo esc_attr( $term->name ); ?>"
                                <?php selected( in_array( (int) $term->term_id, $selected_tags, true ) ); ?>>
                            <?php echo esc_html( $term->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="plnt-link-group__footer">
            <span class="plnt-link-group__count">
                <?php if ( $id ) : ?>
                    Растений в группе: <?php echo esc_html( (string) $count ); ?>
                <?php else : ?>
                    Сначала сохраните группу
                <?php endif; ?>
            </span>
            <button class="button button-primary plnt-link-group__save" type="submit"
                    title="Сохраняет настройки всех групп">Сохранить изменения</button>
        </div>

        <details class="plnt-link-group__maintenance">
            <summary>Дополнительные действия с группой</summary>
            <div class="plnt-link-group__maintenance-content">
                <p>Полностью пересчитать и перезаписать кроссейлы у всех растений этой группы.
                    Сначала сохраните изменения в настройках.</p>
                <button class="button plnt-link-group__sync" type="button"
                        <?php disabled( ! $id ); ?>>Обновить кроссейлы во всех растениях группы</button>
                <span class="plnt-link-group__result" role="status" aria-live="polite"></span>
            </div>
        </details>
    </div>
    <?php
}

/** Основная страница настроек в админке WooCommerce. */
function plnt_plant_cross_sell_render_settings_page(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    $groups     = plnt_plant_cross_sell_groups();
    $categories = plnt_plant_cross_sell_admin_categories();
    $tags       = get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => false ) );
    $tags       = is_wp_error( $tags ) ? array() : $tags;
    ?>
    <div class="wrap plnt-links-settings">
        <h1>Автоматические связи товаров</h1>
        <nav class="plnt-links-settings__sections" aria-label="Разделы настроек">
            <a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=plnt-product-links' ) ); ?>">Кросс-сейлы растений</a>
            <a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=plnt-product-upsells' ) ); ?>">Апсейлы растений</a>
        </nav>
        <h2 id="plnt-cross-sells">Кросс-сейлы растений</h2>
        <p>Внутри группы категории и метки работают по принципу <strong>ИЛИ</strong>: достаточно совпадения
            хотя бы одной категории или одной метки. Каждый термин можно выбрать только в одной группе.
            Если товар подходит нескольким группам, используется первая по порядку.</p>

        <?php if ( isset( $_GET['updated'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p>Настройки групп сохранены.</p></div>
        <?php endif; ?>

        <?php if ( ! plnt_plant_cross_sell_root_category() ) : ?>
            <div class="notice notice-error"><p>Не найдена категория с ярлыком <code>komnatnye-rasteniya</code>. Обновление кросс-сейлов недоступно.</p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="plnt-links-settings-form">
            <input type="hidden" name="action" value="plnt_save_plant_cross_sell_groups">
            <?php wp_nonce_field( 'plnt_save_plant_cross_sell_groups' ); ?>

            <div class="plnt-links-settings__savebar">
                <span class="plnt-links-settings__save-status" id="plnt-links-save-status">Все изменения сохранены</span>
                <button type="submit" class="button button-primary">Сохранить настройки групп</button>
            </div>
            <p class="description">Сначала сохраните изменения. Массовое обновление кроссейлов доступно в разделе «Дополнительные действия с группой» и выполняется отдельно.</p>

            <div id="plnt-link-groups">
                <?php foreach ( $groups as $index => $group ) : ?>
                    <?php plnt_plant_cross_sell_render_group( $group, $index, $categories, $tags ); ?>
                <?php endforeach; ?>
            </div>

            <p><button type="button" class="button" id="plnt-link-add-group">Добавить группу</button></p>
            <?php submit_button( 'Сохранить настройки' ); ?>
        </form>

        <template id="plnt-link-group-template">
            <?php plnt_plant_cross_sell_render_group( array(), '__INDEX__', $categories, $tags ); ?>
        </template>

    </div>
    <?php
}

/** Проверить ID термина и принадлежность категории к комнатным растениям. */
function plnt_plant_cross_sell_validate_term( int $term_id, string $taxonomy ): bool {
    $term = get_term( $term_id, $taxonomy );

    if ( ! $term || is_wp_error( $term ) ) {
        return false;
    }

    if ( 'product_cat' !== $taxonomy ) {
        return true;
    }

    $root_id = plnt_plant_cross_sell_root_category();

    return $root_id && ( $term_id === $root_id
        || in_array( $root_id, array_map( 'intval', get_ancestors( $term_id, 'product_cat', 'taxonomy' ) ), true ) );
}

/** Сохранение настроек. Проверка повторов выполняется и на сервере. */
add_action( 'admin_post_plnt_save_plant_cross_sell_groups', 'plnt_plant_cross_sell_save_groups' );

function plnt_plant_cross_sell_save_groups(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( 'Недостаточно прав.' );
    }

    check_admin_referer( 'plnt_save_plant_cross_sell_groups' );

    $submitted = isset( $_POST['groups'] ) && is_array( $_POST['groups'] )
        ? wp_unslash( $_POST['groups'] )
        : array();

    $groups = array();
    $used   = array( 'categories' => array(), 'tags' => array() );
    $ids    = array();

    foreach ( $submitted as $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }

        $name = sanitize_text_field( $row['name'] ?? '' );

        if ( '' === $name ) {
            wp_die( 'Название группы обязательно.' );
        }

        $group = array(
            'id'         => sanitize_key( $row['id'] ?? '' ),
            'name'       => $name,
            'categories' => array(),
            'tags'       => array(),
        );

        if ( ! $group['id'] || isset( $ids[ $group['id'] ] ) ) {
            $group['id'] = wp_generate_uuid4();
        }

        $ids[ $group['id'] ] = true;

        foreach ( array( 'categories' => 'product_cat', 'tags' => 'product_tag' ) as $key => $taxonomy ) {
            $term_ids = array_values( array_unique( array_filter( array_map(
                'absint', (array) ( $row[ $key ] ?? array() )
            ) ) ) );

            foreach ( $term_ids as $term_id ) {
                if ( ! plnt_plant_cross_sell_validate_term( $term_id, $taxonomy ) ) {
                    wp_die( 'В группе «' . esc_html( $name ) . '» выбрана недопустимая категория или метка.' );
                }

                if ( isset( $used[ $key ][ $term_id ] ) ) {
                    $term = get_term( $term_id, $taxonomy );
                    wp_die( 'Термин «' . esc_html( $term->name ) . '» уже используется в группе «'
                        . esc_html( $used[ $key ][ $term_id ] ) . '».' );
                }

                $used[ $key ][ $term_id ] = $name;
            }

            $group[ $key ] = $term_ids;
        }

        if ( ! $group['categories'] && ! $group['tags'] ) {
            wp_die( 'Выберите хотя бы одну категорию или метку для группы «' . esc_html( $name ) . '».' );
        }

        $groups[] = $group;
    }

    update_option( 'plnt_plant_cross_sell_groups', $groups, false );

    wp_safe_redirect( add_query_arg(
        array( 'page' => 'plnt-product-links', 'updated' => 1 ),
        admin_url( 'admin.php' )
    ) );
    exit;
}

/** Пакетное обновление одной группы, чтобы не упираться во время выполнения PHP. */
add_action( 'wp_ajax_plnt_plant_cross_sell_sync_group', 'plnt_plant_cross_sell_sync_group_ajax' );

function plnt_plant_cross_sell_sync_group_ajax(): void {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    check_ajax_referer( 'plnt_plant_cross_sell_sync', 'nonce' );

    $group_id = isset( $_POST['group_id'] ) ? sanitize_key( wp_unslash( $_POST['group_id'] ) ) : '';
    $offset   = isset( $_POST['offset'] ) ? absint( $_POST['offset'] ) : 0;

    if ( ! $group_id || ! plnt_plant_cross_sell_get_group( $group_id ) ) {
        wp_send_json_error( array( 'message' => 'Группа не найдена. Сохраните настройки.' ), 404 );
    }

    $product_ids = plnt_plant_cross_sell_group_product_ids( $group_id );
    $total       = count( $product_ids );
    $batch       = array_slice( $product_ids, $offset, 20 );

    foreach ( $batch as $product_id ) {
        plnt_plant_cross_sell_update_group_product( $product_id, $product_ids );
    }

    $processed = min( $offset + count( $batch ), $total );

    wp_send_json_success( array(
        'processed' => $processed,
        'total'     => $total,
        'done'      => $processed >= $total,
    ) );
}
