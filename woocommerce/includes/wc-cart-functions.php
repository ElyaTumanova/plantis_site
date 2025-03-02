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
add_action('woocommerce_before_cart', 'woocommerce_cart_totals', 20);

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

// популярные товары в корзине
add_action( 'woocommerce_after_cart', 'plnt_cart_popular', 10);
add_action( 'woocommerce_cart_is_empty', 'plnt_cart_popular', 30);

function plnt_cart_popular() {
	get_template_part('template-parts/products/products-popular'); 
};


// замена товара в корзине для регулярного ассортимента backorder

add_action( 'wp_ajax_replace_backorder_product', 'plnt_replace_backorder_product' );
add_action( 'wp_ajax_nopriv_replace_backorder_product', 'plnt_replace_backorder_product' );
function plnt_replace_backorder_product() {

	if (isset($_POST['backorder_replace_prodId'])){
		global $woocommerce;
		$replaceproductid = $_POST['backorder_replace_prodId']; 
		$replacecartitem = $_POST['backorder_replace_cart_item']; 
		$woocommerce->cart->add_to_cart( $replaceproductid );
		$woocommerce->cart->remove_cart_item( $replacecartitem);
	}    
    die(); // (required)
}

//add_action('woocommerce_before_cart','plnt_cart_backorder_crossell_init', 20);

function plnt_cart_backorder_crossell_init() {	
	?>
	<script>
		jQuery(function($){
			setTimeout(function(){
				cart_backorder_crossell_init ();
			},1000)
		})
	</script>
	<?php
}

// empty cart

add_action( 'woocommerce_cart_is_empty', 'plnt_empty_cart_btns',15 );

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

	$message = '<img class = cart__empty-image src="https://plantis.shop/wp-content/uploads/2024/07/кактус.svg" alt="Empty cart"> Ваша корзина пока пуста.';

	return $message;
}

/*--------------------------------------------------------------
# CART FRAGMENTS 
--------------------------------------------------------------*/

// вывод корзины в хедере и мини корзины
function plnt_woocommerce_cart_header() {
	$cart_icon = carbon_get_theme_option('cart_icon')?>
			<?php if (WC()->cart->get_cart_contents_count() == 0) :?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-btn__wrap header-cart__link">
				<span class="header__count">
			<?php else : ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-btn__wrap header-btn__wrap_active header-cart__link">
				<span class="header__count header__count_active">
			<?php endif;?>
			<?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
			<?php echo $cart_icon ?>
			<span class="header-btn__label">Корзина</span>		
		</a>
	<?php
}

