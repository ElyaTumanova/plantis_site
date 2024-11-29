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
add_action( 'woocommerce_checkout_order_review', 'plnt_delivery_condition_info', 40 );

function plnt_delivery_condition_info () {
	echo '<div class="checkout__text">
        После оформления заказа мы свяжемся с вами в <a href="https://plantis.shop/contacts/">рабочее время</a> и согласуем время доставки.
        <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a> <br>
		Важно! Срочную доставку "день в день" можно оформить до 18 часов.</div>';
}

// // добавляемм новые поля для нтервала и даты доставки

//add_action( 'woocommerce_checkout_order_review', 'plnt_add_delivery_date_field', 50 );

function plnt_add_delivery_date_field() {
    echo "<div class='delivery_wrap'>";
	// выводим поле функцией woocommerce_form_field()
	woocommerce_form_field( 
		'datepicker', 
		array(
			'type'          => 'text', // text, textarea, select, radio, checkbox, password
			'required'	=> false, // по сути только добавляет значок "*" и всё
			'class'         => array( 'input-text' ), // массив классов поля
			'label'         => 'Дата доставки (самовывоза)',
			'label_class'   => '', // класс лейбла
		),
	);
}

add_action( 'woocommerce_checkout_order_review', 'plnt_add_delivery_interval_field', 55 );

function plnt_add_delivery_interval_field() {
	// выводим поле функцией woocommerce_form_field()
	woocommerce_form_field( 
		'additional_delivery_interval', 
		array(
			'type'          => 'radio', // text, textarea, select, radio, checkbox, password
			'required'	=> false, // по сути только добавляет значок "*" и всё
			'class'         => array( 'additional_delivery_interval' ), // массив классов поля
			'label'         => 'Интервал',
			'label_class'   => '', // класс лейбла
            'options'	=> array( // options for  or 
				'11:00 - 21:00'		=> '11:00 - 21:00', // 'значение' => 'заголовок'
				'11:00 - 16:00'	=> '11:00 - 16:00', // 
				'14:00 - 18:00'	=> '14:00 - 18:00',
				'18:00 - 21:00'	=> '18:00 - 21:00',
			)
		),
	);
    echo "</div>";
}


// FOR DEV

add_action( 'woocommerce_checkout_order_review', 'plnt_add_delivery_dates', 50 );

function plnt_add_delivery_dates() {

    $today = date("d.m.y"); 
    $tomorrow = date('d.m.y', time() + 86400); 
    $day3 = date('d.m.y', time() + 86400*2); 
    $day4 = date('d.m.y', time() + 86400*3); 
    $day5 = date('d.m.y', time() + 86400*4); 
    $day6 = date('d.m.y', time() + 86400*5); 
    $day7 = date('d.m.y', time() + 86400*6); 
    $day8 = date('d.m.y', time() + 86400*7); 
    $day9 = date('d.m.y', time() + 86400*8); 
    $day10 = date('d.m.y', time() + 86400*9); 
    $day11 = date('d.m.y', time() + 86400*10); 
    $day12 = date('d.m.y', time() + 86400*11); 
    $day13 = date('d.m.y', time() + 86400*12); 
    $day14 = date('d.m.y', time() + 86400*13); 

    echo "<div class='delivery_wrap'>";
	// выводим поле функцией woocommerce_form_field()
	woocommerce_form_field( 
		'delivery_dates', 
		array(
			'type'          => 'radio', // text, textarea, select, radio, checkbox, password
			'required'	=> false, // по сути только добавляет значок "*" и всё
			'class'         => array( 'delivery_dates' ), // массив классов поля
			'label'         => 'Дата доставки (самовывоза)',
			'label_class'   => '', // класс лейбла
            'options'	=> array( // options for  or 
				'today'		=> $today, // 'значение' => 'заголовок'
				'tomorrow' 	=> $tomorrow,
				'day3'=> $day3,
				'day4'=> $day4,
				'day5'=> $day5,
				'day6'=> $day6,
				'day7'=> $day7,
				'day8'=> $day8,
				'day9'=> $day9,
				'day10'=> $day10,
				'day11'=> $day11,
				'day12'=> $day12,
				'day13'=> $day13,
				'day14'=> $day14,
			)
		),
	);
}

// // сохряняем новое поле в заказе

add_action( 'woocommerce_checkout_update_order_meta', 'plnt_save_delivery_fields', 25 );
 
