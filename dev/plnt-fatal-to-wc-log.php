<?php
/**
 * MU-plugin: PLNT Fatal -> WooCommerce Logs
 * Writes PHP fatal errors to WooCommerce logger (wc-logs) on shutdown.
 */

if ( ! defined('ABSPATH') ) exit;

add_action('shutdown', function () {
    $e = error_get_last();
    if ( ! $e ) return;

    // Fatal / critical types.
    $fatal_types = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
    if ( ! in_array($e['type'], $fatal_types, true) ) return;

    // Build payload.
    $payload = [
        'type'    => $e['type'],
        'message' => $e['message'] ?? '',
        'file'    => $e['file'] ?? '',
        'line'    => $e['line'] ?? '',
        'uri'     => $_SERVER['REQUEST_URI'] ?? '',
        'method'  => $_SERVER['REQUEST_METHOD'] ?? '',
        'ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
    ];

    // Write to WooCommerce logs if possible, fallback to error_log.
    if ( function_exists('wc_get_logger') ) {
        try {
            wc_get_logger()->critical(
                'PHP FATAL (shutdown): ' . wp_json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ['source' => 'plnt-fatal']
            );
            return;
        } catch (Throwable $t) {
            // fall through to error_log
        }
    }

    error_log('PLNT-FATAL (shutdown): ' . json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}, PHP_INT_MAX);