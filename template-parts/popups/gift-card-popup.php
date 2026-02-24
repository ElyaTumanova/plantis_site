<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon');
function datePlus12MonthsIntl() {
    // создаём дату через 12 месяцев
    $date = new DateTime('+12 months');

    // массив русских названий месяцев в родительном падеже
    $months = [
        1 => 'января',
        2 => 'февраля',
        3 => 'марта',
        4 => 'апреля',
        5 => 'мая',
        6 => 'июня',
        7 => 'июля',
        8 => 'августа',
        9 => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    ];

    $day   = $date->format('j');
    $month = $months[(int)$date->format('n')];
    $year  = $date->format('Y');

    return "{$day} {$month} {$year}";
}


?>
<div class="page-popup popup gift-card-popup">
  <div class="page-popup__container popup__container">
    <div class="page-popup__wrap">
        <h2 class="page-popup__heading heading-2">Пример подарочного сертификата</h2>
        <span class="page-popup__close popup__close heading-2"><?php echo $close_icon ?></span>
        <h1 class="gift-card__title">Подарочный сертификат</h1>
        <p class="gift-card__descr">Интернет магазин комнатных растений Plantis</p>
        <div class="gift-card__main">
          <div class="gift-card__wrap">
            <div class="gift-image-wrap">
              <img src="<?php echo get_template_directory_uri()?>/images/gift-card/gc_cover.webp" class="gift-image" alt="Подарочная карта" loading="lazy">
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
              <p><?php echo datePlus12MonthsIntl(); ?></p>
            </div>
          </div>
          
          <div class="gift-card__greeting">
            <p class="gift-card__greeting-to">[Имя (кому)]</p>
            <p class="gift-card__greeting-text">Эта подарочная карта для тебя 🌿
              <br>
              Открой для себя мир комнатных растений, подбери красивые горшки и полезные аксессуары.
              <br>
              Пусть твой дом расцветает вместе с новыми зелёными друзьями!
              <br>
              <br>
              <strong>[Или ваш текст поздравления]</strong>
            </p>
            <p class="gift-card__greeting-from">[Имя (от кого)]</p>
            <a class="button gift-card__btn" href="<?php echo get_site_url()?>/shop">К покупкам</a>
          </div>
        </div>
        <?php get_template_part( 'template-parts/gift-card-faq' );?>
    </div>
  </div>
  <div class="page-popup__popup-overlay popup-overlay"></div>
</div>	