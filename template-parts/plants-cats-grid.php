<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<!-- <div class="cats-grid">
    <a href="<?php //echo get_site_url();?>/product-category/komnatnye-rasteniya/palms/" class="cats-grid__inner cats-grid__inner_palms">
        <h3 class="cats-grid__title">Пальмы</h3>
        <?php //echo wp_get_attachment_image( 15491, 'large' );?>
    </a>
    <a href="<?php //echo get_site_url();?>/product-category/komnatnye-rasteniya/fikusy/" class="cats-grid__inner cats-grid__inner_fikus">
        <h3 class="cats-grid__title">Фикусы</h3>
    </a>
    <a href="<?php //echo get_site_url();?>/product-category/komnatnye-rasteniya/dekorativno-listvennye/" class="cats-grid__inner cats-grid__inner_lisv">
        <h3 class="cats-grid__title">Декоративно-лиственные</h3>
    </a>
    <a href="<?php //echo get_site_url();?>/product-tag/napolnye/" class="cats-grid__inner cats-grid__inner_napol">
        <h3 class="cats-grid__title">Напольные</h3>
    </a>
    <a href="<?php //echo get_site_url();?>/product-category/komnatnye-rasteniya/dekorativno-cvetushchie/" class="cats-grid__inner cats-grid__inner_cvetush">
        <h3 class="cats-grid__title">Цветущие</h3>
    </a>
    <a href="<?php //echo get_site_url();?>/product-category/komnatnye-rasteniya/lianas/" class="cats-grid__inner cats-grid__inner_lianas">
        <h3 class="cats-grid__title">Лианы</h3>
    </a>
    <a href="<?php //echo get_site_url();?>/product-tag/novichkam/" class="cats-grid__inner cats-grid__inner_neprikhotliv">
        <h3 class="cats-grid__title">Неприхотливые</h3>
    </a>
    <a href="<?php //echo get_site_url();?>/product-category/komnatnye-rasteniya/succulent/" class="cats-grid__inner cats-grid__inner_succulent">
        <h3 class="cats-grid__title">Суккуленты</h3>
    </a>
    <a href="<?php// echo get_site_url();?>/product-tag/pet-friendly/" class="cats-grid__inner cats-grid__inner_petfriendly">
        <h3 class="cats-grid__title">Pet Friendly</h3>
    </a>
</div> -->

<div class="cats-grid">
    <a href="<?php echo get_site_url(); ?>/product-category/komnatnye-rasteniya/palms/" class="cats-grid__inner cats-grid__inner_palms">
        <h3 class="cats-grid__title">Пальмы</h3>
        <?php 
        $palms_image_id = carbon_get_theme_option( 'cat_palms' );
        if ( $palms_image_id ) {
            echo wp_get_attachment_image( $palms_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-category/komnatnye-rasteniya/fikusy/" class="cats-grid__inner cats-grid__inner_fikus">
        <h3 class="cats-grid__title">Фикусы</h3>
        <?php 
        $fikusy_image_id = carbon_get_theme_option( 'cat_fikusy' );
        if ( $fikusy_image_id ) {
            echo wp_get_attachment_image( $fikusy_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-category/komnatnye-rasteniya/dekorativno-listvennye/" class="cats-grid__inner cats-grid__inner_lisv">
        <h3 class="cats-grid__title">Декоративно-лиственные</h3>
        <?php 
        $listvennye_image_id = carbon_get_theme_option( 'cat_listvennye' );
        if ( $listvennye_image_id ) {
            echo wp_get_attachment_image( $listvennye_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-tag/napolnye/" class="cats-grid__inner cats-grid__inner_napol">
        <h3 class="cats-grid__title">Напольные</h3>
        <?php 
        $napolnye_image_id = carbon_get_theme_option( 'cat_napolnye' );
        if ( $napolnye_image_id ) {
            echo wp_get_attachment_image( $napolnye_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-category/komnatnye-rasteniya/dekorativno-cvetushchie/" class="cats-grid__inner cats-grid__inner_cvetush">
        <h3 class="cats-grid__title">Цветущие</h3>
        <?php 
        $cvetushchie_image_id = carbon_get_theme_option( 'cat_cvetushchie' );
        if ( $cvetushchie_image_id ) {
            echo wp_get_attachment_image( $cvetushchie_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-category/komnatnye-rasteniya/lianas/" class="cats-grid__inner cats-grid__inner_lianas">
        <h3 class="cats-grid__title">Лианы</h3>
        <?php 
        $lianas_image_id = carbon_get_theme_option( 'cat_lianas' );
        if ( $lianas_image_id ) {
            echo wp_get_attachment_image( $lianas_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-tag/novichkam/" class="cats-grid__inner cats-grid__inner_neprikhotliv">
        <h3 class="cats-grid__title">Неприхотливые</h3>
        <?php 
        $neprikhotlivye_image_id = carbon_get_theme_option( 'cat_neprikhotlivye' );
        if ( $neprikhotlivye_image_id ) {
            echo wp_get_attachment_image( $neprikhotlivye_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-category/komnatnye-rasteniya/succulent/" class="cats-grid__inner cats-grid__inner_succulent">
        <h3 class="cats-grid__title">Суккуленты</h3>
        <?php 
        $succulent_image_id = carbon_get_theme_option( 'cat_succulent' );
        if ( $succulent_image_id ) {
            echo wp_get_attachment_image( $succulent_image_id, 'large' );
        }
        ?>
    </a>
    
    <a href="<?php echo get_site_url(); ?>/product-tag/pet-friendly/" class="cats-grid__inner cats-grid__inner_petfriendly">
        <h3 class="cats-grid__title">Pet Friendly</h3>
        <?php 
        $pet_friendly_image_id = carbon_get_theme_option( 'cat_pet_friendly' );
        if ( $pet_friendly_image_id ) {
            echo wp_get_attachment_image( $pet_friendly_image_id, 'large' );
        }
        ?>
    </a>
</div>