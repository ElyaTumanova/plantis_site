<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="img-popup" id="img-popup" aria-hidden="true">
  <div class="img-popup__backdrop" data-popup-close></div>

  <div class="img-popup__dialog" role="dialog" aria-modal="true" aria-label="Просмотр изображения">
    <button class="img-popup__close" type="button" aria-label="Закрыть" data-popup-close>×</button>

    <figure class="img-popup__figure">
      <img class="img-popup__img" alt="" />
    </figure>
  </div>
</div>

<div id="img-popup-slider" class="img-popup" aria-hidden="true">
  <div class="img-popup__backdrop" data-popup-close></div>

  <div class="img-popup__inner" role="dialog" aria-modal="true">
    <button class="img-popup__close" type="button" data-popup-close aria-label="Закрыть">×</button>

    <div class="swiper img-popup__thumbs">
      <div class="swiper-wrapper"></div>
    </div>

    <button class="img-popup__prev mgm-arrow-btn mgm-arrow-btn--prev is-swiper-arrow" type="button" aria-label="Назад"></button>
    <button class="img-popup__next mgm-arrow-btn mgm-arrow-btn--next is-swiper-arrow" type="button" aria-label="Вперёд"></button>

    <div class="swiper img-popup__swiper">
      <div class="swiper-wrapper"></div>
    </div>
  </div>
</div>