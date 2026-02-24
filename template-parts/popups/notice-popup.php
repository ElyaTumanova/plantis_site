<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="notice-popup popup">
    <div class="plnt-customer-notice page-popup__container">
        <?php do_action('notice_popup') ?>
        <div class="notice__close">✖</div>
    </div>
    <div class="notice__popup-overlay popup-overlay"></div>
</div>