<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class="gc-navbar__nav" aria-label="Навигация по шагам">
  <ul class="gc-navbar__list">

    <li class="gc-navbar__item is-active">
      <button
        type="button"
        class="gc-navbar__link"
        data-gc-step="1"
      >
        <span class="gc-navbar__num">1</span>

        <span class="gc-navbar__text">
          Дизайн
        </span>
      </button>
    </li>

    <li class="gc-navbar__separator">
      <?php echo plnt_icon( 'chevron-right' ); ?>
    </li>

    <li class="gc-navbar__item">
      <button
        type="button"
        class="gc-navbar__link"
        data-gc-step="2"
      >
        <span class="gc-navbar__num">2</span>

        <span class="gc-navbar__text">
          Номинал
        </span>
      </button>
    </li>

    <li class="gc-navbar__separator">
      <?php echo plnt_icon( 'chevron-right' ); ?>
    </li>

    <li class="gc-navbar__item">
      <button
        type="button"
        class="gc-navbar__link"
        data-gc-step="3"
      >
        <span class="gc-navbar__num">3</span>

        <span class="gc-navbar__text">
          Кому
        </span>
      </button>
    </li>

  </ul>
</nav>