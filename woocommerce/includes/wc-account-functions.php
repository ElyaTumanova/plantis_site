<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// убираем ненужные пункты меню из личного кабинета

add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
 
	//unset( $menu_links['edit-address'] ); // Addresses
	unset( $menu_links['dashboard'] ); // Dashboard
	//unset( $menu_links['payment-methods'] ); // Payment Methods
	//unset( $menu_links['orders'] ); // Orders
	//unset( $menu_links['downloads'] ); // Downloads
	//unset( $menu_links['edit-account'] ); // Account details
	//unset( $menu_links['customer-logout'] ); // Logout
 
	return $menu_links;
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