<?php
/** Экспресс-создание простого товара из списка товаров WooCommerce. */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Зарегистрированные глобальные атрибуты для интерфейса. */
function plnt_express_product_attribute_choices(): array {
    if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
        return array();
    }

    $choices = array();

    foreach ( (array) wc_get_attribute_taxonomies() as $attribute ) {
        $taxonomy = wc_attribute_taxonomy_name( (string) $attribute->attribute_name );

        if ( taxonomy_exists( $taxonomy ) ) {
            $choices[] = array(
                'taxonomy' => $taxonomy,
                'label'    => (string) $attribute->attribute_label,
            );
        }
    }

    return $choices;
}

/** Проверка таксономий, доступных для выбора через поиск. */
function plnt_express_product_allowed_taxonomy( string $taxonomy ): bool {
    return in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true )
        || ( taxonomy_exists( $taxonomy ) && (bool) wc_attribute_taxonomy_id_by_name( $taxonomy ) );
}

add_action( 'admin_enqueue_scripts', 'plnt_express_product_enqueue' );

function plnt_express_product_enqueue( string $hook ): void {
    $screen = get_current_screen();

    if ( 'edit.php' !== $hook || ! $screen || 'product' !== $screen->post_type
        || ! current_user_can( 'edit_products' ) || ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    $script = 'assets/js/product-express-create.js';
    $style  = 'assets/css/product-express-create.css';

    wp_enqueue_script( 'selectWoo' );
    wp_enqueue_style( 'select2' );

    wp_enqueue_script(
        'plnt-express-product',
        get_template_directory_uri() . '/' . $script,
        array( 'jquery', 'selectWoo' ),
        filemtime( get_template_directory() . '/' . $script ),
        true
    );

    wp_localize_script( 'plnt-express-product', 'plntExpressProduct', array(
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'plnt_express_product' ),
        'attributes' => plnt_express_product_attribute_choices(),
    ) );

    wp_enqueue_style(
        'plnt-express-product',
        get_template_directory_uri() . '/' . $style,
        array( 'select2' ),
        filemtime( get_template_directory() . '/' . $style )
    );
}

/** Окно выводим только на странице списка товаров; кнопку рядом с заголовком ставит JS. */
add_action( 'admin_footer-edit.php', 'plnt_express_product_render_modal' );

