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
    <span class="footer__info-copyright">© 2023 Plantis | Комнатные растения и аксессуары с доставкой. Тел. <a href="tel:+78002015790">8 800 201 57 90</a></span>
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
    <button class="side-cart__open-btn">hello</button>
    <div class="side-cart__popup popup">
        <div class="side-cart">      
            <?php plnt_woocommerce_mini_cart();?>  
        </div>
        <div class="side-cart__popup-overlay popup-overlay"></div>
    </div>
</div>



</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_template_part('template-parts/popups/search-popup');?>
<?php get_template_part('template-parts/popups/burger-menu');?>
<?php get_template_part('template-parts/popups/catalog-menu');?>

<?php wp_footer(); ?>


</body>
</html>