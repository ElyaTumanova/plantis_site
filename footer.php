<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$start_footer_file = microtime(true);

$close_icon = carbon_get_theme_option('close_icon');
?>

<footer id="footer">
   <?php get_template_part('template-parts/footer/footer-part');?>
<?php //$start = microtime(true); ?>

<?php //echo "<!-- Timing: footer nav = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>


<div class="side-cart__wrap">
    <?php $cart_icon = carbon_get_theme_option('cart_icon')?>
    <button class="side-cart__open-btn">
        <?php plnt_side_cart_count ();?>
        <?php echo $cart_icon ?>
        <!-- <img class="side-cart__icon" src="<?php //echo //$cart_icon ?>" alt="cart" width="35" height="35"> -->
    </button>
    <div class="side-cart__popup side-cart-popup popup">
        <div class="side-cart">
            <h4 class="side-cart__title heading-2">Корзина</h4>
            <div class="modal-mob__close side-cart__close popup__close button"><?php echo $close_icon ?></div>      
            <?php plnt_woocommerce_mini_cart();?>
        </div>
        <div class="side-cart__popup-overlay popup-overlay"></div>
    </div>
</div>


</footer><!-- #colophon -->
</div><!-- #page -->

<?php //get_template_part('template-parts/popups/search-popup');?>
<?php get_template_part('template-parts/menu/burger-menu-mobile');?>

<?php 
if (!is_account_page()) {
  // get_template_part('template-parts/popups/register-form');
  get_template_part('template-parts/popups/login-popup');
  }
  get_template_part('template-parts/popups/notice-popup');
?>

<?php wp_footer(); ?>
<?php echo "<!-- Timing: footer.php = " . round((microtime(true) - $start_footer_file) * 1000, 2) . " ms -->"; ?>
</body>
</html>