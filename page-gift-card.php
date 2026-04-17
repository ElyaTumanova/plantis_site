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

<section class="gift-showcase">
  <div class="gift-showcase__wrap">
    <div class="gift-showcase__grid">

      <div class="gift-showcase__content">
        <h1 class="gift-showcase__title">
          Подарочный сертификат
        </h1>

        <div class="gift-showcase__bottom">
          <div class="gift-showcase__meta">
            <div class="gift-showcase__meta-item">
              <span class="gift-showcase__meta-label">Номер</span>
              <div class="gift-showcase__meta-value gift-showcase__meta-value--code">
                <span id="gift-code"><?php echo esc_html( $gcnum ); ?></span>
                <button class="copy-btn" type="button" data-copy-target="#gift-code">
                  Скопировать
                </button>
              </div>
            </div>

            <div class="gift-showcase__meta-item">
              <span class="gift-showcase__meta-label">Срок действия</span>
              <span class="gift-showcase__meta-value">
                <?php echo esc_html( $gift_card['_ywgc_expiration_date_formatted'][0] ?? '' ); ?>
              </span>
            </div>
          </div>

          <div class="gift-panel__actions gift-panel__actions--left">
            <a class="button gift-btn gift-btn--primary" href="<?php echo esc_url( get_site_url() . '/shop' ); ?>">
              К покупкам
            </a>
          </div>
        </div>
      </div>

      <div class="gift-showcase__card">
        <div class="gift-panel">
          <div class="gift-panel__head">
            <h2 class="gift-panel__title">Ваш сертификат</h2>

            <div class="gift-panel__switch">
              <button class="gift-panel__switch-btn is-active" type="button" data-view-btn="card">
                Сертификат
              </button>
              <button class="gift-panel__switch-btn" type="button" data-view-btn="message">
                Поздравление
              </button>
            </div>
          </div>

          <div class="gift-panel__body">
            <div class="gift-panel__view is-active" data-view-panel="card">
              <div
                class="gift-certificate"
                style="
                  background-image: <?php echo esc_attr( $gradients[ $gradient_key ] ?? '' ); ?>;
                  background-size: cover, cover;
                  background-position: center, center;
                  background-repeat: no-repeat, no-repeat;
                "
              >
              
                  <?php if ( ! empty( $images[ $image_key ] ) ) : ?>
                    <img
                      class="gift-certificate__image"
                      src="<?php echo esc_url( $images[ $image_key ] ); ?>"
                      alt="<?php echo esc_attr( $image_key ); ?>"
                    >
                  <?php endif; ?>
            

     
                  <div class="gift-certificate__amount">
                    <?php echo esc_html( $gift_card['_ywgc_balance_total'][0] ?? '' ); ?><span>₽</span>
                  </div>
            
              </div>
            </div>

            <div class="gift-panel__view" data-view-panel="message">
              <div class="gift-certificate__message">
                <?php if ( ! empty( $gift_card['_ywgc_recipient_name'][0] ) ) : ?>
                  <p class="gift-certificate__message__to">
                    <?php echo esc_html( $gift_card['_ywgc_recipient_name'][0] ); ?>
                  </p>
                <?php endif; ?>

                <div class="gift-certificate__message-text">
                  <?php if ( ! empty( $gift_card['_ywgc_message'][0] ) ) : ?>
                    <?php
                    $message_paragraphs = preg_split('/\r\n|\r|\n/', $gift_card['_ywgc_message'][0]);
                    foreach ( $message_paragraphs as $paragraph ) :
                      $paragraph = trim( $paragraph );
                      if ( $paragraph === '' ) {
                        continue;
                      }
                    ?>
                      <p><?php echo esc_html( $paragraph ); ?></p>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <p>Эта подарочная карта для тебя 🌿</p>
                    <p>Открой для себя мир комнатных растений, подбери красивые горшки и полезные аксессуары.</p>
                    <p>Пусть твой дом расцветает вместе с новыми зелёными друзьями!</p>
                  <?php endif; ?>

                  <?php if ( ! empty( $gift_card['_ywgc_sender_name'][0] ) ) : ?>
                    <p>
                      <strong>От:</strong> <?php echo esc_html( $gift_card['_ywgc_sender_name'][0] ); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php //get_template_part( 'template-parts/gift-card-faq' ); ?>

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