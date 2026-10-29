<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// структура корзины

// // выводим в корзине информацию, о товарах, которые закончились и убиран стандартные уведолмления
// remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
// add_action ('woocommerce_before_cart', 'plnt_check_cart_item_stock',10);

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
add_action('woocommerce_cart_collaterals', 'plnt_cart_totals', 10);
plnt_add_wrapper('cart__wrap section','woocommerce_before_cart', 20, 'woocommerce_after_cart', 10); //cart__wrap
add_action( 'woocommerce_before_cart', function() {
	plnt_get_checkout_steps( 'cart' );
}, 8 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

// популярные товары в корзине
add_action( 'woocommerce_after_cart', 'plnt_cart_popular', 10);

//empty cart
plnt_add_wrapper('cart__wrap section','woocommerce_cart_is_empty', 5, 'woocommerce_cart_is_empty', 80);
plnt_add_wrapper('cart-content-fragment','woocommerce_cart_is_empty', 7, 'woocommerce_cart_is_empty', 70);
plnt_add_wrapper('cart-collaterals','woocommerce_cart_is_empty', 71, 'woocommerce_cart_is_empty', 74);
plnt_add_wrapper('cart_totals','woocommerce_cart_is_empty', 72, 'woocommerce_cart_is_empty', 73);
add_action( 'woocommerce_cart_is_empty', 'plnt_empty_cart_btns',15 );
add_action( 'woocommerce_cart_is_empty', 'plnt_cart_popular', 90);


/* Checkout steps */
  function plnt_get_checkout_steps( $mode ) {
    $cart_url     = wc_get_cart_url();
    $checkout_url = wc_get_checkout_url();
    ?>
    <div class="checkout-steps checkout-steps--<?php echo esc_attr( $mode ); ?>">

      <?php if ( 'cart' === $mode ) : ?>
        <div class="checkout-steps__item checkout-steps__item--cart">
          <span class="checkout-steps__num">1</span>
          <span class="checkout-steps__text">Корзина</span>
        </div>
      <?php else : ?>
        <a
          href="<?php echo esc_url( $cart_url ); ?>"
          class="checkout-steps__item checkout-steps__item--cart"
        >
          <span class="checkout-steps__num"><?php echo plnt_icon( 'check' );?></span>
          <span class="checkout-steps__text">Корзина</span>
        </a>
      <?php endif; ?>

      <span class="checkout-steps__separator">
        <?php echo plnt_icon( 'chevron-right' ); ?>
      </span>

      <?php if ( 'checkout' === $mode ) : ?>
        <div class="checkout-steps__item checkout-steps__item--checkout">
          <span class="checkout-steps__num">2</span>
          <span class="checkout-steps__text">Оформление заказа</span>
        </div>
      <?php else : ?>
        <a
          href="<?php echo esc_url( $checkout_url ); ?>"
          class="checkout-steps__item checkout-steps__item--checkout"
        >
          <span class="checkout-steps__num">2</span>
          <span class="checkout-steps__text">Оформление заказа</span>
        </a>
      <?php endif; ?>

    </div>
    <?php
  }
/*  
*/
/* Cart totals */
  function plnt_cart_totals() {

    $cart_summary = plnt_get_cart_summary();

    if ( $cart_summary['count'] > 0 ) :
    ?>
      <div class="cart_totals">
        <div class="cart_totals__wrap">
          <div class="cart_totals__top cart_totals__row">
            <span class="cart_totals__title">
              <?php esc_html_e( 'Итого', 'plantis' ); ?>
            </span>
            <span class="cart_totals__total">
              <?php echo wp_kses_post( wc_price( $cart_summary['total'] ) ); ?>
            </span>
          </div>
          <div class="cart_totals__list">
            <div class="cart_totals__row">
              <span>
                <?php esc_html_e( 'Кол-во товаров', 'plantis' ); ?>
              </span>
              <span>
                <?php echo esc_html( $cart_summary['count'] ); ?>
              </span>
            </div>
            <div class="cart_totals__row">
              <span>
                <?php esc_html_e( 'Общая стоимость', 'plantis' ); ?>
              </span>
              <span>
                <?php echo wp_kses_post( wc_price( $cart_summary['regular_total'] ) ); ?>
              </span>
            </div>
            <?php if ( $cart_summary['discount'] > 0 ) : ?>
              <div class="cart_totals__row cart_totals__row--discount">
                <span>
                  <?php esc_html_e( 'Скидка', 'plantis' ); ?>
                </span>
                <span class="cart_totals__discount">
                  -<?php echo wp_kses_post( wc_price( $cart_summary['discount'] ) ); ?>
                </span>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <span data-js-button-visible></span>
        <a
          href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
          class="cart_totals__button button button--green"
        >
        <?php esc_html_e( 'Перейти к оформлению', 'plantis' ); ?>
        <span class="cart_totals__button-total"><?php echo wp_kses_post( wc_price( $cart_summary['total'] ) ); ?></span>
        </a>
      </div>
    <?php endif;
    
  }
/*  
*/
/* Cart item subtotal*/

function plnt_get_product_regular_subtotal( $product, $quantity ) {
  if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
      return '';
  }

  // Если товар не на распродаже — ничего не выводим
  if ( ! $product->is_on_sale() ) {
      return '';
  }

  $regular_price = (float) $product->get_regular_price();
  $sale_price    = (float) $product->get_sale_price();

  // Старый подытог
  $regular_subtotal = wc_price( $regular_price * $quantity );

  // Процент скидки
  $percentage = 0;

  if ( $regular_price > 0 ) {
      $percentage = round(
          ( ( $regular_price - $sale_price ) / $regular_price ) * 100
      );
  }

  $html = '<del>' . $regular_subtotal . '</del>';

  if ( $percentage > 0 ) {
      $html .= '<div class="sale_badge">- ' . $percentage . '%</div>';
  }

  return $html;
}
/*  
*/

