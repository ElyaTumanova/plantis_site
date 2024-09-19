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

add_action ('woocommerce_login_form_end','plnt_login_form');

function plnt_login_form () {
	echo '<div>HIHI</div>';
}