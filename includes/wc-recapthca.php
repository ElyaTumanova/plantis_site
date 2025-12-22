<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action('init', function () {
    error_log('[MY_RECAPTCHA] FILE LOADED init ' . date('c'));
});

/**
 * === CONFIG ===
 * Те же ключи, что у CF7 v3
 */
if (!defined('MY_RECAPTCHA_SITE_KEY')) define('MY_RECAPTCHA_SITE_KEY', '6LcP2rIrAAAAAGxrNXEe4AP0rC_fXZ7v7vKVr4wF');
if (!defined('MY_RECAPTCHA_SECRET'))   define('MY_RECAPTCHA_SECRET',   '6LcP2rIrAAAAAKrpzHfISt0G08fTrh6k6v7C_MLh');
if (!defined('MY_RECAPTCHA_SCORE'))    define('MY_RECAPTCHA_SCORE',    0.5); // при ложных отказах попробуй 0.3

/**
 * === DEBUG LOG ===
 * Чтобы error_log писал в wp-content/debug.log включите в wp-config.php:
 * define('WP_DEBUG', true);
 * define('WP_DEBUG_LOG', true);
 * define('WP_DEBUG_DISPLAY', false);
 */
if (!defined('MY_RECAPTCHA_DEBUG')) define('MY_RECAPTCHA_DEBUG', true);

