<?php get_header();?>
<div class="content-area">
  <?php echo 'price '.$product->get_price();?>
  <div class="gift-content-area">
    <h1 class="gift-card__title">Электронный подарочный сертификат</h1>
    <button class="gift-card__example-btn page-popup-open-btn">Посмотреть пример</button>
    <a class="gift-card__example-btn" href="<?php echo get_site_url()?>/gift-card" target="_blank">Проверить баланс</a>
    <div class="gift-image-wrap">
      <img src="<?php echo get_template_directory_uri()?>/images/gift-card/gc_cover.jpg" class="gift-image" alt="Подарочная карта">
      <p class="gift-image-amount">1500<span>₽</span></p>
    </div>
    <?php echo do_shortcode('[yith_ywgc_display_gift_card_form]');?>
    <div class="gift-card__info">
      <h2 class="giftcard-advantages__title">Преимущества подарочного сертификата</h2>
      <div class="giftcard-advantages">
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">01</span>
          <div class="giftcard-advantages__descr">Подходит для физлиц и корпоративных клиентов</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">02</span>
          <div class="giftcard-advantages__descr">Гибкий номинал сертификата от 1500 до 30 000 рублей</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">03</span>
          <div class="giftcard-advantages__descr">Можно использовать сертификат онлайн или в шоурумах</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">04</span>
          <div class="giftcard-advantages__descr">Можно оплачивать товары и услуги со скидкой</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">05</span>
          <div class="giftcard-advantages__descr">Можно использовать многократно в рамках номинала и срока действия</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__number">06</span>
          <div class="giftcard-advantages__descr">Удобно, если вы не знаете, какое именно растение подойдет </div>
        </div>
      </div>
    </div>
    <h2 class="giftcard-advantages__title">Часто задаваемые вопросы</h2>
    <?php get_template_part( 'template-parts/gift-card-faq' );?>
  </div>
</div>
<?php get_template_part('template-parts/popups/gift-card-popup');?>


<?php get_footer();?>