function plnt_woocommerce_cart_header_fragment( $fragments ) {
	ob_start();
	plnt_woocommerce_cart_header();
	$fragments[ 'a.header-cart__link'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_cart_header_fragment', 25 );

// вывод корзины в хедере для мобилки
function plnt_woocommerce_cart_header_mob() {
	$cart_icon = carbon_get_theme_option('cart_icon')?>
			<?php if (WC()->cart->get_cart_contents_count() == 0) :?>
				<div class="header-btn__wrap header-cart__mob">
				<span class="header__count">
			<?php else : ?>
				<div class="header-btn__wrap header-btn__wrap_active header-cart__mob">
				<span class="header__count header__count_active">
			<?php endif;?>
			<?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
			<?php echo $cart_icon ?>
			<span class="header-btn__label">Корзина</span>		
			</div>
	<?php
}

function plnt_woocommerce_cart_header_mob_fragment( $fragments ) {
	ob_start();
	plnt_woocommerce_cart_header_mob();
	$fragments[ 'div.header-cart__mob'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_cart_header_mob_fragment', 25 );

// вывод мини корзины в хедере и side cart
function plnt_woocommerce_mini_cart() {
	?>		
	<div class="mini-cart">
		<?php woocommerce_mini_cart();?>
	</div>
	<?php
}

function plnt_woocommerce_mini_cart_fragment( $fragments ) {
	ob_start();
	plnt_woocommerce_mini_cart();
	$fragments[ 'div.mini-cart'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_mini_cart_fragment', 25 );

// вывод кол-ва товаров в корзине side cart

function plnt_side_cart_count () {
	if (WC()->cart->get_cart_contents_count() == 0) :?>
		<div class="header__count header__nav_cart">
	<?php else : ?>
		<div class="header__count header__nav_cart header__count_active">
	<?php endif;?>
			<span class="side-cart__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
		</div>
	<?php
}


function plnt_side_cart_count_fragment( $fragments ) {
	ob_start();
	plnt_side_cart_count();
	$fragments[ 'div.header__nav_cart'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_side_cart_count_fragment', 25 );

/*--------------------------------------------------------------
# MINI CART 
--------------------------------------------------------------*/

// изменяем подытог мини корзины

remove_action('woocommerce_widget_shopping_cart_total','woocommerce_widget_shopping_cart_subtotal', 10);
add_action('woocommerce_widget_shopping_cart_total','plnt_woocommerce_widget_shopping_cart_subtotal', 10);

function plnt_woocommerce_widget_shopping_cart_subtotal() {
	echo '<span>' . esc_html__( 'Сумма:', 'woocommerce' ) . '</span> ' . WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/*--------------------------------------------------------------
# CART UPDATE 
--------------------------------------------------------------*/
//обновляем мини корзину и количество в корзине с помошью ajax при загрузке страницы, чтобы решить проблему кешрования
function plnt_update_mini_cart() {
	echo wc_get_template( 'cart/mini-cart.php' );
	die();
}
add_filter( 'wp_ajax_nopriv_plnt_update_mini_cart', 'plnt_update_mini_cart' );
add_filter( 'wp_ajax_plnt_update_mini_cart', 'plnt_update_mini_cart' );
//

function plnt_update_header_cart_count() {
	echo wp_kses_data(WC()->cart->get_cart_contents_count());
	die();
}
add_filter( 'wp_ajax_nopriv_plnt_update_header_cart_count', 'plnt_update_header_cart_count' );
add_filter( 'wp_ajax_plnt_update_header_cart_count', 'plnt_update_header_cart_count' );

/*--------------------------------------------------------------
# CART FUNCTIONS 
--------------------------------------------------------------*/

// изменяем кнопку "в корзину" после добавления товара в корзину


add_filter( 'woocommerce_product_single_add_to_cart_text', 'truemisha_single_product_btn_text' ); // текст для страницы самого товара
 
function truemisha_single_product_btn_text( $text ) {
	if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( get_the_ID() ) ) ) {
		$text = 'В корзине';
	}
 
	return $text;
}

/*--------------------------------------------------------------
# HELPERS 
--------------------------------------------------------------*/

 //Функция, возвращающая количество определённого товара в корзине
 function plnt_get_product_quantity_in_cart( $product_id ) {
 
	// по умолчанию количество товара равно 0
	$quantity = 0;
	// проходим циклом через все товары в корзине
	foreach ( WC()->cart->get_cart() as $cart_item ) {
		// можно еще проверяет ID вариаций $cart_item[ 'variation_id' ]
		// если данный товар в цикле – наш товар, то записываем его количество в переменную
		if( $product_id == $cart_item[ 'product_id' ] ){
			$quantity = $cart_item[ 'quantity' ];
			break; // и прерываем цикл
		}
	}
 
	return $quantity;
 
}

// переписана стандартная функция wc_cart_totals_shipping_method_label, которая лежит в woocommerce/plugins/woocommerce/includes/wc-cart-functions.php, чтобы убрать : из названия метода доставки
function plnt_wc_cart_totals_shipping_method_label( $method ) {
	$label     = $method->get_label();
	$has_cost  = 0 < $method->cost;
	$hide_cost = ! $has_cost && in_array( $method->get_method_id(), array( 'free_shipping', 'local_pickup' ), true );

	if ( $has_cost && ! $hide_cost ) {
		if ( WC()->cart->display_prices_including_tax() ) {
			$label .= wc_price( $method->cost + $method->get_shipping_tax() );
			if ( $method->get_shipping_tax() > 0 && ! wc_prices_include_tax() ) {
				$label .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
			}
		} else {
			$label .= wc_price( $method->cost );
			if ( $method->get_shipping_tax() > 0 && wc_prices_include_tax() ) {
				$label .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
			}
		}
	}

	return apply_filters( 'woocommerce_cart_shipping_method_full_label', $label, $method );
}

