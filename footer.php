<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$start_footer_file = microtime(true);

$close_icon = carbon_get_theme_option('close_icon');
?>

<footer id="footer" class="footer" role="contentinfo">
<?php //$start = microtime(true); ?>
<div class="footer__nav container">
    <?php get_template_part('template-parts/menu-footer');?>
</div>
<?php //echo "<!-- Timing: footer nav = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>

<div class="footer__info container">
    <span class="footer__info-copyright">© 2021 - 2025 Plantis | Комнатные растения и аксессуары с доставкой. Тел. <a href="tel:+78002015790">8 800 201 57 90</a></span>
    <a href="<?php echo site_url()?>/privacy-policy/" class="footer__info-link" role="button">
        Политика конфиденциальности
    </a>
    <p class="footer__info-requsits">Туманов Вячеслав Витальевич
        <br>ИНН: 645313252670 ОГРН: 321774600774479 
        <br>Юридический адрес: 105082, Москва, Б. Почтовая, д. 1/33, стр.1
        <br>Расчетный счёт: 40802810900002894566 Банк: АО “ТИНЬКОФФ БАНК” БИК: 044525974 Корр. счёт: 30101810145250000974
    </p>
</div>

<div class="side-cart__wrap">
    <?php $cart_icon = carbon_get_theme_option('cart_icon')?>
    <button class="side-cart__open-btn">
        <?php plnt_side_cart_count ();?>
        <?php echo $cart_icon ?>
        <!-- <img class="side-cart__icon" src="<?php //echo //$cart_icon ?>" alt="cart" width="35" height="35"> -->
    </button>
    <div class="side-cart__popup popup">
        <div class="side-cart">
            <h4 class="side-cart__title heading-2">Корзина</h4>
            <div class="modal-mob__close side-cart__close button"><?php echo $close_icon ?></div>      
            <?php plnt_woocommerce_mini_cart();?>
        </div>
        <div class="side-cart__popup-overlay popup-overlay"></div>
    </div>
</div>


</footer><!-- #colophon -->
</div><!-- #page -->

<?php //get_template_part('template-parts/popups/search-popup');?>
<div class="burger-menu"> 
    <div class="modal-mob">
        <div class="modal-mob__close popup__close button"><?php echo $close_icon ?></div>
        <div class="burger-menu__nav container">
            <div class="burger-menu__nav-btn burger-menu__nav_menu">Меню</div>
            <div class="burger-menu__nav-btn burger-menu__nav_catalog">Каталог</div>
        </div>
        <?php get_template_part('template-parts/burger-menu-mob');?>
        <div class="catalog-menu__wrap">
            <?php get_template_part('template-parts/catalog-menu');?>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div>

<?php 
if (!is_account_page()) {
  get_template_part('template-parts/popups/register-form');
  get_template_part('template-parts/popups/login-form');
}
?>

<?php wp_footer(); ?>
<?php echo "<!-- Timing: footer.php = " . round((microtime(true) - $start_footer_file) * 1000, 2) . " ms -->"; ?>
</body>
</html>