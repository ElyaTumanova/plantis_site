<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$close_icon = carbon_get_theme_option('close_icon')
?>

<footer id="footer" class="footer" role="contentinfo">

<div class="footer__nav container">
    <div class="footer__plants-menu footer__menu-wrap">
        <a class="footer__heading footer__heading_link" href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Растения</a>
        <?php plnt_footer_menu_plants(); ?>
    </div>
    <div class="footer__services-menu footer__menu-wrap">
        <?php plnt_footer_menu_services(); ?>
    </div>
    <div class="footer__info-menu footer__menu-wrap">
        <?php get_template_part('template-parts/info-pages-list');?>
    </div>
    <div class="footer__contacts">
        <?php get_template_part('template-parts/contacts-part');?>
    </div>
</div>


<div class="footer__info container">
    <span class="footer__info-copyright">© 2021 - 2024 Plantis | Комнатные растения и аксессуары с доставкой. Тел. <a href="tel:+78002015790">8 800 201 57 90</a></span>
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

<?php get_template_part('template-parts/popups/search-popup');?>
<div class="burger-menu"> 
    <div class="modal-mob">
        <div class="modal-mob__close burger-menu__close button"><?php echo $close_icon ?></div>
        <div class="burger-menu__nav container">
            <div class="burger-menu__nav-btn burger-menu__nav_menu">Меню</div>
            <div class="burger-menu__nav-btn burger-menu__nav_catalog">Каталог</div>
        </div>
        <?php get_template_part('template-parts/popups/burger-menu');?>
        <div class="catalog-menu__wrap">
            <?php plnt_catalog_menu() ?>
        </div>
    </div>
    <div class="modal-mob__overlay"></div>
</div>

<?php 
if (!is_account_page()) {
   get_template_part('template-parts/popups/register-form');
    get_template_part('template-parts/popups/login-form');
}
?>

<?php wp_footer(); ?>

<div class="site-row">
    <div class="site-popup-inner welcome-pt-message" style="display: none;">
        <form method="post" enctype="multipart/form-data" action="">
            <div class="site-form-title">Добро пожаловать</div>
            <div class="site-row">
                <p class="site-form-text">Благодарим за посещение нашего ресурса.</p>
            </div>
            <div class="site-form-buttons site-form--center">
                <div class="site-form-button">
                    <a href="javascript:void(0);" class="site-btn--submit green welcome-pt-message-btn" onclick="$.fancybox.close();">Продолжить</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo get_template_directory_uri() .'/assets/js/pts.lazyload.js';?>"></script>
<script>
    let dataLazyLoadingJS = {
        data: {
            ya_counter: {
                status: false,
                html: '<!-- Yandex.Metrika counter --> <script type="text/javascript" >	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};	m[i].l=1*new Date();	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");	ym(87781741, "init", {			clickmap:true,			trackLinks:true,			accurateTrackBounce:true,			webvisor:true,			ecommerce:"dataLayer"	});	window.dataLayer = window.dataLayer || [];	</script>	<noscript><div><img src="https://mc.yandex.ru/watch/87781741" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->',
                area: 'head'
            }
        }
    };

    let dataSettings = {
        cookie_name: 'plantis_welcome_cookie',
        fancybox: {
            content: $('.welcome-pt-message'),
            wrapCSS: 'site-scrollable-fancybox'
        }
    };

    let LazyLoad = new ptsLazyLoad(dataLazyLoadingJS, dataSettings);
    let need_check = 1;
    LazyLoad.simpleCheck( need_check ); //метод ожидает 0 или 1, 1 в случае, если необходимо выводить сообщение, 0, если не надо
</script>




</body>
</html>