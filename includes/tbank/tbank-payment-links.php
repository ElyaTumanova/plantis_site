<?php
/**
 * PLANTIS — ссылки на оплату Т-Банка из карточки заказа WooCommerce.
 *
 * Установка: require_once get_stylesheet_directory() . '/inc/tbank-payment-links.php';
 * Требования: WooCommerce, существующие настройки шлюза tbank.
 * Версия: 1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Настройки действующего шлюза. Секрет используется только на сервере. */
function plnt_tbank_links_settings() {
    $settings = get_option( 'woocommerce_tbank_settings', array() );
    return is_array( $settings ) ? $settings : array();
}

/** Токен запросов и уведомлений по официальному алгоритму Т-Банка. */
function plnt_tbank_links_token( $params, $password ) {
    $values = array();

    foreach ( $params as $key => $value ) {
        if ( 'Token' === $key || null === $value || is_array( $value ) || is_object( $value ) ) {
            continue;
        }
        if ( is_bool( $value ) ) {
            $values[ $key ] = $value ? 'true' : 'false';
        } else {
            $values[ $key ] = (string) $value;
        }
    }

    $values['Password'] = html_entity_decode( (string) $password, ENT_QUOTES, 'UTF-8' );
    ksort( $values, SORT_STRING );

    return hash( 'sha256', implode( '', array_values( $values ) ) );
}

/** Отправка запроса в Т-Банк без отключения проверки SSL. */
function plnt_tbank_links_request( $method, $params ) {
    $settings = plnt_tbank_links_settings();
    $terminal = trim( (string) ( $settings['merchant_id'] ?? '' ) );
    $password = (string) ( $settings['secret_key'] ?? '' );

    if ( '' === $terminal || '' === $password ) {
        return new WP_Error( 'plnt_tbank_credentials', 'В настройках действующего шлюза Т-Банка не заполнены терминал или пароль.' );
    }

    if ( ! in_array( $method, array( 'Init', 'GetState' ), true ) ) {
        return new WP_Error( 'plnt_tbank_method', 'Недопустимый метод API.' );
    }

    $params['TerminalKey'] = $terminal;
    $params['Token']       = plnt_tbank_links_token( $params, $password );

    $response = wp_remote_post(
        'https://securepay.tinkoff.ru/v2/' . $method,
        array(
            'timeout'     => 25,
            'redirection' => 0,
            'sslverify'   => true,
            'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
            'body'        => wp_json_encode( $params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
        )
    );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'plnt_tbank_transport', 'Нет ответа от Т-Банка: ' . $response->get_error_message() );
    }

    $status = (int) wp_remote_retrieve_response_code( $response );
    $result = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( 200 !== $status || ! is_array( $result ) ) {
        return new WP_Error( 'plnt_tbank_transport', 'Некорректный HTTP-ответ от Т-Банка (код ' . $status . ').' );
    }

    if ( empty( $result['Success'] ) || '0' !== (string) ( $result['ErrorCode'] ?? '' ) ) {
        $message = trim( (string) ( $result['Details'] ?? $result['Message'] ?? 'Неизвестная ошибка' ) );
        return new WP_Error( 'plnt_tbank_api', 'Т-Банк отклонил запрос (код ' . sanitize_text_field( (string) ( $result['ErrorCode'] ?? '?' ) ) . '): ' . sanitize_text_field( $message ) );
    }

    return $result;
}

/** НДС — используем ставки, сохраненные в самом заказе, а не текущие ставки товара. */
function plnt_tbank_links_item_tax( $item ) {
    $taxes = $item->get_taxes();
    $rates = isset( $taxes['total'] ) && is_array( $taxes['total'] ) ? $taxes['total'] : array();
    $ids   = array_filter( array_keys( $rates ), 'is_numeric' );

    if ( ! $ids ) {
        return 'none';
    }

    if ( count( $ids ) > 1 ) {
        return new WP_Error( 'plnt_tbank_vat', 'В одной позиции заказа несколько ставок НДС. Нужна ручная проверка чека.' );
    }

    $rate = (float) WC_Tax::get_rate_percent_value( (int) reset( $ids ) );
    $map  = array( '0' => 'vat0', '5' => 'vat5', '7' => 'vat7', '10' => 'vat10', '22' => 'vat22' );
    $key  = (string) (int) $rate;

    if ( ( 0.0 === $rate && (float) $item->get_total_tax() > 0 ) ||
         $rate !== (float) (int) $rate || ! isset( $map[ $key ] ) ) {
        return new WP_Error( 'plnt_tbank_vat', 'Ставка НДС ' . $rate . '% не поддерживается текущим API Т-Банка. Проверьте налоговые настройки заказа.' );
    }

    return $map[ $key ];
}

