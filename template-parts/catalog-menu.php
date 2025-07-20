<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class='menu-catalog-container'>
    <ul class='catalog-menu'>
    <?php get_catalog_submenu('gorshki_i_kashpo','/product-category/gorshki_i_kashpo/',2);?>
    <?php get_catalog_submenu('kashpo-treez','/product-category/kashpo-treez/',3,['Treez Effectory ', 'Treez Ergo ']);?>
    <?php get_catalog_submenu('kashpo-lechuza','/product-category/kashpo-lechuza/',2,['Кашпо Lechuza ']);?>
    <?php get_catalog_submenu('ukhod','/product-category/ukhod/',2);?>
    </ul>
</nav>