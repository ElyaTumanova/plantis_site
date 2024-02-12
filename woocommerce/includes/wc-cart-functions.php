<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// структура корзины

remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
add_action('woocommerce_before_cart', 'woocommerce_cart_totals', 20);


// вывод корзины в хедере и мини корзины
function plnt_woocommerce_cart_header() {
	$cart_icon = carbon_get_theme_option('cart_icon')?>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-btn__wrap header-cart__link">
			<span class="header__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
			<img class="header-btn__icon" src="<?php echo $cart_icon ?>" alt="cart" width="25" height="25">
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

function plnt_woocommerce_mini_cart() {
	?>		
		<div class="mini-cart__wrap">
			<div class="mini-cart">
				<?php woocommerce_mini_cart();?>
			</div>
		</div>
	<?php
}

function plnt_woocommerce_mini_cart_fragment( $fragments ) {
	ob_start();
	plnt_woocommerce_mini_cart();
	$fragments[ 'div.mini-cart__wrap'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_mini_cart_fragment', 25 );

// изменяем подытог мини корзины

remove_action('woocommerce_widget_shopping_cart_total','woocommerce_widget_shopping_cart_subtotal', 10);
add_action('woocommerce_widget_shopping_cart_total','plnt_woocommerce_widget_shopping_cart_subtotal', 10);

function plnt_woocommerce_widget_shopping_cart_subtotal() {
	echo '<span>' . esc_html__( 'Сумма:', 'woocommerce' ) . '</span> ' . WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

// доп функции для корзины

// // изменяем кнопку "в корзину" после добавления товара в корзину


add_filter( 'woocommerce_product_single_add_to_cart_text', 'truemisha_single_product_btn_text' ); // текст для страницы самого товара
 
function truemisha_single_product_btn_text( $text ) {
	if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( get_the_ID() ) ) ) {
		$text = 'В корзине';
	}
 
	return $text;
}
 

add_filter( 'woocommerce_product_add_to_cart_text', 'truemisha_product_btn_text', 20, 2 ); //текст для страниц каталога товаров, категорий товаров и т д
 
function truemisha_product_btn_text( $text, $product ) {
	if( 
	   $product->is_type( 'simple' )
	   && $product->is_purchasable()
	   && $product->is_in_stock()
// 		&& !wp_is_mobile()
	   && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) )
	) {
		$text = 'Добавлен';
	}
	return $text;
}

add_action( 'wp_footer', 'ajax_button_text_js_script' );
function ajax_button_text_js_script() {
	if (!(is_product() )){
		$text = __('Добавлен', 'woocommerce');
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"><path fill="#fff" d="M8.305 20.982.179 12.856l2.031-2.031 6.095 6.094 13.08-13.08 2.03 2.032-15.11 15.111Z"/></svg>' 
		?>
	   <script>
		  jQuery(function($) {
			    var text = '<?php echo $text; ?>',      $this;
				var svg = '<?php echo $svg; ?>',      $this;
				$(document.body).on('click', '.ajax_add_to_cart', function(event){
					$this = $(this); 
				});

				$(document.body).on('added_to_cart', function(event,b,data){
					
					var buttonContent = '<span class="jet-woo-button-content"> <span class="button-label">'+text+'</span> <span class="button-icon"> '+svg+'</span> </span>'
					var buttonText = '<span class="button-label">'+text+'</span>';
					var buttonIcon = '<span class="button-icon"> '+svg+'</span>';
					//console.log ($this)
					//$this.html(buttonText).attr('data-tip',text);
					$this.html(buttonText);
				});
			});
		</script>
	  <?php 
	}
 }

 // FOR DEV

//add_action( 'woocommerce_after_shop_loop_item', 'plnt_add_to_cart_action', 10, 6 );


function plnt_add_to_cart_action() {
	global $woocommerce;
   	$items = $woocommerce->cart->get_cart();
   	$last_added_item_details = end($items)['data'];
    ?> 
    <script>
        console.log(<?php echo $last_added_item_details?>)
    </script>
    <?php

}

add_action( 'woocommerce_ajax_added_to_cart', 'wp_kama_woocommerce_ajax_added_to_cart_action' );

/**
 * Function for `woocommerce_ajax_added_to_cart` action-hook.
 * 
 * @param  $product_id 
 *
 * @return void
 */
function wp_kama_woocommerce_ajax_added_to_cart_action( $product_id ){
	var_dump($product_id);
	$productName = $product_id;
	?> 
    <script>
        window.dataLayer.push(
		{
			"ecommerce": {
				"currencyCode": "RUB",
				"add": {
					"products" : [
						{
							"name":productName,
							// "quantity":,
							// "price":
						}
					]
				}
			}
		}
	)
	console.log(JSON.stringify(window.dataLayer));
    </script>
    <?php

	
}