/**
 * Строки чека: сохраняем реальные суммы с учетом скидок.
 * Если сумма не делится на количество без остатка в копейках, разделяем
 * на две строки с соседними ценами — Amount всегда равен Price * Quantity.
 */
function plnt_tbank_links_add_receipt_line( &$items, $name, $amount, $quantity, $tax, $object, $method, $ffd12 ) {
    if ( $amount < 0 || $quantity <= 0 || (float) (int) $quantity !== (float) $quantity ) {
        return new WP_Error( 'plnt_tbank_line', 'Невозможно корректно сформировать чек: отрицательная сумма или дробное количество.' );
    }

    if ( 0 === $amount ) {
        return true; // Бесплатные позиции не включаем в денежный чек.
    }

    $quantity = (int) $quantity;
    $low      = intdiv( $amount, $quantity );
    $extra    = $amount % $quantity;
    $parts    = array();

    if ( $quantity > $extra ) {
        $parts[] = array( 'price' => $low, 'qty' => $quantity - $extra );
    }
    if ( $extra > 0 ) {
        $parts[] = array( 'price' => $low + 1, 'qty' => $extra );
    }

    foreach ( $parts as $part ) {
        if ( $part['price'] < 1 ) {
            return new WP_Error( 'plnt_tbank_line', 'Не удалось распределить стоимость позиции по количеству в чеке.' );
        }

        $line = array(
            'Name'          => mb_substr( wp_strip_all_tags( (string) $name ), 0, 128 ),
            'Price'         => $part['price'],
            'Quantity'      => $part['qty'],
            'Amount'        => $part['price'] * $part['qty'],
            'PaymentMethod' => $method,
            'PaymentObject' => $object,
            'Tax'           => $tax,
        );
        if ( $ffd12 ) {
            $line['MeasurementUnit'] = 'шт';
        }
        $items[] = $line;
    }

    return true;
}

/** Контакт для фискализации: единая проверка для интерфейса и сервера. */
function plnt_tbank_links_receipt_contacts( $order ) {
    $contacts = array();

    $email = sanitize_email( $order->get_billing_email() );
    if ( is_email( $email ) ) {
        $contacts['Email'] = mb_substr( $email, 0, 64 );
    }

    $digits = preg_replace( '/\D+/', '', (string) $order->get_billing_phone() );
    if ( 11 === strlen( $digits ) && '8' === $digits[0] ) {
        $digits = '7' . substr( $digits, 1 );
    }
    if ( 10 === strlen( $digits ) ) {
        $digits = '7' . $digits;
    }
    if ( strlen( $digits ) >= 11 && strlen( $digits ) <= 15 ) {
        $contacts['Phone'] = '+' . $digits;
    }

    if ( ! isset( $contacts['Email'] ) && ! isset( $contacts['Phone'] ) ) {
        return new WP_Error( 'plnt_tbank_contact', 'Для фискализации укажите в заказе корректный email или телефон покупателя.' );
    }

    return $contacts;
}

