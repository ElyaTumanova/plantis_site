<?php
/**
 * MU-plugin: PLNT Payment Debug -> WooCommerce Logs
 * Focused diagnostics for checkout/payment failures.
 */

if (!defined('ABSPATH')) exit;

if (!defined('PLNT_REQUEST_ID')) {
    define('PLNT_REQUEST_ID', function_exists('wp_generate_uuid4') ? wp_generate_uuid4() : uniqid('plnt_', true));
}

function plnt_payment_logger() {
    return function_exists('wc_get_logger') ? wc_get_logger() : null;
}

function plnt_payment_log(string $level, string $message, array $context = []): void {
    $logger = plnt_payment_logger();
    if (!$logger) return;

    $base = [
        'source'     => 'plnt-payment',
        'request_id' => PLNT_REQUEST_ID,
        'uri'        => $_SERVER['REQUEST_URI'] ?? '',
        'method'     => $_SERVER['REQUEST_METHOD'] ?? '',
        'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    $logger->log($level, $message, $base + $context);
}

function plnt_is_checkout_request(): bool {
    if (!empty($_GET['wc-ajax']) && $_GET['wc-ajax'] === 'checkout') {
        return true;
    }

    if (!empty($_POST['payment_method'])) {
        return true;
    }

    if (!empty($_POST['woocommerce-process-checkout-nonce'])) {
        return true;
    }

    return false;
}

function plnt_mask_value(string $key, $value) {
    $sensitive_exact = [
        'billing_phone',
        'billing_email',
        'billing_address_1',
        'billing_address_2',
        'billing_postcode',
        'billing_first_name',
        'billing_last_name',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_postcode',
    ];

    $sensitive_contains = [
        'token',
        'card',
        'pan',
        'cvc',
        'cvv',
        'expiry',
        'exp_month',
        'exp_year',
    ];

    foreach ($sensitive_contains as $needle) {
        if (stripos($key, $needle) !== false) {
            return '[masked]';
        }
    }

    if (in_array($key, $sensitive_exact, true)) {
        if (!is_scalar($value)) {
            return '[masked]';
        }

        $value = (string) $value;
        $len = strlen($value);

        if ($len <= 2) return str_repeat('*', $len);
        if ($len <= 6) return substr($value, 0, 1) . str_repeat('*', max(1, $len - 2)) . substr($value, -1);

        return substr($value, 0, 2) . str_repeat('*', max(1, $len - 4)) . substr($value, -2);
    }

    return $value;
}

function plnt_normalize($value, int $depth = 0) {
    if ($depth > 3) return '[max-depth]';

    if (is_null($value) || is_bool($value) || is_int($value) || is_float($value) || is_string($value)) {
        return $value;
    }

    if (is_object($value)) {
        if ($value instanceof WC_Order) {
            return [
                'object'   => 'WC_Order',
                'order_id' => $value->get_id(),
                'status'   => $value->get_status(),
                'total'    => $value->get_total(),
            ];
        }

        if ($value instanceof WP_Error) {
            return [
                'object'   => 'WP_Error',
                'codes'    => $value->get_error_codes(),
                'messages' => $value->get_error_messages(),
            ];
        }

        return '[object ' . get_class($value) . ']';
    }

    if (is_array($value)) {
        $out = [];
        foreach ($value as $k => $v) {
            $out[$k] = plnt_normalize($v, $depth + 1);
        }
        return $out;
    }

    return '[unknown-type]';
}

function plnt_checkout_post_snapshot(): array {
    $post = $_POST ?? [];
    if (!is_array($post)) return [];

    $snapshot = [];
    $array_fields = [];

    foreach ($post as $key => $value) {
        $should_log =
            strpos($key, 'billing_') === 0 ||
            strpos($key, 'shipping_') === 0 ||
            strpos($key, 'wc_') === 0 ||
            in_array($key, [
                'payment_method',
                'shipping_method',
                'ship_to_different_address',
                'order_comments',
                'terms',
                'createaccount',
            ], true);

        if (!$should_log) {
            continue;
        }

        if (is_array($value)) {
            $array_fields[$key] = array_keys($value);

            $masked = [];
            foreach ($value as $k => $v) {
                $masked[$k] = plnt_mask_value($key, is_scalar($v) ? (string) $v : plnt_normalize($v));
            }
            $snapshot[$key] = $masked;
        } else {
            $snapshot[$key] = plnt_mask_value($key, is_scalar($value) ? (string) $value : plnt_normalize($value));
        }
    }

    return [
        'post_snapshot' => $snapshot,
        'array_fields'  => $array_fields,
    ];
}

$GLOBALS['plnt_payment_started_at'] = microtime(true);
$GLOBALS['plnt_payment_order_processed'] = false;
$GLOBALS['plnt_payment_runtime_errors'] = [];
$GLOBALS['plnt_payment_validation_errors'] = [];
$GLOBALS['plnt_payment_notices'] = [];

/**
 * Log wc_add_notice errors during checkout.
 */
add_filter('woocommerce_add_error', function ($message) {
    if (!plnt_is_checkout_request()) return $message;

    $GLOBALS['plnt_payment_notices'][] = $message;

    plnt_payment_log('error', 'wc_add_error', [
        'message_text' => is_scalar($message) ? (string) $message : '[non-scalar]',
    ]);

    return $message;
}, 9999);

/**
 * Early checkout marker.
 */
add_action('woocommerce_checkout_process', function () {
    if (!plnt_is_checkout_request()) return;

    $payment_method = $_POST['payment_method'] ?? '';
    $shipping = (function_exists('WC') && WC()->session) ? WC()->session->get('chosen_shipping_methods') : [];

    plnt_payment_log('info', 'checkout_process started', [
        'payment_method'  => is_scalar($payment_method) ? (string) $payment_method : '[non-scalar]',
        'chosen_shipping' => plnt_normalize($shipping),
    ] + plnt_checkout_post_snapshot());
}, 1);

/**
 * Validation errors.
 */
add_action('woocommerce_after_checkout_validation', function ($data, $errors) {
    if (!plnt_is_checkout_request()) return;

    $codes = method_exists($errors, 'get_error_codes') ? (array) $errors->get_error_codes() : [];
    $msgs  = method_exists($errors, 'get_error_messages') ? (array) $errors->get_error_messages() : [];

    if (!$codes && !$msgs) return;

    $GLOBALS['plnt_payment_validation_errors'] = [
        'error_codes'    => array_values($codes),
        'error_messages' => array_values($msgs),
    ];

    plnt_payment_log('error', 'checkout validation failed', [
        'payment_method' => is_array($data) ? ($data['payment_method'] ?? '') : '',
        'error_codes'    => array_values($codes),
        'error_messages' => array_values($msgs),
    ] + plnt_checkout_post_snapshot());
}, 5, 2);

/**
 * Runtime PHP warnings/notices during checkout.
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!plnt_is_checkout_request()) {
        return false;
    }

    $entry = [
        'errno' => $errno,
        'error' => $errstr,
        'file'  => $errfile,
        'line'  => $errline,
    ];

    $GLOBALS['plnt_payment_runtime_errors'][] = $entry;

    $interesting = (
        stripos($errstr, 'Array to string conversion') !== false ||
        stripos($errstr, 'Undefined array key') !== false ||
        stripos($errstr, 'TypeError') !== false ||
        stripos($errstr, 'Call to') !== false
    );

    if ($interesting) {
        plnt_payment_log('error', 'checkout runtime php error', $entry + plnt_checkout_post_snapshot());
    }

    return false;
}, E_ALL);

/**
 * Detect order creation.
 */
add_action('woocommerce_checkout_order_processed', function ($order_id, $posted_data, $order) {
    $GLOBALS['plnt_payment_order_processed'] = true;

    plnt_payment_log('info', 'order_processed OK', [
        'order_id'       => $order_id,
        'payment_method' => is_array($posted_data) ? ($posted_data['payment_method'] ?? '') : '',
        'order'          => plnt_normalize($order),
    ]);
}, 10, 3);

/**
 * Log payment complete / failed order status.
 */
add_action('woocommerce_payment_complete', function ($order_id) {
    plnt_payment_log('info', 'payment_complete', [
        'order_id' => $order_id,
    ]);
});

add_action('woocommerce_order_status_failed', function ($order_id) {
    plnt_payment_log('error', 'order_status_failed', [
        'order_id' => $order_id,
    ]);
});

/**
 * Final catcher for failed checkout without fatal.
 */
add_action('shutdown', function () {
    if (!plnt_is_checkout_request()) return;

    $duration_ms = (int) round((microtime(true) - ($GLOBALS['plnt_payment_started_at'] ?? microtime(true))) * 1000);

    if (!function_exists('wc_get_notices')) {
        return;
    }

    $notices = wc_get_notices();
    $error_notices = $notices['error'] ?? [];

    $should_log_failure =
        empty($GLOBALS['plnt_payment_order_processed']) &&
        (
            !empty($error_notices) ||
            !empty($GLOBALS['plnt_payment_runtime_errors']) ||
            !empty($GLOBALS['plnt_payment_validation_errors']) ||
            !empty($GLOBALS['plnt_payment_notices'])
        );

    if (!$should_log_failure) {
        return;
    }

    $payment_method = $_POST['payment_method'] ?? '';
    $shipping = (function_exists('WC') && WC()->session) ? WC()->session->get('chosen_shipping_methods') : [];

    plnt_payment_log('error', 'checkout_failed (shutdown catcher)', [
        'duration_ms'       => $duration_ms,
        'payment_method'    => is_scalar($payment_method) ? (string) $payment_method : '[non-scalar]',
        'chosen_shipping'   => plnt_normalize($shipping),
        'error_notices'     => plnt_normalize($error_notices),
        'validation_errors' => $GLOBALS['plnt_payment_validation_errors'],
        'runtime_errors'    => $GLOBALS['plnt_payment_runtime_errors'],
        'captured_notices'  => $GLOBALS['plnt_payment_notices'],
    ] + plnt_checkout_post_snapshot());
}, PHP_INT_MAX);