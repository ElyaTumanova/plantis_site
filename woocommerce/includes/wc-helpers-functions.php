<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function plnt_is_cart_context() {
	if ( is_cart() ) {
		return true;
	}

	if ( ! wp_doing_ajax() ) {
		return false;
	}

	$cart_actions = [
		'plnt_cart_update',
		'plnt_get_cart_wish',
	];

	$action = isset( $_REQUEST['action'] )
		? sanitize_key( wp_unslash( $_REQUEST['action'] ) )
		: '';

	return in_array( $action, $cart_actions, true );
}