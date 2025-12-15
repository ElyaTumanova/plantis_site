<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon')
?>
<div class="page-popup popup">
    <div class="page-popup__container">
        <div class="page-popup__wrap">
            <h2 class="page-popup__heading heading-2">Получить оптовый прайс-лист</h2>
            <span class="page-popup__close heading-2"><?php echo $close_icon ?></span>
            <div class="page-popup__form"><?php echo do_shortcode('[contact-form-7 id="48896" title="Запрос прайс-листа"]')?></div>
            <p class="page-popup__text">Нажимая кнопку "Отправить", вы даете согласие на обработку своих персональных данных и соглашаетесь с положениями, 
                описанными в нашей <a class="page-popup__link" target="blank" href="<?php get_site_url()?>/privacy-policy/">политике конфиденциальности</a>.</p>
        </div>
    </div>
    <div class="preloader">
        <div class="spinner"></div>
    </div>
    <div class="page-popup__popup-overlay popup-overlay"></div>
</div>		