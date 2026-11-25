<?php
/** Независимый ручной пересчёт кросс-сейлов и апсейлов из карточки товара. */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'add_meta_boxes_product', 'plnt_plant_links_add_product_meta_box' );

function plnt_plant_links_add_product_meta_box( WP_Post $post ): void {
    if ( ! current_user_can( 'edit_post', $post->ID )
        || ! plnt_plant_cross_sell_is_plant( (int) $post->ID ) ) {
        return;
    }

    add_meta_box(
        'plnt-plant-links-product',
        'Авто связи',
        'plnt_plant_links_render_product_meta_box',
        'product',
        'side',
        'default'
    );
}

function plnt_plant_links_render_product_meta_box( WP_Post $post ): void {
    $product_id = (int) $post->ID;

    if ( ! in_array( $post->post_status, array( 'publish', 'private' ), true ) ) {
        echo '<p>Пересчёт доступен после публикации товара или установки статуса «Личное».</p>';
        return;
    }
    ?>
    <p>Обновить связи только этого растения по сохранённым группам.</p>
    <p><button type="button" class="button plnt-plant-links-recalculate" data-kind="cross"
        data-product-id="<?php echo esc_attr( $product_id ); ?>"
        data-nonce="<?php echo esc_attr( wp_create_nonce( 'plnt_plant_links_recalculate_' . $product_id ) ); ?>">
        Пересчитать кросс-сейлы
    </button></p>
    <p class="description" id="plnt-plant-links-cross-result" role="status" aria-live="polite"></p>
    <p><button type="button" class="button plnt-plant-links-recalculate" data-kind="upsell"
        data-product-id="<?php echo esc_attr( $product_id ); ?>"
        data-nonce="<?php echo esc_attr( wp_create_nonce( 'plnt_plant_links_recalculate_' . $product_id ) ); ?>">
        Пересчитать апсейлы
    </button></p>
    <p class="description" id="plnt-plant-links-upsell-result" role="status" aria-live="polite"></p>
    <p><button type="button" class="button plnt-plant-links-recalculate" data-kind="attributes"
        data-product-id="<?php echo esc_attr( $product_id ); ?>"
        data-nonce="<?php echo esc_attr( wp_create_nonce( 'plnt_plant_links_recalculate_' . $product_id ) ); ?>">
        Обновить атрибуты из категорий
    </button></p>
    <p class="description" id="plnt-plant-links-attributes-result" role="status" aria-live="polite"></p>
    <p class="description">Учитываются только сохранённые категории и значения атрибута. Несохранённые изменения сначала сохраните.</p>
    <?php
}

add_action( 'admin_enqueue_scripts', 'plnt_plant_links_enqueue_product_script' );

function plnt_plant_links_enqueue_product_script( string $hook ): void {
    if ( 'post.php' !== $hook ) {
        return;
    }

    $screen = get_current_screen();

    if ( ! $screen || 'product' !== $screen->id ) {
        return;
    }

    $script = 'assets/js/product-plant-autofill.js';

    wp_enqueue_script(
        'plnt-plant-links-product',
        get_template_directory_uri() . '/' . $script,
        array( 'jquery' ),
        filemtime( get_template_directory() . '/' . $script ),
        true
    );
}

add_action( 'wp_ajax_plnt_plant_links_recalculate_product', 'plnt_plant_links_recalculate_product_ajax' );

function plnt_plant_links_recalculate_product_ajax(): void {
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

    if ( ! $product_id || 'product' !== get_post_type( $product_id )
        || ! current_user_can( 'edit_post', $product_id ) ) {
        wp_send_json_error( array( 'message' => 'Нет доступа к товару.' ), 403 );
    }

    check_ajax_referer( 'plnt_plant_links_recalculate_' . $product_id, 'nonce' );

    if ( ! in_array( get_post_status( $product_id ), array( 'publish', 'private' ), true )
        || ! plnt_plant_cross_sell_is_plant( $product_id ) ) {
        wp_send_json_error( array( 'message' => 'Пересчёт доступен только для опубликованных и личных комнатных растений.' ), 400 );
    }

    $kind = isset( $_POST['kind'] ) ? sanitize_key( wp_unslash( $_POST['kind'] ) ) : '';

    if ( ! in_array( $kind, array( 'cross', 'upsell', 'attributes' ), true ) ) {
        wp_send_json_error( array( 'message' => 'Неизвестный тип пересчёта.' ), 400 );
    }

    if ( 'attributes' === $kind ) {
        $result = plnt_plant_links_update_category_attributes( $product_id );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ), 500 );
        }

        wp_send_json_success( array( 'message' => $result ) );
    }

    if ( 'cross' === $kind ) {
        // Без группы не меняем существующие кросс-сейлы. Другие растения не трогаем.
        $group_id = plnt_plant_cross_sell_product_group_id( $product_id );

        if ( null === $group_id ) {
            wp_send_json_success( array( 'message' => 'Кросс-сейлы сохранены без изменений: нет подходящей группы.' ) );
        }

        $cross_ids = plnt_plant_cross_sell_group_product_ids( $group_id );

        if ( ! plnt_plant_cross_sell_update_group_product( $product_id, $cross_ids ) ) {
            wp_send_json_error( array( 'message' => 'Не удалось обновить кросс-сейлы товара.' ), 500 );
        }

        wp_send_json_success( array( 'message' => 'Кросс-сейлы пересчитаны.' ) );
    }

    // Пересчёт апсейлов не затрагивает кроссы.
    $taxonomy = plnt_plant_upsell_attribute_taxonomy();
    $has_upsell_group = false;

    if ( $taxonomy ) {
        foreach ( plnt_plant_upsell_groups() as $group ) {
            $term_id = absint( $group['term_id'] ?? 0 );

            if ( $term_id && has_term( $term_id, $taxonomy, $product_id ) ) {
                $has_upsell_group = true;
                break;
            }
        }
    }

    if ( ! $has_upsell_group ) {
        wp_send_json_success( array( 'message' => 'Апсейлы сохранены без изменений: нет подходящей группы.' ) );
    }

    if ( ! plnt_plant_upsell_update_product( $product_id ) ) {
        wp_send_json_error( array( 'message' => 'Не удалось обновить апсейлы товара.' ), 500 );
    }

    wp_send_json_success( array( 'message' => 'Апсейлы пересчитаны.' ) );
}

