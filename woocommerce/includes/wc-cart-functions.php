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
add_action( 'woocommerce_cart_is_empty', 'plnt_cart_popular',30);
add_action( 'woocommerce_cart_is_empty', 'plnt_cart_popular_swiper_init',40);

function plnt_cart_popular() {
	get_template_part('template-parts/products-popular'); 
};

function plnt_cart_popular_swiper_init() {
	?>
	<script>
		const swiper_cart_popular = new Swiper('.cart-popular-swiper', {
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
				type: 'progressbar'
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			slidesPerView: 6,
			slidesPerGroup: 1,
			spaceBetween: 15,
			loop: false,
			freeMode: true,
			breakpoints: {
				320: {
					slidesPerView: 2,
					spaceBetween: 10,
					navigation: {
						enabled: false,
					},
					freeMode: true,
				},
				768: {
					slidesPerView: 4,
					spaceBetween: 10,
					navigation: {
						enabled: true,
					},
				},
				1024: {
					slidesPerView: 6,
					spaceBetween: 15,
					navigation: {
						enabled: true,
					},
				}
			}
		});
	</script>
	<?php
}



/*--------------------------------------------------------------
# CART FRAGMENTS 
--------------------------------------------------------------*/

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
	?>		
	<span class="side-cart__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count())?></span>
	<?php
}


function plnt_side_cart_count_fragment( $fragments ) {
	ob_start();
	plnt_side_cart_count();
	$fragments[ 'span.side-cart__count'] = ob_get_clean();
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
# CART FUNCTIONS 
--------------------------------------------------------------*/
// доп функции для корзины

// // изменяем кнопку "в корзину" после добавления товара в корзину


add_filter( 'woocommerce_product_single_add_to_cart_text', 'truemisha_single_product_btn_text' ); // текст для страницы самого товара
 
function truemisha_single_product_btn_text( $text ) {
	if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( get_the_ID() ) ) ) {
		$text = 'В корзине';
	}
 
	return $text;
}

// add_filter( 'woocommerce_product_add_to_cart_text', 'truemisha_product_btn_text', 20, 2 ); //текст для страниц каталога товаров, категорий товаров и т д
 
// function truemisha_product_btn_text( $text, $product ) {
// 	if( 
// 	   $product->is_type( 'simple' )
// 	   && $product->is_purchasable()
// 	   && $product->is_in_stock()
// // 		&& !wp_is_mobile()
// 	   && WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) )
// 	) {
// 		$text = 'Добавлен1';
// 	}
// 	return $text;
// }

//add_action( 'wp_footer', 'ajax_button_text_js_script' );
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
					console.log(this);
				});

				$(document.body).on('added_to_cart', function(event,b,data){
					
					var buttonContent = '<span class="jet-woo-button-content"> <span class="button-label">'+text+'</span> <span class="button-icon"> '+svg+'</span> </span>'
					var buttonText = '<span class="button-label">'+text+'</span>';
					var buttonIcon = '<span class="button-icon"> '+svg+'</span>';
					//console.log ($this)
					//$this.html(buttonText).attr('data-tip',text);
					$this.html(buttonText);

					// <?php  
					// $cart_item_key = WC()->cart->generate_cart_id( $product->get_id() );
					// $remove_cart_url = wc_get_cart_remove_url( $cart_item_key );
					// ?>;

					// var remove_cart_url = <?php echo $remove_cart_url; ?>;
					// console.log (remove_cart_url);
				});
			});
		</script>
	  <?php 
	}
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

