<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// убираем ненужные пункты меню из личного кабинета

add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
 
	unset( $menu_links['edit-address'] ); // Addresses
	unset( $menu_links['dashboard'] ); // Dashboard
	//unset( $menu_links['payment-methods'] ); // Payment Methods
	//unset( $menu_links['orders'] ); // Orders
	//unset( $menu_links['downloads'] ); // Downloads
	//unset( $menu_links['edit-account'] ); // Account details
	//unset( $menu_links['customer-logout'] ); // Logout
 
	return $menu_links;
}
//изменяем название вкладки Профиль (Анкета)
add_filter( 'woocommerce_account_menu_items', 'truemisha_rename_menu', 25 );
 
function truemisha_rename_menu( $menu_links ){
	$menu_links[ 'edit-account' ] = 'Профиль'; 
	return $menu_links; 
}

add_filter( 'woocommerce_endpoint_edit-account_title', 'change_my_account_editaccount_title', 10, 2 );
function change_my_account_editaccount_title( $title, $endpoint ) {
    $title = __( "Профиль", "woocommerce" );
    return $title;
}

//добавляем заголовок

add_action ('woocommerce_order_details_after_order_table','plnt_order_adress_header');

function plnt_order_adress_header () {
	echo '<h2 class="woocommerce-order-details__title">Информация о доставке</h2>';
}


// add_action ('woocommerce_login_form_end','plnt_login_form');

// function plnt_login_form () {
// 	echo '<div class=login-form__registration-btn>Регистрация</div>';
// }

//убираем требования к сложности пароля при регистрации
add_action( 'wp_print_scripts', 'remove_wc_password_meter', 100 );
function remove_wc_password_meter() {
	wp_dequeue_script('wc-password-strength-meter');
}

// add_filter( 'woocommerce_login_redirect', 'truemisha_login_redirect', 25, 2 );
 
// function truemisha_login_redirect( $redirect, $user ) {
 
// 	$redirect = site_url();
// 	return $redirect;
 
// }

//add_action('plnt_header_notice','woocommerce_output_all_notices', 10 );


// перевод текстов

function plnt_change_text_order_1( $translated_text ) {
    if ( $translated_text == 'Платёжный адрес' ) {
      $translated_text = 'Контактная информация:';
    }
    return $translated_text;
  }
  add_filter( 'gettext', 'plnt_change_text_order_1', 20 );

add_action('woocommerce_register_form_end', function () {
    echo '<div class=register-form__login-btn>Вход</div>';
});

add_action('woocommerce_login_form_end', function () {
    echo '<div class=login-form__registration-btn>Регистрация</div>';
});