<?php
/**
 * Plantis — WooCommerce tweaks (My Account, notices, login ajax, texts)
 *
 * СОДЕРЖАНИЕ:
 * - Личный кабинет: меню, переименование вкладки "Профиль", заголовок endpoint
 * - Заказы: заголовок блока доставки
 * - Регистрация: отключение проверки сложности пароля
 * - Перевод/замена текста через gettext
 * - Кнопки-переключатели в формах Login/Register
 * - Notices: перенос вывода уведомлений
 * - AJAX логин (nopriv)
 * - Edit account: убрать обязательность фамилии
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* =========================================================
 * Личный кабинет: меню и названия
 * ========================================================= */

/**
 * Убираем ненужные пункты меню из личного кабинета.
 */
add_filter( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ) {

	unset( $menu_links['edit-address'] ); // Addresses
	// unset( $menu_links['dashboard'] );    // Dashboard
	// unset( $menu_links['payment-methods'] ); // Payment Methods
	// unset( $menu_links['orders'] );          // Orders
	// unset( $menu_links['downloads'] );       // Downloads
	// unset( $menu_links['edit-account'] );    // Account details
	// unset( $menu_links['customer-logout'] ); // Logout

	return $menu_links;
}

/**
 * Переименовываем вкладку "edit-account" в "Профиль".
 */
add_filter( 'woocommerce_account_menu_items', 'truemisha_rename_menu', 25 );
function truemisha_rename_menu( $menu_links ) {
	$menu_links['edit-account'] = 'Настройки';
	$menu_links['dashboard'] = 'Профиль';
	return $menu_links;
}

/**
 * Меняем заголовок страницы endpoint "edit-account".
 */
add_filter( 'woocommerce_endpoint_edit-account_title', 'change_my_account_editaccount_title', 10, 2 );
function change_my_account_editaccount_title( $title, $endpoint ) {
	$title = __( 'Профиль', 'woocommerce' );
	return $title;
}

/* =========================================================
 * Заказы: заголовок блока доставки
 * ========================================================= */

/**
 * Добавляем заголовок "Информация о доставке" на странице деталей заказа.
 */
add_action( 'woocommerce_order_details_after_order_table', 'plnt_order_adress_header' );
function plnt_order_adress_header() {
	echo '<h2 class="woocommerce-order-details__title">Информация о доставке</h2>';
}

/* =========================================================
 * Регистрация: отключение проверки сложности пароля
 * ========================================================= */

/**
 * Убираем требования к сложности пароля при регистрации.
 */
add_action( 'wp_print_scripts', 'remove_wc_password_meter', 100 );
function remove_wc_password_meter() {
	wp_dequeue_script( 'wc-password-strength-meter' );
}

/* =========================================================
 * Перевод/замена текста через gettext
 * ========================================================= */

/**
 * Заменяем строку "Платёжный адрес" на "Контактная информация:".
 */
function plnt_change_text_order_1( $translated_text ) {
	if ( $translated_text == 'Платёжный адрес' ) {
		$translated_text = 'Контактная информация:';
	}
	return $translated_text;
}
add_filter( 'gettext', 'plnt_change_text_order_1', 20 );

/* =========================================================
 * Кнопки-переключатели в формах Login/Register
 * ========================================================= */

/**
 * Добавляем кнопку "Вход" в конец формы регистрации.
 */
add_action( 'woocommerce_register_form_end', function () {
	echo '<div class=register-form__login-btn>Вход</div>';
} );

/**
 * Добавляем кнопку "Регистрация" в конец формы логина.
 */
add_action( 'woocommerce_login_form_end', function () {
	echo '<div class=login-form__registration-btn>Регистрация</div>';
} );

/* =========================================================
 * AJAX логин (nopriv)
 * ========================================================= */

