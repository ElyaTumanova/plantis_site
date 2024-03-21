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
        <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a> <br>
		Важно! Срочную доставку "день в день" можно оформить до 18 часов.</div>';
}

// // до бесплатной доставки осталось
add_action( 'woocommerce_checkout_order_review', 'my_delivery_small_oder_info', 20 );

function my_delivery_small_oder_info () {
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
	if ( WC()->cart->subtotal < str_replace(" ","",$min_small_delivery)) {
		$cart = str_replace(" ","",$min_small_delivery) - WC()->cart->subtotal;
		echo '<div class="checkout__free-delivery-text">
        Добавьте товаров на <span>'.$cart,'</span> рублей, чтобы стоимость доставки уменьшилась!</div>';
	} else {
		if ( WC()->cart->subtotal < str_replace(" ","",$min_free_delivery)) {
			$cart = str_replace(" ","",$min_free_delivery) - WC()->cart->subtotal;
			echo '<div class="checkout__text">
			До бесплатной доставки внутри МКАД осталось <span>'.$cart,'</span> рублей!</div>';
		}
	}	
}

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



// перевод текстов

function plnt_change_text_checkout_1( $translated_text ) {
    if ( $translated_text == 'Select a date to view time slots' ) {
      $translated_text = 'Сначала выберите дату доставки';
    }
    return $translated_text;
  }
  add_filter( 'gettext', 'plnt_change_text_checkout_1', 20 );


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

// Conditional Show hide checkout fields based on chosen shipping methods*/

