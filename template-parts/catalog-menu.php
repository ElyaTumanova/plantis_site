<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class='menu-catalog-container'>
    <ul class='catalog-menu'>
    <?php get_catalog_submenu('gorshki_i_kashpo','/product-category/gorshki_i_kashpo/');?>
    <li class="catalog__dropdown catalog__node catalog__node_lvl_1">
        <a href="<?php echo site_url()?>/product-category/kashpo-treez/">Кашпо Treez</a>
        <span class="menu__dropdown-arrow">next</span>
        <ul class = "catalog__dropdown-menu catalog__dropdown-menu_lvl_1 sub-menu">
            <?php get_catalog_submenu('treez-effectory','/product-category/kashpo-treez/',['Treez Effectory ']);?>
            <?php get_catalog_submenu('treez-effectory','/product-category/kashpo-treez/',['Treez Ergo ']);?>
        </ul>
    </li>
    <?php get_catalog_submenu('kashpo-lechuza','/product-category/kashpo-lechuza/',['Кашпо Lechuza ']);?>
    </ul>
</nav>