<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="burger-menu"> 
  <div class="burger-menu__modal modal-mob">
    <div class="burger-menu__header modal-mob__header">
      <div class="burger-menu__nav container">
        <button class="button burger-menu__nav-btn burger-menu__nav_menu burger-menu__nav-btn_active">Меню</button>
        <button class="button burger-menu__nav-btn burger-menu__nav_catalog">Каталог</button>
      </div>
      <button type="button" class="burger-menu__close modal-mob__close popup__close button"><?php echo plnt_icon('close') ?></button>
    </div>
    <div class="burger-menu__body modal-mob__body">
      <div class="burger-menu__body-inner burger-menu__body-inner--menu">
        <div class="burger-menu__body-wrap">
          <span class="burger-menu__heading">Контакты</span>
          <div class="burger-menu__wrap">
            <span class="burger-menu__label">Адрес</span>
            <span class="burger-menu__item">
              <?php echo plnt_adress_link(); ?>
            </span>
            <span class="burger-menu__item burger-menu__item--accent">Шоурум работает ежедневно с 10 до 20</span>
          </div>
          <div class="burger-menu__wrap">
            <span class="burger-menu__label">Телефон</span>
            <span class="burger-menu__item">
              <?php echo plnt_phones_link();?>
            </span>
            <span class="burger-menu__item burger-menu__item--accent">Прием заказов круглосуточно</span>
          </div>
          <div class="burger-menu__wrap">
            <span class="burger-menu__label">Почта</span>
            <span class="burger-menu__item">
              <?php echo plnt_email_link();?>
            </span>
          </div>
          <div class="burger-menu__wrap">
            <?php get_template_part('template-parts/social-media-btns');?>
          </div>
        </div>
        <div class="burger-menu__body-wrap">
           <?php get_template_part('template-parts/menu/secondary-menu');?>
        </div>
        <div class="burger-menu__body-wrap">
           <?php get_template_part('template-parts/menu/info-menu-mob');?>
        </div>
      </div>
      <div class="burger-menu__body-inner burger-menu__body-inner--catalog">
        <?php get_template_part('template-parts/menu/catalog-menu');?>
      </div>
    </div>
  </div>
  <div class="burger-menu__overlay popup-overlay"></div>
</div>