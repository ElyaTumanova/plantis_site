<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 * 
 * MODIFIED FOR PLANTIS THEME
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :
    ?> <div class="woocommerce-order__hero section"><?php
		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>
    </div><!--woocommerce-order__hero-->
		<?php else : ?>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>
    
      <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
        <li class="woocommerce-order-overview__order order">
          <span>Заказ</span>
          <strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong>
        </li>

        <li class="woocommerce-order-overview__date date">
          <span>Дата</span>
          <strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
        </li>

        <?php if ( $order->get_payment_method_title() ) : ?>
          <li class="woocommerce-order-overview__payment-method method">
            <span>Оплата</span>
            <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
          </li>
        <?php endif; ?>

        <?php if ( $order->get_shipping_method() ) : ?>
          <li class="woocommerce-order-overview__shipping shipping">
            <span>Доставка</span>
            <strong><?php echo esc_html( $order->get_shipping_method() ); ?></strong>
          </li>
        <?php endif; ?>

        <li class="woocommerce-order-overview__total total">
          <span>Итого</span>
          <strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
        </li>
      </ul>
    </div><!--woocommerce-order__hero-->
		<?php endif; ?>

    <section class="thankyou-next section">
      <h2 class="h2 thankyou-next__title">Что будет дальше</h2>

      <ul class="thankyou-next__list">
        <li class="thankyou-next__item">
          <span class="thankyou-next__number">01</span>

          <div class="thankyou-next__content">
            <h3 class="h5 thankyou-next__item-title">Подтвердим заказ</h3>
            <p class="thankyou-next__descr">Менеджер проверит состав заказа и свяжется с вами удобным способом.</p>
          </div>
        </li>

        <li class="thankyou-next__item">
          <span class="thankyou-next__number">02</span>

          <div class="thankyou-next__content">
            <h3 class="h5 thankyou-next__item-title">Подготовим растения</h3>
            <p class="thankyou-next__descr">Осмотрим растения, аккуратно соберём заказ и при необходимости пришлём фотографии.</p>
          </div>
        </li>

        <li class="thankyou-next__item">
          <span class="thankyou-next__number">03</span>

          <div class="thankyou-next__content">
            <h3 class="h5 thankyou-next__item-title">Согласуем получение</h3>
            <p class="thankyou-next__descr">Уточним дату и время доставки или сообщим, когда заказ будет готов к самовывозу.</p>
          </div>
        </li>
      </ul>
    </section>

		<?php //do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
        <!-- <h3 class="heading-2 thankyou__adress-heading">Контактная информация</h3> -->
        <?php //wc_get_template( 'order/order-details-customer.php', array('order' => $order ));?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
     

	<?php endif; ?>

</div>