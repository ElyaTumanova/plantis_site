<?php
/**
 * WooCommerce reCAPTCHA v3 (login/register/checkout) + clean logging
 */
if ( ! defined('ABSPATH') ) exit;

/** =======================
 *  CONFIG
 *  ======================= */
if (!defined('MY_RECAPTCHA_SITE_KEY')) define('MY_RECAPTCHA_SITE_KEY', '6LezYTQsAAAAAEzapFcvWQ9w9vAP1uCYtNKXKfXy');
if (!defined('MY_RECAPTCHA_SECRET')) {
    error_log('[MY_RECAPTCHA] FATAL: SECRET NOT DEFINED');
}
if (!defined('MY_RECAPTCHA_SCORE'))    define('MY_RECAPTCHA_SCORE',    0.7); // поднимите (у вас боты получали 0.7-0.9)
if (!defined('MY_RECAPTCHA_DEBUG'))    define('MY_RECAPTCHA_DEBUG',    true);

/**
 * Разрешённые action от reCAPTCHA v3.
 * Сейчас ваш JS в логах отдавал action "woocommerce".
 * Если поправите JS — добавьте сюда нужные.
 */
if (!defined('MY_RECAPTCHA_ALLOWED_ACTIONS')) {
    define('MY_RECAPTCHA_ALLOWED_ACTIONS', wp_json_encode([
        'woocommerce',          // текущий action из вашего JS/CF7-интеграции
        'woocommerce_submit',
        'woocommerce_register',
        'woocommerce_login',
        'woocommerce_modal',
        'checkout'
    ]));
}

/** Rate limit для регистрации (простая защита) */
if (!defined('MY_RECAPTCHA_REG_LIMIT_MAX'))    define('MY_RECAPTCHA_REG_LIMIT_MAX', 3);      // попыток
if (!defined('MY_RECAPTCHA_REG_LIMIT_WINDOW')) define('MY_RECAPTCHA_REG_LIMIT_WINDOW', 3600); // секунд

/** =======================
 *  LOGGING
 *  ======================= */
function my_recaptcha_log(string $msg, array $context = []): void {
    if (!defined('MY_RECAPTCHA_DEBUG') || !MY_RECAPTCHA_DEBUG) return;

    $ctx = $context ? (' | ' . wp_json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) : '';
    error_log('[MY_RECAPTCHA] ' . $msg . $ctx);
}

/** Опциональный "пинг" для проверки, что код грузится (не спамим) */
add_action('init', function () {
    static $once = false;
    if ($once) return;
    $once = true;
    my_recaptcha_log('FILE LOADED', ['time_utc' => gmdate('c')]);
}, 1);

/** =======================
 *  HELPERS
 *  ======================= */
function my_recaptcha_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function my_recaptcha_allowed_hosts(): array {
    $host = parse_url(home_url(), PHP_URL_HOST);
    return array_values(array_filter(array_unique([$host])));
}

function my_recaptcha_allowed_actions(): array {
    $raw = MY_RECAPTCHA_ALLOWED_ACTIONS;
    $arr = json_decode($raw, true);
    return is_array($arr) ? $arr : ['woocommerce'];
}

/** простейший rate-limit по IP */
function my_recaptcha_rate_limit_ok(string $bucket, int $max, int $window_sec): bool {
    $ip = my_recaptcha_ip();
    if (!$ip) return true;

    $key = 'my_rc_rl_' . md5($bucket . '|' . $ip);
    $cnt = (int) get_transient($key);
    $cnt++;
    set_transient($key, $cnt, $window_sec);

    return $cnt <= $max;
}
//Сделайте строгую проверку action по месту (login/register/checkout)
function my_recaptcha_expect_action(string $expected, string $purpose = '') {
    $posted = (string)($_POST['g-recaptcha-action'] ?? '');
    if ($posted === '') {
        my_recaptcha_log('Missing posted action', ['purpose' => $purpose]);
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: отсутствует action.', 'woocommerce'));
    }
    if ($posted !== $expected) {
        my_recaptcha_log('Posted action mismatch', [
            'purpose'   => $purpose,
            'expected'  => $expected,
            'posted'    => $posted,
        ]);
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: неверный action.', 'woocommerce'));
    }
    return true;
}