/** Сбор чека без зависимости от WC()->cart и действующих цен каталога. */
function plnt_tbank_links_receipt( $order, $settings ) {
    $ffd = (string) ( $settings['ffd'] ?? '' );
    $taxation = (string) ( $settings['taxation'] ?? '' );
    $method = (string) ( $settings['payment_method_ffd'] ?? '' );
    $object = (string) ( $settings['payment_object_ffd'] ?? '' );

    if ( ! in_array( $ffd, array( 'ffd105', 'ffd12' ), true ) ||
         ! in_array( $taxation, array( 'osn', 'usn_income', 'usn_income_outcome', 'esn', 'patent' ), true ) ||
         ! in_array( $method, array( 'full_prepayment', 'prepayment', 'advance', 'full_payment', 'partial_payment', 'credit', 'credit_payment' ), true ) ||
         ! in_array( $object, array( 'commodity', 'excise', 'job', 'service', 'gambling_bet', 'gambling_prize', 'lottery', 'lottery_prize', 'intellectual_activity', 'payment', 'agent_commission', 'composite', 'another' ), true ) ) {
        return new WP_Error( 'plnt_tbank_receipt_settings', 'Заполните ФФД, налогообложение и признаки предмета/способа расчёта в настройках шлюза Т-Банка.' );
    }

    $receipt = array(
        'Taxation'   => $taxation,
        'FfdVersion' => 'ffd12' === $ffd ? '1.2' : '1.05',
        'Items'      => array(),
    );

    $contacts = plnt_tbank_links_receipt_contacts( $order );
    if ( is_wp_error( $contacts ) ) {
        return $contacts;
    }
    $receipt = array_merge( $receipt, $contacts );

    $company_email = sanitize_email( (string) ( $settings['email_company'] ?? '' ) );
    if ( is_email( $company_email ) ) {
        $receipt['EmailCompany'] = mb_substr( $company_email, 0, 64 );
    }

    foreach ( $order->get_items( array( 'line_item', 'shipping', 'fee' ) ) as $item ) {
        $type = $item->get_type();
        $name = $item->get_name();
        $qty  = 'line_item' === $type ? (float) $item->get_quantity() : 1;
        $sum  = (int) round( ( (float) $item->get_total() + (float) $item->get_total_tax() ) * 100 );
        $tax  = plnt_tbank_links_item_tax( $item );

        if ( is_wp_error( $tax ) ) {
            return $tax;
        }
        if ( $sum < 0 ) {
            return new WP_Error( 'plnt_tbank_negative', 'В заказе есть отрицательная комиссия. Автоматическое формирование чека невозможно.' );
        }

        $item_object = 'line_item' === $type ? $object : 'service';
        if ( 'line_item' === $type && method_exists( $item, 'get_product_id' ) && has_term( 'peresadka', 'product_cat', $item->get_product_id() ) ) {
            $item_object = 'service';
        }

        $added = plnt_tbank_links_add_receipt_line(
            $receipt['Items'], $name, $sum, $qty, $tax, $item_object, $method, 'ffd12' === $ffd
        );
        if ( is_wp_error( $added ) ) {
            return $added;
        }
    }

    if ( ! $receipt['Items'] || count( $receipt['Items'] ) > 100 ) {
        return new WP_Error( 'plnt_tbank_items_count', 'Чек должен содержать от 1 до 100 ненулевых товарных строк.' );
    }

    $receipt_total = array_sum( array_column( $receipt['Items'], 'Amount' ) );
    $order_total   = (int) round( (float) $order->get_total() * 100 );

    if ( $receipt_total !== $order_total ) {
        return new WP_Error( 'plnt_tbank_items_total', sprintf(
            'Сумма строк чека (%s коп.) не равна сумме заказа (%s коп.). Проверьте скидки, доставку и округление.',
            $receipt_total, $order_total
        ) );
    }

    return $receipt;
}

/** Храним историю ссылок только в метаданных самого заказа (совместимо с HPOS). */
function plnt_tbank_links_history( $order ) {
    $history = $order->get_meta( '_plnt_tbank_links', true );
    return is_array( $history ) ? array_values( $history ) : array();
}

function plnt_tbank_links_save_history( $order, $history ) {
    $order->update_meta_data( '_plnt_tbank_links', array_values( $history ) );
    $order->save();
}

function plnt_tbank_links_is_active( $link ) {
    $status = (string) ( $link['status'] ?? '' );
    $expiry = (int) ( $link['expires'] ?? 0 );
    return in_array( $status, array( 'NEW', 'FORM_SHOWED', 'AUTHORIZING', 'AUTHORIZED', '3DS_CHECKING', '3DS_CHECKED' ), true ) && $expiry > time();
}

