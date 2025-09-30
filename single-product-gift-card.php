<?php get_header(); 
global $gcid;

//wc_get_template( 'single-product/product-image.php' );
?>
<div class="gift-content-area">
  <div>
    <h1 class="gift-card__title">Электронный подарочный сертификат</h1>
    <button class="gift-card__example-btn page-popup-open-btn">Посмотреть пример</button>
  </div>
  <div class="gift-image-wrap">
    <img src="https://plantis-shop.ru/wp-content/uploads/2025/07/decor-n.webp" class="gift-image" alt="Подарочная карта">
    <p class="gift-image-amount">1500<span>₽</span></p>
  </div>
  <?php echo do_shortcode('[yith_ywgc_display_gift_card_form]');?>

  <div class="gift-card__info">
    <h2 class="giftcard-advantages__title">Преимущества подарочного сертификата</h2>
    <div class="giftcard-advantages">
      <div class="giftcard-advantages__wrap">
        <img width="111" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/фото_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">													
        <div class="giftcard-advantages__descr">Подходит для физлиц и корпоративных клиентов</div>
      </div>
      <div class="giftcard-advantages__wrap">
        <img width="110" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/пересадка_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">

        <div class="giftcard-advantages__descr">Гибкий номинал сертификата от 1500 до 30 000 рублей</div>		
      </div>
      <div class="giftcard-advantages__wrap">									
        <img width="115" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/доставка_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">														

        <div class="giftcard-advantages__descr">Можно использовать сертификат онлайн или в шоурумах</div>	
      </div>
      <div class="giftcard-advantages__wrap">		
        <img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">												

        <div class="giftcard-advantages__descr">Можно оплачивать товары и услуги со скидкой</div>
      </div>	
      <div class="giftcard-advantages__wrap">		
        <img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">												

        <div class="giftcard-advantages__descr">Можно использовать многократно в рамках номинала и срока действия</div>
      </div>	
      <div class="giftcard-advantages__wrap">		
        <img width="112" height="100" src="https://plantis.shop/wp-content/uploads/2023/11/уход_зел.svg" class="giftcard-advantages__image" alt="" loading="lazy">												

        <div class="giftcard-advantages__descr">Удобно, если вы не знаете, какое именно растение подойдет </div>
      </div>	
    </div>
  </div>
</div>
<?php get_template_part('template-parts/popups/gift-card-popup');?>


<?php get_footer();?>