add_action( 'wp_footer', 'new_custom_checkout_field_script' );
function new_custom_checkout_field_script() {
	
	if( !is_page( 'checkout' ) ) {
		return;
	}

    // HERE your shipping methods rate IDs
    global $local_pickup;
	global $urgent_delivery_inMKAD;
	global $urgent_delivery_outMKAD;
	global $urgent_delivery_inMKAD_small;
	global $urgent_delivery_outMKAD_small;

    $required_text = esc_attr__( 'required', 'woocommerce' );
    $required_html = '<abbr class="required" title="' . $required_text . '">*</abbr>';
    ?>
    <script>
		deliveryDate = document.querySelector('#e_deliverydate_field');
		deliveryInterval = document.querySelector('#orddd_time_slot_field');
        additionalAddress = document.querySelector('.additional-address-field');
        jQuery(function($){
            var ism = 'input[name^="shipping_method"]',         ismc = ism+':checked',
                csa = 'input#ship-to-different-address-checkbox',
                rq = '-required',       vr = 'validate'+rq,     w = 'woocommerce',      wv = w+'-validated',
                iv = '-invalid',        fi = '-field',          wir = w+iv+' '+w+iv+rq+fi,
                b = '#billing_',        s = '#shipping_',       f = '_field',
                a1 = 'country',     a2 = 'address_1',   a3 = 'address_2',   a4 = 'postcode',    a5 = 'state', a6 = 'city', a7 = 'address_3', a8 = 'address_4'
                b1 = b+a1+f,        b2 = b+a2+f,        b3 = b+a3+f,        b4 = b+a4+f,        b5 = b+a5+f, b6 = b+a6+f, b7 = b+a7+f, b8 = b+a8+f,
                s1 = s+a1+f,        s2 = s+a2+f,        s3 = s+a3+f,        s4 = s+a4+f,        s5 = s+a5+f,
                localPickup = '<?php echo $local_pickup; ?>',
				urgentPickup1 = '<?php echo $urgent_delivery_inMKAD; ?>',urgentPickup2 = '<?php echo $urgent_delivery_outMKAD; ?>',
				urgentPickup3 = '<?php echo $urgent_delivery_inMKAD_small; ?>',urgentPickup4 = '<?php echo $urgent_delivery_outMKAD_small; ?>';

            // Utility function to shows or hide checkout fields
            function showHide( action='show', selector='' ){
                if( action == 'show' )
                    $(selector).show(function(){
                        $(this).addClass(vr);
                        $(this).removeClass(wv);
                        $(this).removeClass(wir);
                        if( $(selector+' > label > abbr').html() == undefined )
                            $(selector+' label').append('<?php echo $required_html; ?>');
                    });
                else
                    $(selector).hide(function(){
                        $(this).removeClass(vr);
                        $(this).removeClass(wv);
                        $(this).removeClass(wir);
                        if( $(selector+' > label > abbr').html() != undefined )
                            $(selector+' label > .required').remove();
                    });
            }

            // Initializing at start after checkout init (Based on the chosen shipping method)
            setTimeout(function(){
                if( $(ismc).val() == localPickup ) // Chosen "Local pickup" (Hiding "Delivery")
                {
                    // showHide('hide',b1);    //#billing_country_field - always hidden
                    showHide('hide',b2);    //#billing_adress_1_field
                    //showHide('hide',b3);    //#billing_adress_2_field  
                    //showHide('hide',b4);    //postcode
                    //showHide('hide',b5);    //state
					//showHide('hide',b6);    //city
                    //showHide('hide',b7);    //#billing_adress_3_field
                    //showHide('hide',b8);    //#billing_adress_4_field
                    additionalAddress.classList.add('d-none');   
                }
        
                else
                {
                    // showHide('show',b1);
                    showHide('show',b2);
                    //showHide('show',b3);
                    //showHide('show',b4);
                    //showHide('show',b5);
					//showHide('show',b6);
					//showHide('show',b7);
					//showHide('show',b8);
                    additionalAddress.classList.remove('d-none'); 
                }        
            }, 100);
			
			setTimeout(function(){
                     if( $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2 || $(ismc).val() == urgentPickup3 || $(ismc).val() == urgentPickup4) // Chosen "Urgent pickup" (Hiding "Date")
                {
                    deliveryDate.classList.add('d-none');
                } else {
					deliveryDate.classList.remove('d-none');
				}
            }, 100);
			
			setTimeout(function(){
                     if( $(ismc).val() == localPickup || $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2 || $(ismc).val() == urgentPickup3 || $(ismc).val() == urgentPickup4) // Chosen "Local pickup or Urgent pickup" (Hiding "Interval")
                {
					deliveryInterval.classList.add('d-none');
                } else {
					deliveryInterval.classList.remove('d-none');
				}
            }, 100);
			
			// Initializing at start after checkout init (Based on the chosen shipping method)
            $( 'form.checkout' ).on( 'change', ism, function() {		
				if( $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2 || $(ismc).val() == urgentPickup3 || $(ismc).val() == urgentPickup4) // Chosen "Urgent pickup" (Hiding "Date")
                {
                    deliveryDate.classList.add('d-none');
                } else {
					deliveryDate.classList.remove('d-none');
				};
            });
			
			 $( 'form.checkout' ).on( 'change', ism, function() {		
				if( $(ismc).val() == localPickup || $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2 || $(ismc).val() == urgentPickup3 || $(ismc).val() == urgentPickup4) // Chosen "Local pickup or Urgent pickup" (Hiding "Interval")
                {
					deliveryInterval.classList.add('d-none');
                } else {
					deliveryInterval.classList.remove('d-none');
				}
            });

            // When shipping method is changed (Live event)
            $( 'form.checkout' ).on( 'change', ism, function() {
                if( $(ismc).val() == localPickup )
                {
                    // showHide('hide',b1);
                    showHide('hide',b2);
                    // showHide('hide',b3);
                    // showHide('hide',b4);
                    // showHide('hide',b5);
					// showHide('hide',b6);
					// showHide('hide',b7);
					// showHide('hide',b8);
                    additionalAddress.classList.add('d-none'); 
                }
             
                else
                {
                    // showHide('show',b1);
                    showHide('show',b2);
                    // showHide('show',b3);
                    // showHide('show',b4);
                    // showHide('show',b5);
					// showHide('show',b6);
					// showHide('show',b7);
					// showHide('show',b8);
                    additionalAddress.classList.remove('d-none'); 
                }
            });
	
        });
    </script>
    <?php
}



/* скрываем лишние способы доставки если доступна доставка бесплатная*/

add_filter( 'woocommerce_package_rates', 'new_truemisha_remove_shipping_method', 20, 2 );
 
