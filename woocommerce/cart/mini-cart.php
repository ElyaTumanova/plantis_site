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
				$parentCatId = check_category($_product);
        $catName = get_the_category_by_ID( $parentCatId );
        $price = $_product->get_price();
        ?>
				<li 
          class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>"
          data-js-metrika-product
          data-product_id = "<?php echo esc_attr($product_id);?>"
          data-product_name = "<?php echo esc_attr($product_name);?>"
          data-product_price = "<?php echo esc_attr($price);?>"
          data-product_category = "<?php echo esc_attr($catName);?>"  
        >

          <div class="mini-cart-item__remove">
            <?php
            
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
                esc_attr( $product_name ),
                esc_attr( $catName ),
                esc_attr( $quantity ),
                esc_attr( $price ),
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
            <?php
            $quantity = (int) $cart_item['quantity'];

            if ( $_product->is_sold_individually() ) {
              $min_quantity = 1;
              $max_quantity = 1;
            } else {
              $min_quantity = 0;
              $max_quantity = (int) $_product->get_max_purchase_quantity();
            }

            /*
            * WooCommerce возвращает -1, если максимальное количество
            * товара не ограничено.
            */
            $has_max_quantity = $max_quantity > 0;
            $quantity_input_id = wp_unique_id( 'quantity_' );
            ?>

            <div class="quantity ajax-quantity">

              <div class="minus">
                <?php
                if ( $quantity <= 1 ) {
                  echo plnt_icon( 'trash', 'trash-icon' );
                } else {
                  echo plnt_icon( 'minus' );
                }
                ?>
              </div>

              <div class="quantity">
                <label
                  class="screen-reader-text"
                  for="<?php echo esc_attr( $quantity_input_id ); ?>"
                >
                  <?php
                  printf(
                    /* translators: %s: product name */
                    esc_html__( 'Количество товара %s', 'woocommerce' ),
                    esc_html( wp_strip_all_tags( $product_name ) )
                  );
                  ?>
                </label>

                <input
                  type="number"
                  id="<?php echo esc_attr( $quantity_input_id ); ?>"
                  class="input-text qty text"
                  name="<?php echo esc_attr( "cart[{$cart_item_key}][qty]" ); ?>"
                  value="<?php echo esc_attr( $quantity ); ?>"
                  aria-label="<?php esc_attr_e( 'Количество товара', 'woocommerce' ); ?>"
                  min="<?php echo esc_attr( $min_quantity ); ?>"
                  max="<?php echo $has_max_quantity ? esc_attr( $max_quantity ) : ''; ?>"
                  step="1"
                  placeholder=""
                  inputmode="numeric"
                  autocomplete="off"
                >
              </div>

              <div
                class="plus"
                <?php if ( $has_max_quantity && $quantity >= $max_quantity ) : ?>
                  style="opacity: .5; cursor: default;"
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