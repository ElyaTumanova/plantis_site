<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class="menu menu--secondary">
  <ul class="menu__list">
    <li class="menu__item">
      <a
        href="<?php get_site_url()?>/product-tag/skidki"
      >
        Скидки
      </a>
    </li>
    <li class="menu__item icon icon--chevron-down"
      data-js-dropdown-open
    >
      Услуги
      <nav class="menu__dropdown dropdown">
        <ul class="dropdown__list">
          <li class="dropdown__item">
              <a href="<?php echo site_url()?>/pokupka-komnatnyh-rastenij-optom/">Оптовая покупка</a>
          </li>
          <li class="dropdown__item">
              <a href="<?php echo site_url()?>/professionalnyj-uhod-za-rasteniyami/">Профессиональный уход</a>
          </li>
          <li class="dropdown__item">
              <a href="<?php echo site_url()?>/landscaping/">Озеленение</a>
          </li>
          <li class="dropdown__item">
              <a href="<?php echo site_url()?>/usluga-peresadki-komnatnyh-rastenij/">Пересадка</a>
          </li>
        </ul>
      </nav>
    </li>
    <li class="menu__item icon icon--pre icon--gift">
      <a
        href="<?php get_site_url()?>/shop/gift-card"
      >
        Подарочный сертификат
      </a>
    </li>
  </ul>
</nav>