/** Подтянуть в растение только те атрибуты, которые настроены у его категорий. */
function plnt_plant_links_update_category_attributes( int $product_id ) {
    $product = wc_get_product( $product_id );

    if ( ! $product || ! class_exists( 'WC_Product_Attribute' ) ) {
        return new WP_Error( 'no_product', 'Товар не найден.' );
    }

    // Учитываем назначенные категории и их родителей.
    $category_ids = array();

    foreach ( $product->get_category_ids( 'edit' ) as $category_id ) {
        $category_ids[] = (int) $category_id;
        $category_ids = array_merge( $category_ids, array_map(
            'intval', get_ancestors( (int) $category_id, 'product_cat', 'taxonomy' )
        ) );
    }

    $configured = array();

    foreach ( array_unique( $category_ids ) as $category_id ) {
        $rows = get_term_meta( $category_id, 'plnt_plant_category_attributes', true );

        foreach ( is_array( $rows ) ? $rows : array() as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }

            $taxonomy = (string) ( $row['taxonomy'] ?? '' );
            $term_id  = absint( $row['term_id'] ?? 0 );

            if ( ! $term_id || ! taxonomy_exists( $taxonomy )
                || ! wc_attribute_taxonomy_id_by_name( $taxonomy ) ) {
                continue;
            }

            $term = get_term( $term_id, $taxonomy );

            if ( $term && ! is_wp_error( $term ) ) {
                $configured[ $taxonomy ][ $term_id ] = $term_id;
            }
        }
    }

    if ( ! $configured ) {
        return 'В категориях нет настроенных атрибутов. Товар не изменён.';
    }

    $attributes = $product->get_attributes( 'edit' );
    $added      = 0;
    $updated    = 0;

    foreach ( $configured as $taxonomy => $term_ids ) {
        $new_ids = array_values( $term_ids );
        $key     = null;

        // Ключ массива WooCommerce может отличаться от имени таксономии.
        foreach ( $attributes as $attribute_key => $attribute ) {
            if ( $attribute instanceof WC_Product_Attribute && $attribute->get_name() === $taxonomy ) {
                $key = $attribute_key;
                break;
            }
        }

        if ( null !== $key ) {
            $old_ids = array_map( 'intval', $attributes[ $key ]->get_options() );
            $old_sorted = $old_ids;
            $new_sorted = $new_ids;
            sort( $old_sorted );
            sort( $new_sorted );

            if ( $old_sorted === $new_sorted ) {
                continue;
            }

            $attribute = clone $attributes[ $key ];
            $attribute->set_options( $new_ids );
            $attributes[ $key ] = $attribute;
            ++$updated;
        } else {
            $attribute = new WC_Product_Attribute();
            $attribute->set_id( (int) wc_attribute_taxonomy_id_by_name( $taxonomy ) );
            $attribute->set_name( $taxonomy );
            $attribute->set_options( $new_ids );
            $attribute->set_visible( true );
            $attribute->set_variation( false );
            $attribute->set_position( count( $attributes ) );
            $attributes[] = $attribute;
            ++$added;
        }
    }

    if ( $added || $updated ) {
        // Не запускаем устаревшие обработчики автоматического обновления апсейлов.
        $was_writing = ! empty( $GLOBALS['plnt_plant_upsell_is_writing'] );
        $GLOBALS['plnt_plant_upsell_is_writing'] = true;

        try {
            $product->set_attributes( $attributes );
            $product->save();
        } finally {
            if ( ! $was_writing ) {
                unset( $GLOBALS['plnt_plant_upsell_is_writing'] );
            }
        }
    }

    return sprintf( 'Атрибуты обновлены: добавлено %d, изменено %d.', $added, $updated );
}
