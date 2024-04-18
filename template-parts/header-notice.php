<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$notice = carbon_get_theme_option('notice');
$show_notice = carbon_get_theme_option('show_notice');

if ($show_notice === '1') {
?>
<div class="header__notice">
    <?php echo $notice ?>
    <?php echo $show_notice ?>
</div> 
<?php
};
