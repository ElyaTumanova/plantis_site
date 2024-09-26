<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$notice = carbon_get_theme_option('notice');
$show_notice = carbon_get_theme_option('show_notice');

if ($show_notice) {
?>
<div class="header__notice container">
    <?php echo $notice ?>
</div> 
<?php
};
