<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Проверяет принадлежность товара к категории «Пересадка» (включая дочерние).
 */
function plnt_admin_is_peresadka_product( $product_id ) {
    $product_id = absint( $product_id );

    if ( ! $product_id ) {
        return false;
    }

    $term = get_term_by( 'slug', 'peresadka', 'product_cat' );

    if ( ! $term || is_wp_error( $term ) ) {
        return false;
    }

    $term_ids = wp_get_post_terms(
        $product_id,
        'product_cat',
        array( 'fields' => 'ids' )
    );

    if ( is_wp_error( $term_ids ) ) {
        return false;
    }

    foreach ( $term_ids as $term_id ) {
        if ( (int) $term_id === (int) $term->term_id ) {
            return true;
        }

        $ancestors = array_map(
            'intval',
            get_ancestors( (int) $term_id, 'product_cat', 'taxonomy' )
        );

        if ( in_array( (int) $term->term_id, $ancestors, true ) ) {
            return true;
        }
    }

    return false;
}

/**
 * Подходящие услуги пересадки из cross-sell текущего товара.
 */
function plnt_admin_get_peresadka_products( $product ) {
    if ( ! $product instanceof WC_Product ) {
        return array();
    }

    $result = array();

    foreach ( $product->get_cross_sell_ids() as $crosssell_id ) {
        if ( ! plnt_admin_is_peresadka_product( $crosssell_id ) ) {
            continue;
        }

        $peresadka = wc_get_product( $crosssell_id );

        if (
            ! $peresadka
            || ! $peresadka->is_type( 'simple' )
            || 'publish' !== $peresadka->get_status()
            || ! $peresadka->is_in_stock()
            || '' === $peresadka->get_price()
        ) {
            continue;
        }

        $result[] = $peresadka;
    }

    return $result;
}

/**
 * Кнопка под товаром в позиции заказа.
 * Никакой связи с исходной позицией заказа не сохраняем.
 */
add_action( 'woocommerce_after_order_itemmeta', 'plnt_admin_render_peresadka_button', 20, 3 );

function plnt_admin_render_peresadka_button( $item_id, $item, $product ) {
    if (
        ! $item instanceof WC_Order_Item_Product
        || ! $product instanceof WC_Product
        || ! current_user_can( 'edit_shop_orders' )
    ) {
        return;
    }

    if ( plnt_admin_is_peresadka_product( $product->get_id() ) ) {
        return;
    }

    $peresadki = plnt_admin_get_peresadka_products( $product );

    if ( ! $peresadki ) {
        return;
    }

    // Одна случайная услуга, как в шаблоне корзины.
    $peresadka = $peresadki[ array_rand( $peresadki ) ];
    $order_id  = $item->get_order_id();
    $order     = $order_id ? wc_get_order( $order_id ) : false;
    $args      = $order ? array( 'currency' => $order->get_currency() ) : array();
    ?>
    <div class="plnt-admin-peresadka">
        <button
            type="button"
            class="button button-small plnt-admin-add-peresadka"
            data-peresadka-id="<?php echo esc_attr( $peresadka->get_id() ); ?>"
        >
            Добавить пересадку — <?php echo wp_kses_post( wc_price( $peresadka->get_price(), $args ) ); ?>
        </button>
    </div>
    <?php
}

/**
 * После добавления нашей услуги обновить итог заказа.
 * Используем штатный WooCommerce AJAX: он работает и с auto-draft заказами.
 */
add_action( 'woocommerce_ajax_order_items_added', 'plnt_admin_recalculate_peresadka_order', 20, 2 );

function plnt_admin_recalculate_peresadka_order( $added_items, $order ) {
    // Признак ставится только нашей кнопкой; остальные добавления не трогаем.
    if ( ! isset( $_POST['plnt_admin_peresadka'] ) || '1' !== (string) wp_unslash( $_POST['plnt_admin_peresadka'] ) ) {
        return;
    }

    if ( ! $order instanceof WC_Order || empty( $added_items ) ) {
        return;
    }

    // Суммы пересчитаются без принудительного изменения налогов и доставки.
    $order->calculate_totals( false );
}

/**
 * Скрипт на страницах создания и редактирования заказов (включая HPOS).
 */
add_action( 'admin_footer', 'plnt_admin_peresadka_assets' );

function plnt_admin_peresadka_assets() {
    $screen = get_current_screen();

    if (
        ! $screen
        || ! in_array(
            $screen->id,
            array( 'shop_order', 'woocommerce_page_wc-orders', 'admin_page_wc-orders' ),
            true
        )
    ) {
        return;
    }
    ?>
    <style>
        .plnt-admin-peresadka { margin-top: 10px; }
        .plnt-admin-peresadka .button { height: auto; min-height: 28px; white-space: normal; }
    </style>
    <script>
    jQuery(function($) {
        $(document).on('click', '.plnt-admin-add-peresadka', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $panel = $('#woocommerce-order-items');
            const settings = window.woocommerce_admin_meta_boxes;
            const productId = parseInt($button.attr('data-peresadka-id'), 10);
            const orderId = parseInt(settings && settings.post_id, 10);

            if ($button.prop('disabled')) {
                return;
            }

            if (!settings || !settings.order_item_nonce || !orderId || !productId) {
                window.alert('Не удалось определить заказ. Обновите страницу.');
                return;
            }

            const originalText = $button.html();
            $button.prop('disabled', true).text('Добавление...');
            $panel.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

            const data = {
                action: 'woocommerce_add_order_item',
                order_id: orderId,
                security: settings.order_item_nonce,
                plnt_admin_peresadka: '1',
                data: [{ id: productId, qty: 1 }]
            };

            // Как у штатной кнопки добавления товара: сохраняем незавершённые
            // правки количества/цен, чтобы не потерять их при обновлении списка.
            if ($panel.find('button.cancel-action[data-reload="true"]').length) {
                data.items = $('table.woocommerce_order_items :input[name], .wc-order-totals-items :input[name]').serialize();
            }

            $.ajax({
                url: settings.ajax_url || ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: data
            })
            .done(function(response) {
                if (!response || !response.success || !response.data || !response.data.html) {
                    const message = response && response.data && response.data.error
                        ? response.data.error
                        : 'Не удалось добавить пересадку.';
                    window.alert(message);
                    return;
                }

                // Без перезагрузки страницы: иначе новый auto-draft заказ
                // может открыться как другая новая форма.
                $panel.find('.inside').empty().append(response.data.html);
                $panel.trigger('wc_order_items_reloaded');

                // Обновляем историю заказа, если WooCommerce вернул её разметку.
                if (response.data.notes_html && $('#woocommerce-order-notes ul.order_notes').length) {
                    $('#woocommerce-order-notes ul.order_notes').replaceWith(response.data.notes_html);
                }
            })
            .fail(function(xhr) {
                const message = xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.error
                    ? xhr.responseJSON.data.error
                    : 'Ошибка AJAX при добавлении пересадки.';
                window.alert(message);
            })
            .always(function() {
                $panel.unblock();
                $button.prop('disabled', false).html(originalText);
            });
        });
    });
    </script>
    <?php
}
