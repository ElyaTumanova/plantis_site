<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="login-popup side-popup popup">
    <div class="plnt-customer-login page-popup__container">
        <?php wc_get_template('myaccount/form-login.php'); ?>
        <div class="login__close popup__close">✖</div>
    </div>
    <div class="login__popup-overlay popup-overlay"></div>
</div>