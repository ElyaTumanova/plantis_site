<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# CART FRAGMENTS 
--------------------------------------------------------------*/

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_cart_fragments', 25 );

function plnt_woocommerce_cart_fragments( $fragments ) {
	$items = array(
		'div.header-cart__mob' => 'plnt_woocommerce_cart_header_mob',
		'a.header-cart__link'  => 'plnt_woocommerce_cart_header',
    'div.mini-cart' => 'plnt_woocommerce_mini_cart',
    'div.header__nav_cart' => 'plnt_side_cart_count',
    'div.cart-summary' => 'plnt_cart_totals'
	);

	foreach ( $items as $selector => $callback ) {
		if ( ! function_exists( $callback ) ) {
			continue;
		}

		ob_start();
		$callback();
		$fragments[ $selector ] = ob_get_clean();
	}

	return $fragments;
}

// вывод корзины в хедере и мини корзины
function plnt_woocommerce_cart_header() {
	$count     = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$is_active = $count > 0;
	?>

	<div class="header__actions header-cart">
    <a
      href="<?php echo esc_url( wc_get_cart_url() ); ?>"
      class="header-cart__link"
    >
      <span class="header__actions-count<?php echo $is_active ? ' header__actions-count--active' : ''; ?>">
        <?php echo esc_html( $count ); ?>
      </span>
      <?php echo plnt_icon( 'cart' ); ?>
    </a>
  </div>

	<?php
}

// вывод корзины в хедере для мобилки
function plnt_woocommerce_cart_header_mob() {
	$count     = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$is_active = $count > 0;
	?>

	<div class="header__actions header-cart__mob">
		<span class="header__actions-count<?php echo $is_active ? ' header__actions-count--active' : ''; ?>">
			<?php echo esc_html( $count ); ?>
		</span>

		<?php echo plnt_icon( 'cart' ); ?>
	</div>

	<?php
}

// вывод мини корзины в хедере и side cart
function plnt_woocommerce_mini_cart() {
	?>		
	<div class="mini-cart">
		<?php woocommerce_mini_cart();?>
	</div>
	<?php
}

// вывод кол-ва товаров в корзине side cart

function plnt_side_cart_count () {
	if (WC()->cart->get_cart_contents_count() == 0) :?>
		<div class="header__actions-count header__nav_cart">
	<?php else : ?>
		<div class="header__actions-count header__nav_cart header__actions-count--active">
	<?php endif;?>
			<span class="side-cart__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
		</div>
	<?php
}

