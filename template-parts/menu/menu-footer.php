<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$logo = carbon_get_theme_option('logo');
?>
<div class="footer__nav-wrap">
  <a class="menu__item menu__item--accent" href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Растения</a>
  <nav class="menu menu--footer-plants">
    <ul class="menu__list">
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/novichkam/">Неприхотливые</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye/">Декоративно-лиственные</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/fikusy/">Фикусы</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/palms/">Пальмы</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/lianas/">Лианы</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/dekorativno-cvetushchie/">Цветущие</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/paporotniki/">Папоротники</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/succulent/">Суккуленты</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/komnatnye-rasteniya/kaktusy/">Кактусы</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/ampelnye/">Ампельные</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/napolnye/">Напольные</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/malenkie-cvety-v-gorshkah/">Компактные</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/variegatnye/">Вариегатные</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/ehkzoticheskie-komnatnye-rasteniya/">Экзотические</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/bonsay/">Бонсаи</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/pet-friendly/">Pet Friendly</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-tag/skidki/">Скидки</a></li>
      <li class="menu__item"><a class="menu__link" href="<?php get_site_url()?>/product-category/iskusstvennye-rasteniya-treez/">Искусственные растения Treez</a></li>
    </ul>
  </nav>
</div>

<div class="footer__nav-wrap">
  <p class="menu__heading">Информация</p>
  <?php get_template_part('template-parts/menu/info-menu-mob');?>
</div>

<div class="footer__nav-wrap">
  <nav class="menu menu--footer-cats">
    <ul class="menu__list">
      <li class="menu__item menu__item--accent"><a class="menu__link" href="<?php get_site_url()?>/product-category/gorshki_i_kashpo/">Горшки и кашпо</a></li>
      <li class="menu__item menu__item--accent"><a class="menu__link" href="<?php get_site_url()?>/usluga-peresadki-komnatnyh-rastenij/">Пересадка</a></li>
      <li class="menu__item menu__item--accent menu__item--wide "><a class="menu__link" href="<?php get_site_url()?>/professionalnyj-uhod-za-rasteniyami/">Профессиональный уход за растениями</a></li>
      <li class="menu__item menu__item--accent"><a class="menu__link" href="<?php get_site_url()?>/pokupka-komnatnyh-rastenij-optom/">Растения оптом</a></li>
      <li class="menu__item menu__item--accent"><a class="menu__link" href="<?php get_site_url()?>/landscaping/">Озеленение</a></li>
      <li class="menu__item menu__item--accent"><a class="menu__link" href="<?php get_site_url()?>/product-tag/skidki/">Скидки</a></li>
    </ul>
  </nav>
</div>
