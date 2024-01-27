<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// структура страницы

// // переместили блок с выбором способа оплаты
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_checkout_shipping', 'woocommerce_checkout_payment', 20);

// // информация о пересадке в горшок
add_action('woocommerce_checkout_shipping', 'plnt_checkout_peresadka_info', 15);

function plnt_checkout_peresadka_info(){
	?>
	<div class="checkout__additional">Мы БЕСПЛАТНО пересадим вашего нового друга в качественный грунт при одновременной покупке растения и горшка (доплата за грунт не требуется).</div>
	<?php
}

// // информация об условиях доставки
add_action( 'woocommerce_checkout_order_review', 'plnt_delivery_condition_info', 30 );

function plnt_delivery_condition_info () {
	echo '<div class="checkout__text">
        После оформления заказа мы свяжемся с вами в <a href="https://plantis.shop/contacts/">рабочее время</a> и согласуем время доставки.
        <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a>
		Важно! Срочную доставку "день в день" можно оформить до 18 часов.</div>';
}

// // до бесплатной доставки осталось
add_action( 'woocommerce_checkout_order_review', 'my_delivery_small_oder_info', 20 );

function my_delivery_small_oder_info () {
	if ( WC()->cart->subtotal < 2000 ) {
		$cart = 2000 - WC()->cart->subtotal;
		echo '<div class="checkout__free-delivery-text">
        Добавьте товаров на <span>'.$cart,'</span> рублей, чтобы стоимость доставки уменьшилась!</div>';
	} else {
		if ( WC()->cart->subtotal < 15000 ) {
			$cart = 15000 - WC()->cart->subtotal;
			echo '<div class="checkout__text">
			До бесплатной доставки внутри МКАД осталось <span>'.$cart,'</span> рублей!</div>';
		}
	}	
}

// доп функции

/* Уведомление об ошибке в оформлении заказа */
add_action( 'woocommerce_after_checkout_validation', 'checkout_validation_unique_error', 9999, 2 );
function checkout_validation_unique_error( $data, $errors ){
    // Check for any validation errors
    if( ! empty( $errors->get_error_codes() ) ) {

        // Remove all validation errors
        foreach( $errors->get_error_codes() as $code ) {
            $errors->remove( $code );
        }

        // Add a unique custom one
        $errors->add( 'validation', 'Упс! Не все обязательные поля были заполнены' );
    }
}