/** Проверка статуса применяется как к вебхуку, так и к ручному GetState. */
function plnt_tbank_links_apply_status( $order, $index, $status, $payment_id, $amount ) {
    $history = plnt_tbank_links_history( $order );
    if ( ! isset( $history[ $index ] ) ) {
        return new WP_Error( 'plnt_tbank_unknown', 'Платёж не найден в истории заказа.' );
    }

    $saved = $history[ $index ];
    if ( (string) $saved['payment_id'] !== (string) $payment_id || (int) $saved['amount'] !== (int) $amount ) {
        return new WP_Error( 'plnt_tbank_match', 'Параметры платежа не совпадают с сохранёнными.' );
    }

    $old_status = (string) ( $saved['status'] ?? '' );
    // Вебхуки AUTHORIZED и CONFIRMED могут прийти не по порядку.
    if ( 'CONFIRMED' === $old_status && 'CONFIRMED' !== $status ) {
        return true;
    }
    $history[ $index ]['status'] = sanitize_text_field( (string) $status );
    plnt_tbank_links_save_history( $order, $history );

    if ( 'CONFIRMED' === $status && 'CONFIRMED' !== $old_status ) {
        $actual_total = (int) round( (float) $order->get_total() * 100 );

        if ( $actual_total !== (int) $amount ) {
            $order->add_order_note( 'ВНИМАНИЕ: Т-Банк подтвердил оплату ' . $payment_id . ', но сумма текущего заказа отличается от оплаченной. Проверьте вручную!' );
        } else {
            // Статус WooCommerce сам по себе не доказывает, что деньги получены.
            // Сверяем лишь известные подтверждения Т-Банка и идентификаторы транзакций.
            $another_confirmed = '';
            foreach ( $history as $link_index => $link ) {
                if ( (int) $link_index !== (int) $index &&
                     'CONFIRMED' === ( $link['status'] ?? '' ) &&
                     ! empty( $link['payment_id'] ) ) {
                    $another_confirmed = (string) $link['payment_id'];
                    break;
                }
            }

            $existing_transaction = (string) $order->get_transaction_id();
            if ( $another_confirmed ) {
                $order->add_order_note( 'ВНИМАНИЕ: подтверждён ещё один платёж Т-Банка (' . $payment_id . '). Уже есть подтверждённый платёж ' . $another_confirmed . '. Проверьте возможную двойную оплату.' );
            } elseif ( '' !== $existing_transaction && $existing_transaction !== (string) $payment_id ) {
                $order->add_order_note( 'ВНИМАНИЕ: Т-Банк подтвердил платёж ' . $payment_id . ', но у заказа уже сохранён другой Transaction ID (' . $existing_transaction . '). Проверьте оплаты вручную.' );
            } elseif ( $order->has_status( array( 'refunded', 'cancelled' ) ) ) {
                $order->add_order_note( 'ВНИМАНИЕ: Т-Банк подтвердил платёж ' . $payment_id . ', но заказ отменён или возвращён. Требуется ручная проверка.' );
            } else {
                // Для ожидающих статусов WooCommerce сам переведёт заказ в обработку.
                if ( $order->has_status( array( 'pending', 'on-hold', 'failed' ) ) ) {
                    $order->payment_complete( (string) $payment_id );
                } else {
                    // Например, «Обработка» была выставлена вручную до оплаты.
                    // Статус не меняем, но фиксируем подтверждённую транзакцию банка.
                    $order->set_transaction_id( (string) $payment_id );
                    if ( ! $order->get_date_paid() ) {
                        $order->set_date_paid( time() );
                    }
                    $order->save();
                }
                $order->add_order_note( 'Т-Банк подтвердил оплату по ссылке. PaymentId: ' . $payment_id . '.' );
            }
        }
    } elseif ( $old_status !== $status && 'CONFIRMED' !== $status ) {
        $order->add_order_note( 'Статус ссылки Т-Банка (' . $payment_id . '): ' . sanitize_text_field( (string) $status ) . '.' );
    }

    return true;
}

