<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="register-popup popup">
    <div class="plnt-customer-register page-popup__container">
        <?php wc_get_template('myaccount/form-login.php'); ?>
        <div class="register__close">✖</div>
    </div>
    <div class="register__popup-overlay popup-overlay"></div>
</div>