<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon')
?>

<div class="catalog-menu__wrap">
    <!-- <div class="modal-mob__close catalog-menu__close button"><?php //echo $close_icon ?></div> -->
    <!-- <div class="menu__item_accent ">Каталог</div> -->
    <?php plnt_catalog_menu() ?>
</div>