add_action( 'wp_ajax_nopriv_plantis_ajax_login', 'plantis_ajax_login' );
function plantis_ajax_login() {
	check_ajax_referer( 'plantis_ajax_login', 'nonce' );

	$username = isset( $_POST['username'] ) ? wc_clean( wp_unslash( $_POST['username'] ) ) : '';
	$password = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : '';
	$remember = ! empty( $_POST['rememberme'] );

	$creds = [
		'user_login'    => $username,
		'user_password' => $password,
		'remember'      => $remember,
	];

	$user = wp_signon( $creds, is_ssl() );

	if ( is_wp_error( $user ) ) {
		$code = $user->get_error_code();

		$map = [
			'incorrect_password' => 'Неверный пароль. Попробуйте ещё раз.',
			'invalid_username'   => 'Пользователь не найден. Проверьте email.',
			'empty_username'     => 'Введите email или логин.',
			'empty_password'     => 'Введите пароль.',
		];

		$msg = $map[ $code ] ?? $user->get_error_message();
		wc_add_notice( $msg, 'error' );

		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => $code,
		] );
	}

	wp_set_current_user( $user->ID );

	wp_send_json_success( [
		'ok'       => true,
		'redirect' => wc_get_page_permalink( 'myaccount' ),
	] );
}


/* =========================================================
 * AJAX регистрация (nopriv)
 * ========================================================= */

add_action( 'wp_ajax_nopriv_plantis_ajax_register', 'plantis_ajax_register' );
function plantis_ajax_register() {
	check_ajax_referer( 'plantis_ajax_register', 'nonce' );

	$email    = isset( $_POST['email'] ) ? wc_clean( wp_unslash( $_POST['email'] ) ) : '';
	$password = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : '';

	$email = strtolower( trim( $email ) );

	// Базовые проверки (под твои поля)
	if ( empty( $email ) ) {
		wc_add_notice( 'Введите email.', 'error' );
		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => 'empty_email',
		] );
	}

	if ( ! is_email( $email ) ) {
		wc_add_notice( 'Некорректный email.', 'error' );
		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => 'invalid_email',
		] );
	}

	if ( empty( $password ) ) {
		wc_add_notice( 'Введите пароль.', 'error' );
		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => 'empty_password',
		] );
	}

	// Если Woo отключил регистрацию — сразу ошибка (на всякий)
	if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
		wc_add_notice( 'Регистрация отключена на сайте.', 'error' );
		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => 'registration_disabled',
		] );
	}

	if ( email_exists( $email ) ) {
		wc_add_notice( 'Пользователь с таким email уже зарегистрирован.', 'error' );
		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => 'email_exists',
		] );
	}

	// Woo обычно использует email как логин (если отдельного поля логина нет)
	$username = $email;

	$user_id = wc_create_new_customer( $email, $username, $password );

	if ( is_wp_error( $user_id ) ) {
		$code = $user_id->get_error_code();

		$map = [
			'registration-error-email-exists'    => 'Пользователь с таким email уже зарегистрирован.',
			'registration-error-invalid-email'   => 'Некорректный email.',
			'registration-error-username-exists' => 'Пользователь уже существует.',
		];

		$msg = $map[ $code ] ?? $user_id->get_error_message();

		wc_add_notice( $msg, 'error' );

		wp_send_json_success( [
			'ok'      => false,
			'notices' => wc_print_notices( true ),
			'code'    => $code,
		] );
	}

	// Сразу авторизуем
	wc_set_customer_auth_cookie( $user_id );

	wp_send_json_success( [
		'ok'       => true,
		'redirect' => wc_get_page_permalink( 'myaccount' ),
	] );
}
/* =========================================================
 * Edit account: убрать обязательность фамилии
 * ========================================================= */

/**
 * Убираем обязательность фамилии в форме редактирования аккаунта.
 */
add_filter( 'woocommerce_save_account_details_required_fields', function ( $required ) {
	// Убираем обязательность фамилии
	if ( isset( $required['account_last_name'] ) ) {
		unset( $required['account_last_name'] );
	}
	return $required;
} );