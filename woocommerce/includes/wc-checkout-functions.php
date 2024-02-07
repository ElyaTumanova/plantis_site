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
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    echo str_replace(" ","",$min_free_delivery);
	if ( WC()->cart->subtotal < str_replace(" ","",$min_free_delivery)) {
		$cart = str_replace(" ","",$min_free_delivery) - WC()->cart->subtotal;
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


/* ПОЛЯ ФОРМЫ ОФОРМЛЕНИЯ ЗАКАЗА  - АКТИВИРОВАТЬ ДЛЯ PLANTIS.SHOP!!!!
 
// Conditional Show hide checkout fields based on chosen shipping methods*/

//add_action( 'wp_footer', 'custom_checkout_field_script' );
function custom_checkout_field_script() {
	
	if( !is_page( 16 ) ) {
		return;
	}

    // HERE your shipping methods rate IDs
    $local_pickup = 'local_pickup:1';
	$urgent_delivery1 = 'flat_rate:5';
	$urgent_delivery2 = 'flat_rate:6';

    $required_text = esc_attr__( 'required', 'woocommerce' );
    $required_html = '<abbr class="required" title="' . $required_text . '">*</abbr>';
    ?>
    <script>
		deliveryDate = document.querySelector('#e_deliverydate_field');
		deliveryInterval = document.querySelector('#additional_delivery_interval_field');
        jQuery(function($){
            var ism = 'input[name^="shipping_method"]',         ismc = ism+':checked',
                csa = 'input#ship-to-different-address-checkbox',
                rq = '-required',       vr = 'validate'+rq,     w = 'woocommerce',      wv = w+'-validated',
                iv = '-invalid',        fi = '-field',          wir = w+iv+' '+w+iv+rq+fi,
                b = '#billing_',        s = '#shipping_',       f = '_field',
                a1 = 'country',     a2 = 'address_1',   a3 = 'address_2',   a4 = 'postcode',    a5 = 'state', a6 = 'city',
                b1 = b+a1+f,        b2 = b+a2+f,        b3 = b+a3+f,        b4 = b+a4+f,        b5 = b+a5+f, b6 = b+a6+f,
                s1 = s+a1+f,        s2 = s+a2+f,        s3 = s+a3+f,        s4 = s+a4+f,        s5 = s+a5+f,
                localPickup = '<?php echo $local_pickup; ?>',
				urgentPickup1 = '<?php echo $urgent_delivery1; ?>',urgentPickup2 = '<?php echo $urgent_delivery2; ?>';

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
                    showHide('hide',b1);
                    showHide('hide',b2);
                    showHide('hide',b3);
                    showHide('hide',b4);
                    showHide('hide',b5);
					showHide('hide',b6);
                }
        
                else
                {
                    showHide('show',b1);
                    showHide('show',b2);
                    showHide('show',b3);
                    showHide('show',b4);
                    showHide('show',b5);
					showHide('show',b6);
                }        
            }, 100);
			
			setTimeout(function(){
                     if( $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2) // Chosen "Local pickup" (Hiding "Delivery")
                {
                    deliveryDate.classList.add('d-none');
                } else {
					deliveryDate.classList.remove('d-none');
				}
            }, 100);
			
			setTimeout(function(){
                     if( $(ismc).val() == localPickup || $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2) // Chosen "Local pickup" (Hiding "Delivery")
                {
					deliveryInterval.classList.add('d-none');
                } else {
					deliveryInterval.classList.remove('d-none');
				}
            }, 100);
			
			// Initializing at start after checkout init (Based on the chosen shipping method)
            $( 'form.checkout' ).on( 'change', ism, function() {		
				if( $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2) // Chosen "Local pickup" (Hiding "Delivery")
                {
                    deliveryDate.classList.add('d-none');
                } else {
					deliveryDate.classList.remove('d-none');
				}
            });
			
			 $( 'form.checkout' ).on( 'change', ism, function() {		
				if( $(ismc).val() == localPickup || $(ismc).val() == urgentPickup1 || $(ismc).val() == urgentPickup2) // Chosen "Local pickup" (Hiding "Delivery")
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
                    showHide('hide',b1);
                    showHide('hide',b2);
                    showHide('hide',b3);
                    showHide('hide',b4);
                    showHide('hide',b5);
					showHide('hide',b6);
   
                }
             
                else
                {
                    showHide('show',b1);
                    showHide('show',b2);
                    showHide('show',b3);
                    showHide('show',b4);
                    showHide('show',b5);
					showHide('show',b6);
                }
            });
	
        });
    </script>
    <?php
}

/* скрываем лишние способы доставки если доступна доставка бесплатная*/
//add_filter( 'woocommerce_package_rates', 'truemisha_remove_shipping_method', 20, 2 );
 
function truemisha_remove_shipping_method( $rates, $package ) {
 
	// удаляем способ доставки, если доступна бесплатная
	if ( isset( $rates[ 'free_shipping:4' ] ) ) {
	    unset( $rates[ 'flat_rate:2' ] );
// 		unset( $rates[ 'flat_rate:3' ] );
		unset( $rates[ 'flat_rate:5' ] );
// 		unset( $rates[ 'flat_rate:6' ] );
		unset( $rates[ 'flat_rate:9' ] );
		unset( $rates[ 'flat_rate:10' ] );
		unset( $rates[ 'flat_rate:11' ] );
		unset( $rates[ 'flat_rate:12' ] );
	}
 
	return $rates;
}



/* стоимость доставки в зависимости от суммы заказа*/
	
//add_filter( 'woocommerce_package_rates', 'truemisha_remove_shipping_on_price', 25, 2 );
 
function truemisha_remove_shipping_on_price( $rates, $package ) {
 
	// если сумма всех товаров в корзине меньше 1000, отключаем способ доставки
	if ( WC()->cart->subtotal < 2000 ) {
	    unset( $rates[ 'flat_rate:2' ] );
		unset( $rates[ 'flat_rate:3' ] );
		unset( $rates[ 'flat_rate:5' ] );
		unset( $rates[ 'flat_rate:6' ] );		
	} else {
		unset( $rates[ 'flat_rate:9' ] );
		unset( $rates[ 'flat_rate:10' ] );
		unset( $rates[ 'flat_rate:11' ] );
		unset( $rates[ 'flat_rate:12' ] );
	}
 
	return $rates;
 
}