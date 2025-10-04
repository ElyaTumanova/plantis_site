<?php get_header(); 

$gcnum = get_query_var('gcnum');
$gift_card_id = mytheme_get_giftcard_by_code( $gcnum );
$gift_card = get_post_meta( $gift_card_id );
// echo('<pre>');
// if ( $gift_card ) {
//     echo 'Карта найдена.';
//     // echo 'Номер карты: ' . esc_html( $gift_card->gift_card_number );
//     // echo '<br>Баланс: ' . wc_price( $gift_card->get_balance() );
//     // echo '<br>Действует до: ' . date_i18n( get_option( 'date_format' ), $gift_card->get_expiration() );
// } else {
//     echo 'Карта с таким номером не найдена.';
// }
// print_r($gcnum);
// print_r($gift_card);
// echo('</pre>');
?>

<?php if ( $gift_card ):?>

  <div class="gift-card-content-area">
    <h1 class="gift-card__title">Подарочный сертификат</h1>
    <p class="gift-card__descr">Интернет магазин комнатных растений Plantis</p>
    <div class="gift-card__main">
      <div class="gift-card__wrap">
        <div class="gift-image-wrap">
          <img src="https://plantis-shop.ru/wp-content/uploads/2025/07/decor-n.webp" class="gift-image" alt="Подарочная карта" loading="lazy">
          <p class="gift-image-amount"><?php echo $gift_card['_ywgc_balance_total'][0] ?><span>₽</span></p>
        </div>
        <div class="gift-card__row">
          <p>Номер сертификата:</p>
          <p class="copy-wrap">
            <span id="gift-code"><?php echo $gcnum?></span>
            <button class="copy-btn" type="button" data-copy-target="#gift-code">Скопировать</button>
          </p>
        </div>
        <div class="gift-card__row">
          <p>Срок действия сертификата:</p>
          <p><?php echo $gift_card['_ywgc_expiration_date_formatted'][0] ?></p>
        </div>
      </div>
      
      <div class="gift-card__greeting">
        <p class="gift-card__greeting-to"><?php echo $gift_card['_ywgc_recipient_name'][0] ?></p>
        <?php if($gift_card['_ywgc_message'][0]):?>
        <p class="gift-card__greeting-text"><?php echo $gift_card['_ywgc_message'][0] ?></p>
        <?php else:?>
          <p class="gift-card__greeting-text">Эта подарочная карта для тебя 🌿
              <br>
              Открой для себя мир комнатных растений, подбери красивые горшки и полезные аксессуары.
              <br>
              Пусть твой дом расцветает вместе с новыми зелёными друзьями!
          </p>
        <?php endif; ?>
        <p class="gift-card__greeting-from"><?php echo $gift_card['_ywgc_sender_name'][0] ?></p>
        <a class="button gift-card__btn" href="<?php echo get_site_url()?>/shop">К покупкам</a>
      </div>
    </div>
    <?php get_template_part( 'template-parts/gift-card-faq' );?>
  </div>

<?php else:?>
  <div>Карта с таким номером не найдена.</div>
<? endif;?>


<?php get_footer();?>