function new_truemisha_remove_shipping_method( $rates, $package ) {

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
	
add_filter( 'woocommerce_package_rates', 'new_truemisha_remove_shipping_on_price', 25, 2 );
 
function new_truemisha_remove_shipping_on_price( $rates, $package ) {

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
 
	// если сумма всех товаров в корзине меньше 2000, отключаем способ доставки
	if ( WC()->cart->subtotal < 2000 ) {
	    unset( $rates[ $delivery_inMKAD ] );
		unset( $rates[ $delivery_outMKAD ] );
		unset( $rates[ $urgent_delivery_inMKAD ] );
		unset( $rates[ $urgent_delivery_outMKAD ] );		
	} else {
		unset( $rates[ $delivery_inMKAD_small ] );
		unset( $rates[ $delivery_outMKAD_small ] );
		unset( $rates[ $urgent_delivery_inMKAD_small ] );
		unset( $rates[ $urgent_delivery_outMKAD_small ] );
	}
 
	return $rates;
 
}

// делим поле billing_address_2 на несколько полей//

add_filter( 'woocommerce_form_field_text', 'true_fields', 25, 4 );
 
function true_fields( $field, $key, $args, $value ) {
 
	if( 'billing_address_2' === $key ) {
 
		$field = '<p class="form-row address-field additional-address-field form-row-wide" data-priority="60">
			<span class="woocommerce-input-wrapper true-wrapper woocommerce-address-wrapper">
				<input type="text" name="billing_address_2" id="billing_address_2" placeholder="Подъезд" value="">
				<input type="text" name="billing_address_3" id="billing_address_3" placeholder="Этаж" value="">
				<input type="text" name="billing_address_4" id="billing_address_4" placeholder="Дополнительная информация" value="">
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
		$fields[] = 'подъезд ' . $_POST[ 'billing_address_2' ];
	}
	// затем этаж
	if( ! empty( $_POST[ 'billing_address_3' ] ) ) {
		$fields[] = 'этаж ' . $_POST[ 'billing_address_3' ];
	}

	// затем доп поля
	if( ! empty( $_POST[ 'billing_address_4' ] ) ) {
		$fields[] = ' ' . $_POST[ 'billing_address_4' ];
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
    $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
	$qty = 0; // обязательно сначала ставим 0
 	$cat_amount = 0;
	$products_min = false;
	foreach ( WC()->cart->get_cart() as $cart_item ) {
			$_product = $cart_item['data'];
            $_product_id = $_product->id;
            $terms = get_the_terms( $_product_id, 'product_cat' );
			foreach ($terms as $term) {
                    $_categoryid = $term->term_id;
                }
                // your products categories
                if ( $_categoryid === $treez_cat_id ) {
                    $products_min = true;
					$qty = $cart_item[ 'quantity' ];
					$price = $cart_item['data']->get_price();
					$cat_amount = $cat_amount + $price*$qty;
                }	
	}
 
    if( ( is_cart() || is_checkout() ) && $cat_amount < $min_treez_delivery && $products_min) {
        wc_print_notice(
            sprintf( 'Минимальная сумма заказа для кашпо Treez %s (без учета стоимости других товаров).'  ,
                wc_price( $min_treez_delivery ),
                wc_price( WC()->cart->total )
            ), 'error'
        );
    } 

    if ( $cat_amount < $min_treez_delivery && $products_min) {

        wc_add_notice( 
            sprintf( 
                'Минимальная сумма заказа для кашпо Treez %s (без учета стоимости других товаров).',
                wc_price( $min_treez_delivery ),
                wc_price( WC()->cart->subtotal )
            ),
            'error'
        );

    }
}

//add_action( 'woocommerce_before_checkout_form', 'min_amount_for_category_info' );
add_action( 'woocommerce_checkout_order_review', 'min_amount_for_category_info',40 );

function min_amount_for_category_info(){
    global $treez_cat_id;
    $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
    echo '<div>hi</div>';
    echo '<div class="checkout__free-delivery-text">
    Минимальная сумма заказа для кашпо Treez <span>'.$min_treez_delivery,'</span> (без учета стоимости других товаров)</div>';
	$qty = 0; // обязательно сначала ставим 0
 	$cat_amount = 0;
	$products_min = false;
	foreach ( WC()->cart->get_cart() as $cart_item ) {
			$_product = $cart_item['data'];
            $_product_id = $_product->id;
            $terms = get_the_terms( $_product_id, 'product_cat' );
			foreach ($terms as $term) {
                    $_categoryid = $term->term_id;
                }
                // your products categories
                if ( $_categoryid === $treez_cat_id ) {
                    $products_min = true;
					$qty = $cart_item[ 'quantity' ];
					$price = $cart_item['data']->get_price();
					$cat_amount = $cat_amount + $price*$qty;
                }	
	}
 
    // if( ( is_cart() || is_checkout() ) && $cat_amount < $min_treez_delivery && $products_min) {
    //     echo '<div>
    //     Минимальная сумма заказа для кашпо Treez <span>'.$min_treez_delivery,'</span> (без учета стоимости других товаров)</div>';
    // } 
    // echo '<div><span>'.$treez_cat_id,'</span></div>';
    if( $cat_amount < $min_treez_delivery && $products_min) {
        echo '<div>
        Минимальная сумма заказа для кашпо Treez <span>'.$min_treez_delivery,'</span> (без учета стоимости других товаров)</div>';
    } 
   
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