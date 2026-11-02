
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Есть ли в заказе комнатные растения.
 */
// function plnt_admin_order_has_plants( $order ) {
//     global $plants_cat_id;

//     $plant_term = get_term_by(
//         'slug',
//         'komnatnye-rasteniya',
//         'product_cat'
//     );

//     foreach ( $order->get_items( 'line_item' ) as $item ) {
//         $product = $item->get_product();

//         if ( ! $product ) {
//             continue;
//         }

//         // Используем существующую функцию сайта.
//         if (
//             function_exists( 'check_category' )
//             && ! empty( $plants_cat_id )
//             && (int) check_category( $product ) === (int) $plants_cat_id
//         ) {
//             return true;
//         }

//         // Резервная проверка по категории и её потомкам.
//         if ( ! $plant_term ) {
//             continue;
//         }

//         $product_id = $product->get_parent_id() ?: $product->get_id();

//         $categories = wp_get_post_terms(
//             $product_id,
//             'product_cat',
//             array( 'fields' => 'ids' )
//         );

//         if ( is_wp_error( $categories ) ) {
//             continue;
//         }

//         foreach ( $categories as $category_id ) {
//             if (
//                 (int) $category_id === (int) $plant_term->term_id
//                 || in_array(
//                     (int) $plant_term->term_id,
//                     get_ancestors( $category_id, 'product_cat', 'taxonomy' ),
//                     true
//                 )
//             ) {
//                 return true;
//             }
//         }
//     }

//     return false;
// }

/**
 * Получить доступные пересадки из cross-sell товара.
 */
function plnt_admin_get_peresadka_products( $product ) {
    if ( ! $product ) {
        return array();
    }

    $ids = $product->get_cross_sell_ids();

    if ( empty( $ids ) ) {
        return array();
    }

    $result = array();

    foreach ( $ids as $id ) {
        if ( ! has_term( 'peresadka', 'product_cat', $id ) ) {
            continue;
        }

        $peresadka = wc_get_product( $id );

        if (
            ! $peresadka
            || ! $peresadka->is_type( 'simple' )
            || 'publish' !== $peresadka->get_status()
            || ! $peresadka->is_in_stock()
            || ! $peresadka->is_purchasable()
        ) {
            continue;
        }

        $result[] = $peresadka;
    }

    return $result;
}

/**
 * Проверяем, добавлена ли пересадка к позиции заказа.
 */
function plnt_admin_order_has_peresadka( $order, $parent_item_id ) {
    foreach ( $order->get_items( 'line_item' ) as $item ) {
        if (
            (int) $item->get_meta( '_plnt_peresadka_parent_item_id', true )
            === (int) $parent_item_id
        ) {
            return true;
        }
    }

    return false;
}

/**
 * Кнопка пересадки под товаром в заказе.
 */
add_action(
    'woocommerce_after_order_itemmeta',
    'plnt_admin_render_peresadka_button',
    20,
    3
);

function plnt_admin_render_peresadka_button( $item_id, $item, $product ) {
    if (
        ! $item instanceof WC_Order_Item_Product
        || ! $product
        || ! current_user_can( 'edit_shop_orders' )
    ) {
        return;
    }

    // Для самой услуги пересадки кнопку не показываем.
    if ( has_term( 'peresadka', 'product_cat', $product->get_id() ) ) {
        return;
    }

    $order = wc_get_order( $item->get_order_id() );

    if ( ! $order ) {
        return;
    }

    if ( $order->has_status( array( 'cancelled', 'refunded', 'failed' ) ) ) {
        return;
    }

    $peresadki = plnt_admin_get_peresadka_products( $product );

    if ( empty( $peresadki ) ) {
        return;
    }

    // Если вариантов несколько, выбираем один, как в корзине.
    $peresadka = $peresadki[ array_rand( $peresadki ) ];

    if ( plnt_admin_order_has_peresadka( $order, $item_id ) ) {
        echo '<div class="plnt-admin-peresadka">';
        echo '<span class="plnt-admin-peresadka-added">✓ Пересадка добавлена</span>';
        echo '</div>';
        return;
    }

    ?>
    <div class="plnt-admin-peresadka">
        <button
            type="button"
            class="button button-small plnt-admin-add-peresadka"
            data-order-id="<?php echo esc_attr( $order->get_id() ); ?>"
            data-item-id="<?php echo esc_attr( $item_id ); ?>"
            data-peresadka-id="<?php echo esc_attr( $peresadka->get_id() ); ?>"
            data-nonce="<?php echo esc_attr( wp_create_nonce( 'plnt_add_peresadka_' . $order->get_id() ) ); ?>"
        >
            Добавить пересадку — <?php echo wp_kses_post( wc_price( $peresadka->get_price(), array( 'currency' => $order->get_currency() ) ) ); ?>
        </button>
    </div>
    <?php
}

/**
 * AJAX: добавление пересадки в заказ.
 */
add_action(
    'wp_ajax_plnt_admin_add_peresadka',
    'plnt_admin_ajax_add_peresadka'
);

