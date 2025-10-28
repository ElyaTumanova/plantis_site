<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// те же ключи, что у CF7 v3
if (!defined('MY_RECAPTCHA_SITE_KEY')) define('MY_RECAPTCHA_SITE_KEY', '6LcP2rIrAAAAAGxrNXEe4AP0rC_fXZ7v7vKVr4wF');
if (!defined('MY_RECAPTCHA_SECRET'))   define('MY_RECAPTCHA_SECRET', '6LcP2rIrAAAAAKrpzHfISt0G08fTrh6k6v7C_MLh');
if (!defined('MY_RECAPTCHA_SCORE'))    define('MY_RECAPTCHA_SCORE', 0.5); // при ложных отказах попробуй 0.3


//Скрытые поля в формы входа/регистрации (через хуки Woo)

// Вставляем <input name="g-recaptcha-response"> в формы на /my-account
add_action('woocommerce_login_form', function () {
    echo '<input type="hidden" name="g-recaptcha-response" value="">';
});
add_action('woocommerce_register_form', function () {
    echo '<input type="hidden" name="g-recaptcha-response" value="">';
});

//Серверная проверка (универсально, без привязки к странице)

function my_recaptcha_v3_verify_token($token){
    if (empty($token)) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: пустой токен.', 'woocommerce'));
    }
    $res = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'body' => [
            'secret'   => MY_RECAPTCHA_SECRET,
            'response' => sanitize_text_field($token),
        ],
        'timeout' => 10,
    ]);
    if (is_wp_error($res)) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: сбой соединения.', 'woocommerce'));
    }
    $body = json_decode(wp_remote_retrieve_body($res), true);

    if (empty($body['success'])) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: проверка не пройдена.', 'woocommerce'));
    }
    if (isset($body['score']) && floatval($body['score']) < floatval(MY_RECAPTCHA_SCORE)) {
        return new WP_Error('captcha_error', __('Ошибка reCAPTCHA: подозрительная активность.', 'woocommerce'));
    }
    return true;
}

// Вход
add_filter('authenticate', function($user, $username, $password){
    if (is_wp_error($user)) return $user;
    $token = $_POST['g-recaptcha-response'] ?? '';
    $ok = my_recaptcha_v3_verify_token($token);
    if (is_wp_error($ok)) return $ok;
    return $user;
}, 21, 3);

// Регистрация
add_action('woocommerce_register_post', function($username, $email, $errors){
    $token = $_POST['g-recaptcha-response'] ?? '';
    $ok = my_recaptcha_v3_verify_token($token);
    if (is_wp_error($ok)) {
        $errors->add($ok->get_error_code(), $ok->get_error_message());
    }
}, 10, 3);