/** Создание новой ссылки (только после сохранения заказа). */
function plnt_tbank_links_create( $order ) {
    if ( 'RUB' !== $order->get_currency() ) {
        return new WP_Error( 'plnt_tbank_currency', 'Этот терминал используется только для заказов в рублях.' );
    }
    $amount = (int) round( (float) $order->get_total() * 100 );
    if ( $amount <= 0 ) {
        return new WP_Error( 'plnt_tbank_amount', 'Сумма заказа должна быть больше нуля.' );
    }

    $history = plnt_tbank_links_history( $order );
    $last    = $history ? end( $history ) : null;
    if ( $last && ( plnt_tbank_links_is_active( $last ) || 'UNKNOWN' === ( $last['status'] ?? '' ) ) ) {
        return new WP_Error( 'plnt_tbank_active', 'У заказа уже есть действующая ссылка либо запрос с неопределённым результатом. Проверьте статус/операции в Т-Банке.' );
    }

    $settings = plnt_tbank_links_settings();
    $expires  = time() + 3 * DAY_IN_SECONDS;
    $bank_id  = 'plnt-' . $order->get_id() . '-' . bin2hex( random_bytes( 6 ) );
    $params   = array(
        'Amount'          => $amount,
        'OrderId'         => $bank_id,
        'Description'     => mb_substr( 'Оплата заказа №' . $order->get_order_number(), 0, 140 ),
        'PayType'         => 'O',
        'NotificationURL' => add_query_arg( 'wc-api', 'plnt_tbank_links', home_url( '/' ) ),
        'SuccessURL'      => $order->get_checkout_order_received_url(),
        'FailURL'         => $order->get_checkout_payment_url(),
        'RedirectDueDate' => wp_date( 'Y-m-d\TH:i:sP', $expires ),
        'DATA'            => array( 'OperationInitiatorType' => '0' ),
    );

    if ( 'en' === ( $settings['payment_form_language'] ?? '' ) ) {
        $params['Language'] = 'en';
    }
    if ( 'yes' === ( $settings['check_data_tax'] ?? '' ) ) {
        $receipt = plnt_tbank_links_receipt( $order, $settings );
        if ( is_wp_error( $receipt ) ) {
            return $receipt;
        }
        $params['Receipt'] = $receipt;
    }

    // Записываем попытку ДО внешнего запроса — если сеть оборвётся, не создадим случайно дубликат.
    $history[] = array(
        'order_id'   => $bank_id,
        'payment_id' => '',
        'url'        => '',
        'amount'     => $amount,
        'status'     => 'UNKNOWN',
        'created'    => time(),
        'expires'    => $expires,
    );
    plnt_tbank_links_save_history( $order, $history );
    $index = count( $history ) - 1;

    $response = plnt_tbank_links_request( 'Init', $params );
    if ( is_wp_error( $response ) ) {
        // Если банк явно отказал, этой попыткой можно не блокировать новый запрос.
        if ( 'plnt_tbank_api' === $response->get_error_code() ) {
            $history[ $index ]['status'] = 'ERROR';
            plnt_tbank_links_save_history( $order, $history );
        }
        $order->add_order_note( 'Не удалось создать ссылку Т-Банка: ' . $response->get_error_message() . ' (без записи секретов).' );
        return $response;
    }

    if ( empty( $response['PaymentId'] ) || empty( $response['PaymentURL'] ) ||
         (string) ( $response['OrderId'] ?? '' ) !== $bank_id ||
         (int) ( $response['Amount'] ?? -1 ) !== $amount ||
         ! wp_http_validate_url( (string) $response['PaymentURL'] ) ||
         'https' !== wp_parse_url( (string) $response['PaymentURL'], PHP_URL_SCHEME ) ) {
        return new WP_Error( 'plnt_tbank_response', 'Т-Банк вернул неполные данные. Перед повторным созданием проверьте операцию в кабинете банка.' );
    }

    $history[ $index ]['payment_id'] = (string) $response['PaymentId'];
    $history[ $index ]['url']        = esc_url_raw( (string) $response['PaymentURL'] );
    $history[ $index ]['status']     = sanitize_text_field( (string) ( $response['Status'] ?? 'NEW' ) );
    plnt_tbank_links_save_history( $order, $history );
    $order->add_order_note( 'Создана ссылка на оплату Т-Банка на ' . wp_strip_all_tags( wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ) ) . '. PaymentId: ' . $response['PaymentId'] . '. Срок действия: 3 дня.' );

    return $history[ $index ];
}

/** Проверка статуса только текущей ссылки; callback принимает также старые платежи. */
function plnt_tbank_links_check( $order ) {
    $history = plnt_tbank_links_history( $order );
    if ( ! $history ) {
        return new WP_Error( 'plnt_tbank_empty', 'Ссылка для этого заказа ещё не создана.' );
    }
    $index = count( $history ) - 1;
    $last  = $history[ $index ];
    if ( empty( $last['payment_id'] ) ) {
        return new WP_Error( 'plnt_tbank_not_ready', 'У платежа нет PaymentId. Проверьте операцию в личном кабинете банка.' );
    }

    $response = plnt_tbank_links_request( 'GetState', array( 'PaymentId' => (string) $last['payment_id'] ) );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    if ( (string) ( $response['PaymentId'] ?? '' ) !== (string) $last['payment_id'] ||
         (string) ( $response['OrderId'] ?? '' ) !== (string) $last['order_id'] ||
         (int) ( $response['Amount'] ?? -1 ) !== (int) $last['amount'] ) {
        return new WP_Error( 'plnt_tbank_invalid_state', 'Т-Банк вернул информацию о другом платеже или сумме.' );
    }

    $applied = plnt_tbank_links_apply_status( $order, $index, (string) ( $response['Status'] ?? '' ), $last['payment_id'], $last['amount'] );
    return is_wp_error( $applied ) ? $applied : (string) ( $response['Status'] ?? '' );
}

