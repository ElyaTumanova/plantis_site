<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon');
?>

<div class="popup gc-balance-popup">
  <div class="gc-balance-popup__container">
    <div class="page-popup__wrap">
        <h2 class="page-popup__heading heading-2">Проверить баланс подарочного сертификата</h2>
        <span class="gc-balance-popup__close heading-2"><?php echo $close_icon ?></span>
        <?php //echo do_shortcode('[yith_gift_card_check_balance_form]');?>

        <form method="post" id="gc-balance-form" novalidate>
          <label for="code">Код подарочной карты</label>
          <input id="code" name="code" type="text" inputmode="latin"
                placeholder="Например: AB12-CD34-EF56"
                autocomplete="off" required
                pattern="[A-Za-z0-9-]{8,24}" />
          <div class="hint">Допустимы буквы, цифры и дефис. Минимум 8 символов.</div>

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
  </div>
  <div class="gc-balance-popup__popup-overlay popup-overlay"></div>
</div>	