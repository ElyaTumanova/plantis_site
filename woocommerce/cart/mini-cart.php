<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 * 
 * MODIFIED FOR PLANTIS THEME
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">

          <div class="mini-cart-item__remove">
            <?php
            $parentCatId = check_category($_product);
            $quantity    = plnt_get_product_quantity_in_cart($product_id);

            echo apply_filters(
              'woocommerce_cart_item_remove_link',
              sprintf(
                '<a href="%s" class="remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-product_name="%s" data-product_category="%s" data-product_quantity="%s" data-product_price="%s">%s</a>',
                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                esc_attr( $product_id ),
                esc_attr( $cart_item_key ),
                esc_attr( $_product->get_sku() ),
                esc_attr( $_product->get_title() ),
                esc_attr( get_the_category_by_ID( $parentCatId ) ),
                esc_attr( $quantity ),
                esc_attr( $_product->get_price() ),
                plnt_icon( 'trash' )
              ),
              $cart_item_key
            );
            ?>
          </div>

          <div class="mini-cart-item__thumbnail">
            <?php
            if ( ! $product_permalink ) {
              echo $thumbnail;
            } else {
              printf(
                '<a href="%s">%s</a>',
                esc_url( $product_permalink ),
                $thumbnail
              );
            }
            ?>
          </div>

          <div class="mini-cart-item__name">
            <?php
            if ( ! $product_permalink ) {
              echo '<span>' . wp_kses_post( $product_name ) . '</span>';
            } else {
              echo wp_kses_post(
                apply_filters(
                  'woocommerce_cart_item_name',
                  sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( $product_permalink ),
                    $_product->get_name()
                  ),
                  $cart_item,
                  $cart_item_key
                )
              );
            }

            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

            echo wc_get_formatted_cart_item_data( $cart_item );

            if (
              $_product->backorders_require_notification()
              && $_product->is_on_backorder( $cart_item['quantity'] )
            ) {
              echo wp_kses_post(
                apply_filters(
                  'woocommerce_cart_item_backorder_notification',
                  '<p class="backorder_notification">' .
                  esc_html__( 'Available on backorder', 'woocommerce' ) .
                  '</p>',
                  $product_id
                )
              );
            }
            ?>
            <?php get_backorder_info_snippet( $_product, $cart_item['quantity'] ); ?>
          </div>


          <div class="cart__peresadka" data-product_id="<?php echo esc_attr( $product_id ); ?>">
            <?php
            get_template_part(
              'template-parts/products/products-peresadka',
              null,
              array(
                'product_id'    => $product_id,
                'cart_item_key' => $cart_item_key,
              )
            );
            ?>
          </div>


          <div class="mini-cart-item__price">
            <?php
            echo apply_filters(
              'woocommerce_cart_item_price',
              WC()->cart->get_product_price( $_product ),
              $cart_item,
              $cart_item_key
            );
            ?>
          </div>

          <div class="mini-cart-item__quantity">
            <div class="quantity">

              <div class="minus">
                <?php
                if ( (int) $cart_item['quantity'] <= 1 ) {
                  echo plnt_icon( 'trash', 'trash-icon' );
                } else {
                  echo plnt_icon( 'minus' );
                }
                ?>
              </div>

              <?php

              if ( $_product->is_sold_individually() ) {
                $min_quantity = 1;
                $max_quantity = 1;
              } else {
                $min_quantity = 0;
                $max_quantity = $_product->get_max_purchase_quantity();
              }

              echo woocommerce_quantity_input(
                array(
                  'input_name'   => "cart[{$cart_item_key}][qty]",
                  'input_value'  => $cart_item['quantity'],
                  'max_value'    => $max_quantity,
                  'min_value'    => $min_quantity,
                  'product_name' => $product_name,
                ),
                $_product,
                false
              );
              ?>

              <div
                class="plus"
                <?php if ( $cart_item['quantity'] === $max_quantity ) : ?>
                  style="opacity:.5;cursor:default;"
                <?php endif; ?>
              >
                <?php echo plnt_icon( 'plus' ); ?>
              </div>

            </div>
          </div>

          <div class="mini-cart-item__subtotal">
            <?php
            echo apply_filters(
              'woocommerce_cart_item_subtotal',
              WC()->cart->get_product_subtotal(
                $_product,
                $cart_item['quantity']
              ),
              $cart_item,
              $cart_item_key
            );

            echo plnt_get_product_regular_subtotal(
              $_product,
              $cart_item['quantity']
            );
            ?>
          </div>

        </li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<div class="woocommerce-mini-cart__footer">
    <p class="woocommerce-mini-cart__total total">
      <?php
      /**
       * Hook: woocommerce_widget_shopping_cart_total.
       *
       * @hooked woocommerce_widget_shopping_cart_subtotal - 10
       */
      do_action( 'woocommerce_widget_shopping_cart_total' );
      ?>
    </p>
    <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
    <p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>
    <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
  </div>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>