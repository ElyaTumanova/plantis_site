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

// $giftcard_designs = plnt_get_giftcard_designs_config();


// $gradients = $giftcard_designs['gradients'] ?? [];
// $backgrounds = $giftcard_designs['backgrounds'] ?? [];
// $images = $giftcard_designs['images'] ?? [];


$gift_card_id = (int) plnt_get_giftcard_by_code( $gcnum );
if ( $gift_card_id > 0 ) {
  
  $gift_card = (array) get_post_meta( $gift_card_id );
  // $order = plantis_get_order_from_yith_gift_card($gift_card);

  $gift_card_design = [];

  if ( ! empty( $gift_card['_ywgc_design'][0] ) ) {
      $maybe_design = maybe_unserialize( $gift_card['_ywgc_design'][0] );

      if ( is_array( $maybe_design ) ) {
          $gift_card_design = $maybe_design;
      }
  }

  $gradient_css = '';

  if ( ! empty( $gift_card_design['_plnt_giftcard_gradient_css'] ) ) {
      $gradient_css = plnt_sanitize_giftcard_gradient_css(
          $gift_card_design['_plnt_giftcard_gradient_css']
      );
  }

  if ( empty( $gradient_css ) ) {
      $gradient_css = plnt_get_giftcard_fallback_gradient_css();
  }

  $image_url = '';

  if ( ! empty( $gift_card_design['_plnt_giftcard_image_url'] ) ) {
      $image_url = esc_url_raw(
          $gift_card_design['_plnt_giftcard_image_url']
      );
  }

  if ( empty( $image_url ) ) {
      $image_url = plnt_get_giftcard_fallback_image_url();
  }
}

//for dev

// echo('<pre>');

// // print_r($gift_card);
// // print_r($order);
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

<div class="content-area">
  <section class="gift-showcase section">
    <div class="gift-showcase__wrap">
  
      <h1 class="gift-showcase__title h1">
        Подарочный сертификат
      </h1>
  
      <div class="gift-showcase__card">
        <div class="gift-panel">
          <div class="gift-panel__head">
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
                  background-image: <?php echo esc_attr( $gradient_css ); ?>;
                  background-size: cover, cover;
                  background-position: center, center;
                  background-repeat: no-repeat, no-repeat;
                "
              >
  
                  <?php if ( ! empty( $image_url ) ) : ?>
                    <img
                      class="gift-certificate__image"
                      src="<?php echo esc_url( $image_url ); ?>"
                      alt=""
                    >
                  <?php endif; ?>
  
                  <div class="gift-image-amount">
                    <?php echo esc_html( $gift_card['_ywgc_balance_total'][0] ?? '' ); ?><span>₽</span>
                  </div>
  
              </div>
            </div>
  
            <div class="gift-panel__view" data-view-panel="message">
              <div 
                class="gift-certificate__message"
                style="
                  background-image: <?php echo esc_attr( $gradient_css ); ?>;
                  background-size: cover, cover;
                  background-position: center, center;
                  background-repeat: no-repeat, no-repeat;
                "
                >
                <?php if ( ! empty( $gift_card['_ywgc_recipient_name'][0] ) ) : ?>
                  <p class="gift-certificate__message__to">
                    <?php echo esc_html( $gift_card['_ywgc_recipient_name'][0] ); ?>
                  </p>
                <?php endif; ?>
  
                <div class="gift-certificate__message-text">
                  <?php //pretty_print($gift_card['_ywgc_message'][0] );?>
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
  
  
      <a class="gift-showcase__button button button--green" href="<?php echo esc_url( get_site_url() . '/shop' ); ?>">
        К покупкам
      </a>
  
      <div class="gift-showcase__meta">
        <div class="gift-showcase__meta-item">
            Действует до:
  
            <?php echo esc_html( $gift_card['_ywgc_expiration_date_formatted'][0] ?? '' ); ?>
  
        </div>
  
        <div class="gift-showcase__meta-item gift-showcase__meta-item--code">
          <span class="gift-showcase__meta-label">Номер сертификата</span>
          <div class="gift-showcase__meta-value gift-showcase__meta-value--code">
            <button
              class="copy-btn button button--clean icon icon--copy icon--pre"
              type="button"
              data-copy-target="#gift-code">
            </button>
            <span id="gift-code"><?php echo esc_html( $gcnum ); ?></span>
          </div>
        </div>
  
  
      </div>
  
  
    </div>
  </section>
  
  <?php get_template_part( 'template-parts/gift-card/gift-card-advantages' ); ?>
  <?php get_template_part( 'template-parts/gift-card/gift-card-faq' ); ?>
</div>

<?php else:?>
    <div class="gift-card-cb-content-area section">
      <h1 class="gift-card__check-title h1">Проверить баланс подарочного сертификата</h1>

      <form method="get" class="gc-balance-form" id="gc-balance-form" novalidate>
        <div class="gc-balance-form-wrap">
          <img class="gc-balance-form-image"
            src="<?php echo esc_url( get_template_directory_uri() . '/images/gift-card/check-balance-bg.png' ); ?>" 
            alt=""
            width="480"
            height="363"
          >
          <div class="gc-balance-form-inner">
            <label for="gcnum">Введите номер подарочной карты</label>
            <input id="gcnum"
            name="gcnum"
            type="text"
            inputmode="latin"
            autocomplete="off"
            placeholder="— — — —   — — — —   — — — —    — — — —"
            required
            pattern="^[0-9A-Fa-f]{4}(?:-[0-9A-Fa-f]{4}){3}$"
            title="Формат: XXXX-XXXX-XXXX-XXXX (только 0-9 и A-F)"/>
            <span class="field__errors"></span>

            <?php if ($gcnum):?>
              <p class="gift-card__not-found">Карта <span><?php echo esc_html($gcnum)?></span> не найдена.</p>
            <?php endif; ?>
          </div>
        </div>
          <div class="gc-balance-form-submit">
            <button type="submit" class="button button--green gc-balance__checkBtn">
              Проверить
            </button>
            <button type="button" class="button button--green-l gc-balance__clearBtn">Очистить</button>
          </div>
        </form>

      
</div>
<? endif;?>


<?php get_footer();?>