function plnt_admin_ajax_add_peresadka() {
    $order_id     = absint( $_POST['order_id'] ?? 0 );
    $item_id      = absint( $_POST['item_id'] ?? 0 );
    $peresadka_id = absint( $_POST['peresadka_id'] ?? 0 );

    check_ajax_referer(
        'plnt_add_peresadka_' . $order_id,
        'security'
    );

    if ( ! current_user_can( 'edit_shop_orders' ) ) {
        wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
    }

    $order = wc_get_order( $order_id );

    if ( ! $order ) {
        wp_send_json_error( array( 'message' => 'Заказ не найден.' ), 404 );
    }

    if ( $order->has_status( array( 'cancelled', 'refunded', 'failed' ) ) ) {
        wp_send_json_error( array( 'message' => 'Для этого заказа добавление недоступно.' ) );
    }

    $parent_item = $order->get_item( $item_id );

    if ( ! $parent_item instanceof WC_Order_Item_Product ) {
        wp_send_json_error( array( 'message' => 'Позиция заказа не найдена.' ) );
    }

    $parent_product = $parent_item->get_product();

    if ( ! $parent_product ) {
        wp_send_json_error( array( 'message' => 'Товар не найден.' ) );
    }

    if ( plnt_admin_order_has_peresadka( $order, $item_id ) ) {
        wp_send_json_error( array( 'message' => 'Пересадка уже добавлена.' ) );
    }

    // Повторно проверяем cross-sell и доступность услуги.
    $available = plnt_admin_get_peresadka_products( $parent_product );
    $peresadka = null;

    foreach ( $available as $candidate ) {
        if ( $candidate->get_id() === $peresadka_id ) {
            $peresadka = $candidate;
            break;
        }
    }

    if ( ! $peresadka ) {
        wp_send_json_error( array( 'message' => 'Услуга пересадки недоступна.' ) );
    }

    try {
        // Добавляем одну услугу пересадки.
        $new_item_id = $order->add_product( $peresadka, 1 );

        if ( ! $new_item_id || is_wp_error( $new_item_id ) ) {
            throw new Exception( 'Не удалось добавить пересадку.' );
        }

        // Сохраняем связь с исходной позицией заказа.
        $new_item = $order->get_item( $new_item_id );

        if ( ! $new_item ) {
            throw new Exception( 'Не удалось получить позицию пересадки.' );
        }

        $new_item->update_meta_data(
            '_plnt_peresadka_parent_item_id',
            $item_id
        );

        $new_item->save();

        // Пересчитываем стоимость заказа.
        $order->calculate_totals();

        $order->add_order_note(
            sprintf(
                'Добавлена пересадка «%s» для товара «%s».',
                $peresadka->get_name(),
                $parent_item->get_name()
            )
        );

        wp_send_json_success(
            array( 'message' => 'Пересадка добавлена.' )
        );

    } catch ( Throwable $e ) {
        wp_send_json_error(
            array( 'message' => $e->getMessage() )
        );
    }
}

/**
 * JS и стили для страницы заказа.
 */
add_action( 'admin_footer', 'plnt_admin_peresadka_assets' );

function plnt_admin_peresadka_assets() {
    $screen = get_current_screen();

    if (
        ! $screen
        || ! in_array(
            $screen->id,
            array(
                'shop_order',
                'woocommerce_page_wc-orders',
                'admin_page_wc-orders',
            ),
            true
        )
    ) {
        return;
    }
    ?>
    <style>
        .plnt-admin-peresadka {
            margin-top: 10px;
        }

        .plnt-admin-peresadka .button {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            height: auto;
            min-height: 28px;
            white-space: normal;
        }

        .plnt-admin-peresadka-added {
            color: #008a20;
            font-size: 12px;
        }
    </style>

    <script>
    jQuery(function($) {
        $(document).on('click', '.plnt-admin-add-peresadka', function(e) {
            e.preventDefault();

            const $button = $(this);

            if ($button.prop('disabled')) {
                return;
            }

            if ($('.woocommerce_order_items_wrapper .edit:visible').length) {
                alert('Сначала сохраните изменения позиций заказа.');
                return;
            }

            const originalText = $button.html();

            $button.prop('disabled', true).text('Добавление...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'plnt_admin_add_peresadka',
                    security: $button.attr('data-nonce'),
                    order_id: $button.attr('data-order-id'),
                    item_id: $button.attr('data-item-id'),
                    peresadka_id: $button.attr('data-peresadka-id')
                }
            })
            .done(function(response) {
                if (response && response.success) {
                    window.location.reload();
                    return;
                }

                alert(
                    response && response.data && response.data.message
                        ? response.data.message
                        : 'Не удалось добавить пересадку.'
                );
            })
            .fail(function() {
                alert('Ошибка при добавлении пересадки.');
            })
            .always(function() {
                $button.prop('disabled', false).html(originalText);
            });
        });
    });
    </script>
    <?php
}
