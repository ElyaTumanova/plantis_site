<?php get_header();

function my_get_logged_in_user_email() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        return ! empty( $user->user_email ) ? $user->user_email : '';
    }
    return '';
}

function my_logged_in_email_focus_class() {
    $email = my_get_logged_in_user_email();
    return $email !== '' ? ' focus' : '';
}

function my_get_logged_in_user_name() {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();

        // приоритет: first_name → display_name → user_login
        if ( ! empty( $user->first_name ) ) {
            return $user->first_name;
        }

        if ( ! empty( $user->display_name ) ) {
            return $user->display_name;
        }

        return $user->user_login;
    }

    return '';
}

function my_logged_in_name_focus_class() {
    $name = my_get_logged_in_user_name();
    return $name !== '' ? ' focus' : '';
}

$gcid = get_the_ID();

$giftcard_designs = plnt_get_giftcard_designs_config();


$gradients = $giftcard_designs['gradients'] ?? [];
$backgrounds = $giftcard_designs['backgrounds'] ?? [];
$images = $giftcard_designs['images'] ?? [];
$default_gradient  = plnt_get_giftcard_default_gradient();
$default_image  = plnt_get_giftcard_default_image();
$background_css    = plnt_get_giftcard_background_css( $default_gradient, $default_image );

// echo ('<pre>');
// print_r($giftcard_designs);
// echo ('</pre>');


