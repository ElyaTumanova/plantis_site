<?php get_header(); 

/** 1) берём и нормализуем gcnum */
$raw_gcnum = (string) get_query_var('gcnum');
$raw_gcnum = wp_unslash($raw_gcnum);

// 2) снять ВСЕ HTML-теги (никаких <b>, <script> и т.п.)
$no_html = wp_strip_all_tags($raw_gcnum, true);

// 3) общая санитизация текста (убирает управляющие символы, null-bytes и пр.)
$clean = sanitize_text_field($no_html);

// 4) нормализация под ваш формат
$gcnum = strtoupper($clean);


$gift_card_id = 0;
$gift_card    = [];

$giftcard_designs = plnt_get_giftcard_designs_config();


$gradients = $giftcard_designs['gradients'] ?? [];
$backgrounds = $giftcard_designs['backgrounds'] ?? [];
$images = $giftcard_designs['images'] ?? [];


$gift_card_id = (int) plnt_get_giftcard_by_code( $gcnum );
if ( $gift_card_id > 0 ) {
  
  $gift_card = (array) get_post_meta( $gift_card_id );
  $gift_card_design = unserialize($gift_card['_ywgc_design'][0]);

  $gradient_key = ! empty( $gift_card_design['_plnt_giftcard_gradient'] )
  ? sanitize_key( $gift_card_design['_plnt_giftcard_gradient'] )
  : plnt_get_giftcard_default_gradient();

  $image_key = ! empty( $gift_card_design['_plnt_giftcard_image'] )
  ? sanitize_key( $gift_card_design['_plnt_giftcard_image'] )
  : plnt_get_giftcard_default_image();

  $background_css = plnt_get_giftcard_background_css( $gradient_key, $image_key );
}

//for dev

// echo('<pre>');
// // if ( $gift_card ) {
// //     echo 'Карта найдена.';
// // } else {
// //     echo 'Карта с таким номером не найдена.';
// // }
// // print_r($raw_gcnum);
// // print_r($gcnum);
// // print_r($gift_card_design);
// // print_r($image_key);
// // print_r($gradient_key);

// echo('</pre>');
?>

<?php if ( $gift_card ):?>

  <div class="gift-card-content-area">
    <h1 class="gift-card__title">Подарочный сертификат</h1>
    <p class="gift-card__descr">Интернет магазин комнатных растений Plantis</p>
    <div class="gift-card__main">
      <div class="gift-card__wrap">
        <div class="gift-image-wrap"
            style="background-image: <?php echo esc_attr( $gradients[$gradient_key] ); ?>; background-size: cover, cover; background-position: center, center; background-repeat: no-repeat, no-repeat;">
           <img
            class="gc-main-slide__image"
            src="<?php echo esc_url($images[$image_key]); ?>"
            alt="<?php echo esc_attr($image_key); ?>"
          >
            <p class="gift-image-amount"><?php echo esc_html($gift_card['_ywgc_balance_total'][0]) ?><span>₽</span></p>
        </div>
        <div class="gift-card__row">
          <p>Номер сертификата:</p>
          <p class="copy-wrap">
            <span id="gift-code"><?php echo esc_html($gcnum)?></span>
            <button class="copy-btn" type="button" data-copy-target="#gift-code">Скопировать</button>
          </p>
        </div>
        <div class="gift-card__row">
          <p>Срок действия сертификата:</p>
          <p><?php echo esc_html($gift_card['_ywgc_expiration_date_formatted'][0]) ?></p>
        </div>
      </div>
      
      <div class="gift-card__greeting">
        <p class="gift-card__greeting-to"><?php echo esc_html($gift_card['_ywgc_recipient_name'][0]) ?></p>
        <?php if($gift_card['_ywgc_message'][0]):?>
        <p class="gift-card__greeting-text"><?php echo esc_html($gift_card['_ywgc_message'][0]) ?></p>
        <?php else:?>
          <p class="gift-card__greeting-text">Эта подарочная карта для тебя 🌿
              <br>
              Открой для себя мир комнатных растений, подбери красивые горшки и полезные аксессуары.
              <br>
              Пусть твой дом расцветает вместе с новыми зелёными друзьями!
          </p>
        <?php endif; ?>
        <p class="gift-card__greeting-from"><?php echo esc_html($gift_card['_ywgc_sender_name'][0]) ?></p>
        <a class="button gift-card__btn" href="<?php echo get_site_url()?>/shop">К покупкам</a>
      </div>
    </div>
    <?php get_template_part( 'template-parts/gift-card-faq' );?>
  </div>

<?php else:?>
    <div class="gift-card-cb-content-area">
      <h1 class="gift-card__check-title">Проверить баланс подарочного сертификата</h1>
      <form method="get" class="gc-balance-form" novalidate>
        <label for="gcnum">Номер подарочной карты</label>
        <input id="gcnum" 
        name="gcnum" 
        type="text" 
        inputmode="latin"
        autocomplete="off" 
        required 
        pattern="^[0-9A-Fa-f]{4}(?:-[0-9A-Fa-f]{4}){3}$"
        title="Формат: XXXX-XXXX-XXXX-XXXX (только 0-9 и A-F)"/>
        <span class="field__errors"></span>

        <div class="row">
          <button type="submit" class="button gc-balance__checkBtn">
            Проверить
            <!-- <span id="spin" class="spinner" style="display:none"></span> -->
          </button>
          <button type="button" class="button gc-balance__clearBtn">Очистить</button>
        </div>

        <!-- <div id="msg" class="result" style="display:none"></div> -->
      </form>
      <?php if ($gcnum):?>
          <p class="gift-card__not-found">Карта с номером <span><?php echo esc_html($gcnum)?></span> не найдена.</p>
      <?php endif; ?>
</div>
<? endif;?>


<?php get_footer();?>