/** =======================
 *  1) Hidden field injection
 *  ======================= */
add_action('woocommerce_login_form', function () {
    echo '<input type="hidden" name="g-recaptcha-response" value="">';
    echo '<input type="hidden" name="g-recaptcha-action" value="">';
});
add_action('woocommerce_register_form', function () {
    echo '<input type="hidden" name="g-recaptcha-response" value="">';
    echo '<input type="hidden" name="g-recaptcha-action" value="">';
});

/**
 * На checkout поле лучше вставлять в саму форму, но этот хук хотя бы добавит в разметку.
 * Если у вас есть кастомный checkout, и поле уже есть — можете убрать.
 */
add_action('woocommerce_checkout_before_customer_details', function () {
    echo '<input type="hidden" name="g-recaptcha-response" value="">';
    echo '<input type="hidden" name="g-recaptcha-action" value="">';
});

/** =======================
 *  2) Server-side verify
 *  ======================= */
function my_recaptcha_v3_verify_token(string $token, string $purpose = '') {
    $token = trim((string)$token);
    if ($token === '') {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: пустой токен.', 'woocommerce'));
    }

    $res = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'timeout' => 10,
        'body' => [
            'secret'   => MY_RECAPTCHA_SECRET,
            'response' => sanitize_text_field($token),
            'remoteip' => my_recaptcha_ip(),
        ],
    ]);

    if (is_wp_error($res)) {
        my_recaptcha_log('Google request error', ['purpose' => $purpose, 'err' => $res->get_error_message()]);
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: сбой соединения.', 'woocommerce'));
    }

    $raw  = wp_remote_retrieve_body($res);
    $body = json_decode($raw, true);

    $posted_action = (string)($_POST['g-recaptcha-action'] ?? '');
    if (!empty($body['action']) && $posted_action !== '' && $body['action'] !== $posted_action) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: action не совпадает.', 'woocommerce'));
    }

    if (!is_array($body)) {
        my_recaptcha_log('Bad JSON from Google', ['purpose' => $purpose, 'raw' => $raw]);
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: некорректный ответ проверки.', 'woocommerce'));
    }

    my_recaptcha_log('Google response', [
        'purpose'  => $purpose,
        'success'  => $body['success'] ?? null,
        'score'    => $body['score'] ?? null,
        'action'   => $body['action'] ?? null,
        'hostname' => $body['hostname'] ?? null,
        'errors'   => $body['error-codes'] ?? null,
    ]);

    if (empty($body['success'])) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: проверка не пройдена.', 'woocommerce'));
    }

    // action check (v3)
    if (!empty($body['action'])) {
        $allowed = my_recaptcha_allowed_actions();
        if (!in_array($body['action'], $allowed, true)) {
            return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: неверный action.', 'woocommerce'));
        }
    }

    // score check
    $score = isset($body['score']) ? (float)$body['score'] : 0.0;
    if ($score < (float)MY_RECAPTCHA_SCORE) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: подозрительная активность.', 'woocommerce'));
    }

    // hostname check (рекомендуется)
    if (!empty($body['hostname'])) {
        $allowed_hosts = my_recaptcha_allowed_hosts();
        if (!in_array($body['hostname'], $allowed_hosts, true)) {
            return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: неверный домен.', 'woocommerce'));
        }
    }

    return true;
}

/** =======================
 *  3) Login protection (только Woo login)
 *  ======================= */
