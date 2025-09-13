<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon')
?>
<div class="page-popup popup notices-popup">
    <div class="page-popup__container">
        <div class="page-popup__wrap">
            <h2 class="page-popup__heading heading-2">lalala</h2>
            <?php do_action('plnt_notices')?>
            <span class="notices-popup__close heading-2"><?php echo $close_icon ?></span>  
          </div>
    </div>
    <div class="notices__popup-overlay popup-overlay"></div>
</div>	