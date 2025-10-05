<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon');
?>

<div class="popup gc-balance-popup">
  <div class="gc-balance-popup__container">
    <div class="page-popup__wrap">
        <h2 class="page-popup__heading heading-2">Проверить баланс подарочного сертификата</h2>
        <span class="gc-balance-popup__close heading-2"><?php echo $close_icon ?></span>
        <?php echo do_shortcode('[yith_gift_card_check_balance_form]');?>
    </div>
  </div>
  <div class="gc-balance-popup__popup-overlay popup-overlay"></div>
</div>	