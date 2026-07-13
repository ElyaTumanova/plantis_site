<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
# CART FRAGMENTS 
--------------------------------------------------------------*/
//в стандартные фрагменты корзины добавляем только легкие фрагменты - потому что они вызываются везде
add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_cart_fragments', 25 );

function plnt_woocommerce_cart_fragments( $fragments ) {
	$items = array(
		'div.header-cart__mob' => 'plnt_woocommerce_cart_header_mob',
		'a.header-cart__link'  => 'plnt_woocommerce_cart_header',
    'div.mini-cart' => 'plnt_woocommerce_mini_cart',
    'div.header__nav_cart' => 'plnt_side_cart_count',
    // 'div.cart_totals' => 'plnt_cart_totals',
    // 'div.cart-content-fragment' => 'plnt_woocommerce_cart_content',
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

/* Дополнительные фрагменты страницы корзины */
add_action('wp_ajax_nopriv_plnt_get_cart_page_fragments',	'plnt_get_cart_page_fragments');

add_action(	'wp_ajax_plnt_get_cart_page_fragments',	'plnt_get_cart_page_fragments');

function plnt_get_cart_page_fragments() {
	if ( ! WC()->cart ) {
		wc_load_cart();
	}

	$fragments = array();

	$items = array(
		'div.cart_totals'            => 'plnt_cart_totals',
		'div.cart-content-fragment' => 'plnt_woocommerce_cart_content',
	);

	foreach ( $items as $selector => $callback ) {
		if ( ! is_callable( $callback ) ) {
			continue;
		}

		ob_start();
		call_user_func( $callback );

		$fragments[ $selector ] = ob_get_clean();
	}

	wp_send_json_success(
		array(
			'fragments' => $fragments,
		)
	);
}

function plnt_woocommerce_cart_content() {
	?>
		<?php
		if ( WC()->cart->is_empty() ) {
			
    // wc_get_template( 'cart/cart-empty.php' ); 
    ?>

            <div class="cart-content-fragment">
      <div class="wc-empty-cart-message">
	<div class="cart-empty woocommerce-info">
		<img decoding="async" class="cart__empty-image" src="https://dev.plantis-shop.ru/wp-content/themes/plantis_dev/images/empty_cart.svg" alt="Empty cart"> Ваша корзина пока пуста.	</div>
  </div> 
		<div class="cart__catalog-buttons-wrap">
			<a class="main__plants-button button" href="https://dev.plantis-shop.ru/product-category/komnatnye-rasteniya/">Комнатные растения</a>
			<a class="main__gorshki-button button" href="https://dev.plantis-shop.ru/product-category/gorshki_i_kashpo/">Горшки и кашпо</a>
		</div>

        
    <?php
      
		} else {
			wc_get_template( 'cart/cart-inner.php' );
		}
		?>
	<?php
}