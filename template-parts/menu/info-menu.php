<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class="menu menu--info">
  <ul class="menu__list">
      <li class="menu__item">
          <a href="<?php echo esc_url( get_site_url() . '/about-us/' ); ?>">О нас</a>
      </li>
      <li class="menu__item"> 
          <a href="<?php echo esc_url( get_site_url() . '/contacts/' ); ?>">Контакты</a>
      </li>
      <li class="menu__item" data-js-dropdown-open> Покупателям
        <nav class="dropdown">
          <ul class="dropdown__list">
            <li class="dropdown__item">
              <a href="<?php echo esc_url( get_site_url() . '/delivery/' ); ?>">Доставка и самовывоз (Москва и МО)</a>
            </li>
            <li class="dropdown__item">
              <a href="<?php echo esc_url( get_site_url() . '/dostavka-komnatnyh-rastenij-po-rossii/' ); ?>">Доставка по России</a>
            </li>
            <li class="dropdown__item">
              <a href="<?php echo esc_url( get_site_url() . '/payment/' ); ?>">Способы оплаты</a>
            </li>
            <li class="dropdown__item">
              <a href="<?php echo esc_url( get_site_url() . '/refund_returns/' ); ?>">Возврат и обмен</a>
            </li>
            <li class="dropdown__item">
              <a href="<?php echo esc_url( get_site_url() . '/faq/' ); ?>">Вопросы и ответы</a>
            </li>
            <li class="dropdown__item">
              <a href="<?php echo esc_url( get_site_url() . '/partners/' ); ?>">Поставщикам и партнерам</a>
            </li>
          </ul>
        </nav>
      </li>
      <li class="menu__item">
          <a href="<?php echo esc_url( get_site_url() . '/vakansii/' ); ?>">Вакансии</a>
      </li>
  </ul>
</nav>