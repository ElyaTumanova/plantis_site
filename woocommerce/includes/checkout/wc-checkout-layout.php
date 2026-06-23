<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# Checkout page adjustments
--------------------------------------------------------------*/

    /* checkout steps */
    add_action( 'woocommerce_before_checkout_form', function() {
      plnt_get_checkout_steps( 'checkout' );
    }, 10 );

    /* скрываем Уже покупали? и форму логирования на странице оформления заказа*/
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

    plnt_add_wrapper('checkout__footer', 'woocommerce_checkout_order_review', 10, 'woocommerce_checkout_order_review', 99);

    // // переместили блок с выбором способа оплаты
    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
    add_action('woocommerce_checkout_shipping', 'woocommerce_checkout_payment', 20);

    // // блок со способами доставки
    add_action( 'woocommerce_checkout_order_review', 'wc_cart_totals_shipping_html', 12 );
    // // информация о пересадке в горшок
    add_action('woocommerce_checkout_order_review', 'plnt_checkout_peresadka_info', 11);

    function plnt_checkout_peresadka_info(){
        ?>
        <div class="checkout__additional">
          <p class="checkout__additional-text">Мы <span>БЕСПЛАТНО</span> пересадим вашего нового друга в качественный грунт при одновременной покупке растения и горшка (доплата за грунт не требуется).</p>
          <small>*Кроме кашпо Trezz и Lechuza диаметром больше 26 см</small>
          <img
            class="checkout__additional-image"
            src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/peresadka-decor.png' ); ?>"
            alt=""
            aria-hidden="true"
            width="50"
            height="48"
          >
        </div>
        <?php
    }

    // // информация об условиях доставки
    //add_action( 'woocommerce_checkout_order_review', 'plnt_delivery_condition_info', 30 );

    // function plnt_delivery_condition_info () {
    //     echo '<div class="checkout__text checkout__text_delivery-info">
    //         После оформления заказа мы свяжемся с вами в <a href="https://plantis-shop.ru/contacts/">рабочее время</a> и согласуем время доставки.
    //         <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки.</a> <br>
    //         Важно! Срочную доставку "день в день" можно оформить до 18 часов.</div>';
    // }
    
    // хук для подарчной карты #giftcard

    //add_action( 'woocommerce_checkout_order_review', 'plnt_set_giftcard_hook', 25 );

    function plnt_set_giftcard_hook() {
        if (is_not_gift_card_checkout()) {
          echo ('<div class="checkout__gift">');
          do_action( 'plnt_woocommerce_checkout_gift_card' );
          echo('</div>');
        }
    }

    function plnt_checkout_giftcard_form() {
      ?>
      <div class="checkout__gift">
        <div class="ywgc_have_code">Подарочный сертификат
          <a href="#" class="ywgc-show-giftcard"></a>
        </div>
        <div class="ywgc_enter_code">
          <div class="checkout-gift-card__row checkout-gift-card__row--input">
            <input
              type="text"
              name="gift_card_code"
              class="input-text checkout-gift-card__input"
              placeholder="<?php esc_attr_e( 'Введите номер', 'plantis' ); ?>"
              id="giftcard_code"
              value=""
            >
            <button
              type="submit"
              class="button ywgc_apply_gift_card_button button button--green-l checkout-gift-card__btn"
              name="ywgc_apply_gift_card"
              value="<?php esc_attr_e( 'Применить', 'plantis' ); ?>"
            >
              <?php echo plnt_icon('send'); ?>
            </button>
            <input type="hidden" name="is_gift_card" value="1">
          </div>
          <?php plnt_gift_card_applied();?>
        </div>
      </div>
      <?php
              
    }

    function plnt_gift_card_applied() {
      if ( empty( WC()->cart->applied_gift_cards ) ) {
        return;
      }

 

      foreach ( WC()->cart->applied_gift_cards as $code ) {

        $amount = ! empty( WC()->cart->applied_gift_cards_amounts[ $code ] )
          ? WC()->cart->applied_gift_cards_amounts[ $code ]
          : 0;

        $remove_url = add_query_arg(
          'remove_gift_card_code',
          urlencode( $code ),
          wc_get_checkout_url()
        );
        ?>
        <div class="checkout-gift-card__row checkout-gift-card__row--code">
          <div class="checkout-gift-card__input">
            <?php echo($code);?>
          </div>

          <div class="checkout-gift-card__btn button button--green">
            <a
              href="<?php echo esc_url( $remove_url ); ?>"
              class="ywgc-remove-gift-card"
              data-gift-card-code="<?php echo esc_attr( $code ); ?>"
            >
            </a>
          </div>
        </div>
        <?php
      }
    }

    // хук перед итоговой стоимостью
    //add_action( 'woocommerce_checkout_order_review', 'plnt_set_before_order_total_hook', 30 );

    function plnt_set_before_order_total_hook() {
      echo '<table class="plnt-before-order-total">
      <tbody>' ;
      do_action( 'woocommerce_review_order_before_order_total' );
      echo '
      </tbody>
      </table>' ;
    }

    // добавляем фрагмент, чтобы апдейтить поле с подарочной картой
    // add_action( 'woocommerce_update_order_review_fragments', 'my_update_order_review_giftcard_fragments', 10, 1 );
    function my_update_order_review_giftcard_fragments( $fragments ) {
        ob_start();
        plnt_set_before_order_total_hook();
        $fragments[ 'table.plnt-before-order-total'] = ob_get_clean();
        return $fragments;
    }

    // итоговая стоимость
    // add_action( 'woocommerce_checkout_order_review', 'plnt_order_total', 35 );

    function plnt_order_total() {

        if ( ! WC()->cart ) {
            return;
        }

        $cart_summary = plnt_get_cart_summary();

        $shipping_total = (float) WC()->cart->get_shipping_total();
        $shipping_total += (float) WC()->cart->get_shipping_tax();

        if ( $cart_summary['count'] <= 0 ) {
            return;
        }

        $gift_card_total = 0;

        if ( ! empty( WC()->cart->applied_gift_cards_amounts ) ) {
            $gift_card_total = array_sum( WC()->cart->applied_gift_cards_amounts );
        }
        ?>

        <div class="checkout-summary">
            <div class="cart-summary__wrap">

                <div class="cart-summary__top cart-summary__row">
                    <span class="cart-summary__title">
                        <?php esc_html_e( 'Итого', 'plantis' ); ?>
                    </span>
                    <span class="cart-summary__total">
                        <?php echo wp_kses_post( wc_price( $cart_summary['total'] ) ); ?>
                    </span>
                </div>

                <div class="cart-summary__list">

                    <div class="cart-summary__row">
                        <span>
                            <?php esc_html_e( 'Кол-во товаров', 'plantis' ); ?>
                        </span>
                        <span>
                            <?php echo esc_html( $cart_summary['count'] ); ?>
                        </span>
                    </div>

                    <div class="cart-summary__row">
                        <span>
                            <?php esc_html_e( 'Общая стоимость', 'plantis' ); ?>
                        </span>
                        <span>
                            <?php echo wp_kses_post( wc_price( $cart_summary['regular_total'] ) ); ?>
                        </span>
                    </div>

                    <?php if ( $cart_summary['discount'] > 0 ) : ?>
                        <div class="cart-summary__row cart-summary__row--discount">
                            <span>
                                <?php esc_html_e( 'Скидка', 'plantis' ); ?>
                            </span>
                            <span class="cart-summary__discount">
                                -<?php echo wp_kses_post( wc_price( $cart_summary['discount'] ) ); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $gift_card_total > 0 ) : ?>
                      <div class="cart-summary__row cart-summary__row--gift-card">
                          <span>
                              <?php esc_html_e( 'Подарочный сертификат', 'plantis' ); ?>
                          </span>
                          <span class="cart-summary__gift-card">
                              -<?php echo wp_kses_post( wc_price( $gift_card_total ) ); ?>
                          </span>
                      </div>
                    <?php endif; ?>

                    <?php if ( $shipping_total > 0 ) : ?>
                        <div class="cart-summary__row">
                            <span>
                                <?php esc_html_e( 'Доставка', 'plantis' ); ?>
                            </span>
                            <span>
                                <?php echo wp_kses_post( wc_price( $shipping_total ) ); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
            <?php plnt_checkout_giftcard_form();?>

            <button
                type="submit"
                class="cart-summary__button button button--green alt"
                name="woocommerce_checkout_place_order"
                id="place_order"
                value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>"
            >
                <?php esc_html_e( 'Оформить заказ', 'plantis' ); ?>
                <span class="cart-summary__button-total">
                    <?php echo wp_kses_post( wc_price( $cart_summary['total'] ) ); ?>
                </span>
            </button>

            <div class="woocommerce-privacy-policy-text">
                <p>
                    Нажимая кнопку "Подтвердить заказ", вы даете согласие на обработку своих персональных данных и соглашаетесь с положениями, описанными в нашей
                    <a
                        href="<?php echo esc_url( get_privacy_policy_url() ); ?>"
                        class="woocommerce-privacy-policy-link"
                        target="_blank"
                    >
                        политике конфиденциальности
                    </a>.
                </p>
            </div>

        </div>

        <?php
    }

    function plnt_order_total_old() {
            ?>
            <div class="plnt-order-total">
                <div>Итого</div>
                <div class="plnt-order-total_price"><?php wc_cart_totals_order_total_html(); ?></div>
            </div>
            <?php 
    };

    // добавляем фрагмент, чтобы апдейтить итоговую стоимость
    add_action( 'woocommerce_update_order_review_fragments', 'my_update_order_review_fragments', 10, 1 );
    function my_update_order_review_fragments( $fragments ) {
        ob_start();
        plnt_order_total();
        $fragments[ 'div.checkout-summary'] = ob_get_clean();
        return $fragments;
    }

    // выводим в форме оформления заказа информацию, о товарах, которые закончились
    add_action ('woocommerce_cart_has_errors', 'plnt_check_cart_item_stock');

    function plnt_check_cart_item_stock() {

      if ( ! WC()->cart ) return;

      $out = [];

      foreach ( WC()->cart->get_cart() as $cart_item ) {
          if ( empty($cart_item['data']) || ! is_a($cart_item['data'], 'WC_Product' ) ) continue;

          /** @var WC_Product $product */
          $product = $cart_item['data'];

          if ( $product->get_stock_status() === 'outofstock' ) {
              $out[] = $product->get_name();
          }
      }

      // Нечего выводить
      if ( empty($out) ) return;

      echo '<div class="cart-error-list" role="group" aria-label="Недоступные товары">';
      echo '<div class="cart-error-list__title">Товары, недоступные для заказа</div>';
      echo '<ul class="cart-error-list__items">';

      foreach ( $out as $name ) {
          echo '<li class="cart-error-list__item">' . esc_html( $name ) . '</li>';
      }

      echo '</ul>';
      echo '</div>';
    }