function plnt_save_delivery_fields( $order_id ){
 
	if( ! empty( $_POST[ 'datepicker' ] ) ) {
		update_post_meta( $order_id, 'datepicker', sanitize_text_field( $_POST[ 'datepicker' ] ) );
	}

    if( ! empty( $_POST[ 'additional_delivery_interval' ] ) ) {
		update_post_meta( $order_id, 'additional_delivery_interval', $_POST[ 'additional_delivery_interval' ] );
	}
}

// // добавляем поле дата доставки в админку

add_action( 'woocommerce_admin_order_data_after_billing_address', 'plnt_print_editable_delivery_field_value', 25 );
 
function plnt_print_editable_delivery_field_value( $order ){
 
	$method = get_post_meta( $order->get_id(), 'datepicker', true );
 
	echo '<div class="address">
		<p' . ( ! $method ? ' class="none_set"' : '' ) . '>
			<strong>Дата доставки (самовывоза)</strong>
			' . ( $method ? $method : 'Не указан.' ) . '
		</p>
	</div>
	<div class="edit_address">';
	woocommerce_wp_text_input( array(
        'id' => 'datepicker',
        'label' => 'Дата доставки (самовывоза)',
    ) );
	echo '</div>';
}

// // добавляем поле интервал доставки в админку
add_action( 'woocommerce_admin_order_data_after_billing_address', 'plnt_print_editable_delivery_interval_field_value', 25 );
 
function plnt_print_editable_delivery_interval_field_value( $order ){
 
	$method = get_post_meta( $order->get_id(), 'additional_delivery_interval', true );
 
	echo '<div class="address">
		<p' . ( ! $method ? ' class="none_set"' : '' ) . '>
			<strong>Интервал доставки</strong>
			' . ( $method ? $method : 'Не указан.' ) . '
		</p>
	</div>
	<div class="edit_address">';
	woocommerce_wp_select( array(
		'id' => 'additional_delivery_interval',
		'label' => 'Интервал доставки',
		'wrapper_class' => 'form-field-wide',
		'value' => $method,
		'options' => array(
			'11:00 - 21:00'		=> '11:00 - 21:00', // 'значение' => 'заголовок'
            '11:00 - 16:00'	=> '11:00 - 16:00', 
            '14:00 - 18:00'	=> '14:00 - 18:00',
            '18:00 - 21:00'	=> '18:00 - 21:00',
		)
	) );
	echo '</div>';
}
 
// и сохраняем поля доставки в заказе после редактирования
add_action( 'woocommerce_process_shop_order_meta', 'plnt_save_delivery_field_value' );
 
function plnt_save_delivery_field_value( $order_id ){
	update_post_meta( $order_id, 'datepicker', wc_clean( $_POST[ 'datepicker' ] ) );
	update_post_meta( $order_id, 'additional_delivery_interval', wc_clean( $_POST[ 'additional_delivery_interval' ] ) );
}


// // добавляем новые поля в письма

add_filter( 'woocommerce_get_order_item_totals', 'plnt_delivery_fields_in_email', 25, 2 );
 
function plnt_delivery_fields_in_email( $rows, $order ) {
 
 	// удалите это условие, если хотите добавить значение поля и на страницу "Заказ принят"
	// if( is_order_received_page() ) {
	// 	return $rows;
	// }
 
	$rows[ 'datepicker' ] = array(
		'label' => 'Дата доставки (самовывоза)',
		'value' => get_post_meta( $order->get_id(), 'datepicker', true )
	);

	$rows[ 'additional_delivery_interval' ] = array(
		'label' => 'Интервал доставки',
		'value' => get_post_meta( $order->get_id(), 'additional_delivery_interval', true )
	);
 
	return $rows;
 
}


// // до бесплатной доставки осталось
add_action( 'woocommerce_checkout_order_review', 'my_delivery_small_oder_info', 20 );

function my_delivery_small_oder_info () {
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    if ($min_free_delivery) {
        if ( WC()->cart->subtotal < str_replace(" ","",$min_small_delivery)) {
            $cart = str_replace(" ","",$min_small_delivery) - WC()->cart->subtotal;
            echo '<div class="checkout__free-delivery-text">
            Добавьте товаров на <span>'.$cart,'</span> рублей, чтобы стоимость доставки уменьшилась!</div>';
        } else {
            if(WC()->cart->subtotal > (str_replace(" ","",$min_free_delivery)-10000)) {
                if ( WC()->cart->subtotal < str_replace(" ","",$min_free_delivery)) {
                    $cart = str_replace(" ","",$min_free_delivery) - WC()->cart->subtotal;
                    echo '<div class="checkout__text">
                    До бесплатной доставки внутри МКАД осталось <span>'.$cart,'</span> рублей!</div>';
                }
            }
        }	
    }
}