/** Метабокс на обычной странице заказа и на экране HPOS. */
add_action( 'add_meta_boxes', 'plnt_tbank_links_register_box', 30 );
function plnt_tbank_links_register_box() {
    if ( ! function_exists( 'wc_get_page_screen_id' ) ) {
        return;
    }
    $screens = array_unique( array( 'shop_order', wc_get_page_screen_id( 'shop-order' ) ) );
    foreach ( $screens as $screen ) {
        add_meta_box( 'plnt_tbank_links', 'Ссылка на оплату Т-Банк', 'plnt_tbank_links_render_box', $screen, 'side', 'default' );
    }
}

function plnt_tbank_links_render_box( $post_or_order ) {
    $order = $post_or_order instanceof WC_Order
        ? $post_or_order
        : ( $post_or_order instanceof WP_Post ? wc_get_order( $post_or_order->ID ) : false );

    if ( ! $order || ! $order->get_id() ) {
        echo '<p>Сначала сохраните заказ.</p>';
        return;
    }
    if ( ! current_user_can( 'edit_shop_orders' ) ) {
        return;
    }

    $history = plnt_tbank_links_history( $order );
    $last    = $history ? end( $history ) : null;
    $nonce   = wp_create_nonce( 'plnt_tbank_links_' . $order->get_id() );

    echo '<div class="plnt-tbank-links" data-order="' . esc_attr( $order->get_id() ) . '" data-nonce="' . esc_attr( $nonce ) . '">';
    echo '<p>Сумма: <strong>' . wp_kses_post( wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ) ) . '</strong></p>';

    if ( $last ) {
        echo '<p><strong>Статус:</strong> ' . esc_html( $last['status'] ?? '—' ) . '</p>';
        if ( ! empty( $last['payment_id'] ) ) {
            echo '<p><strong>PaymentId:</strong> ' . esc_html( $last['payment_id'] ) . '</p>';
        }
        if ( ! empty( $last['url'] ) ) {
            echo '<p><input type="text" style="width:100%" readonly value="' . esc_attr( $last['url'] ) . '" class="plnt-tbank-url" aria-label="Ссылка на оплату"></p>';
            echo '<p><button class="button plnt-tbank-copy" type="button">Копировать ссылку</button> ';
            echo '<a href="' . esc_url( $last['url'] ) . '" target="_blank" rel="noopener noreferrer">Открыть ↗</a></p>';
        }
        if ( ! empty( $last['expires'] ) ) {
            echo '<p><small>Действует до: ' . esc_html( wp_date( 'd.m.Y H:i', (int) $last['expires'] ) ) . '</small></p>';
        }
    }

    // Статус заказа не ограничивает создание ссылки: только состояние предыдущей операции.
    $can_create = ! $last || ( ! plnt_tbank_links_is_active( $last ) && 'UNKNOWN' !== ( $last['status'] ?? '' ) );

    // Если включена фискализация, перед созданием проверяем контакты покупателя.
    $check_data_tax = 'yes' === ( plnt_tbank_links_settings()['check_data_tax'] ?? '' );
    $contact_check  = $check_data_tax ? plnt_tbank_links_receipt_contacts( $order ) : array();
    $contact_error  = is_wp_error( $contact_check ) ? $contact_check : null;

    if ( $can_create ) {
        if ( $contact_error ) {
            echo '<p><button type="button" class="button button-primary" disabled>Создать ссылку на оплату</button></p>';
            echo '<p style="color:#b32d2e">' . esc_html( $contact_error->get_error_message() ) . '</p>';
        } else {
            echo '<p><button type="button" class="button button-primary plnt-tbank-action" data-operation="create">Создать ссылку на оплату</button></p>';
        }
    }
    if ( $last && ! empty( $last['payment_id'] ) ) {
        echo '<p><button type="button" class="button plnt-tbank-action" data-operation="check">Проверить оплату</button></p>';
    }
    if ( $last && 'UNKNOWN' === ( $last['status'] ?? '' ) ) {
        echo '<p style="color:#9a6700">Результат запроса неизвестен. Проверьте операцию по OrderId ' . esc_html( $last['order_id'] ) . ' в Т-Банке. Повторное создание заблокировано во избежание дубля.</p>';
    }
    if ( 'yes' !== ( plnt_tbank_links_settings()['check_data_tax'] ?? '' ) ) {
        echo '<p><small>Передача чека отключена в настройках Т-Банка: состав заказа в Receipt не отправляется.</small></p>';
    }
    echo '<p class="plnt-tbank-feedback" role="status" aria-live="polite"></p>';

    // Сравниваем с последней реально созданной ссылкой, игнорируя неуспешные попытки.
    $last_created_link = null;
    foreach ( array_reverse( $history ) as $link ) {
        if ( ! empty( $link['url'] ) && isset( $link['amount'] ) ) {
            $last_created_link = $link;
            break;
        }
    }

    if ( $last_created_link ) {
        $current_amount = (int) round( (float) $order->get_total() * 100 );
        if ( $current_amount !== (int) $last_created_link['amount'] ) {
            echo '<p style="color:#b32d2e"><strong>Внимание:</strong> сумма заказа отличается от суммы ранее созданной ссылки. Не отправляйте старую ссылку покупателю.</p>';
        }
    }

    // Предупреждаем только о подтверждённой Т-Банком оплате этой интеграции.
    if ( $can_create ) {
        foreach ( $history as $link ) {
            if ( 'CONFIRMED' === ( $link['status'] ?? '' ) ) {
                echo '<p style="color:#b32d2e"><small>Т-Банк уже подтвердил оплату по одной из ссылок этого заказа. Новая ссылка может привести к повторной оплате.</small></p>';
                break;
            }
        }
    }
    echo '</div>';
}

