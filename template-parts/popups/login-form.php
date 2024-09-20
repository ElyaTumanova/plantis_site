<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="login-popup popup">
    <div class="plnt-customer-login page-popup__containe">
        <?php wp_signon(); ?>
        <div class="login__close">✖</div>
    </div>
    <div class="login__popup-overlay popup-overlay"></div>
</div>