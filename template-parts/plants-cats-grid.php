<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
	$cats_palms = carbon_get_theme_option('cats_palms');
	$cats_fikus = carbon_get_theme_option('cats_fikus');
	$cats_lisv = carbon_get_theme_option('cats_lisv');
	$cats_napol = carbon_get_theme_option('cats_napol');
	$cats_cvetush = carbon_get_theme_option('cats_cvetush');
	$cats_lianas = carbon_get_theme_option('cats_lianas');
	$cats_neprikhotliv = carbon_get_theme_option('cats_neprikhotliv');
	$cats_succulent = carbon_get_theme_option('cats_succulent');
	$cats_petfriendly = carbon_get_theme_option('cats_petfriendly');
?>

<div class="cats-grid">
    <a href="<?php echo get_site_url();?>/product-category/komnatnye-rasteniya/palms/" class="cats-grid__inner cats-grid__inner_palms">
        <h3 class="cats-grid__title">Пальмы</h3>
        <img src="<?php echo $cats_palms ?>" class="cats-grid__img" alt="Пальмы">
    </a>
    <a href="<?php echo get_site_url();?>/product-category/komnatnye-rasteniya/fikusy/" class="cats-grid__inner cats-grid__inner_fikus">
        <h3 class="cats-grid__title">Фикусы</h3>
        <img src="<?php echo $cats_fikus ?>" class="cats-grid__img" alt="Фикусы">
    </a>
    <a href="<?php echo get_site_url();?>/product-category/komnatnye-rasteniya/dekorativno-listvennye/" class="cats-grid__inner cats-grid__inner_lisv">
        <h3 class="cats-grid__title">Декоративно-лиственные</h3>
        <img src="<?php echo $cats_lisv ?>" class="cats-grid__img" alt="Декоративно-лиственные">
    </a>
    <a href="<?php echo get_site_url();?>/product-tag/napolnye/" class="cats-grid__inner cats-grid__inner_napol">
        <h3 class="cats-grid__title">Напольные</h3>
        <img src="<?php echo $cats_napol ?>" class="cats-grid__img" alt="Напольные">
    </a>
    <a href="<?php echo get_site_url();?>/product-category/komnatnye-rasteniya/dekorativno-cvetushchie/" class="cats-grid__inner cats-grid__inner_cvetush">
        <h3 class="cats-grid__title">Цветущие</h3>
        <img src="<?php echo $cats_cvetush ?>" class="cats-grid__img" alt="Цветущие">
    </a>
    <a href="<?php echo get_site_url();?>/product-category/komnatnye-rasteniya/lianas/" class="cats-grid__inner cats-grid__inner_lianas">
        <h3 class="cats-grid__title">Лианы</h3>
        <img src="<?php echo $cats_lianas ?>" class="cats-grid__img" alt="Лианы">
    </a>
    <a href="<?php echo get_site_url();?>/product-tag/novichkam/" class="cats-grid__inner cats-grid__inner_neprikhotliv">
        <h3 class="cats-grid__title">Неприхотливые</h3>
        <img src="<?php echo $cats_neprikhotliv ?>" class="cats-grid__img" alt="Неприхотливые растения">
    </a>
    <a href="<?php echo get_site_url();?>/product-category/komnatnye-rasteniya/succulent/" class="cats-grid__inner cats-grid__inner_succulent">
        <h3 class="cats-grid__title">Суккуленты</h3>
        <img src="<?php echo $cats_succulent ?>" class="cats-grid__img" alt="Суккуленты">
    </a>
    <a href="<?php echo get_site_url();?>/product-tag/pet-friendly/" class="cats-grid__inner cats-grid__inner_petfriendly">
        <h3 class="cats-grid__title">Pet Friendly</h3>
        <img src="<?php echo $cats_petfriendly ?>" class="cats-grid__img" alt="Pet Friendly">
    </a>
</div>