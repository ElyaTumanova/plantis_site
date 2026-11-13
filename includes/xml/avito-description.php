<?php
/**
 * PLANTIS — генерация описания Авито.
 * Основное описание собирается в фиксированной последовательности без ACF.
 * Старая функция сборки из ACF оставлена ниже для возможного возврата.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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

    // Пороговые суммы заказов и надбавки.
    $small        = plnt_avito_delivery_number( 'min_small_delivery' );
    $medium       = plnt_avito_delivery_number( 'min_medium_delivery' );
    $extra_small  = plnt_avito_delivery_number( 'small_markup_delivery' );
    $extra_medium = plnt_avito_delivery_number( 'medium_markup_delivery' );

    // Базовые тарифы доставки.
    $shipping = function_exists( 'plnt_get_shiping_costs' )
        ? plnt_get_shiping_costs()
        : [];

    $rates_known = is_array( $shipping )
        && isset( $delivery_inMKAD, $delivery_outMKAD )
        && isset( $shipping[ $delivery_inMKAD ], $shipping[ $delivery_outMKAD ] );

    $within = $rates_known
        ? plnt_avito_number( $shipping[ $delivery_inMKAD ] )
        : 0;

    $outside = $rates_known
        ? plnt_avito_number( $shipping[ $delivery_outMKAD ] )
        : 0;

    $rates_known = $rates_known && $within > 0 && $outside > 0;

    // Специальные тарифы.
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

        // Надбавки только за крупногабаритность.
        $large_in = $large
            ? plnt_avito_delivery_number( 'large_markup_delivery_in_mkad' )
            : 0;

        $large_out = $large
            ? plnt_avito_delivery_number( 'large_markup_delivery_out_mkad' )
            : 0;

        // Стоимость без надбавок за небольшие заказы.
        $normal_in  = $within + $large_in;
        $normal_out = $outside + $large_out;

        $html .= plnt_avito_heading( 'Доставка на следующий день или позже:' );

        $html .= '<ul>';
        $html .= '<li>В пределах МКАД — от ' . plnt_avito_money( $normal_in ) . ' ₽</li>';
        $html .= '<li>За пределы МКАД (до 5 км) — от ' . plnt_avito_money( $normal_out ) . ' ₽</li>';
        $html .= '<li>За пределы МКАД (от 5 км) — по тарифу грузоперевозчика, стоимость рассчитывается индивидуально.</li>';
        $html .= '</ul>';

        $html .= plnt_avito_heading( '⚡ Срочная доставка в день заказа (оформление до 18:00):' );

        if ( $urgent_courier ) {

            $html .= '<p>' . esc_html( $courier_text ) . '</p>';

        } else {

            // Для крупногабаритных товаров отдельная срочная надбавка.
            $urgent_extra = plnt_avito_delivery_number(
                $large ? 'urgent_markup_delivery_large' : 'urgent_markup_delivery'
            );

            $html .= '<ul>';
            $html .= '<li>В пределах МКАД — от ' . plnt_avito_money( $normal_in + $urgent_extra ) . ' ₽</li>';
            $html .= '<li>За пределы МКАД (до 5 км) — от ' . plnt_avito_money( $normal_out + $urgent_extra ) . ' ₽</li>';
            $html .= '<li>За пределы МКАД (от 5 км) — по тарифу грузоперевозчика.</li>';
            $html .= '</ul>';
        }

    } else {

        $html .= '<p>Стоимость доставки рассчитывается индивидуально по адресу заказа.</p>';
    }

    // Условия доставки небольших заказов.
    // Порог определяем по цене растения, но текст относится ко всему заказу.
    if ( $medium > 0 && $price < $medium ) {

        $html .= '<p><strong>Доплаты для небольших заказов:</strong></p>';

        if ( $small > 0 && $extra_small > 0 ) {

            $html .= '<p>Для заказов стоимостью до '
                . plnt_avito_money( $small - 1 )
                . ' ₽ стоимость доставки увеличивается на '
                . plnt_avito_money( $extra_small )
                . ' ₽.</p>';
        }

        if ( $small > 0 && $medium > $small && $extra_medium > 0 ) {

            $html .= '<p>Для заказов стоимостью от '
                . plnt_avito_money( $small )
                . ' до '
                . plnt_avito_money( $medium - 1 )
                . ' ₽ стоимость доставки увеличивается на '
                . plnt_avito_money( $extra_medium )
                . ' ₽.</p>';
        }
    }

    // Интервалы доставки.
    $html .= plnt_avito_heading( '🕒 ИНТЕРВАЛЫ ДОСТАВКИ' );
    $html .= '<p>Доставляем ежедневно, без выходных.</p>';

    $extra_interval = function_exists( 'plnt_delivery_option' )
        ? (string) plnt_delivery_option( 'expensive_interval_delivery' )
        : '';

    $interval_price = plnt_avito_delivery_number( 'expensive_interval_markup_delivery' );

    $html .= '<ul>';

    foreach ( [
        '11:00 - 22:00',
        '11:00 - 16:00',
        '14:00 - 19:00',
        '18:00 - 22:00',
    ] as $interval ) {

        $line = 'С ' . str_replace( ' - ', ' до ', $interval );

        if (
            str_replace( ' ', '', $extra_interval ) === str_replace( ' ', '', $interval )
            && $interval_price > 0
        ) {
            $line .= ' (+' . plnt_avito_money( $interval_price ) . ' ₽ к стоимости доставки)';
        }

        $html .= '<li>' . esc_html( $line ) . '</li>';
    }

    $html .= '</ul>';
    $html .= '<p>При срочной доставке удобное время согласовывается с менеджером.</p>';

    // Почта России — только для некрупногабаритных растений.
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
    // Краткое описание товара.
    $short_description = $product->get_short_description();

    if ( $short_description ) {
        $html .= plnt_avito_description_html( wpautop( $short_description ) );
    }
    $html .= plnt_avito_heading( '🌱 УХОД И ХАРАКТЕРИСТИКИ' );
    $html .= plnt_avito_get_product_attributes( $product );
    $html .= '<p>Растение продаётся в транспортировочном горшке.</p>';
    return $html;
}

/**
 * Текущее описание Авито: статические тексты + динамическая доставка и товар.
 * ACF Flexible Content не используется. Порядок блоков фиксированный.
 */
