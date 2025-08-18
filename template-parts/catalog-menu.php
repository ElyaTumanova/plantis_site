<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $treez_poliv_cat_id;
?>

<nav class='menu-catalog-container'>
    <ul class='catalog-menu'>
        <?php get_catalog_submenu('komnatnye-rasteniya','/product-category',3);?>
        <?php get_catalog_submenu('gorshki_i_kashpo','/product-category',2);?>
        <?php get_catalog_submenu('kashpo-treez','/product-category',3,[$treez_poliv_cat_id],['Treez Effectory ', 'Treez Ergo ']);?>
        <?php get_catalog_submenu('kashpo-lechuza','/product-category',2,[],['Кашпо Lechuza '], true);?>
        <?php get_catalog_submenu('ukhod','/product-category',2);?>
        <?php get_catalog_submenu('iskusstvennye-rasteniya-treez','/product-category',3,[],['Treez','Искусственные', 'Искусственная', 'Искусственное', 'Искусственный','растения'],true);?>
    </ul>
</nav>