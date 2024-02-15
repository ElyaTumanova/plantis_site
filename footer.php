<footer id="footer" class="footer" role="contentinfo">

<div class="footer__nav container">
    <div class="footer__menu-wrap">
        <?php get_template_part('template-parts/info-pages-list');?>
    </div>
    <div class="footer__contacts">
        <?php get_template_part('template-parts/contacts-part');?>
    </div>
</div>


<div class="footer__info container">
    <span class="footer__info-copyright">© 2023 Plantis | Комнатные растения и аксессуары с доставкой. Тел. <a href="tel:+78002015790">8 800 201 57 90</a></span>
    <a href="https://plantis.shop/privacy-policy/" class="footer__info-link" role="button">
        Политика конфиденциальности
    </a>
    <p class="footer__info-requsits">Туманов Вячеслав Витальевич
        <br>ИНН: 645313252670 ОГРН: 321774600774479 
        <br>Юридический адрес: 105082, Москва, Б. Почтовая, д. 1/33, стр.1
        <br>Расчетный счёт: 40802810900002894566 Банк: АО “ТИНЬКОФФ БАНК” БИК: 044525974 Корр. счёт: 30101810145250000974
    </p>
</div>



</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_template_part('template-parts/popups/search-popup');?>
<?php get_template_part('template-parts/popups/burger-menu');?>
<?php get_template_part('template-parts/popups/catalog-menu');?>

<?php wp_footer(); ?>


</body>
</html>