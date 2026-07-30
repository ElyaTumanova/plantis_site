<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 * 
 * MODIFIED FOR PLANTIS THEME
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.0.0
 *
 * @var bool $show_downloads Controls whether the downloads table should be rendered.
 */

 // phpcs:disable WooCommerce.Commenting.CommentHooks.MissingHookComment

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

$order_number = $order->get_order_number();
$order_status = esc_html( wc_get_order_status_name( $order->get_status() ) );
$order_date = esc_html( wc_format_datetime( $order->get_date_created() ) );

if ( ! $order ) {
	return;
}

  // echo '<pre>';
	// print_r( $order_number );
	// print_r( $order_status );
	// print_r( $order_date );
	// echo '</pre>';

$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads          = $order->get_downloadable_items();

// We make sure the order belongs to the user. This will also be true if the user is a guest, and the order belongs to a guest (userID === 0).
$show_customer_details = $order->get_user_id() === get_current_user_id() || current_user_can('manage_woocommerce');

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details section">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<h2 class="woocommerce-order-details__title h2"><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h2>

    <!-- new code -->
    <div class="plnt-order__details">
        <div class=plnt-order__items>
            <?php
            do_action( 'woocommerce_order_details_before_order_table_items', $order );

            foreach ( $order_items as $item_id => $item ) {
              $product   = $item->get_product();
              $quantity  = $item->get_quantity();
              $item_name = $item->get_name();
              $item_total = $order->get_formatted_line_subtotal( $item );

              $image = $product
                ? $product->get_image( 'woocommerce_thumbnail', [ 'class' => 'order-item__image' ] )
                : wc_placeholder_img( 'woocommerce_thumbnail', [ 'class' => 'order-item__image' ] );
              ?>

              <div class="order-item">
                <div class="order-item__thumb">
                  <?php echo wp_kses_post( $image ); ?>
                </div>

                <div class="order-item__content">
                  <div class="order-item__name">
                    <?php if ( $product && $product->is_visible() ) : ?>
                      <a href="<?php echo esc_url( $product->get_permalink( $item ) ); ?>">
                        <?php echo esc_html( $item_name ); ?>
                      </a>
                    <?php else : ?>
                      <?php echo esc_html( $item_name ); ?>
                    <?php endif; ?>
                  </div>

                  <div class="order-item__quantity">
                    <?php echo esc_html( $quantity ); ?>
                  </div>

                  <div class="order-item__price">
                    <?php echo wp_kses_post( $item_total ); ?>
                  </div>
                </div>
              </div>

              <?php
            }

            do_action( 'woocommerce_order_details_after_order_table_items', $order );
            ?>
        </div>
        <div class="plnt-order__totals">
            <?php
            $plnt_order_totals = $order->get_order_item_totals();
            $is_courier_tariff = $order->get_meta('_is_courier_deliv_flag', true);
                // echo '<pre>';
                // print_r( $plnt_order_totals );
                // // print_r( $is_courier_tariff );
                // echo '</pre>';
                ?>
                <div class='plnt-order__totals-row'>
                    <div class='plnt-order__totals-label' scope="row">Итого товары:</div>
                    <div class='plnt-order__totals-value'><?php echo wp_kses_post( $plnt_order_totals['cart_subtotal']['value'] ); ?></div>
                </div>
                <?php if($is_courier_tariff == '1'):?>
                  <div class='plnt-order__totals-row'>
                      <div class='plnt-order__totals-label' scope="row">Доставка (<?php echo wp_kses_post( $plnt_order_totals['shipping']['meta'] ); ?>):</div>
                      <div class='plnt-order__totals-value plnt-order__totals-value_delivery'>по тарифу курьерской службы</div>
                  </div>
                <? else:?>
                  <?php if(array_key_exists('shipping', $plnt_order_totals)):?>
                    <div class='plnt-order__totals-row'>
                        <div class='plnt-order__totals-label' scope="row">Доставка (<?php echo wp_kses_post( $plnt_order_totals['shipping']['meta'] ); ?>):</div>
                        <div class='plnt-order__totals-value plnt-order__totals-value_delivery'><?php echo wp_kses_post( $plnt_order_totals['shipping']['value'] ); ?></div>
                    </div>
                  <?php endif;?>
                <?php endif;?>
                <?php if(array_key_exists('yith_gift_cards', $plnt_order_totals)):?>
                  <div class='plnt-order__totals-row'>
                      <div class='plnt-order__totals-label' scope="row">Подарочный сертификат:</div>
                      <div class='plnt-order__totals-value plnt-order__totals-value_gift'><?php echo wp_kses_post( $plnt_order_totals['yith_gift_cards']['value'] ); ?></div>
                  </div>
                <?php endif;?>
                <div class='plnt-order__totals-row plnt-order__totals-row_total'>
                    <div class='plnt-order__totals-label' scope="row">Итого:</div>
                    <div class='plnt-order__totals-value plnt-order__totals-value_total'><?php echo wp_kses_post( $plnt_order_totals['order_total']['value'] ); ?></div>
                </div>
                <?php
            // foreach ( $order->get_order_item_totals() as $key => $total ) {
                ?>
                <!-- <div>
                    <div class='plnt-order__totals-label' scope="row"><?php //echo esc_html( $total['label'] ); ?></div>
                    <div class='plnt-order__totals-value'><?php //echo wp_kses_post( $total['value'] ); ?></div>
                </div> -->
                <?php
            //}
            ?>
        </div>
    </div>
    <!-- //new code -->

  </section>
	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );
?>
<section class="section order-info">
  <div class="order-info__card">
    <h2 class="h2 order-info__title">Контактная информация</h2>

    <?php if ( $show_customer_details ) : ?>
    <div class="order-info__contacts">
      <?php
      $customer_name = trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() );

      $address_parts = array_filter( [
        $order->get_billing_state(),
        $order->get_billing_city(),
        $order->get_billing_address_1(),
      ] );

      $billing_address = implode( ', ', $address_parts );
      ?>

      <?php if ( $customer_name ) : ?>
        <div class="order-info__row">
          <div class="order-info__label">Получатель</div>
          <div class="order-info__value"><?php echo esc_html( $customer_name ); ?></div>
        </div>
      <?php endif; ?>

      <?php if ( $order->get_billing_company() ) : ?>
        <div class="order-info__row">
          <div class="order-info__label">Компания</div>
          <div class="order-info__value"><?php echo esc_html( $order->get_billing_company() ); ?></div>
        </div>
      <?php endif; ?>

      <?php if ( $billing_address || $order->get_billing_address_2() ) : ?>
        <div class="order-info__row">
          <div class="order-info__label">Адрес</div>
          <div class="order-info__value">
            <?php echo esc_html( $billing_address ); ?>

            <?php if ( $billing_address && $order->get_billing_address_2() ) : ?>
              <br>
            <?php endif; ?>

            <?php echo esc_html( $order->get_billing_address_2() ); ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if ( $order->get_billing_phone() ) : ?>
        <div class="order-info__row">
          <div class="order-info__label">Телефон</div>
          <div class="order-info__value">
            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $order->get_billing_phone() ) ); ?>">
              <?php echo esc_html( $order->get_billing_phone() ); ?>
            </a>
          </div>
        </div>
      <?php endif; ?>

      <?php if ( $order->get_billing_email() ) : ?>
        <div class="order-info__row">
          <div class="order-info__label">Email</div>
          <div class="order-info__value">
            <a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>">
              <?php echo esc_html( $order->get_billing_email() ); ?>
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ( $plnt_order_totals['dontcallme']['value'] ) : ?>
      <?php
      $contact_method = $plnt_order_totals['dontcallme']['value'];
      $tg_nik = $order->get_meta( 'additional_tg_nik' );

      if ( 'Написать в Telegram' === $contact_method && ! empty( $tg_nik ) ) {
        $contact_method .= ' (@' . $tg_nik . ')';
      }
      ?>

      <div class="order-info__row">
        <div class="order-info__label">Способ связи</div>
        <div class="order-info__value"><?php echo esc_html( $contact_method ); ?></div>
      </div>
    <?php endif; ?>

    <?php if ( $plnt_order_totals['additional_inn']['value'] ) : ?>
      <div class="order-info__row">
        <div class="order-info__label"><?php echo wp_kses_post( $plnt_order_totals['additional_inn']['label'] ); ?></div>
        <div class="order-info__value"><?php echo wp_kses_post( $plnt_order_totals['additional_inn']['value'] ); ?></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="order-info__card">
    <h2 class="h2 order-info__title">Информация о доставке</h2>

    <?php if ( $plnt_order_totals['delivery_dates']['value'] ) : ?>
      <div class="order-info__row">
        <div class="order-info__label">Дата доставки (самовывоза)</div>
        <div class="order-info__value"><?php echo wp_kses_post( $plnt_order_totals['delivery_dates']['value'] ); ?></div>
      </div>
    <?php endif; ?>

    <?php if ( $plnt_order_totals['additional_delivery_interval']['value'] ) : ?>
      <div class="order-info__row">
        <div class="order-info__label">Интервал доставки</div>
        <div class="order-info__value"><?php echo wp_kses_post( $plnt_order_totals['additional_delivery_interval']['value'] ); ?></div>
      </div>
    <?php endif; ?>

    <?php if ( $order->get_shipping_method() ) : ?>
      <div class="order-info__row">
        <div class="order-info__label">Способ получения</div>
        <div class="order-info__value"><?php echo esc_html( $order->get_shipping_method() ); ?></div>
      </div>
    <?php endif; ?>

    <?php if ( $order->get_customer_note() ) : ?>
      <div class="order-info__row order-info__row--note">
        <div class="order-info__label">Примечание</div>
        <div class="order-info__value"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></div>
      </div>
    <?php endif; ?>
  </div>
</section>