?>
<div>
  <div class="gift-content-area">
    <aside class="gc-sidebar">
      <nav class="gc-sidebar__nav" aria-label="Навигация по шагам">
        <ul class="gc-sidebar__list">
          <li class="gc-sidebar__item is-active">
            <button type="button" class="gc-sidebar__link" data-gc-step="1">
              <span class="gc-sidebar__dot"></span>
              <span class="gc-sidebar__text">1. Дизайн</span>
            </button>
          </li>
  
          <li class="gc-sidebar__item">
            <button type="button" class="gc-sidebar__link" data-gc-step="2">
              <span class="gc-sidebar__dot"></span>
              <span class="gc-sidebar__text">2. Номинал</span>
            </button>
          </li>
  
          <li class="gc-sidebar__item">
            <button type="button" class="gc-sidebar__link" data-gc-step="3">
              <span class="gc-sidebar__dot"></span>
              <span class="gc-sidebar__text">3. Кому</span>
            </button>
          </li>
        </ul>
      </nav>
    </aside>
    <div class="gc-step-panel is-active" data-gc-step-panel="1">
      <h2 class="gc-amount__title" data-heading-tag="H2">Выбери дизайн карты</h2>
      <div class="gc-slider-section">
        <div class="gc-slider">
          <button
            class="gc-slider__nav gc-slider__nav--prev"
            type="button"
            aria-label="Предыдущий слайд"
          ></button>
          <div class="gc-slider__stage">
            <div class="gc-card gc-card--side gc-card--left">
              <div class="gc-card__media gc-card__media--circle" id="gcPrevMedia"></div>
            </div>
            <div class="gc-card gc-card--main">
              <?php if (!empty($images) && is_array($images)) : ?>
                <div class="swiper gc-main-swiper">
                  <div class="swiper-wrapper">
                    <?php foreach ($images as $key => $image_url) : ?>
                      <div class="swiper-slide" data-image-key="<?php echo esc_attr($key); ?>">
                        <div class="gc-main-slide">
                          <img
                            class="gc-main-slide__image"
                            src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($key); ?>"
                          >
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
            <div class="gc-card gc-card--side gc-card--right">
              <div class="gc-card__media gc-card__media--circle" id="gcNextMedia"></div>
            </div>
            <div class="gc-card gc-card--side gc-card--right-2">
              <div class="gc-card__media gc-card__media--circle" id="gcNext2Media"></div>
            </div>
          </div>
          <button
            class="gc-slider__nav gc-slider__nav--next"
            type="button"
            aria-label="Следующий слайд"
          ></button>
          <div class="gc-slider__dots" id="gcSliderDots"></div>
        </div>

        <div class="gift-gradient-arc">
          <div class="gift-gradient-picker__list gift-gradient-arc__track">
            <?php foreach ( $gradients as $gradient_key => $gradient_css ) : ?>
              <?php
              $is_active = $gradient_key === $default_gradient;
              $label = $gradient_labels[ $gradient_key ] ?? $gradient_key;
              ?>
              <button
                type="button"
                class="gift-gradient-picker__btn gift-gradient-arc__item<?php echo $is_active ? ' is-active' : ''; ?>"
                data-gradient-key="<?php echo esc_attr( $gradient_key ); ?>"
                aria-label="<?php echo esc_attr( $label ); ?>"
                title="<?php echo esc_attr( $label ); ?>"
              >
                <span style="background-image: <?php echo esc_attr( $gradient_css ); ?>;"></span>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      

      <div class="gc-step-actions">
        <button type="button" class="gc-step-btn gc-step-btn--prev" data-gc-prev-step disabled>
          Назад
        </button>
        <button type="button" class="gc-step-btn gc-step-btn--next" data-gc-next-step>
          Далее
        </button>
      </div>
    </div>

    <div class="gc-step-panel" data-gc-step-panel="2" hidden>
      <h2 class="gc-amount__title">Выбери номинал карты</h2>

      <div class="gc-amount__layout">
        <div class="gc-amount__visual">
          <div class="gc-amount__circle-bg"></div>

          <div class="gc-amount__card-wrap">
            <!-- сюда подставляй картинку из этапа выбора дизайна -->
            <img
              class="gc-amount__card-image"
              src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1200&q=80""
              alt="Выбранный дизайн подарочной карты"
              id="giftCardPreview"
            />
          </div>

          <div
            class="gc-amount__wheel"
            id="amountWheel"
            aria-label="Выбор номинала"
          ></div>
        </div>

        <div class="gc-amount__info">
          <label class="gc-amount__input-wrap" for="giftCardAmountInput">
            <input
              class="gc-amount__input"
              id="giftCardAmountInput"
              type="text"
              inputmode="numeric"
              value="1500"
              autocomplete="off"
            />
            <span class="gc-amount__currency">₽</span>
          </label>

          <div class="gc-amount__hint">
            можно купить ≈ <strong id="giftCardAmountHintText">небольшой букет</strong>
          </div>
        </div>
      </div>


      <div class="gc-step-actions">
        <button type="button" class="gc-step-btn gc-step-btn--prev" data-gc-prev-step disabled>
          Назад
        </button>
        <button type="button" class="gc-step-btn gc-step-btn--next" data-gc-next-step>
          Далее
        </button>
      </div>
    </div>

    <div class="gc-step-panel" data-gc-step-panel="3" hidden>
      <form method="post" action="/wp-admin/admin-post.php" class="gift-cards_form" novalidate>
        <input type="hidden" name="action" value="giftcard_pay">
        <input type="hidden" name="giftcard_product_id" value="<?php echo $gcid?>"> <!-- ID товара gift-card -->
        <input type="hidden" name="giftcard_gradient" id="giftcard_gradient" value="<?php echo esc_attr( $default_gradient ); ?>">
        <input type="hidden" name="giftcard_image" id="giftcard_image" value="<?php echo esc_attr( $default_image ); ?>">
        <input type="number" name="giftcard_amount" id="giftcard_amount" value="" min="1" required style="display:none;">
        <div class="gift-card-content">
          <div>
            <h3>Куда отправить сертификат</h3>
            <div class="gift-buyer-name gift-input-wrap gift-input-wrap_labeled">
              <label for="gift-buyer-name">Как вас зовут*</label>
              <input type="text"
                    id="gift-buyer-name"
                    name="gift-buyer-name[]"
                    value="<?php echo esc_attr( my_get_logged_in_user_name() ); ?>"
                    required
                    class="gift-recipient yith_wc_gift_card_input_recipient_details <?php echo esc_attr( my_logged_in_name_focus_class() ); ?>">
              <span class="field__errors"></span>
            </div>
            <div class="gift-recipient-email gift-input-wrap gift-input-wrap_labeled">
              <label for="gift-recipient-email">Ваша почта*</label>
              <input type="email"
                    id="gift-recipient-email"
                    name="gift-recipient-email[]"
                    value="<?php echo esc_attr( my_get_logged_in_user_email() ); ?>"
                    required
                    pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
                    title="Введите корректный email в формате name@example.com"
                    class="gift-recipient yith_wc_gift_card_input_recipient_details <?php echo esc_attr( my_logged_in_email_focus_class() ); ?>">
              <span class="field__errors"></span>
              <p class="gift-recipient-note">Ссылка на подарочный сертификат будет направлена на указанную почту автоматически после оплаты</p>
            </div>
            <div class="gift-recipient-phone gift-input-wrap gift-input-wrap_labeled">
              <label for="gift-recipient-phone">Ваш номер телефона*</label>
              <input type="tel"
                    id="gift-recipient-phone"
                    name="gift-recipient-phone[]"
                    required
                    pattern="^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$"
                    title="Введите номер в формате: +7 (XXX) XXX-XX-XX"
                    class="gift-recipient yith_wc_gift_card_input_recipient_details">
              <span class="field__errors"></span>
              <p class="gift-recipient-note">Ваш номер телефона для связи</p>
            </div>
          </div>
          <div>
            <h3>Кому дарим</h3>
            <div class="gift-recipient-name gift-input-wrap gift-input-wrap_labeled">
              <label for="gift-recipient-name">Имя получателя*</label>
              <input type="text"
                    id="gift-recipient-name"
                    name="gift-recipient-name[]"
                    required
                    class="yith_wc_gift_card_input_recipient_details">
              <span class="field__errors"></span>
            </div>
            <div class="gift-message gift-input-wrap gift-input-wrap_labeled">
              <label for="gift-edit-message">Добавьте теплых слов</label>
              <textarea id="gift-edit-message" name="gift-edit-message" rows="5"></textarea>
            </div>
            <div class="gift-sender-name gift-input-wrap gift-input-wrap_labeled">
              <label for="gift-sender-name">Имя отправителя</label>
              <input type="text" name="gift-sender-name" id="gift-sender-name" value="">
            </div>
            <button type="submit" class = "gift-card__submit-btn button">К оплате</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<?php get_footer();?>