// выбрана крупногабартная доставка
add_action( 'woocommerce_checkout_order_review', 'my_delivery_large_products_oder_info', 25 );

function my_delivery_large_products_oder_info () {
    $class_slug = 'delivery_large';

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if( $cart_item['data']->get_shipping_class() == $class_slug ){
            echo '<div class="checkout__text">
			Вы выбрали крупногабаритный товар. Стоимость доставки увеличена. <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a></div>';
            break; // Stop the loop
        } 	
    }
}

/* скрываем Уже покупали? и форму логирования на странице оформления заказа*/
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

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

/* ПОЛЯ ФОРМЫ ОФОРМЛЕНИЯ ЗАКАЗА*/


//СПОСОБЫ ДОСТАВКИ
// СТОИМОСТЬ ДОСТАВКИ ПО ДАТЕ

add_action( 'wp_ajax_get_urgent_shipping', 'plnt_get_urgent_shipping' );
add_action( 'wp_ajax_nopriv_get_urgent_shipping', 'plnt_get_urgent_shipping' );
function plnt_get_urgent_shipping() {
    if ( $_POST['isUrgent'] === '1'){
        WC()->session->set('isUrgent', '1' );
    } else {
        WC()->session->set('isUrgent', '0' );
    }
    die(); // (required)
}

add_action( 'woocommerce_checkout_update_order_review', 'plnt_refresh_shipping_methods_for_urgent', 10, 1 );
function plnt_refresh_shipping_methods_for_urgent( $post_data ){
    $bool = true;

    if ( WC()->session->get('isUrgent' ) === '1' )
        $bool = false;

    // Mandatory to make it work with shipping methods
    foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
        WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
    }
    WC()->cart->calculate_shipping();
}

add_filter( 'woocommerce_package_rates', 'plnt_shipping_rates_for_urgent', 100, 2 );
function plnt_shipping_rates_for_urgent( $rates, $package ) {

    //переменные
    // TO BE DELETED
    // global $local_pickup;
        
    // global $delivery_inMKAD;
    // global $delivery_outMKAD;
    // global $delivery_inMKAD_small;
    // global $delivery_outMKAD_small;


    // global $urgent_delivery_inMKAD; 
    // global $urgent_delivery_outMKAD; 
    // global $urgent_delivery_inMKAD_small; 
    // global $urgent_delivery_outMKAD_small;

    // global $delivery_free;

    $urgent_delivery_markup = carbon_get_theme_option('urgent_delivery_markup');

	if (WC()->session->get('isUrgent' ) === '1') {
		foreach( $rates as $rate) {
            if ( 'local_pickup' !== $rate->method_id ) {
                $rate->cost = $rate->cost + $urgent_delivery_markup;
            }	
		}
	 }
    return $rates;

}

// уведомление о срочной доставке

add_action( 'woocommerce_checkout_order_review', 'plnt_urgent_delivery_info', 45 );

function plnt_urgent_delivery_info(){
    echo '<div class="checkout__text checkout__text_alarm checkout__urgent-text"></div>'; 
}

/*СТОИМОСТЬ ДОСТАВКИ ПО ВЕСУ*/

add_filter( 'woocommerce_package_rates', 'truemisha_shipping_by_weight', 30, 2 );
 
function truemisha_shipping_by_weight( $rates, $package ) {

    //переменные
    // TO BE DELETED
    // global $local_pickup;
        
    // global $delivery_inMKAD;
    // global $delivery_outMKAD;
    // global $delivery_inMKAD_small;
    // global $delivery_outMKAD_small;


    // global $urgent_delivery_inMKAD; 
    // global $urgent_delivery_outMKAD; 
    // global $urgent_delivery_inMKAD_small; 
    // global $urgent_delivery_outMKAD_small;

    // global $delivery_free;

    $large_delivery_markup = carbon_get_theme_option('large_delivery_markup');

    if ($large_delivery_markup) {
        // вес товаров в корзине
        $cart_weight = WC()->cart->cart_contents_weight;
    
        if ($cart_weight >= 11) {
           foreach( $rates as $rate) {
            
            if ( 'local_pickup' !== $rate->method_id ) {
                $rate->cost = $rate->cost + $large_delivery_markup;
            }
           }
        }
    }

	return $rates;
}

