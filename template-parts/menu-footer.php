<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="footer__plants-menu footer__menu-wrap">
    <a class="footer__heading footer__heading_link" href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Растения</a>
    <?php //plnt_footer_menu_plants(); ?>
</div>
<div class="footer__services-menu footer__menu-wrap">
    <?php //plnt_footer_menu_services(); ?>
</div>
<?php echo "<!-- Timing: footer nav = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
<div class="footer__info-menu footer__menu-wrap">
    <?php get_template_part('template-parts/info-pages-list');?>
</div>
<div class="footer__contacts">
    <?php get_template_part('template-parts/contacts-part');?>
</div>