<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="page-popup popup service-popup">
    <div class="page-popup__container popup__container">
        <span class="page-popup__close popup__close"><?php echo plnt_icon('close') ?></span>
        <div class="page-popup__wrap">
            <div class="page-popup__form"><?php echo do_shortcode('[contact-form-7 id="212bafa" title="Уход за растениями"]')?></div>
            <p class="page-popup__text">Нажимая кнопку "Отправить", вы даете согласие на обработку своих персональных данных и соглашаетесь с положениями, 
                описанными в нашей <a class="page-popup__link" target="blank" href="<?php get_site_url()?>/privacy-policy/">политике конфиденциальности</a>.</p>
        </div>
    </div>
    <div class="preloader">
        <div class="spinner"></div>
    </div>
    <div class="page-popup__popup-overlay popup-overlay"></div>
</div>	
