<?php
/**
 * PLANTIS — описание объявления Авито.
 * ACF Flexible Content: text / delivery / product_description.
 * Доставка и характеристики генерируются PHP, текстовые блоки редактируются в ACF.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const PLNT_AVITO_DESCRIPTION_POST_ID = 'plnt_avito_description';
const PLNT_AVITO_DESCRIPTION_FIELD   = 'plnt_avito_description_blocks';

// add_action( 'acf/init', 'plnt_avito_register_description_options' );
// function plnt_avito_register_description_options(): void {
//     if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
//         return;
//     }

//     acf_add_options_sub_page( [
//         'page_title'    => 'Авито — описание объявлений',
//         'menu_title'    => 'Авито — описание',
//         'menu_slug'     => 'plnt-avito-description',
//         'parent_slug'   => 'woocommerce',
//         'capability'    => 'manage_woocommerce',
//         'post_id'       => PLNT_AVITO_DESCRIPTION_POST_ID,
//         'autoload'      => false,
//         'update_button'=> 'Сохранить описание',
//     ] );
// }

/**
 * Первоначальные блоки для новой установки. Все произвольные тексты доступны
 * для редактирования в «Свободном тексте», доставка и товар рассчитываются PHP.
 */
// function plnt_avito_default_description_blocks(): array {
//     return [
//         [
//             'acf_fc_layout' => 'text',
//             'content' => '<p>📸 Пришлём актуальные фотографии растения перед покупкой.</p>'
//                 . '<p>✅ Бесплатно пересадим растение при одновременной покупке керамического горшка диаметром до 26 см.</p>'
//                 . '<p>🎓 Расскажем об уходе за растением и поможем после покупки.</p>'
//                 . '<p>🌴 В нашем профиле ещё больше комнатных растений!</p>',
//         ],
//         [ 'acf_fc_layout' => 'delivery' ],
//         [
//             'acf_fc_layout' => 'text',
//             'content' => '<p><strong>📍 САМОВЫВОЗ</strong></p>'
//                 . '<p>Бесплатный самовывоз из нашего шоурума:</p>'
//                 . '<p>Москва, ул. Мещерякова, д. 3 (м. Тушинская или м. Сокол).</p>'
//                 . '<p>Ежедневно с 10:00 до 20:00.</p>'
//                 . '<p>❄️ Зимой бесплатно утеплим растение при самовывозе, если вы не на машине.</p>'
//                 . '<p><strong>💳 СПОСОБЫ ОПЛАТЫ</strong></p>'
//                 . '<ul><li>Банковской картой</li><li>Переводом</li><li>Наличными при самовывозе</li><li>По счёту</li></ul>'
//                 . '<p>Работаем с физическими и юридическими лицами.</p>'
//                 . '<p><strong>📌 РЕЗЕРВ И ПЕРЕСАДКА</strong></p>'
//                 . '<p>Бесплатно зарезервируем растение на срок до 48 часов без предварительной оплаты.</p>'
//                 . '<p>Бесплатная пересадка осуществляется при одновременной покупке растения и подходящего керамического горшка диаметром до 26 см.</p>',
//         ],
//         [ 'acf_fc_layout' => 'product_description' ],
//         [
//             'acf_fc_layout' => 'text',
//             'content' => '<p>🌿 <strong>PLANTIS — комнатные растения с заботой!</strong></p>',
//         ],
//     ];
// }

/** Переход со старых 10 макетов на 3 новых с сохранением общих текстов. */
// function plnt_avito_normalize_description_rows( array $rows ): array {
//     $out = [];
//     foreach ( $rows as $row ) {
//         if ( ! is_array( $row ) ) {
//             continue;
//         }
//         $type = (string) ( $row['acf_fc_layout'] ?? '' );
//         if ( in_array( $type, [ 'text', 'delivery', 'product_description' ], true ) ) {
//             $out[] = $row;
//             continue;
//         }
//         if ( array_key_exists( 'enabled', $row ) && ! $row['enabled'] ) {
//             continue;
//         }
//         // Старые автоматические интервалы, Почта и атрибуты теперь внутри PHP-блоков.
//         if ( in_array( $type, [ 'intervals', 'russian_mail', 'attributes' ], true ) ) {
//             continue;
//         }
//         if ( in_array( $type, [ 'advantages', 'pickup', 'payment', 'reserve', 'closing' ], true ) ) {
//             $heading = trim( (string) ( $row['title'] ?? '' ) );
//             $content = (string) ( $row['content'] ?? '' );
//             $html = ( $heading ? '<p><strong>' . esc_html( $heading ) . '</strong></p>' : '' ) . $content;
//             if ( '' !== trim( $html ) ) {
//                 $out[] = [ 'acf_fc_layout' => 'text', 'content' => $html ];
//             }
//         }
//     }
//     return $out;
// }