function plnt_cart_popular() {
	get_template_part('template-parts/products/products-popular'); 
};



// замена товара в корзине для регулярного ассортимента backorder

add_action('woocommerce_after_shop_loop_item','plnt_backorder_replace_button',20);

function plnt_backorder_replace_button() {
    global $plnt_cart_product_slider_mode;
    global $plnt_replace_cart_item_key;

    if (
        $plnt_cart_product_slider_mode !== 'backorder'
        || ! $plnt_replace_cart_item_key
    ) {
        return;
    }

    ?>
    <button
        class="backorder_replace_btn button button--green icon icon--swap icon--pre"
        type = "button"
        data-product_id="<?php echo esc_attr( get_the_ID() ); ?>"
        data-cart_item="<?php echo esc_attr( $plnt_replace_cart_item_key ); ?>"
    >
        Заменить
    </button>
    <?php
}

// empty cart


function plnt_empty_cart_btns() {
	global $plants_cat_id;
	global $gorshki_cat_id;
	?> 
		<div class="cart__catalog-buttons-wrap">
			<a class="main__plants-button button" href="<?php echo get_term_link( $plants_cat_id, 'product_cat' );?>">Комнатные растения</a>
			<a class="main__gorshki-button button" href="<?php echo get_term_link( $gorshki_cat_id, 'product_cat' );?>">Горшки и кашпо</a>
		</div>
	<?php
};


add_filter( 'wc_empty_cart_message', 'plnt_empty_cart_message_filter' );

function plnt_empty_cart_message_filter( $message ){
    $dir = get_template_directory_uri() . '/images/empty_cart.svg';
	$message = '<img class = cart__empty-image src="'. $dir.'" alt="Empty cart"> Ваша корзина пока пуста.';

	return $message;
}



/*--------------------------------------------------------------
# MINI CART 
--------------------------------------------------------------*/

// изменяем подытог мини корзины

remove_action('woocommerce_widget_shopping_cart_total','woocommerce_widget_shopping_cart_subtotal', 10);
add_action('woocommerce_widget_shopping_cart_total','plnt_woocommerce_widget_shopping_cart_subtotal', 10);

function plnt_woocommerce_widget_shopping_cart_subtotal() {
	echo '<span>' . esc_html__( 'Сумма:', 'woocommerce' ) . '</span> ' . WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}


