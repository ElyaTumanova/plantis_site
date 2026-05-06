<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class="menu menu--cats">
  <ul class="menu__list">
    <li class="menu__item">
      <a href="<?php echo esc_url( site_url( '/product-category/komnatnye-rasteniya/' ) ); ?>">
          Комнатные растения
      </a>
    </li>

    <li class="cats-menu__item">
      <a href="<?php echo esc_url( site_url( '/product-category/gorshki_i_kashpo/' ) ); ?>">
          Горшки и кашпо
      </a>
    </li>

    <li class="cats-menu__item">
      <a href="<?php echo esc_url( site_url( '/product-category/iskusstvennye-rasteniya-treez/' ) ); ?>">
          Искусственные растения Treez
      </a>
    </li>

    <li class="cats-menu__item">
      <a href="<?php echo esc_url( site_url( '/product-category/ukhod/' ) ); ?>">
          Все для ухода
      </a>
    </li>
  </ul>
</nav>