add_filter('authenticate', function($user, $username, $password){

    if (is_wp_error($user)) return $user;

    // Не трогаем wp-login.php и админку
    if (is_admin() || (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php')) {
        return $user;
    }

    // Только Woo login form (по nonce)
    if (empty($_POST['woocommerce-login-nonce'])) {
        return $user;
    }

    my_recaptcha_log('authenticate (Woo login) fired', [
        'username'  => (string)$username,
        'has_token' => !empty($_POST['g-recaptcha-response']),
    ]);

    $act_ok = my_recaptcha_expect_action('woocommerce_login', 'woo_login');
    if (is_wp_error($act_ok)) return $act_ok;


    $ok = my_recaptcha_v3_verify_token((string)($_POST['g-recaptcha-response'] ?? ''), 'woo_login');

    if (is_wp_error($ok)) {
        my_recaptcha_log('authenticate (Woo login) blocked', ['error' => $ok->get_error_message()]);
        return $ok;
    }

    return $user;

}, 21, 3);

/** =======================
 *  4) Registration protection (Woo)
 *  ======================= */
add_filter('woocommerce_process_registration_errors', function($errors, $username, $email){

    // rate-limit (на попытки регистрации)
    if (!my_recaptcha_rate_limit_ok('register', (int)MY_RECAPTCHA_REG_LIMIT_MAX, (int)MY_RECAPTCHA_REG_LIMIT_WINDOW)) {
        $errors->add('captcha_error', __('Слишком много попыток регистрации. Попробуйте позже.', 'woocommerce'));
        my_recaptcha_log('registration rate-limited', ['ip' => my_recaptcha_ip()]);
        return $errors;
    }

    my_recaptcha_log('woocommerce_process_registration_errors fired', [
        'email_arg'  => (string)$email,
        'email_post' => (string)($_POST['email'] ?? ''),
        'has_token'  => !empty($_POST['g-recaptcha-response']),
    ]);

    $act_ok = my_recaptcha_expect_action('woocommerce_register', 'woo_register');
    if (is_wp_error($act_ok)) {
        $errors->add($act_ok->get_error_code(), $act_ok->get_error_message());
        return $errors;
    }

    $ok = my_recaptcha_v3_verify_token((string)($_POST['g-recaptcha-response'] ?? ''), 'woo_register');

    if (is_wp_error($ok)) {
        my_recaptcha_log('woocommerce_process_registration_errors blocked', ['error' => $ok->get_error_message()]);
        $errors->add($ok->get_error_code(), $ok->get_error_message());
    }

    return $errors;
}, 10, 3);

/** =======================
 *  5) Checkout protection (если аккаунт создаётся при оформлении)
 *  ======================= */
add_action('woocommerce_checkout_process', function(){

    if (is_user_logged_in()) return;

    $creating_account =
        !empty($_POST['createaccount']) ||
        (function_exists('WC') && WC()->checkout() && WC()->checkout()->is_registration_required());

    my_recaptcha_log('woocommerce_checkout_process fired', [
        'creating_account' => (bool)$creating_account,
        'has_token'        => !empty($_POST['g-recaptcha-response']),
    ]);

    if (!$creating_account) return;

    // отдельный rate-limit на чек-аут регистрации
    if (!my_recaptcha_rate_limit_ok('checkout_register', 5, 3600)) {
        wc_add_notice(__('Слишком много попыток. Попробуйте позже.', 'woocommerce'), 'error');
        my_recaptcha_log('checkout rate-limited', ['ip' => my_recaptcha_ip()]);
        return;
    }

    $expected = 'checkout'; // или 'woocommerce_register' если вы так решите
    $act_ok = my_recaptcha_expect_action($expected, 'checkout_register');
    if (is_wp_error($act_ok)) {
        wc_add_notice($act_ok->get_error_message(), 'error');
        return;
    }


    $ok = my_recaptcha_v3_verify_token((string)($_POST['g-recaptcha-response'] ?? ''), 'checkout_register');

    if (is_wp_error($ok)) {
        my_recaptcha_log('checkout blocked', ['error' => $ok->get_error_message()]);
        wc_add_notice($ok->get_error_message(), 'error');
    }
});

/** =======================
 *  6) Diagnostics: log real WP new-user mail (optional)
 *  ======================= */
add_filter('wp_mail', function($args){
    $subject = $args['subject'] ?? '';
    if ($subject && mb_stripos($subject, 'Регистрация нового пользователя') !== false) {
        my_recaptcha_log('WP_MAIL new-user', [
            'to'      => is_array($args['to'] ?? null) ? implode(',', $args['to']) : ($args['to'] ?? ''),
            'subject' => $subject,
        ]);
    }
    return $args;
});