add_action('plnt_large_delivery_notice', 'plnt_large_delivery_notice');

function plnt_large_delivery_notice() {
    $large_delivery_markup = carbon_get_theme_option('large_delivery_markup');

    if ($large_delivery_markup) {
        // вес товаров в корзине
        $cart_weight = WC()->cart->cart_contents_weight;
    
        if ($cart_weight >= 11) {
           echo '<div class=large_delivery_notice>
           <img class=large_delivery_img src="https://plantis.shop/wp-content/uploads/2024/08/car.svg" alt="car">
           <p>Для заказа предусмотрена крупногабаритная доставка!</p></div>';
        }
    }
}

/* скрываем лишние способы доставки если доступна доставка бесплатная*/

add_filter( 'woocommerce_package_rates', 'new_truemisha_remove_shipping_method', 20, 2 );
 
function new_truemisha_remove_shipping_method( $rates, $package ) {

    //переменные
    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;
    global $delivery_inMKAD_small;
    global $delivery_outMKAD_small;


    global $urgent_delivery_inMKAD; 
    global $urgent_delivery_outMKAD; 
    global $urgent_delivery_inMKAD_small; 
    global $urgent_delivery_outMKAD_small;

    global $delivery_free;
 
	// удаляем способ доставки, если доступна бесплатная
	if ( isset( $rates[ $delivery_free ] ) ) { 
	    unset( $rates[ $delivery_inMKAD ] );
// 		unset( $rates[ $delivery_outMKAD ] );
		unset( $rates[ $urgent_delivery_inMKAD ] );
// 		unset( $rates[ $urgent_delivery_outMKAD ] );
		unset( $rates[ $delivery_inMKAD_small ] );
		unset( $rates[ $delivery_outMKAD_small ] );
		unset( $rates[ $urgent_delivery_inMKAD_small ] );
		unset( $rates[ $urgent_delivery_outMKAD_small ] );
	}
 
	return $rates;
}


/* стоимость доставки в зависимости от суммы заказа*/

//убираем способ онлайн-оплаты, если маленькая сумма заказа
add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_small_order' );

function plnt_disable_payment_small_order( $available_gateways ) {
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    global $delivery_courier;

    if( is_admin() ) {
		return $available_gateways;
	}

    if( is_wc_endpoint_url( 'order-pay' ) ) {
		return $available_gateways;
	}

    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

    // стоимость товаров в корзине
    if (WC()->cart->subtotal < $min_small_delivery && $delivery_courier == $chosen_methods[0]) {
        unset( $available_gateways['bacs'] ); //to be updated - chenge to tinkoff
    }

    return $available_gateways;
}
	
add_filter( 'woocommerce_package_rates', 'new_truemisha_remove_shipping_on_price', 25, 2 );
 
function new_truemisha_remove_shipping_on_price( $rates, $package ) {

    //переменные
    // TO BE DELETED
    global $local_pickup;
        
    global $delivery_inMKAD;
    global $delivery_outMKAD;
    // global $delivery_inMKAD_small;
    // global $delivery_outMKAD_small;


    // global $urgent_delivery_inMKAD; 
    // global $urgent_delivery_outMKAD; 
    // global $urgent_delivery_inMKAD_small; 
    // global $urgent_delivery_outMKAD_small;

    // global $delivery_free;
    global $delivery_courier;

    $small_delivery_markup = carbon_get_theme_option('small_delivery_markup');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');

	// если сумма всех товаров в корзине меньше min_small_delivery, увеличиваем стоимость доставки
    if ($small_delivery_markup) { //если наценка не задана, то будет запущен второй вариант алгоритма с отключением способов доставки
        // отключаем опцию доставики по тарифам курьерской службы
        unset( $rates[ $delivery_courier ] );
        // стоимость товаров в корзине
        if (WC()->cart->subtotal < $min_small_delivery) {
           foreach( $rates as $rate) {
            
            if ( 'local_pickup' !== $rate->method_id ) {
                $rate->cost = $rate->cost + $small_delivery_markup;
            }
           }
        }
    } else {
        // если сумма всех товаров в корзине меньше min_small_delivery, отключаем способ доставки
        
            if ( WC()->cart->subtotal < $min_small_delivery ) {
                unset( $rates[ $delivery_inMKAD ] );
                unset( $rates[ $delivery_outMKAD ] );
                isset($rates[ $delivery_courier ]);
                // unset( $rates[ $urgent_delivery_inMKAD ] );
                // unset( $rates[ $urgent_delivery_outMKAD ] );			
            } 
            else {
            //     unset( $rates[ $delivery_inMKAD_small ] );
            //     unset( $rates[ $delivery_outMKAD_small ] );
            //     unset( $rates[ $urgent_delivery_inMKAD_small ] );
            //     unset( $rates[ $urgent_delivery_outMKAD_small ] );
                unset( $rates[ $delivery_courier ] );
            }
    }
 
	return $rates;
}