/** JS работает без вложенных форм (редактор заказа WooCommerce сам является формой). */
add_action( 'admin_enqueue_scripts', 'plnt_tbank_links_admin_js' );
function plnt_tbank_links_admin_js() {
    if ( ! function_exists( 'wc_get_page_screen_id' ) || ! function_exists( 'get_current_screen' ) ) {
        return;
    }
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'shop_order', wc_get_page_screen_id( 'shop-order' ) ), true ) ) {
        return;
    }
    wp_enqueue_script( 'jquery' );
    $script = <<<'JS'
jQuery(function($) {
    $(document).on('click', '.plnt-tbank-action', function() {
        var button = $(this), box = button.closest('.plnt-tbank-links');
        var feedback = box.find('.plnt-tbank-feedback');
        box.find('.plnt-tbank-action').prop('disabled', true);
        feedback.text('Запрашиваем Т-Банк…').css('color', '');
        $.post(ajaxurl, {
            action: 'plnt_tbank_links_action',
            nonce: box.attr('data-nonce'),
            order_id: box.attr('data-order'),
            operation: button.attr('data-operation')
        }).done(function(response) {
            if (response && response.success) {
                feedback.text(response.data.message || 'Готово.').css('color', '#008a20');
                window.location.reload();
            } else {
                feedback.text((response && response.data && response.data.message) || 'Ошибка запроса').css('color', '#b32d2e');
                box.find('.plnt-tbank-action').prop('disabled', false);
            }
        }).fail(function(xhr) {
            // WP отдаёт ошибки в responseJSON при HTTP 400/403/409.
            var serverMessage = xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message;
            var fallback = 'Не удалось получить ответ сервера. Проверьте заказ перед повторной попыткой.';
            feedback.text(serverMessage || fallback).css('color', '#b32d2e');
            box.find('.plnt-tbank-action').prop('disabled', false);
        });
    });
    $(document).on('click', '.plnt-tbank-copy', function() {
        var input = $(this).closest('.plnt-tbank-links').find('.plnt-tbank-url').get(0);
        if (!input) return;
        var feedback = $(this).closest('.plnt-tbank-links').find('.plnt-tbank-feedback');
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(function() { feedback.text('Ссылка скопирована.'); }).catch(function() { input.focus(); input.select(); feedback.text('Ссылка выделена — нажмите Ctrl+C.'); });
        } else {
            input.focus(); input.select();
            feedback.text(document.execCommand('copy') ? 'Ссылка скопирована.' : 'Ссылка выделена — нажмите Ctrl+C.');
        }
    });
});
JS;
    wp_add_inline_script( 'jquery', $script );
}