/**
 * Один раз создаём стартовую последовательность либо переносим старые блоки.
 * Не трогаем сохранённые пользователем новые блоки и их последовательность.
 */
// add_action( 'admin_init', 'plnt_avito_initialize_description_blocks' );
// function plnt_avito_initialize_description_blocks(): void {
//     if ( ! current_user_can( 'manage_woocommerce' )
//         || get_option( 'plnt_avito_description_v7_initialized', false )
//         || ! function_exists( 'acf_get_field' )
//         || ! function_exists( 'update_field' )
//         || ! acf_get_field( 'field_plnt_avito_description_blocks' )
//     ) {
//         return;
//     }

//     $existing = get_field( PLNT_AVITO_DESCRIPTION_FIELD, PLNT_AVITO_DESCRIPTION_POST_ID );
//     if ( is_array( $existing ) && $existing ) {
//         $rows = plnt_avito_normalize_description_rows( $existing );
//         $has_legacy = count( $existing ) !== count( $rows )
//             || array_values( array_column( $existing, 'acf_fc_layout' ) ) !== array_values( array_column( $rows, 'acf_fc_layout' ) );
//         if ( ! $has_legacy ) {
//             update_option( 'plnt_avito_description_v7_initialized', 1, false );
//             return;
//         }
//         // Перед обновлением сохранённого набора оставляем резервную копию.
//         update_option( 'plnt_avito_description_legacy_backup_v7', $existing, false );
//     } else {
//         $rows = plnt_avito_default_description_blocks();
//     }

//     $prepared = [];
//     foreach ( $rows as $row ) {
//         $item = [ 'acf_fc_layout' => $row['acf_fc_layout'] ];
//         if ( 'text' === $row['acf_fc_layout'] ) {
//             $item['field_plnt_avito_desc_free_text_content'] = (string) ( $row['content'] ?? '' );
//         }
//         $prepared[] = $item;
//     }
//     if ( update_field( 'field_plnt_avito_description_blocks', $prepared, PLNT_AVITO_DESCRIPTION_POST_ID ) ) {
//         update_option( 'plnt_avito_description_v7_initialized', 1, false );
//     }
// }

/** Только поддерживаемая HTML-разметка для Авито, без ссылок и медиа. */
function plnt_avito_description_html( string $html ): string {
    return trim( wp_kses( strip_shortcodes( $html ), [
        'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [],
        'ul' => [], 'ol' => [], 'li' => [],
    ] ) );
}

function plnt_avito_heading( string $heading ): string {
    return '<p><strong>' . esc_html( $heading ) . '</strong></p>';
}

function plnt_avito_number( $value ): float {
    $clean = preg_replace( '/[^\d,.-]/u', '', (string) $value );
    return (float) str_replace( ',', '.', $clean ?? '' );
}

function plnt_avito_money( float $value ): string {
    return number_format( max( 0, $value ), 0, ',', ' ' );
}

function plnt_avito_delivery_number( string $name ): float {
    return function_exists( 'plnt_delivery_option' )
        ? plnt_avito_number( plnt_delivery_option( $name ) )
        : 0;
}

/** Все видимые атрибуты WooCommerce, как в plnt_product_attributes(). */
function plnt_avito_get_product_attributes( WC_Product $product ): string {
    $items = [];
    foreach ( $product->get_attributes() as $attribute ) {
        if ( ! $attribute->get_visible() ) {
            continue;
        }
        $name = wc_attribute_label( $attribute->get_name() );
        $values = $attribute->is_taxonomy()
            ? wc_get_product_terms( $product->get_id(), $attribute->get_name(), [ 'fields' => 'names' ] )
            : $attribute->get_options();
        if ( is_wp_error( $values ) || ! is_array( $values ) ) {
            continue;
        }
        $values = array_filter( array_map( 'strval', $values ), 'strlen' );
        if ( $values ) {
            $items[] = '<li>' . esc_html( $name ) . ': ' . esc_html( implode( ', ', $values ) ) . '</li>';
        }
    }
    return $items ? '<ul>' . implode( '', $items ) . '</ul>' : '';
}

