<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="login-popup popup">
    <div class="plnt-customer-login">
        <?php wp_login_form( $args ); ?>
        <div class="search__close">✖</div>
    </div>
    <div class="login__popup-overlay popup-overlay"></div>
</div>