/** AJAX — только для менеджера и только с nonce конкретного заказа. */
add_action( 'wp_ajax_plnt_tbank_links_action', 'plnt_tbank_links_ajax' );
function plnt_tbank_links_ajax() {
    $order_id  = absint( $_POST['order_id'] ?? 0 );
    $operation = sanitize_key( wp_unslash( $_POST['operation'] ?? '' ) );
    $nonce     = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

    if ( ! $order_id || ! current_user_can( 'edit_shop_orders' ) || ! wp_verify_nonce( $nonce, 'plnt_tbank_links_' . $order_id ) ) {
        wp_send_json_error( array( 'message' => 'Нет доступа или истекла сессия.' ), 403 );
    }
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        wp_send_json_error( array( 'message' => 'Заказ не найден.' ), 404 );
    }
    if ( ! in_array( $operation, array( 'create', 'check' ), true ) ) {
        wp_send_json_error( array( 'message' => 'Неизвестная операция.' ), 400 );
    }

    $lock_name = 'plnt_tbank_links_lock_' . $order_id;
    $lock_at   = (int) get_option( $lock_name, 0 );
    if ( $lock_at && $lock_at < time() - 120 ) {
        delete_option( $lock_name );
    }
    if ( ! add_option( $lock_name, time(), '', false ) ) {
        wp_send_json_error( array( 'message' => 'По этому заказу уже выполняется запрос. Обновите страницу позже.' ), 409 );
    }

    try {
        $result = 'create' === $operation ? plnt_tbank_links_create( $order ) : plnt_tbank_links_check( $order );
    } catch ( Throwable $error ) {
        // Не раскрываем потенциально чувствительные сведения клиентскому JS.
        $result = new WP_Error( 'plnt_tbank_exception', 'Ошибка при выполнении операции. Проверьте журнал PHP.' );
        error_log( '[PLNT TBank links] ' . $error->getMessage() );
    } finally {
        delete_option( $lock_name );
    }

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
    }
    wp_send_json_success( array( 'message' => 'create' === $operation ? 'Ссылка создана.' : 'Текущий статус: ' . $result ) );
}

/**
 * Webhook только для созданных этим модулем платежей.
 * URL: https://ДОМЕН/?wc-api=plnt_tbank_links
 */
add_action( 'woocommerce_api_plnt_tbank_links', 'plnt_tbank_links_webhook' );
function plnt_tbank_links_webhook() {
    if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        plnt_tbank_links_webhook_reply( 405, 'METHOD NOT ALLOWED' );
    }

    $payload = json_decode( file_get_contents( 'php://input' ), true );
    if ( ! is_array( $payload ) || empty( $payload['Token'] ) || empty( $payload['OrderId'] ) || empty( $payload['PaymentId'] ) ) {
        plnt_tbank_links_webhook_reply( 400, 'BAD REQUEST' );
    }

    $settings = plnt_tbank_links_settings();
    if ( (string) ( $settings['merchant_id'] ?? '' ) !== (string) ( $payload['TerminalKey'] ?? '' ) ) {
        plnt_tbank_links_webhook_reply( 403, 'INVALID TERMINAL' );
    }
    $expected = plnt_tbank_links_token( $payload, (string) ( $settings['secret_key'] ?? '' ) );
    if ( ! hash_equals( $expected, (string) $payload['Token'] ) ) {
        plnt_tbank_links_webhook_reply( 403, 'INVALID TOKEN' );
    }

    if ( ! preg_match( '/^plnt-([0-9]+)-[a-f0-9]{12}$/D', (string) $payload['OrderId'], $matches ) ) {
        plnt_tbank_links_webhook_reply( 400, 'BAD ORDER' );
    }
    $order = wc_get_order( (int) $matches[1] );
    if ( ! $order ) {
        plnt_tbank_links_webhook_reply( 404, 'ORDER NOT FOUND' );
    }

    $state = (string) ( $payload['Status'] ?? '' );
    if ( '' === $state || ( 'CONFIRMED' === $state && empty( $payload['Success'] ) ) ) {
        plnt_tbank_links_webhook_reply( 400, 'INVALID PAYMENT STATUS' );
    }

    $history = plnt_tbank_links_history( $order );
    foreach ( $history as $index => $link ) {
        if ( (string) $link['order_id'] !== (string) $payload['OrderId'] ||
             (string) $link['payment_id'] !== (string) $payload['PaymentId'] ) {
            continue;
        }
        if ( ! isset( $payload['Amount'] ) || (int) $payload['Amount'] !== (int) $link['amount'] ) {
            plnt_tbank_links_webhook_reply( 400, 'AMOUNT MISMATCH' );
        }
        $applied = plnt_tbank_links_apply_status(
            $order, $index, (string) ( $payload['Status'] ?? '' ), $link['payment_id'], $link['amount']
        );
        if ( is_wp_error( $applied ) ) {
            plnt_tbank_links_webhook_reply( 400, 'PAYMENT MISMATCH' );
        }
        plnt_tbank_links_webhook_reply( 200, 'OK' );
    }

    // Уведомление могло прийти до ответа Init: пусть банк повторит доставку.
    plnt_tbank_links_webhook_reply( 503, 'PAYMENT NOT READY' );
}

function plnt_tbank_links_webhook_reply( $code, $message ) {
    status_header( $code );
    header( 'Content-Type: text/plain; charset=utf-8' );
    echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed server messages.
    exit;
}
