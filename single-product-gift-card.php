<?php get_header(); 
global $gcid;

//wc_get_template( 'single-product/product-image.php' );
?>
<div class="gift-content-area">
  <h1>Электронный подарочный сертификат</h1>
  <div class="gift-image-wrap">
    <img src="https://plantis-shop.ru/wp-content/uploads/2025/07/decor-n.webp" class="gift-image" alt="Подарочная карта">
    <p class="gift-image-amount">1500<span>₽</span></p>
  </div>
  <?php echo do_shortcode('[yith_ywgc_display_gift_card_form]');?>
</div>


<?php get_footer();?>