function my_recaptcha_log($msg, $context = []) {
	if (!MY_RECAPTCHA_DEBUG) return;
	$ctx = '';
	if (!empty($context)) {
		$ctx = ' | ' . wp_json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	error_log('[MY_RECAPTCHA] ' . $msg . $ctx);
}

add_action('user_register', function($user_id){
    $user = get_userdata($user_id);
    $email = $user ? $user->user_email : '';
    $login = $user ? $user->user_login : '';

    error_log('[MY_RECAPTCHA] USER_REGISTER EVENT | ' . wp_json_encode([
        'user_id'    => $user_id,
        'login'      => $login,
        'email'      => $email,
        'request_uri'=> $_SERVER['REQUEST_URI'] ?? '',
        'method'     => $_SERVER['REQUEST_METHOD'] ?? '',
        'referer'    => $_SERVER['HTTP_REFERER'] ?? '',
        'ua'         => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}, 10, 1);


/**
 * === 1) Hidden field injection in Woo forms ===
 * Вставляем <input name="g-recaptcha-response"> в формы на /my-account
 */
add_action('woocommerce_login_form', function () {
	echo '<input type="hidden" name="g-recaptcha-response" value="">';
});

add_action('woocommerce_register_form', function () {
	echo '<input type="hidden" name="g-recaptcha-response" value="">';
});

// (Опционально, но полезно) На checkout тоже может создаваться аккаунт
add_action('woocommerce_checkout_before_customer_details', function () {
	echo '<input type="hidden" name="g-recaptcha-response" value="">';
});

/**
 * === 2) Server-side verify ===
 */
function my_recaptcha_v3_verify_token($token){
	if (empty($token)) {
		return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: пустой токен.', 'woocommerce'));
	}

	$res = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
		'body' => [
			'secret'   => MY_RECAPTCHA_SECRET,
			'response' => sanitize_text_field($token),
			'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
		],
		'timeout' => 10,
	]);

	if (is_wp_error($res)) {
		return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: сбой соединения.', 'woocommerce'));
	}

	$raw  = wp_remote_retrieve_body($res);
	$body = json_decode($raw, true);

	if (!is_array($body)) {
		my_recaptcha_log('Bad JSON from Google', ['raw' => $raw]);
		return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: некорректный ответ проверки.', 'woocommerce'));
	}

	// DEBUG: логируем ответ (без токена)
	my_recaptcha_log('Google response', [
		'success'  => $body['success'] ?? null,
		'score'    => $body['score'] ?? null,
		'action'   => $body['action'] ?? null,
		'hostname' => $body['hostname'] ?? null,
		'errors'   => $body['error-codes'] ?? null,
	]);

	if (empty($body['success'])) {
		return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: проверка не пройдена.', 'woocommerce'));
	}

	// score (v3)
	$score = isset($body['score']) ? (float)$body['score'] : 0.0;
	if ($score < (float)MY_RECAPTCHA_SCORE) {
		return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: подозрительная активность.', 'woocommerce'));
	}

	// hostname (рекомендуется)
	$allowed_hosts = [
		parse_url(home_url(), PHP_URL_HOST),
	];
	$allowed_hosts = array_filter(array_unique($allowed_hosts));

	if (!empty($body['hostname']) && !in_array($body['hostname'], $allowed_hosts, true)) {
		return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: неверный домен.', 'woocommerce'));
	}

	return true;
}

/**
 * === 3) Login protection (WordPress auth) ===
 */
// Вход (ТОЛЬКО WooCommerce формы, не трогаем wp-login.php / админку)
add_filter('authenticate', function($user, $username, $password){

    // если уже ошибка — не мешаем
    if (is_wp_error($user)) return $user;

    // 1) Не проверяем вход в админку / wp-login.php
    // (и вообще любые экраны, где нет вашего JS)
    if (is_admin() || (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php')) {
        return $user;
    }

    // 2) Проверяем ТОЛЬКО если это WooCommerce login form
    // Признак Woo-логина: woocommerce-login-nonce присутствует в POST
    if (empty($_POST['woocommerce-login-nonce'])) {
        return $user; // не Woo-логин → не вмешиваемся
    }

    // 3) Здесь уже точно Woo login → проверяем токен
    my_recaptcha_log('authenticate (Woo login) fired', [
        'username'  => $username,
        'has_token' => !empty($_POST['g-recaptcha-response']),
    ]);

    $token = $_POST['g-recaptcha-response'] ?? '';
    $ok = my_recaptcha_v3_verify_token($token);

    if (is_wp_error($ok)) {
        my_recaptcha_log('authenticate (Woo login) blocked', [
            'error' => $ok->get_error_message(),
        ]);
        return $ok;
    }

    return $user;

}, 21, 3);


/**
 * === 4) Registration protection (WooCommerce) ===
 * Ваш старый хук (оставляем)
 */
// add_action('woocommerce_register_post', function($username, $email, $errors){
// 	my_recaptcha_log('woocommerce_register_post fired', [
// 		'email' => $email,
// 		'has_token' => !empty($_POST['g-recaptcha-response']),
// 	]);

// 	$token = $_POST['g-recaptcha-response'] ?? '';
// 	$ok = my_recaptcha_v3_verify_token($token);

// 	if (is_wp_error($ok)) {
// 		my_recaptcha_log('woocommerce_register_post blocked', [
// 			'error' => $ok->get_error_message(),
// 		]);
// 		$errors->add($ok->get_error_code(), $ok->get_error_message());
// 	}
// }, 10, 3);

/**
 * === 5) Registration protection (универсально через errors filter) ===
 * Это часто “надежнее”, чем woocommerce_register_post, и ловит больше кейсов.
 */
add_filter('woocommerce_process_registration_errors', function($errors, $username, $email){
	my_recaptcha_log('woocommerce_process_registration_errors fired', [
		'email' => $email,
		'has_token' => !empty($_POST['g-recaptcha-response']),
	]);

	$token = $_POST['g-recaptcha-response'] ?? '';
	$ok = my_recaptcha_v3_verify_token($token);

	if (is_wp_error($ok)) {
		my_recaptcha_log('woocommerce_process_registration_errors blocked', [
			'error' => $ok->get_error_message(),
		]);
		$errors->add($ok->get_error_code(), $ok->get_error_message());
	}

	return $errors;
}, 10, 3);

/**
 * === 6) Checkout protection (если аккаунт создаётся при оформлении) ===
 */
add_action('woocommerce_checkout_process', function(){
	// Уже залогинен — не проверяем
	if (is_user_logged_in()) return;

	$creating_account =
		!empty($_POST['createaccount']) ||
		(function_exists('WC') && WC()->checkout() && WC()->checkout()->is_registration_required());

	my_recaptcha_log('woocommerce_checkout_process fired', [
		'creating_account' => $creating_account,
		'has_token' => !empty($_POST['g-recaptcha-response']),
	]);

	if (!$creating_account) return;

	$token = $_POST['g-recaptcha-response'] ?? '';
	$ok = my_recaptcha_v3_verify_token($token);

	if (is_wp_error($ok)) {
		my_recaptcha_log('checkout blocked', [
			'error' => $ok->get_error_message(),
		]);
		wc_add_notice($ok->get_error_message(), 'error');
	}
});