function plnt_avito_get_description( WC_Product $product ): string {
    $advantages = <<<'HTML'
<p>📸 Пришлём актуальные фотографии растения перед покупкой.</p>
<p>✅ Бесплатно пересадим растение при одновременной покупке керамического горшка диаметром до 26 см.</p>
<p>🎓 Расскажем об уходе за растением и поможем после покупки.</p>
<p>🌴 В нашем профиле ещё больше комнатных растений!</p>
HTML;

    $store_info = <<<'HTML'
<p><strong>📍 САМОВЫВОЗ</strong></p>
<p>Бесплатный самовывоз из нашего шоурума:</p>
<p>Москва, ул. Мещерякова, д. 3 (м. Тушинская или м. Сокол).</p>
<p>Ежедневно с 10:00 до 20:00.</p>
<p>❄️ Зимой бесплатно утеплим растение при самовывозе, если вы не на машине.</p>

<p><strong>💳 СПОСОБЫ ОПЛАТЫ</strong></p>
<ul>
    <li>Банковской картой</li>
    <li>Переводом</li>
    <li>Наличными при самовывозе</li>
    <li>По счёту</li>
</ul>
<p>Работаем с физическими и юридическими лицами.</p>

<p><strong>📌 РЕЗЕРВ И ПЕРЕСАДКА</strong></p>
<p>Бесплатно зарезервируем растение на срок до 48 часов без предварительной оплаты.</p>
<p>Бесплатная пересадка осуществляется при одновременной покупке растения и подходящего керамического горшка диаметром до 26 см.</p>
HTML;

    $parts = [
        $advantages,
        plnt_avito_get_delivery_description( $product ),
        $store_info,
        plnt_avito_get_product_description( $product ),
        '<p>🌿 <strong>PLANTIS — комнатные растения с заботой!</strong></p>',
    ];

    $description = plnt_avito_description_html( implode( "\n", $parts ) );

    if ( mb_strlen( $description, 'UTF-8' ) > 7500 ) {
        // Не обрезаем HTML посередине тега.
        $description = mb_substr( wp_strip_all_tags( $description ), 0, 7500, 'UTF-8' );
    }

    return $description;
}

/**
 * Старая реализация через ACF Flexible Content.
 * Сохранена без изменений в логике, но нигде не вызывается.
 */
function plnt_avito_get_description_from_acf_blocks( WC_Product $product ): string {
    // Стандартная ACF Options Page: post_id = 'option'.
    $rows = function_exists( 'get_field' )
        ? get_field( 'plnt_avito_description_blocks', 'option' )
        : false;

    if ( ! is_array( $rows ) || ! $rows ) {
        return '';
    }

    $parts = [];

    foreach ( $rows as $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }

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
        // Не обрезаем HTML посередине тега.
        $description = mb_substr( wp_strip_all_tags( $description ), 0, 7500, 'UTF-8' );
    }

    return $description;
}


/**
 * Предпросмотр описания Авито в карточке товара WooCommerce.
 */
add_action( 'add_meta_boxes_product', 'plnt_avito_add_description_preview' );

function plnt_avito_add_description_preview(): void {

    add_meta_box(
        'plnt-avito-description-preview',
        'Описание для Авито — предпросмотр',
        'plnt_avito_render_description_preview',
        'product',
        'normal',
        'low'
    );
}


/**
 * Вывод готового описания без возможности редактирования.
 */
function plnt_avito_render_description_preview( WP_Post $post ): void {

    if ( ! function_exists( 'plnt_avito_get_description' ) ) {
        return;
    }

    $product = wc_get_product( $post->ID );

    if ( ! $product ) {
        return;
    }

    $description = plnt_avito_get_description( $product );

    if ( '' === trim( $description ) ) {
        echo '<p>Описание Авито не сформировано.</p>';
        return;
    }

    ?>
    <div class="plnt-avito-preview">
        <?php echo wp_kses_post( $description ); ?>
    </div>

    <style>
        .plnt-avito-preview {
            max-height: 650px;
            overflow-y: auto;
            padding: 20px 24px;
            background: #f8faf9;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.7;
            white-space: normal;
        }

        .plnt-avito-preview p {
            margin: 0 0 14px;
        }

        .plnt-avito-preview ul,
        .plnt-avito-preview ol {
            margin: 0 0 16px 20px;
            padding: 0;
        }

        .plnt-avito-preview li {
            margin-bottom: 5px;
        }

        .plnt-avito-preview strong {
            font-weight: 600;
        }

        .plnt-avito-preview > :last-child {
            margin-bottom: 0;
        }
    </style>
    <?php
}