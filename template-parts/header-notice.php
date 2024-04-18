<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$notice = carbon_get_theme_option('notice');
$show_notice = carbon_get_theme_option('show_notice');

if ($show_notice === 'yes') {
?>
<div class="header__notice">
    <?php echo $notice ?>
</div> 
<?php
};
