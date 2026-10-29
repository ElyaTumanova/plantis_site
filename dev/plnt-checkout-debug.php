<?php
/**
 * MU-plugin: PLNT Checkout Debug -> WooCommerce Logs
 * Logs checkout failures (even without PHP fatal) with minimal noise.
 */
if (!defined('ABSPATH')) exit;

function plnt_wc_logger() {
    return function_exists('wc_get_logger') ? wc_get_logger() : null;
}

function plnt_checkout_log(string $level, string $message, array $context = []): void {
    $logger = plnt_wc_logger();
    if (!$logger) return;

    $base = [
        'source' => 'plnt-checkout',
        'uri'    => $_SERVER['REQUEST_URI'] ?? '',
        'method' => $_SERVER['REQUEST_METHOD'] ?? '',
        'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    $logger->log($level, $message, $base + $context);
}

/**
 * Markers for this request
 */
$GLOBALS['plnt_checkout_ok'] = false;
$GLOBALS['plnt_checkout_validation_errors'] = [];

/**
 * 1) Validation errors (keep, but compact)
 */
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
    $codes = method_exists($errors, 'get_error_codes') ? (array) $errors->get_error_codes() : [];
    $msgs  = method_exists($errors, 'get_error_messages') ? (array) $errors->get_error_messages() : [];

    if (!$codes && !$msgs) return;

    $GLOBALS['plnt_checkout_validation_errors'] = [
        'error_codes'    => array_values($codes),
        'error_messages' => array_values($msgs),
        'payment_method' => is_array($data) ? ($data['payment_method'] ?? '') : '',
    ];

    plnt_checkout_log('error', 'after_checkout_validation errors', $GLOBALS['plnt_checkout_validation_errors']);
}, 5, 2);

/**
 * 2) Success marker
 */
add_action('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    $GLOBALS['plnt_checkout_ok'] = true;

    $payment  = is_array($posted_data) ? ($posted_data['payment_method'] ?? '') : '';
    $shipping = (function_exists('WC') && WC()->session) ? (WC()->session->get('chosen_shipping_methods') ?? []) : [];

    plnt_checkout_log('info', 'order_processed OK', [
        'order_id'        => $order_id,
        'payment_method'  => $payment,
        'chosen_shipping' => $shipping,
    ]);
}, 10, 3);

/**
 * 3) Final catcher: wc-ajax=checkout finished, but order was not created.
 * Logs ONLY if there are error notices, and ONLY if order_processed didn't happen.
 */
add_action('shutdown', function () {
    if (!empty($GLOBALS['plnt_checkout_ok'])) return;

    if (empty($_GET['wc-ajax']) || $_GET['wc-ajax'] !== 'checkout') return;
    if (!function_exists('wc_get_notices')) return;

    $notices = wc_get_notices('error');
    if (empty($notices)) return;

    // Avoid duplicate log if we already logged validation errors (optional)
    $validation = $GLOBALS['plnt_checkout_validation_errors'] ?? [];

    $payment_method = $_POST['payment_method'] ?? '';
    $chosen_shipping = (function_exists('WC') && WC()->session) ? (WC()->session->get('chosen_shipping_methods') ?? []) : [];

    plnt_checkout_log('error', 'checkout_failed (shutdown catcher)', [
        'payment_method'   => is_string($payment_method) ? $payment_method : '',
        'chosen_shipping'  => $chosen_shipping,
        'notices'          => $notices,
        'validation_codes' => $validation['error_codes'] ?? [],
    ]);
}, PHP_INT_MAX);
/**
 * 4) Delivery diagnostics. All entries go to the existing plnt-checkout log.
 * No customer addresses, contact details or full checkout POST payloads.
 */
function plnt_checkout_delivery_posted_data(): array {
    $data = [];

    if (isset($_POST['post_data']) && is_string($_POST['post_data'])) {
        parse_str(wp_unslash($_POST['post_data']), $data);
    } elseif (isset($_POST['shipping_method']) || isset($_POST['delivery_dates'])) {
        $data = wp_unslash($_POST);
    }

    $methods = $data['shipping_method'] ?? [];
    if (!is_array($methods)) {
        $methods = [$methods];
    }

    return [
        'shipping_method' => array_values(array_map('sanitize_text_field', $methods)),
        'delivery_date' => isset($data['delivery_dates']) && is_scalar($data['delivery_dates'])
            ? sanitize_text_field((string) $data['delivery_dates']) : '',
        'interval' => isset($data['additional_delivery_interval']) && is_scalar($data['additional_delivery_interval'])
            ? sanitize_text_field((string) $data['additional_delivery_interval']) : '',
    ];
}

