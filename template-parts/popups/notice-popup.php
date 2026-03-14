<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="notice-popup popup">
    <div class="popup__container">
        <?php do_action('notice_popup') ?>
        <div class="popup__close">✖</div>
    </div>
    <div class="notice__popup-overlay popup-overlay"></div>
</div>