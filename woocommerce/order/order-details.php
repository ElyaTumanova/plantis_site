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

    echo '<pre>';
	print_r( $order_number );
	print_r( $order_status );
	print_r( $order_date );
	echo '</pre>';

$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads          = $order->get_downloadable_items();

// We make sure the order belongs to the user. This will also be true if the user is a guest, and the order belongs to a guest (userID === 0).
$show_customer_details = $order->get_user_id() === get_current_user_id();

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
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h2>

    <!-- new code -->
    <div class="plnt-order__details">
        <div class=plnt-order__items>
            <?php
            do_action( 'woocommerce_order_details_before_order_table_items', $order );

            foreach ( $order_items as $item_id => $item ) {
                $product = $item->get_product();
                echo '<div class=plnt-order__item>';
                wc_get_template(
                    'order/order-details-item.php',
                    array(
                        'order'              => $order,
                        'item_id'            => $item_id,
                        'item'               => $item,
                        'show_purchase_note' => $show_purchase_note,
                        'purchase_note'      => $product ? $product->get_purchase_note() : '',
                        'product'            => $product,
                    )
                );
                echo '</div>';
            }

            do_action( 'woocommerce_order_details_after_order_table_items', $order );
            ?>
        </div>
        <div class="plnt-order__totals">
            <?php
            $plnt_order_totals = $order->get_order_item_totals();
                echo '<pre>';
                print_r( $plnt_order_totals );
                print_r( $plnt_order_totals['payment_method'] );
                
                echo '</pre>';
                ?> 
                <div class='plnt-order__totals-row'>
                    <div class='plnt-order__totals-label' scope="row">Товары:</div>
                    <div class='plnt-order__totals-value'><?php echo wp_kses_post( $plnt_order_totals['cart_subtotal']['value'] ); ?></div>
                </div>
                
                <?php
            foreach ( $order->get_order_item_totals() as $key => $total ) {
                
                if ($total['label']==='Подытог:') {
                    ?>
                    <div>
                        <div class='plnt-order__totals-label' scope="row">Товары:</div>
                        <div class='plnt-order__totals-value'><?php echo wp_kses_post( $total['value'] ); ?></div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div>
                        <div class='plnt-order__totals-label' scope="row"><?php echo esc_html( $total['label'] ); ?></div>
                        <div class='plnt-order__totals-value'><?php echo wp_kses_post( $total['value'] ); ?></div>
                    </div>
                    <?php
                }
                
            }
            ?>
            <?php if ( $order->get_customer_note() ) : ?>
                <div>
                    <div><?php esc_html_e( 'Note:', 'woocommerce' ); ?></div>
                    <div><?php echo wp_kses( nl2br( wptexturize( $order->get_customer_note() ) ), array() ); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- //new code -->

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}