/**
 * Весь блок доставки; контент в PHP, а не в ACF. Цена одного растения
 * нужна для ориентировочного тарифа и определения наличия небольшого заказа.
 * Текст надбавки объясняет правило для суммы ВСЕГО заказа.
 */
function plnt_avito_get_delivery_description( WC_Product $product ): string {
    global $delivery_inMKAD, $delivery_outMKAD;

    $price = (float) $product->get_price();
    $large = (float) $product->get_weight() >= 11;
    $small = plnt_avito_delivery_number( 'min_small_delivery' );
    $medium = plnt_avito_delivery_number( 'min_medium_delivery' );
    $extra_small = plnt_avito_delivery_number( 'small_markup_delivery' );
    $extra_medium = plnt_avito_delivery_number( 'medium_markup_delivery' );
    $extra_by_price = 0;
    if ( $small > 0 && $price < $small ) {
        $extra_by_price = $extra_small;
    } elseif ( $medium > 0 && $price < $medium ) {
        $extra_by_price = $extra_medium;
    }

    $shipping = function_exists( 'plnt_get_shiping_costs' ) ? plnt_get_shiping_costs() : [];
    $rates_known = is_array( $shipping )
        && isset( $delivery_inMKAD, $delivery_outMKAD )
        && isset( $shipping[ $delivery_inMKAD ], $shipping[ $delivery_outMKAD ] );
    $within = $rates_known ? plnt_avito_number( $shipping[ $delivery_inMKAD ] ) : 0;
    $outside = $rates_known ? plnt_avito_number( $shipping[ $delivery_outMKAD ] ) : 0;
    $rates_known = $rates_known && $within > 0 && $outside > 0;

    $holiday = function_exists( 'plnt_delivery_option' )
        && (string) plnt_delivery_option( 'is_holiday_courier_tariff' ) === '1';
    $urgent_courier = function_exists( 'plnt_delivery_option' )
        && (string) plnt_delivery_option( 'is_urgent_courier_tariff' ) === '1';
    $courier_text = 'По тарифу грузоперевозчика, стоимость рассчитывается индивидуально.';

    $html = plnt_avito_heading( '🚚 ДОСТАВКА ПО МОСКВЕ И МО' );
    if ( $holiday && $price < 5000 ) {
        $html .= '<p>В праздничные дни заказы стоимостью до 5 000 ₽ доставляются по тарифу курьерской службы.</p>';
        $html .= '<p>' . esc_html( $courier_text ) . '</p>';
    } elseif ( $rates_known ) {
        $large_in = $large ? plnt_avito_delivery_number( 'large_markup_delivery_in_mkad' ) : 0;
        $large_out = $large ? plnt_avito_delivery_number( 'large_markup_delivery_out_mkad' ) : 0;
        $normal_in = $within + $extra_by_price + $large_in;
        $normal_out = $outside + $extra_by_price + $large_out;

        $html .= plnt_avito_heading( 'Доставка на следующий день или позже:' );
        $html .= '<ul><li>В пределах МКАД — от ' . plnt_avito_money( $normal_in ) . ' ₽</li>';
        $html .= '<li>За пределы МКАД (до 5 км) — от ' . plnt_avito_money( $normal_out ) . ' ₽</li>';
        $html .= '<li>За пределы МКАД (от 5 км) — по тарифу грузоперевозчика, стоимость рассчитывается индивидуально.</li></ul>';

        $html .= plnt_avito_heading( '⚡ Срочная доставка в день заказа (оформление до 18:00):' );
        if ( $urgent_courier ) {
            $html .= '<p>' . esc_html( $courier_text ) . '</p>';
        } else {
            // Важно: у крупногабаритных свой размер СРОЧНОЙ надбавки, не сумма двух.
            $urgent_extra = plnt_avito_delivery_number( $large ? 'urgent_markup_delivery_large' : 'urgent_markup_delivery' );
            $html .= '<ul><li>В пределах МКАД — от ' . plnt_avito_money( $normal_in + $urgent_extra ) . ' ₽</li>';
            $html .= '<li>За пределы МКАД (до 5 км) — от ' . plnt_avito_money( $normal_out + $urgent_extra ) . ' ₽</li>';
            $html .= '<li>За пределы МКАД (от 5 км) — по тарифу грузоперевозчика.</li></ul>';
        }
    } else {
        $html .= '<p>Стоимость доставки рассчитывается индивидуально по адресу заказа.</p>';
    }

    if ( $medium > 0 && $price < $medium ) {
        if ( $small > 0 && $extra_small > 0 ) {
            $html .= '<p>Для заказов стоимостью до ' . plnt_avito_money( $small - 1 )
                . ' ₽ стоимость доставки увеличивается на ' . plnt_avito_money( $extra_small ) . ' ₽.</p>';
        }
        if ( $small > 0 && $medium > $small && $extra_medium > 0 ) {
            $html .= '<p>Для заказов стоимостью от ' . plnt_avito_money( $small )
                . ' до ' . plnt_avito_money( $medium - 1 )
                . ' ₽ стоимость доставки увеличивается на ' . plnt_avito_money( $extra_medium ) . ' ₽.</p>';
        }
    }
    if ( $rates_known && !( $holiday && $price < 5000 ) ) {
        $html .= '<p>Стоимость рассчитана для покупки одного растения. При изменении состава заказа она может измениться.</p>';
    }

    $html .= plnt_avito_heading( '🕒 ИНТЕРВАЛЫ ДОСТАВКИ' );
    $html .= '<p>Доставляем ежедневно, без выходных.</p>';
    $extra_interval = function_exists( 'plnt_delivery_option' )
        ? (string) plnt_delivery_option( 'expensive_interval_delivery' ) : '';
    $interval_price = plnt_avito_delivery_number( 'expensive_interval_markup_delivery' );
    $html .= '<ul>';
    foreach ( [ '11:00 - 22:00', '11:00 - 16:00', '14:00 - 19:00', '18:00 - 22:00' ] as $interval ) {
        $line = 'С ' . str_replace( ' - ', ' до ', $interval );
        if ( str_replace( ' ', '', $extra_interval ) === str_replace( ' ', '', $interval ) && $interval_price > 0 ) {
            $line .= ' (+' . plnt_avito_money( $interval_price ) . ' ₽ к стоимости доставки)';
        }
        $html .= '<li>' . esc_html( $line ) . '</li>';
    }
    $html .= '</ul>';
    $html .= '<p>При срочной доставке удобное время согласовывается с менеджером.</p>';

    if ( ! $large ) {
        $html .= plnt_avito_heading( '📦 ДОСТАВКА ПО РОССИИ' );
        $html .= '<p>Отправляем растения Почтой России.</p>';
        $html .= '<p>Стоимость упаковки и передачи в почтовое отделение — 300 ₽. '
            . 'Пересылку получатель оплачивает отдельно по тарифам Почты России.</p>';
    }

    return $html;
}