//уведомление о маленькой сумме заказа

add_action( 'woocommerce_checkout_order_review', 'min_amount_delivery_info', 30 );

function min_amount_delivery_info(){
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $small_delivery_markup = carbon_get_theme_option('small_delivery_markup');

    if (WC()->cart->subtotal < $min_small_delivery) {
        if ($small_delivery_markup) {
            echo '<div class="checkout__text checkout__text_alarm">
            При заказе на сумму менее <span>'.$min_small_delivery,'</span> стоимость доставки увеличена. 
            <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a></div>';
        } else {
            echo '<div class="checkout__text checkout__text_alarm">
            При заказе на сумму менее <span>'.$min_small_delivery,'</span> доставка осуществляется по тарифам курьерской службы. 
            Наш менеджер свяжется с Вами после оформления заказа и произведет расчет стоимости доставки.</div>';
        }  
    }
}

// делим поле billing_address_2 на несколько полей//

add_filter( 'woocommerce_form_field_text', 'true_fields', 25, 4 );
 
function true_fields( $field, $key, $args, $value ) {
 
	if( 'billing_address_2' === $key ) {
 
		$field = '<p class="form-row address-field additional-address-field form-row-wide" data-priority="60">
			<span class="woocommerce-input-wrapper true-wrapper woocommerce-address-wrapper">
				<input type="text" name="billing_address_2" id="billing_address_2" placeholder="Квартира" value="">
				<input type="text" name="billing_address_3" id="billing_address_3" placeholder="Подъезд" value="">
				<input type="text" name="billing_address_4" id="billing_address_4" placeholder="Этаж" value="">
				<input type="text" name="billing_address_5" id="billing_address_5" placeholder="Дополнительная информация" value="">
			</span>
		</p>';
 
	}
 
	return $field;
 
}

add_filter( 'woocommerce_checkout_posted_data', 'true_process_fields' );
 
function true_process_fields( $data ) {
 
	// в поле billing_address_2 мы и будем записывать новые значения полей
	$data[ 'billing_address_2' ] = '';
	$fields = array();
 
	// получаем данные из глобального $_POST, сначала парадную (подъезд)
	if( ! empty( $_POST[ 'billing_address_2' ] ) ) {
		$fields[] = 'квартира ' . $_POST[ 'billing_address_2' ];
	}

	if( ! empty( $_POST[ 'billing_address_3' ] ) ) {
		$fields[] = 'подъезд ' . $_POST[ 'billing_address_3' ];
	}
	// затем этаж
	if( ! empty( $_POST[ 'billing_address_4' ] ) ) {
		$fields[] = 'этаж ' . $_POST[ 'billing_address_4' ];
	}

	// затем доп поля
	if( ! empty( $_POST[ 'billing_address_5' ] ) ) {
		$fields[] = ' ' . $_POST[ 'billing_address_5' ];
	}

	// объединяем все заполненные данные запятой
	$data[ 'billing_address_2' ] = join( ', ', $fields );
 
	// возвращаем результат
	return $data;
 
}



/*минимальная сумма заказа для кашпо Teez*/
add_action( 'woocommerce_checkout_process', 'min_amount_for_category' );
// add_action( 'woocommerce_before_checkout_form', 'min_amount_for_category' );

function min_amount_for_category(){
    global $treez_cat_id;
    global $plants_treez_cat_id;
    $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
	$qty = 0; // обязательно сначала ставим 0
 	$cat_amount = 0;
	$products_min = false;
	foreach ( WC()->cart->get_cart() as $cart_item ) {
			$_product = $cart_item['data'];
            $_product_id = $_product->id;
            $parentCat = check_category ($_product);
            // $terms = get_the_terms( $_product_id, 'product_cat' );
			// foreach ($terms as $term) {
            //         $_categoryid = $term->term_id;
            //     }
            // your products categories
            if ( $parentCat === $treez_cat_id || $parentCat === $plants_treez_cat_id ) {
                $products_min = true;
                $qty = $cart_item[ 'quantity' ];
                $price = $cart_item['data']->get_price();
                $cat_amount = $cat_amount + $price*$qty;
            }	
	}
 
    if( ( is_cart() || is_checkout() ) && $cat_amount < $min_treez_delivery && $products_min) {
        wc_print_notice(
            sprintf( 'Минимальная сумма заказа для кашпо и искусственных растений Treez %s (без учета стоимости других товаров).'  ,
                wc_price( $min_treez_delivery ),
                wc_price( WC()->cart->total )
            ), 'error'
        );
    } 

    if ( $cat_amount < $min_treez_delivery && $products_min) {

        wc_add_notice( 
            sprintf( 
                'Минимальная сумма заказа для кашпо и искусственных растений Treez %s (без учета стоимости других товаров).',
                wc_price( $min_treez_delivery ),
                wc_price( WC()->cart->subtotal )
            ),
            'error'
        );

    }
}

