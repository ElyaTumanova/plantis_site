<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$notice = carbon_get_theme_option('notice');?>

<div class="header__notice">
    <?php echo $notice ?>
</div>