function plnt_express_product_render_modal(): void {
    $screen = get_current_screen();

    if ( ! $screen || 'product' !== $screen->post_type || ! current_user_can( 'edit_products' ) ) {
        return;
    }
    ?>
    <div id="plnt-express-modal" class="plnt-express-modal" hidden>
        <div class="plnt-express-modal__backdrop"></div>
        <div class="plnt-express-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="plnt-express-heading" tabindex="-1">
            <header class="plnt-express-modal__header">
                <h2 id="plnt-express-heading">Добавить товар — экспресс</h2>
                <span class="plnt-express-modal__status">Статус: Личное</span>
                <button type="button" class="plnt-express-modal__close" aria-label="Закрыть окно">×</button>
            </header>

            <nav class="plnt-express-modal__steps" aria-label="Шаги создания товара">
                <span class="plnt-express-modal__step-indicator is-active" data-step-indicator="1" aria-current="step"><span class="plnt-express-modal__step-number">1</span> Основное</span>
                <span class="plnt-express-modal__step-indicator" data-step-indicator="2"><span class="plnt-express-modal__step-number">2</span> Атрибуты</span>
            </nav>

            <form id="plnt-express-form" class="plnt-express-modal__form">
                <div class="plnt-express-modal__content">
                    <section class="plnt-express-modal__step" data-step="1" aria-label="Основные данные товара">
                    <div class="plnt-express-modal__field">
                        <label for="plnt-express-name">Название <span class="required">*</span></label>
                        <input id="plnt-express-name" name="name" type="text" maxlength="200" required placeholder="Название товара">
                    </div>

                    <div class="plnt-express-modal__grid">
                        <div class="plnt-express-modal__field">
                            <label for="plnt-express-cost">Цена закупки, ₽</label>
                            <input id="plnt-express-cost" name="cost" type="text" inputmode="decimal" placeholder="0">
                            <span class="description">Пока только поле ввода, значение не сохраняется.</span>
                        </div>
                        <div class="plnt-express-modal__field">
                            <label for="plnt-express-stock">Количество</label>
                            <input id="plnt-express-stock" name="stock" type="number" min="0" step="1" placeholder="0">
                            <span class="description">Если заполнено, включим учёт запасов и сохраним указанное количество.</span>
                        </div>
                    </div>

                    <div class="plnt-express-modal__grid">
                        <div class="plnt-express-modal__field">
                            <label for="plnt-express-slug">URL (ярлык)</label>
                            <input id="plnt-express-slug" name="slug" type="text" placeholder="Создаётся из названия, если не заполнен">
                        </div>
                        <div class="plnt-express-modal__field">
                            <label for="plnt-express-sku">Артикул</label>
                            <input id="plnt-express-sku" name="sku" type="text" placeholder="Артикул товара">
                            <div class="plnt-express-modal__sku-reference">
                                <label for="plnt-express-last-sku">Последний созданный артикул (справочно)</label>
                                <input id="plnt-express-last-sku" type="text" readonly placeholder="Загружаем…" aria-describedby="plnt-express-last-sku-name">
                                <span id="plnt-express-last-sku-name" class="description" aria-live="polite"></span>
                            </div>
                        </div>
                    </div>

                    <div class="plnt-express-modal__field">
                        <label for="plnt-express-price">Цена продажи, ₽ <span class="required">*</span></label>
                        <input id="plnt-express-price" name="price" type="text" inputmode="decimal" required placeholder="0">
                    </div>

                    <h3>Категории и метки</h3>
                    <div class="plnt-express-modal__grid">
                        <div class="plnt-express-modal__field">
                            <label for="plnt-express-categories">Категории <span class="required">*</span></label>
                            <select id="plnt-express-categories" class="plnt-express-modal__search" multiple data-taxonomy="product_cat" aria-label="Поиск категорий"></select>
                        </div>
                        <div class="plnt-express-modal__field">
                            <label for="plnt-express-tags">Метки</label>
                            <select id="plnt-express-tags" class="plnt-express-modal__search" multiple data-taxonomy="product_tag" aria-label="Поиск меток"></select>
                        </div>
                    </div>
                    </section>

                    <section class="plnt-express-modal__step" data-step="2" aria-label="Атрибуты товара" hidden>
                    <h3>Атрибуты товара</h3>
                    <div class="plnt-express-modal__attribute-actions">
                        <button type="button" id="plnt-express-import-attributes" class="button">Подтянуть атрибуты из категорий</button>
                        <span id="plnt-express-import-result" role="status" aria-live="polite"></span>
                    </div>

                    <div id="plnt-express-attributes" class="plnt-express-modal__attributes"></div>
                    <div class="plnt-express-modal__add-attribute-action">
                        <button type="button" id="plnt-express-add-attribute" class="button">Добавить атрибут</button>
                    </div>
                    <p class="description">Можно выбрать несколько значений каждого атрибута. После загрузки из категорий значения можно отредактировать вручную.</p>
                    </section>

                    <div id="plnt-express-error" class="notice notice-error inline" role="alert" hidden></div>
                </div>

                <footer class="plnt-express-modal__footer">
                    <span id="plnt-express-footer-hint">Шаг 1 из 2. Товар будет создан со статусом «Личное».</span>
                    <div class="plnt-express-modal__footer-actions">
                        <button type="button" class="button plnt-express-modal__cancel">Отмена</button>
                        <button type="button" id="plnt-express-back" class="button" hidden>Назад</button>
                        <button type="button" id="plnt-express-next" class="button button-primary">Далее: атрибуты</button>
                        <button type="submit" id="plnt-express-save" class="button button-primary" hidden>Сохранить товар</button>
                    </div>
                </footer>
            </form>

            <div id="plnt-express-success" class="plnt-express-modal__success" hidden>
                <h3>Товар успешно создан</h3>
                <p id="plnt-express-success-message">Статус: «Личное».</p>
                <div class="plnt-express-modal__footer-actions">
                    <a id="plnt-express-edit-link" class="button button-primary" target="_blank" rel="noopener noreferrer">Открыть товар</a>
                    <button type="button" id="plnt-express-add-another" class="button">Добавить ещё один</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/** Поиск категорий, меток и значений глобальных атрибутов WooCommerce. */
add_action( 'wp_ajax_plnt_express_search_terms', 'plnt_express_product_search_terms' );

function plnt_express_product_search_terms(): void {
    if ( ! current_user_can( 'edit_products' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    check_ajax_referer( 'plnt_express_product', 'nonce' );

    // sanitize_key() удаляет кириллицу; WooCommerce допускает кириллические ярлыки атрибутов.
    $taxonomy = isset( $_GET['taxonomy'] ) && is_string( $_GET['taxonomy'] )
        ? sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) )
        : '';

    if ( ! plnt_express_product_allowed_taxonomy( $taxonomy ) ) {
        wp_send_json_error( array( 'message' => 'Недопустимая таксономия.' ), 400 );
    }

    $search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
    // Для выбранного атрибута один раз отдаём все значения — поиск дальше локальный.
    $all_attribute_terms = isset( $_GET['all'] ) && '1' === (string) $_GET['all']
        && ! in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true );
    $terms  = get_terms( array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'search'     => $search,
        'number'     => $all_attribute_terms ? 0 : 30,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    if ( is_wp_error( $terms ) ) {
        wp_send_json_error( array( 'message' => $terms->get_error_message() ), 500 );
    }

    $items = array();

    foreach ( $terms as $term ) {
        $title = $term->name;

        if ( 'product_cat' === $taxonomy ) {
            $parent_names = array();

            foreach ( array_reverse( get_ancestors( $term->term_id, $taxonomy, 'taxonomy' ) ) as $parent_id ) {
                $parent = get_term( $parent_id, $taxonomy );

                if ( $parent && ! is_wp_error( $parent ) ) {
                    $parent_names[] = $parent->name;
                }
            }

            if ( $parent_names ) {
                $title = implode( ' / ', $parent_names ) . ' / ' . $title;
            }
        }

        $items[] = array( 'id' => (int) $term->term_id, 'text' => $title );
    }

    wp_send_json_success( array( 'results' => $items ) );
}

/** Собрать атрибуты выбранных категорий и их родителей для предпросмотра формы. */
add_action( 'wp_ajax_plnt_express_import_attributes', 'plnt_express_product_import_attributes' );

function plnt_express_product_import_attributes(): void {
    if ( ! current_user_can( 'edit_products' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    check_ajax_referer( 'plnt_express_product', 'nonce' );

    $ids = isset( $_POST['categories'] ) && is_array( $_POST['categories'] )
        ? array_map( 'absint', $_POST['categories'] )
        : array();
    $category_ids = array();

    foreach ( $ids as $id ) {
        $term = get_term( $id, 'product_cat' );

        if ( ! $term || is_wp_error( $term ) ) {
            continue;
        }

        $category_ids[] = $id;
        $category_ids   = array_merge( $category_ids, array_map(
            'intval', get_ancestors( $id, 'product_cat', 'taxonomy' )
        ) );
    }

    $configured = array();

    foreach ( array_unique( $category_ids ) as $category_id ) {
        $rows = get_term_meta( $category_id, 'plnt_plant_category_attributes', true );

        foreach ( is_array( $rows ) ? $rows : array() as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }

            $taxonomy = sanitize_text_field( (string) ( $row['taxonomy'] ?? '' ) );
            $term_id  = absint( $row['term_id'] ?? 0 );

            if ( ! plnt_express_product_allowed_taxonomy( $taxonomy )
                || in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true ) ) {
                continue;
            }

            $term = $term_id ? get_term( $term_id, $taxonomy ) : null;

            if ( $term && ! is_wp_error( $term ) ) {
                $configured[ $taxonomy ][ $term_id ] = array(
                    'id'   => $term_id,
                    'text' => $term->name,
                );
            }
        }
    }

    $attributes = array();

    foreach ( $configured as $taxonomy => $terms ) {
        $attributes[] = array(
            'taxonomy' => $taxonomy,
            'label'    => wc_attribute_label( $taxonomy ),
            'terms'    => array_values( $terms ),
        );
    }

    wp_send_json_success( array( 'attributes' => $attributes ) );
}

/** Разрешить только реально существующие термины заданной таксономии. */
function plnt_express_product_term_ids( $raw, string $taxonomy ): array {
    $result = array();

    foreach ( is_array( $raw ) ? $raw : array() as $id ) {
        $id   = absint( $id );
        $term = $id ? get_term( $id, $taxonomy ) : null;

        if ( $term && ! is_wp_error( $term ) ) {
            $result[ $id ] = $id;
        }
    }

    return array_values( $result );
}

/** Последний по дате создания товар с заполненным артикулом (включая «Личное»). */
add_action( 'wp_ajax_plnt_express_last_sku', 'plnt_express_product_last_sku_ajax' );

function plnt_express_product_last_sku_ajax(): void {
    if ( ! current_user_can( 'edit_products' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    check_ajax_referer( 'plnt_express_product', 'nonce' );

    $ids = get_posts( array(
        'post_type'           => 'product',
        'post_status'         => array( 'publish', 'private', 'draft', 'pending', 'future' ),
        'posts_per_page'      => 1,
        'fields'              => 'ids',
        'orderby'             => array( 'date' => 'DESC', 'ID' => 'DESC' ),
        'suppress_filters'    => false,
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
        'meta_query'          => array(
            array(
                'key'     => '_sku',
                'value'   => '',
                'compare' => '!=',
            ),
        ),
    ) );

    $product = $ids ? wc_get_product( (int) $ids[0] ) : false;

    wp_send_json_success( array(
        'sku'  => $product ? (string) $product->get_sku() : '',
        'name' => $product ? (string) $product->get_name() : '',
    ) );
}

/** Сохранение экспресс-товара; стандартные сохранения других товаров не меняем. */
add_action( 'wp_ajax_plnt_express_create_product', 'plnt_express_product_create_ajax' );

function plnt_express_product_create_ajax(): void {
    if ( ! current_user_can( 'edit_products' ) || ! class_exists( 'WC_Product_Simple' ) ) {
        wp_send_json_error( array( 'message' => 'Нет доступа к созданию товаров.' ), 403 );
    }

    check_ajax_referer( 'plnt_express_product', 'nonce' );

    $name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    $slug = isset( $_POST['slug'] ) ? sanitize_title( wp_unslash( $_POST['slug'] ) ) : '';
    $sku  = isset( $_POST['sku'] ) ? wc_clean( wp_unslash( $_POST['sku'] ) ) : '';
    $price_raw = isset( $_POST['price'] ) ? wc_format_decimal( wp_unslash( $_POST['price'] ) ) : '';
    $stock_raw = isset( $_POST['stock'] ) && is_scalar( $_POST['stock'] )
        ? trim( (string) wp_unslash( $_POST['stock'] ) )
        : '';
    $stock_quantity = null;

    if ( '' === $name || '' === $price_raw || ! is_numeric( $price_raw ) || (float) $price_raw < 0 ) {
        wp_send_json_error( array( 'message' => 'Заполните название и корректную цену продажи.' ), 400 );
    }

    if ( '' !== $stock_raw ) {
        $stock_quantity = filter_var( $stock_raw, FILTER_VALIDATE_INT, array(
            'options' => array( 'min_range' => 0 ),
        ) );

        if ( false === $stock_quantity ) {
            wp_send_json_error( array( 'message' => 'Количество должно быть целым неотрицательным числом.' ), 400 );
        }
    }

    if ( '' !== $sku && wc_get_product_id_by_sku( $sku ) ) {
        wp_send_json_error( array( 'message' => 'Товар с таким артикулом уже существует.' ), 409 );
    }

    $categories = plnt_express_product_term_ids( $_POST['categories'] ?? array(), 'product_cat' );
    $tags       = plnt_express_product_term_ids( $_POST['tags'] ?? array(), 'product_tag' );

    if ( ! $categories ) {
        wp_send_json_error( array( 'message' => 'Выберите хотя бы одну категорию товара.' ), 400 );
    }

    $raw_attributes = isset( $_POST['attributes'] ) && is_string( $_POST['attributes'] )
        ? json_decode( wp_unslash( $_POST['attributes'] ), true )
        : array();
    $attributes = array();

    foreach ( is_array( $raw_attributes ) ? $raw_attributes : array() as $taxonomy => $term_ids ) {
        $taxonomy = sanitize_text_field( (string) $taxonomy );

        if ( ! taxonomy_exists( $taxonomy ) || ! wc_attribute_taxonomy_id_by_name( $taxonomy ) ) {
            continue;
        }

        $terms = plnt_express_product_term_ids( $term_ids, $taxonomy );

        if ( ! $terms ) {
            continue;
        }

        $attribute = new WC_Product_Attribute();
        $attribute->set_id( (int) wc_attribute_taxonomy_id_by_name( $taxonomy ) );
        $attribute->set_name( $taxonomy );
        $attribute->set_options( $terms );
        $attribute->set_visible( true );
        $attribute->set_variation( false );
        $attribute->set_position( count( $attributes ) );
        $attributes[] = $attribute;
    }

    $product = new WC_Product_Simple();

    try {
        $product->set_name( $name );
        $product->set_status( 'private' );
        $product->set_slug( '' !== $slug ? $slug : sanitize_title( $name ) );
        $product->set_regular_price( $price_raw );
        $product->set_price( $price_raw );
        $product->set_sku( $sku );
        $product->set_category_ids( $categories );
        $product->set_tag_ids( $tags );
        $product->set_attributes( $attributes );

        // Пустое поле не меняет стандартные настройки наличия нового товара.
        if ( null !== $stock_quantity ) {
            $product->set_manage_stock( true );
            $product->set_stock_quantity( $stock_quantity );
            $product->set_stock_status( $stock_quantity > 0 ? 'instock' : 'outofstock' );
        }

        $product_id = (int) $product->save();
    } catch ( Throwable $error ) {
        wp_send_json_error( array( 'message' => 'Не удалось создать товар: ' . $error->getMessage() ), 400 );
    }

    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => 'WooCommerce не вернул ID созданного товара.' ), 500 );
    }

    $is_plant = function_exists( 'plnt_plant_cross_sell_is_plant' )
        && plnt_plant_cross_sell_is_plant( $product_id );

    if ( $is_plant ) {
        // Добавление нового растения в кроссы выполняем только один раз.
        if ( function_exists( 'plnt_plant_cross_sell_add_product' ) ) {
            plnt_plant_cross_sell_add_product( $product_id );
            unset( $GLOBALS['plnt_plant_cross_sell_add_queue'][ $product_id ] );
        }

        // Это действие только для экспресс-создания. Обычный «Обновить» не запускает апсейлы.
        if ( function_exists( 'plnt_plant_upsell_update_product' ) ) {
            plnt_plant_upsell_update_product( $product_id );
        }
    }

    wp_send_json_success( array(
        'id'       => $product_id,
        'editUrl'  => get_edit_post_link( $product_id, '' ),
        'isPlant'  => $is_plant,
        'message'  => $is_plant
            ? 'Товар создан. Кросс-сейлы и апсейлы обработаны.'
            : 'Товар создан. Связи растений не запускались.',
    ) );
}
