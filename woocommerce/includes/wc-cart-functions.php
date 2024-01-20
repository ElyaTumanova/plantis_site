<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// вывод корзины в хедере
function plnt_woocommerce_cart_header() {
	?>
		<?php $cart_icon = carbon_get_theme_option('cart_icon')?>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-btn__wrap header-cart__link">
			<span class="header-cart__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
			<img class="header-btn__icon" src="<?php echo $cart_icon ?>" alt="cart" width="25" height="25">
			<span class="header-btn__label">Корзина</span>		
		</a>
	<?php
}


function plnt_woocommerce_cart_header_fragment( $fragments ) {
 
	ob_start();
	plnt_woocommerce_cart_header();
 
	$fragments[ 'a.header-cart__link' ] = ob_get_clean();
	
	return $fragments;
 
}

add_filter( 'woocommerce_add_to_cart_fragments', 'plnt_woocommerce_cart_header_fragment', 25 );


// FOR DEV

// add_action('woocommerce_before_cart','my_get_cart_amount',40);

function my_get_cart_amount () {
	?>
	<a class="header-cart__link"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></a>
	<?php
};

// доп функции для корзины

// // изменяем кнопку "в корзину" после добавления товара в корзину


add_filter( 'woocommerce_product_single_add_to_cart_text', 'truemisha_single_product_btn_text' ); // текст для страницы самого товара
 
function truemisha_single_product_btn_text( $text ) {
	if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( get_the_ID() ) ) ) {
		$text = 'Добавлен';
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
		?>;
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