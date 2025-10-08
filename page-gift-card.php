<?php get_header(); 


/** helper: безопасное чтение мета */
function gc_meta(array $meta, string $key, $default = '') {
    return isset($meta[$key][0]) ? $meta[$key][0] : $default;
}

/** 1) берём и нормализуем gcnum */
$raw_gcnum = (string) get_query_var('gcnum');
$gcnum = strtoupper( sanitize_text_field( $raw_gcnum ) );

/** строгая allow-list валидация */
// $gc_valid = (bool) preg_match('/^[A-Z0-9]{4}(?:-[A-Z0-9]{4}){3}$/', $gcnum);

/** если код невалидный — можно сразу показать форму и/или выставить 400 */
// if ( $raw_gcnum && ! $gc_valid ) {
//     status_header(400); // опционально
//     $gcnum = '';        // не пропускаем дальше
// }

/** 2) ищем карту только для валидного кода */
$gift_card_id = 0;
$gift_card    = [];

// if ( $gc_valid ) {
    $gift_card_id = (int) plnt_get_giftcard_by_code( $gcnum );
    if ( $gift_card_id > 0 ) {
        $gift_card = (array) get_post_meta( $gift_card_id );
    }
// }
//for dev

echo('<pre>');
if ( $gift_card ) {
    echo 'Карта найдена.';
} else {
    echo 'Карта с таким номером не найдена.';
}
print_r($raw_gcnum);
print_r($gcnum);
print_r($gift_card);
echo('</pre>');
?>

<?php if ( $gift_card ):?>

  <div class="gift-card-content-area">
    <h1 class="gift-card__title">Подарочный сертификат</h1>
    <p class="gift-card__descr">Интернет магазин комнатных растений Plantis</p>
    <div class="gift-card__main">
      <div class="gift-card__wrap">
        <div class="gift-image-wrap">
          <img src="<?php echo get_template_directory_uri()?>/images/gift-card/gc_cover.jpg" class="gift-image" alt="Подарочная карта" loading="lazy">
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
    <div class="gift-card-content-area">
      <?php if ($gcnum):?>
        <p>Карта с номером <?php echo esc_html($gcnum)?> не найдена.</p>
      <?php endif; ?>
      <h1>Проверить баланс подарочного сертификата</h1>
      <form method="get" novalidate>
      <label for="gcnum">Код подарочной карты</label>
      <input id="gcnum" name="gcnum" type="text" inputmode="latin"
            placeholder="XXXX-XXXX-XXXX-XXXX"
            autocomplete="off" required />

      <div class="row">
        <button id="checkBtn" type="submit">
          Проверить
          <span id="spin" class="spinner" style="display:none"></span>
        </button>
        <button type="button" id="clearBtn" style="background:#e5e7eb;color:#111;">Очистить</button>
      </div>

      <div id="msg" class="result" style="display:none"></div>
    </form>
</div>
<? endif;?>


<?php get_footer();?>