function plnt_avito_get_product_description( WC_Product $product ): string {
    $html = plnt_avito_heading( '🌿 ' . $product->get_name() );
    $html .= plnt_avito_description_html( wpautop( (string) $product->get_description() ) );
    $html .= plnt_avito_heading( '🌱 УХОД И ХАРАКТЕРИСТИКИ' );
    $html .= plnt_avito_get_product_attributes( $product );
    $html .= '<p>Растение продаётся в транспортировочном горшке.</p>';
    return $html;
}

/** Возвращает описание в сохранённом порядке ACF Flexible Content. */
function plnt_avito_get_description( WC_Product $product ): string {
    $raw_rows = function_exists( 'get_field' )
        ? get_field( PLNT_AVITO_DESCRIPTION_FIELD, PLNT_AVITO_DESCRIPTION_POST_ID )
        : false;

    if ( is_array( $raw_rows ) ) {
        $rows = plnt_avito_normalize_description_rows( $raw_rows );
    } else {
        // Пока новый набор не сохранён, показываем безопасный вариант по умолчанию.
        $rows = get_option( 'plnt_avito_description_v7_initialized', false )
            ? [] : plnt_avito_default_description_blocks();
    }

    $parts = [];
    foreach ( $rows as $row ) {
        $type = (string) ( $row['acf_fc_layout'] ?? '' );
        $html = '';
        switch ( $type ) {
            case 'text':
                $html = plnt_avito_description_html( (string) ( $row['content'] ?? '' ) );
                break;
            case 'delivery':
                $html = plnt_avito_get_delivery_description( $product );
                break;
            case 'product_description':
                $html = plnt_avito_get_product_description( $product );
                break;
        }
        if ( '' !== trim( $html ) ) {
            $parts[] = trim( $html );
        }
    }

    $description = plnt_avito_description_html( implode( "\n", $parts ) );
    if ( mb_strlen( $description, 'UTF-8' ) > 7500 ) {
        // Нельзя обрезать HTML посередине тега — преобразуем в простой текст.
        $description = mb_substr( wp_strip_all_tags( $description ), 0, 7500, 'UTF-8' );
    }
    return $description;
}
