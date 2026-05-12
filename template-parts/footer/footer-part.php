<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$logo = carbon_get_theme_option('logo');
?>

<section class="footer">
  <div class="footer__top">
    <div class="footer__top-inner container">
      <div class="footer__logo logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link">
          <img
          src="<?php echo esc_url(get_template_directory_uri() . '/images/frontend/logo-bw.svg') ?>"
          class="logo__img"
          alt="Plantis"
          width="207"
          height="38">
        </a>
      </div>
      <div itemscope itemtype="https://schema.org/Florist" class="footer__contacts">
        <div class="footer__contacts-wrap">
          <span class="footer__contacts-label">Телефоны</span>
          <span class="footer__contacts-item">
            <?php echo plnt_phones_link();?>
          </span>
        </div>
        <div class="footer__contacts-wrap">
          <span class="footer__contacts-label">Адрес оффлайн магазина</span>
          <span class="footer__contacts-item">
            <?php echo plnt_adress_link(); ?>
          </span>
        </div>
        <div class="footer__contacts-wrap">
          <span class="footer__contacts-label">Почта</span>
          <span class="footer__contacts-item">
            <?php echo plnt_email_link();?>
          </span>
        </div>
        <!-- Schema.org -->
        <meta itemprop="name" content="Интернет-магазин комнатных растений в Москве - Plantis">
        <meta itemprop="image" content="<?php echo $logo ?>">
        <meta itemprop="openingHours" content="Mo-Su 10:00-20:00"/>
        <meta itemprop="telephone" content="+7 800 201 57 90">
        <meta itemprop="email" content="INFO@PLANTIS.SHOP">
        <div itemprop = "address" itemscope itemtype="http://schema.org/PostalAddress">
            <meta itemprop="addressLocality" content="г. Москва">
            <meta itemprop="streetAddress" content="ул. Мещерякова, д.3.">
        </div>
        <!-- /Schema.org -->
      </div>
      <?php get_template_part('template-parts/social-media-btns')?>
    </div>
  </div>
  <div class="footer__nav container">
     <?php get_template_part('template-parts/menu/menu-footer')?>
  </div>
  <div class="footer__bottom container border-over">
    <div class="footer__requsits">
      <div>
        <span>Туманов Вячеслав Витальевич</span>
      </div>

      <div>
        <span>ИНН: 645313252670</span>
        <span>ОГРН: 321774600774479</span>
      </div>

      <div>
        <span>Юридический адрес: 105082, Москва, Б. Почтовая, д. 1/33, стр.1</span>
      </div>
      <!-- 
      <div>
        <span>Расчетный счёт: 40802810900002894566</span>
        <span>Банк: АО “ТИНЬКОФФ БАНК”</span>
        <span>БИК: 044525974</span>
        <span>Корр. счёт: 30101810145250000974</span>
      </div> -->
    </div>
     <a class="footer__policy" href="<?php echo site_url()?>/privacy-policy/">
        Политика конфиденциальности
    </a>
    <span class="footer__copyright">© 2021 - <?php echo date('Y'); ?> Plantis | Комнатные растения и аксессуары с доставкой</span>
    <a class="footer__dev container" href="https://misty-studio.ru" target="_blank" rel="noopener nofollow">
      <?php echo plnt_icon('misty');?>
      <span>Разработано в MISTY studio</span>
    </a>
  </div>
</section>