add_action( 'woocommerce_checkout_order_review', 'min_amount_for_treez_info', 10 );

function min_amount_for_treez_info(){
    global $treez_cat_id;
    global $plants_treez_cat_id;
    $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
	$qty = 0; // обязательно сначала ставим 0
 	$cat_amount = 0;
	$products_min = false;
	foreach ( WC()->cart->get_cart() as $cart_item ) {
			$_product = $cart_item['data'];
            $_product_id = $_product->id;
            $parentCat = check_category ($_product);

            if ( $parentCat === $treez_cat_id || $parentCat === $plants_treez_cat_id ) {
                $products_min = true;
                $qty = $cart_item[ 'quantity' ];
                $price = $cart_item['data']->get_price();
                $cat_amount = $cat_amount + $price*$qty;
            }	
	}

    if( $cat_amount < $min_treez_delivery && $products_min) {
        echo '<div class="checkout__text checkout__text_alarm">
        Минимальная сумма заказа для кашпо и искусственных растений Treez <span>'.$min_treez_delivery,'</span> рублей (без учета стоимости других товаров).</div>';
    }   
    if( $products_min) {
        echo '<div class="checkout__text checkout__text_alarm">
        Оплатить заказ с кашпо и искусственными растениями Treez можно будет после подтверждения их наличия. Наш менеджер свяжется с Вами после оформления заказа.</div>';
    }   
}

add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_treez' );

function plnt_disable_payment_treez( $available_gateways ) {
    global $treez_cat_id;
    global $plants_treez_cat_id;
    // $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
	// $qty = 0; // обязательно сначала ставим 0
 	// $cat_amount = 0;
	$products_min = false;
    if (is_admin()) {
        return $available_gateways;
    } else {
        foreach ( WC()->cart->get_cart() as $cart_item ) {
                $_product = $cart_item['data'];
                $_product_id = $_product->id;
                $parentCat = check_category ($_product);
    
                if ( $parentCat === $treez_cat_id || $parentCat === $plants_treez_cat_id  ) {
                    $products_min = true;
                    // $qty = $cart_item[ 'quantity' ];
                    // $price = $cart_item['data']->get_price();
                    // $cat_amount = $cat_amount + $price*$qty;
                }	
        }
    
        if( $products_min) {
            unset( $available_gateways['tinkoff'] );
        }
        return $available_gateways;
    }
}

// выводим в форме оформления заказа информацию, о товарах, которые закончились
add_action ('woocommerce_cart_has_errors', 'plnt_check_cart_item_stock');

function plnt_check_cart_item_stock() {

    $isOutOfStock = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];

        if ( $product->get_stock_status() ==='outofstock') {
            $isOutOfStock = true;
        } 
    }
    
    if ($isOutOfStock) {
        echo '<div class="cart-error-list"> Товары, недоступные для заказа:';
    }

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];

        if ( $product->get_stock_status() ==='outofstock') {
            echo '<p class="cart-error-list__name">';
            print_r( $product->get_name() );
            echo '</p>';
        } 
    }
    echo '</div>';
}


/*--------------------------------------------------------------
# Thankyou page
--------------------------------------------------------------*/

// уведомление Спасибо за заказ

add_filter( 'woocommerce_thankyou_order_received_text', 'plnt_custom_ty_msg' );

    function plnt_custom_ty_msg ( $thank_you_msg ) {
        $emoji = '<img draggable="false" role="img" class="emoji" alt="😉" height="20px" width="20px" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f609.svg">';
        $thank_you_msg =  'Спасибо за ваш заказ! Наши менеджеры пляшут от радости! Как закончат танцевать, сразу вам перезвонят ' . $emoji ;

    return $thank_you_msg;
}