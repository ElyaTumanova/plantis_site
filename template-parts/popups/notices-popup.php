<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon')
?>
<div class="popup notices-popup">
    <div class="notices-popup__container">
        <div class="notices-popup__wrap">
            <?php do_action('plnt_notices')?>
            <span class="notices-popup__close heading-2"><?php echo $close_icon ?></span>  
          </div>
    </div>
    <div class="notices__popup-overlay popup-overlay"></div>
</div>	