function plnt_checkout_delivery_session_snapshot(): array {
    if (!function_exists('WC') || !WC()->session) {
        return [];
    }

    $session = WC()->session;
    $chosen = $session->get('chosen_shipping_methods', []);

    return [
        'chosen_shipping' => is_array($chosen) ? array_values($chosen) : [],
        'isUrgent' => $session->get('isUrgent'),
        'isLate' => $session->get('isLate'),
        'isExpensive' => $session->get('isExpensive'),
        'is_courier_deliv_flag' => $session->get('is_courier_deliv_flag'),
    ];
}

function plnt_checkout_delivery_rates_snapshot(): array {
    if (!function_exists('WC') || !WC()->shipping()) {
        return [];
    }

    $rates = [];
    foreach ((array) WC()->shipping()->get_packages() as $index => $package) {
        foreach (($package['rates'] ?? []) as $rate) {
            if (!$rate instanceof WC_Shipping_Rate) {
                continue;
            }
            $rates[] = [
                'package' => $index,
                'id' => $rate->get_id(),
                'cost' => $rate->get_cost(),
                'taxes' => array_sum(array_map('floatval', (array) $rate->get_taxes())),
            ];
        }
    }
    return $rates;
}

function plnt_checkout_delivery_snapshot(bool $include_rates = true): array {
    $snapshot = [
        'moscow_time' => wp_date('Y-m-d H:i:s', new DateTimeZone('Europe/Moscow')),
        'posted' => plnt_checkout_delivery_posted_data(),
        'session' => plnt_checkout_delivery_session_snapshot(),
    ];

    if (function_exists('WC') && WC()->cart) {
        $snapshot['cart'] = [
            'subtotal' => WC()->cart->get_subtotal(),
            'shipping_total' => WC()->cart->get_shipping_total(),
            'shipping_tax' => WC()->cart->get_shipping_tax(),
            'total' => WC()->cart->get_total('edit'),
        ];
        if (function_exists('check_if_large_delivery')) {
            $snapshot['cart']['is_large_delivery'] = (bool) check_if_large_delivery();
        }
        if (function_exists('get_delivery_markup')) {
            $snapshot['delivery_markup'] = get_delivery_markup();
        }
    }

    if ($include_rates) {
        $snapshot['rates'] = plnt_checkout_delivery_rates_snapshot();
    }

    return $snapshot;
}

// Captures flags sent by JS and their resulting session values.
// The existing AJAX callback terminates with wp_send_json_success(),
// so this diagnostic runs at shutdown rather than on a later AJAX hook.
add_action('shutdown', function () {
    if (!isset($_POST['action']) || $_POST['action'] !== 'get_urgent_shipping') {
        return;
    }

    $sent = [];
    foreach (['isUrgent', 'isLate', 'isExpensive'] as $key) {
        $sent[$key] = isset($_POST[$key]) && is_scalar($_POST[$key])
            ? sanitize_text_field(wp_unslash((string) $_POST[$key])) : null;
    }

    plnt_checkout_log('info', 'delivery AJAX flags', [
        'sent' => $sent,
        'session' => plnt_checkout_delivery_session_snapshot(),
    ]);
}, 50);

// Record the completed WooCommerce checkout recalculation, not just its start.
add_action('shutdown', function () {
    if (($_GET['wc-ajax'] ?? '') !== 'update_order_review') {
        return;
    }

    plnt_checkout_log('info', 'delivery checkout recalculated', plnt_checkout_delivery_snapshot());
}, 50);

// Capture exactly what was submitted when the order was created.
add_action('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    $shipping_lines = [];
    foreach ($order->get_items('shipping') as $item) {
        $shipping_lines[] = [
            'method_id' => $item->get_method_id(),
            'instance_id' => $item->get_instance_id(),
            'total' => $item->get_total(),
            'taxes' => $item->get_taxes(),
        ];
    }

    $submitted = [
        'shipping_method' => $posted_data['shipping_method'] ?? [],
        'delivery_date' => $posted_data['delivery_dates'] ?? '',
        'interval' => $posted_data['additional_delivery_interval'] ?? '',
    ];

    plnt_checkout_log('info', 'delivery order snapshot', [
        'order_id' => $order_id,
        'submitted' => $submitted,
        'saved_delivery_date' => $order->get_meta('delivery_dates'),
        'saved_interval' => $order->get_meta('additional_delivery_interval'),
        'order_shipping_total' => $order->get_shipping_total(),
        'order_shipping_tax' => $order->get_shipping_tax(),
        'order_shipping_lines' => $shipping_lines,
        'runtime' => plnt_checkout_delivery_snapshot(),
    ]);
}, 20, 3);
