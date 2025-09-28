<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon');
function datePlus3MonthsIntl() {
    $date = new DateTime('+3 months');
    $fmt  = new IntlDateFormatter('ru_RU', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $fmt->setPattern('d MMMM, yyyy'); // 27 сентября, 2026
    return $fmt->format($date);
}

?>
<div class="page-popup popup gift-card-popup">
  <div class="page-popup__container">
    <div class="page-popup__wrap">
        <h2 class="page-popup__heading heading-2">Предзаказ товара</h2>
        <span class="page-popup__close heading-2"><?php echo $close_icon ?></span>
        <h1 class="gift-card__title">Подарочный сертификат</h1>
        <p class="gift-card__descr">Интернет магазин комнатных растений Plantis</p>
        <div class="gift-card__main">
          <div class="gift-card__wrap">
            <div class="gift-image-wrap">
              <img src="https://plantis-shop.ru/wp-content/uploads/2025/07/decor-n.webp" class="gift-image" alt="Подарочная карта" loading="lazy">
              <p class="gift-image-amount">5000<span>₽</span></p>
            </div>
            <div class="gift-card__row">
              <p>Номер сертификата:</p>
              <p class="copy-wrap">
                <span id="gift-code">0000-0000-0000-0000</span>
                <button class="copy-btn" type="button" data-copy-target="#gift-code">Скопировать</button>
              </p>
            </div>
            <div class="gift-card__row">
              <p>Срок действия сертификата:</p>
              <p><?php echo datePlus3MonthsIntl(); ?></p>
            </div>
          </div>
          
          <div class="gift-card__greeting">
            <p class="gift-card__greeting-to">Имя (кому)</p>
            <p class="gift-card__greeting-text">Эта подарочная карта для тебя 🌿
              Открой для себя мир комнатных растений, подбери красивые горшки и полезные аксессуары.
              Пусть твой дом расцветает вместе с новыми зелёными друзьями!
              <br>
              <strong>Или ваш текст поздравления.</strong>
            </p>
            <p class="gift-card__greeting-from">Имя (от кого)</p>
            <a class="button gift-card__btn" href="<?php echo get_site_url()?>/shop">К покупкам</a>
          </div>
        </div>
        <?php get_template_part( 'template-parts/gift-card-exam' );?>
    </div>
  </div>
  <div class="page-popup__popup-overlay popup-overlay"></div>
</div>	