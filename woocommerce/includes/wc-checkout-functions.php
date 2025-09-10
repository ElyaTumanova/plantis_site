<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
Contents
# Backorders
# Checkout page adjustments
# Delivery date & Interval fields
# Notifications
# Treez & Lechuza notifications
# Checkout form fields
# Dont call me radio buttons
# INN field
# Billing adress field
# T Bank
# Payment method
# Thankyou page & email
# Additional fields for admin
--------------------------------------------------------------*/

/*--------------------------------------------------------------
# Backorders
--------------------------------------------------------------*/

    // проверка наличия товара Backorders в корзине

    function plnt_is_backorder() {
        $qty = 0; // обязательно сначала ставим 0
        $isbackorders = false;
        
        if( is_checkout( ) && ! is_wc_endpoint_url()) {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $_product = $cart_item['data'];
                $_product_id = $_product->get_id();
                $qty = $cart_item[ 'quantity' ];
                $stock_qty = $_product->get_stock_quantity();
                
                if ( $_product->backorders_allowed() && $qty > $stock_qty ) {
                    $isbackorders = true;
                }	
            }
        }      
        
        return $isbackorders;
    }

    //отключаем способ оплаты для Backorders
    add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_backorders' );

    function plnt_disable_payment_backorders( $available_gateways ) {
        if (is_admin()) {
            return $available_gateways;
        } else {
            $isbackorders = plnt_is_backorder();
            if( $isbackorders) {
                unset( $available_gateways['tbank'] ); //to do change to tbank
                unset( $available_gateways['cop'] ); 
            }
            return $available_gateways;
        }
    }

    // меняем название и описание способа оплаты для Backorders
    add_filter( 'woocommerce_gateway_title', 'change_payment_gateway_title_backorders', 100, 2 );

    function change_payment_gateway_title_backorders( $title, $payment_id ){
        $targeted_payment_id = 'cod'; // Задайте идентификатор вашего способа оплаты
        // Только на странице оформления заказа для определённого идентификатора способа оплаты
        if( is_checkout( ) && ! is_wc_endpoint_url() && $payment_id === $targeted_payment_id ) {
            $isbackorders = plnt_is_backorder();
            if( $isbackorders) {
                return __("Оплата после подтверждения заказа менеджером", "woocommerce" );
            }
        }
        return $title;
    }

    add_filter( 'woocommerce_gateway_description', 'change_payment_gateway_description_backorders', 100, 2 );

    function change_payment_gateway_description_backorders( $description, $payment_id ){
        $targeted_payment_id = 'cod'; // Задайте идентификатор вашего способа оплаты
        // Только на странице оформления заказа для определённого идентификатора способа оплаты
        if( is_checkout( ) && ! is_wc_endpoint_url() && $payment_id === $targeted_payment_id ) {
            $isbackorders = plnt_is_backorder();
            if( $isbackorders) {
                return __("Наш менеджер свяжется с Вами для подтверждения заказа и направит ссылку для оплаты картой.", "woocommerce" );
            }
        }
        return $description;
    }

/*--------------------------------------------------------------
# Checkout page adjustments
--------------------------------------------------------------*/

    /* скрываем Уже покупали? и форму логирования на странице оформления заказа*/
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

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
    //add_action( 'woocommerce_checkout_order_review', 'plnt_delivery_condition_info', 30 );

    // function plnt_delivery_condition_info () {
    //     echo '<div class="checkout__text checkout__text_delivery-info">
    //         После оформления заказа мы свяжемся с вами в <a href="https://plantis-shop.ru/contacts/">рабочее время</a> и согласуем время доставки.
    //         <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки.</a> <br>
    //         Важно! Срочную доставку "день в день" можно оформить до 18 часов.</div>';
    // }

    // итоговая стоимость
    add_action( 'woocommerce_checkout_order_review', 'plnt_order_total', 25 );

    function plnt_order_total() {
            ?>
            <div class="plnt-order-total">
                <div>Итого</div>
                <div class="plnt-order-total_price"><?php wc_cart_totals_order_total_html(); ?></div>
            </div>
            <?php 
    };

    // добавляем фрагмент, чтобы апдейтить итоговую стоимость
    add_action( 'woocommerce_update_order_review_fragments', 'my_update_order_review_fragments', 10, 1 );
    function my_update_order_review_fragments( $fragments ) {
        ob_start();
        plnt_order_total();
        $fragments[ 'div.plnt-order-total'] = ob_get_clean();
        return $fragments;
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
# Delivery date & Interval fields
--------------------------------------------------------------*/

    // // добавляем новые поля для нтервала и даты доставки

    add_action( 'woocommerce_checkout_order_review', 'plnt_add_delivery_interval_field', 20 );

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

    add_action( 'woocommerce_checkout_order_review', 'plnt_add_delivery_dates', 15 );

    function plnt_add_delivery_dates() {

        $days = array();

        $weekend_string = carbon_get_theme_option('weekend');
        $weekend_array = explode( ",", $weekend_string);

        date_default_timezone_set('Europe/Moscow');
        $hour = date("H");

        if ($hour >=18) {
            $days_start = 1;
            $days_amount = 14;
        } else {
            $days_start = 0;
            $days_amount = 13;
        }

        for ($i = $days_start; $i <= $days_amount;  $i++) {
            $day = date('d.m', time() + 86400*$i);
            if (in_array($day, $weekend_array)) {
                $days_amount ++;
            } else {
                array_push($days, $day);
            }
        }
        echo "<div class='delivery_wrap'>";
        // выводим поле функцией woocommerce_form_field()
        woocommerce_form_field( 
            'delivery_dates', 
            array(
                'type'          => 'radio', // text, textarea, select, radio, checkbox, password
                'required'	=> false, // по сути только добавляет значок "*" и всё
                'class'         => array( 'delivery_dates', 'swiper' ), // массив классов поля
                'label'         => 'Дата доставки (самовывоза)',
                'label_class'   => array( 'delivery_dates_label', 'swiper-slide' ), // класс лейбла
                'options'	=> array( // options for  or 
                    $days[0]		=> $days[0], // 'значение' => 'заголовок'
                    $days[1]		=> $days[1], // 'значение' => 'заголовок'
                    $days[2]		=> $days[2], // 'значение' => 'заголовок'
                    $days[3]		=> $days[3], // 'значение' => 'заголовок'
                    $days[4]		=> $days[4], // 'значение' => 'заголовок'
                    $days[5]		=> $days[5], // 'значение' => 'заголовок'
                    $days[6]		=> $days[6], // 'значение' => 'заголовок'
                    $days[7]		=> $days[7], // 'значение' => 'заголовок'
                    $days[8]		=> $days[8], // 'значение' => 'заголовок'
                    $days[9]		=> $days[9], // 'значение' => 'заголовок'
                    $days[10]		=> $days[10], // 'значение' => 'заголовок'
                    $days[11]		=> $days[11], // 'значение' => 'заголовок'
                    $days[12]		=> $days[12], // 'значение' => 'заголовок'
                    $days[13]		=> $days[13], // 'значение' => 'заголовок'
                )
            ),
        );
    }

    add_filter( 'woocommerce_form_field' , 'elex_remove_checkout_optional_text', 10, 4 );
    function elex_remove_checkout_optional_text( $field, $key, $args, $value ) {
        if( is_checkout() && ! is_wc_endpoint_url() ) {
            $optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
            $field = str_replace( $optional, '', $field );
        }
        return $field;
    } 

    // // сохряняем новое поле в заказе


    add_action( 'woocommerce_checkout_update_order_meta', 'plnt_save_delivery_fields', 25 );
    
    function plnt_save_delivery_fields( $order_id ){
    
        if( ! empty( $_POST[ 'delivery_dates' ] ) ) {
            update_post_meta( $order_id, 'delivery_dates', sanitize_text_field( $_POST[ 'delivery_dates' ] ) );
        }


        if( ! empty( $_POST[ 'additional_delivery_interval' ] ) ) {
            update_post_meta( $order_id, 'additional_delivery_interval', $_POST[ 'additional_delivery_interval' ] );
        }
    }

    // // добавляем поле дата доставки в админку

    add_action( 'woocommerce_admin_order_data_after_billing_address', 'plnt_print_editable_delivery_field_value', 25 );
    
    function plnt_print_editable_delivery_field_value( $order ){
    
        $method = get_post_meta( $order->get_id(), 'delivery_dates', true );
    
        echo '<div class="address">
            <p' . ( ! $method ? ' class="none_set"' : '' ) . '>
                <strong>Дата доставки (самовывоза)</strong>
                ' . ( $method ? $method : 'Не указан.' ) . '
            </p>
        </div>
        <div class="edit_address">';
        woocommerce_wp_text_input( array(
            'id' => 'delivery_dates',
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
        update_post_meta( $order_id, 'delivery_dates', wc_clean( $_POST[ 'delivery_dates' ] ) );
        update_post_meta( $order_id, 'additional_delivery_interval', wc_clean( $_POST[ 'additional_delivery_interval' ] ) );
    }


    // // добавляем новые поля в письма

    add_filter( 'woocommerce_get_order_item_totals', 'plnt_delivery_fields_in_email', 25, 2 );
    
    function plnt_delivery_fields_in_email( $rows, $order ) {
    
        // удалите это условие, если хотите добавить значение поля и на страницу "Заказ принят"
        // if( is_order_received_page() ) {
        // 	return $rows;
        // }
    
        $rows[ 'delivery_dates' ] = array(
            'label' => 'Дата доставки (самовывоза)',
            'value' => get_post_meta( $order->get_id(), 'delivery_dates', true )
        );

        $rows[ 'additional_delivery_interval' ] = array(
            'label' => 'Интервал доставки',
            'value' => get_post_meta( $order->get_id(), 'additional_delivery_interval', true )
        );
    
        return $rows;
    
    }

/*--------------------------------------------------------------
# Notifications
--------------------------------------------------------------*/
    // // до бесплатной доставки осталось
    add_action( 'woocommerce_checkout_order_review', 'my_delivery_small_oder_info', 40 );

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

    // выбрана крупногабартная доставка (с использованием класса доставки - не применяем)
    //add_action( 'woocommerce_checkout_order_review', 'my_delivery_large_products_oder_info', 40 );
    //add_action( 'woocommerce_review_order_before_shipping', 'my_delivery_large_products_oder_info', 10 ); //встраиваем в таблицу, использовать теги таблицы

    function my_delivery_large_products_oder_info () {
        $class_slug = 'delivery_large';

        foreach ( WC()->cart->get_cart() as $cart_item ) {
            if( $cart_item['data']->get_shipping_class() == $class_slug ){
                echo '<tr> <td colspan="2" class="checkout__text checkout__text_large">
                Вы выбрали крупногабаритный товар. Стоимость доставки увеличена. <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки.</a></td></tr>';
                break; // Stop the loop
            } 	
        }
    }

    // сообщение о крупногабартной доставке (с машинкой)

    add_action('plnt_large_delivery_notice', 'plnt_large_delivery_notice');

    function plnt_large_delivery_notice() {

        if (check_if_large_delivery()) {
            ?>
            <div class=large_delivery_notice>
            <img class=large_delivery_img src="https://plantis-shop.ru/wp-content/themes/plantis_site/images/icons/car.svg" alt="car">
            <p>Для заказа предусмотрена крупногабаритная доставка!</p></div>
            <?php
        }
        
    }

    
    //комментарий к выбранному способу доставки

    add_action( 'woocommerce_checkout_order_review', 'delivery_info', 10 );
   
    function delivery_info(){
        $min_small_delivery = carbon_get_theme_option('min_small_delivery');
        $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');
        $shipping_costs = plnt_get_shiping_costs();
        global $delivery_inMKAD;
        global $delivery_outMKAD;
        global $delivery_courier;
        global $delivery_long_dist;
        global $local_pickup;
        global $delivery_pochta;

        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        date_default_timezone_set('Europe/Moscow');
        $hour = date("H");

        echo '<div class="checkout__comment">';

        //обычная доставка за и в пределах МКАД

        if ($delivery_inMKAD == $chosen_methods[0] || $delivery_outMKAD == $chosen_methods[0]) {

            //Срочная доставка "День в день"

            if ( WC()->session->get('isUrgent' ) === '1') {
                if($hour < 18) {
                    echo '<div class="checkout__text checkout__text_urgent">
                        Срочную доставку можно оформить до 18:00. 
                        После оформления заказа мы свяжемся с вами для его подтверждения. 
                        <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a></div>';
                }
            //при оформлении после 20:00-00:00 текущего дня
                if($hour >= 20) {
                    echo '<div class="checkout__text checkout__text_normal-late">
                        При оформлении после 20:00 доставки на следующий день стоимость рассчитывается по тарифу срочной доставки. 
                        После оформления заказа мы свяжемся с вами в рабочее время для его подтверждения.</div>';
                }
            }
        
            //Доставка не срочная
            if ( WC()->session->get('isUrgent' ) === '0' ) {
                //при оформлении до 20:00
                if ($hour < 20) {
                    echo '<div class="checkout__text checkout__text_normal">
                        После оформления заказа мы свяжемся с вами в рабочее время с 10:00 до 20:00 для его подтверждения. 
                        <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a></div>';
                //при оформлении после 20:00-00:00 текущего дня
                } else {
                    echo '<div class="checkout__text checkout__text_normal-late">
                        При оформлении после 20:00 доставки на следующий день стоимость рассчитывается по тарифу срочной доставки. 
                        После оформления заказа мы свяжемся с вами в рабочее время для его подтверждения.</div>';
                }
            }
            
            //Доставка заказов до 1500 рублей
            if (WC()->cart->subtotal < $min_small_delivery) {
                if(!array_key_exists($delivery_courier,$shipping_costs)) {
                    echo '<div class="checkout__text checkout__text_small-order">
                    При заказе на сумму менее '.$min_small_delivery,' рублей стоимость доставки увеличена. 
                    <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки.</a></div';
                } else if ($delivery_courier == $chosen_methods[0] ) {
                    echo '<div class="checkout__text checkout__text_small-order-holiday">
                    В связи с высокой загрузкой курьеров в предпраздничные дни заказы стоимостью до '.$min_small_delivery,' рублей доставляются в любой день по тарифу курьерской службы. 
                    Мы свяжемся с Вами после оформления заказа и произведем расчет стоимости доставки. 
                    Также, вы можете самостоятельно бесплатно забрать заказ в нашем магазине, оформив самовывоз.
                    <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                    </div>';
                }  
            }
            //Доставка заказов до 2500 рублей
            else if (WC()->cart->subtotal < $min_medium_delivery){
                if(!array_key_exists($delivery_courier,$shipping_costs)) {
                    echo '<div class="checkout__text checkout__text_small-order">
                    При заказе на сумму менее '.$min_medium_delivery,' рублей стоимость доставки увеличена. 
                    <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки.</a></div';
                } else if ($delivery_courier == $chosen_methods[0] ) {
                    echo '<div class="checkout__text checkout__text_small-order-holiday">
                    В связи с высокой загрузкой курьеров в предпраздничные дни заказы стоимостью до '.$min_medium_delivery,' рублей доставляются в любой день по тарифу курьерской службы. 
                    Мы свяжемся с Вами после оформления заказа и произведем расчет стоимости доставки. 
                    Также, вы можете самостоятельно бесплатно забрать заказ в нашем магазине, оформив самовывоз.
                    <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                    </div>';
                }  
       
            }
        }

        //Доставка заказов свыше 5км от МКАД
        if ( $delivery_long_dist == $chosen_methods[0]) {
            echo '<div class="checkout__text checkout__text_long-dist">
                Доставка на расстояние свыше 5км от МКАД осуществляется по тарифам курьерской службы. 
                Мы свяжемся с Вами после оформления заказа в рабочее время с 10:00 до 20:00 и рассчитаем стоимость доставки.
                <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                </div>';
        }

        //Доставка Почтой России
        if ( $delivery_pochta == $chosen_methods[0]) {
            $shipping_costs = plnt_get_shiping_costs();
            $delivery_pochta_cost = $shipping_costs[$delivery_pochta];
            echo '<div class="checkout__text checkout__text_long-dist">
                Стоимость упаковки и доставки до отделения почты — '.$delivery_pochta_cost,' рублей за каждое растение. 
                Получатель также оплачивает услугу пересылки. 
                Рассчитать стоимость и срок доставки вы можете на <a href="https://www.pochta.ru/shipment?type=PARCEL">сайте</a> "Почты России".
                Оплатить заказ можно будет после его оформления.
                Мы свяжемся с Вами после оформления заказа в рабочее время с 10:00 до 20:00.
                <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                </div>';
        }

        //Самовывоз
        if ( $local_pickup == $chosen_methods[0]) {
            echo '<div class="checkout__text checkout__text_local-pickup">
                После оформления заказа мы свяжемся с вами в рабочее время с 10:00 до 20:00 для его подтверждения.
                <a href="https://plantis-shop.ru/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                </div>';
        }

        if ($delivery_courier == $chosen_methods[0]) {
            echo '<div class="checkout__text checkout__text_holiday">
                В связи с высокой загрузкой курьеров в праздничные дни заказы доставляются по тарифу курьерской службы. 
                Мы свяжемся с Вами после оформления заказа и произведем расчет стоимости доставки. 
                Также, вы можете самостоятельно бесплатно забрать заказ в нашем магазине, оформив самовывоз.
                </div>';
        }


        echo '</div>';

    }

    // добавляем фрагмент, чтобы апдейтить комментарий к доставке
    add_action( 'woocommerce_update_order_review_fragments', 'update_order_review_notifications_fragments', 20, 1 );
    function update_order_review_notifications_fragments( $fragments ) {
        ob_start();
        delivery_info();
        $fragments[ 'div.checkout__comment'] = ob_get_clean();
        return $fragments;
    }

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

    //уведомление для доставки Почтой России
    add_action( 'woocommerce_checkout_process', 'delivery_pochta_error_notification');

    function delivery_pochta_error_notification() {
        global $delivery_pochta;
        global $plants_cat_id;
        $notOnlyPlantsInCart = false;
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $parentCatId = check_category($_product);
            if ($parentCatId !== $plants_cat_id ){
                $notOnlyPlantsInCart = true;
                break;
            } 
        }

        if( ( is_cart() || is_checkout() ) && $notOnlyPlantsInCart && $delivery_pochta == $chosen_methods[0]) {
            wc_print_notice(
                sprintf( 'Почтой России мы доставляем только комнатные растения.'  ,
                    // wc_price( $min_treez_delivery ),
                    // wc_price( WC()->cart->total )
                ), 'error'
            );
        }

        if( $notOnlyPlantsInCart && $delivery_pochta == $chosen_methods[0]) {
            wc_add_notice( 
                sprintf( 
                    'Почтой России мы доставляем только комнатные растения.',
                    // wc_price( $min_treez_delivery ),
                    // wc_price( WC()->cart->subtotal )
                ),
                'error'
            );
        }
    }

    add_action( 'woocommerce_review_order_before_shipping', 'delivery_pochta_info', 10 ); //встраиваем в таблицу, использовать теги таблицы

    function delivery_pochta_info() {
        global $delivery_pochta;
        global $plants_cat_id;
        $notOnlyPlantsInCart = false;
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $parentCatId = check_category($_product);
            if ($parentCatId !== $plants_cat_id ){
                $notOnlyPlantsInCart = true;
                break;
            } 
        }

        if( $notOnlyPlantsInCart && $delivery_pochta == $chosen_methods[0]) {
            echo '<tr> <td colspan="2" class="checkout__text checkout__text_pochta checkout__text_alarm">
            Почтой России мы доставляем только комнатные растения.</td></tr>';
        }
    }

/*--------------------------------------------------------------
# Treez & Lechuza notifications
--------------------------------------------------------------*/

/*минимальная сумма заказа для кашпо Treez & Lechuza*/
    //add_action( 'woocommerce_checkout_process', 'min_amount_for_category_treez' );
    //add_action( 'woocommerce_checkout_process', 'min_amount_for_category_lechuza' );

    function min_amount_for_category_treez(){
        $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
        $qty = 0; // обязательно сначала ставим 0
        $cat_amount = 0;
        $products_min = false;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
          $_product = $cart_item['data'];
          $isTreez = check_is_treez($_product);
          if ( $isTreez) {
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

    function min_amount_for_category_lechuza(){
        $min_lechuza_delivery = carbon_get_theme_option('min_lechuza_delivery');
        $qty = 0; // обязательно сначала ставим 0
        $cat_amount = 0;
        $products_min = false;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
          $_product = $cart_item['data'];
          $isLechuza = check_is_lechuza($_product);
          if ( $isLechuza) {
              $products_min = true;
              $qty = $cart_item[ 'quantity' ];
              $price = $cart_item['data']->get_price();
              $cat_amount = $cat_amount + $price*$qty;
          }	
        }
    
        if( ( is_cart() || is_checkout() ) && $cat_amount < $min_lechuza_delivery && $products_min) {
            wc_print_notice(
                sprintf( 'Минимальная сумма заказа для кашпо Lechuza %s (без учета стоимости других товаров).'  ,
                    wc_price( $min_lechuza_delivery ),
                    wc_price( WC()->cart->total )
                ), 'error'
            );
        } 

        if ( $cat_amount < $min_lechuza_delivery && $products_min) {

            wc_add_notice( 
                sprintf( 
                    'Минимальная сумма заказа для кашпо Lechuza %s (без учета стоимости других товаров).',
                    wc_price( $min_lechuza_delivery ),
                    wc_price( WC()->cart->subtotal )
                ),
                'error'
            );

        }
    }

//уведомление о минимальной сумме заказа для Treez & Lechuza
    //add_action( 'woocommerce_checkout_order_review', 'min_amount_for_treez_info', 40 );
    add_action( 'woocommerce_review_order_before_shipping', 'min_amount_for_treez_info', 10 ); //встраиваем в таблицу, использовать теги таблицы
    add_action( 'woocommerce_review_order_before_shipping', 'min_amount_for_lechuza_info', 20 ); //встраиваем в таблицу, использовать теги таблицы

    function min_amount_for_treez_info(){
        $min_treez_delivery = carbon_get_theme_option('min_treez_delivery');
        $qty = 0; // обязательно сначала ставим 0
        $cat_amount = 0;
        $products_min = false;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $_product = $cart_item['data'];
            $isTreez = check_is_treez($_product);
            if ( $isTreez) {
                $products_min = true;
                $qty = $cart_item[ 'quantity' ];
                $price = $cart_item['data']->get_price();
                $cat_amount = $cat_amount + $price*$qty;
            }	
        }

        // if( $cat_amount < $min_treez_delivery && $products_min) {
        //     echo '<tr> <td colspan="2" class="checkout__text checkout__text_treez checkout__text_alarm">
        //     Минимальная сумма заказа для кашпо и искусственных растений Treez <span>'.$min_treez_delivery,'</span> рублей (без учета стоимости других товаров).</td></tr>';
        // }   
        if( $products_min) {
            echo '<tr> <td colspan="2" class="checkout__text checkout__text_treez checkout__text_alarm">
            Доставка кашпо и искусственных растений Treez осуществляется со склада в течение 3-7 дней. <br>Оплатить заказ с кашпо и искусственными растениями Treez можно будет после подтверждения их наличия. Наш менеджер свяжется с Вами после оформления заказа.</td></tr>';
        }   
    }

    function min_amount_for_lechuza_info(){
        $min_lechuza_delivery = carbon_get_theme_option('min_lechuza_delivery');
        $qty = 0; // обязательно сначала ставим 0
        $cat_amount = 0;
        $products_min = false;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
          $_product = $cart_item['data'];
          $isLechuza = check_is_lechuza($_product);
          if ( $isLechuza) {
              $products_min = true;
              $qty = $cart_item[ 'quantity' ];
              $price = $cart_item['data']->get_price();
              $cat_amount = $cat_amount + $price*$qty;
          }	
        }

        // if( $cat_amount < $min_lechuza_delivery && $products_min) {
        //     echo '<tr> <td colspan="2" class="checkout__text checkout__text_treez checkout__text_alarm">
        //     Минимальная сумма заказа для кашпо Lechuza <span>'.$min_lechuza_delivery,'</span> рублей (без учета стоимости других товаров).</td></tr>';
        // }   
        if( $products_min) {
            echo '<tr> <td colspan="2" class="checkout__text checkout__text_treez checkout__text_alarm">
            Доставка кашпо Lechuza осуществляется со склада в течение 3-7 дней. <br> Оплатить заказ с кашпо Lechuza можно будет после подтверждения их наличия. Наш менеджер свяжется с Вами после оформления заказа.</td></tr>';
        }   
    }

//отключаем способ оплаты для Treez & Lechuza
    add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_treez' );
    add_filter( 'woocommerce_available_payment_gateways', 'plnt_disable_payment_lechuza' );

    function plnt_disable_payment_treez( $available_gateways ) {
        $products_min = false;
        if (is_admin()) {
            return $available_gateways;
        } else {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                    $_product = $cart_item['data'];
                    $isTreez = check_is_treez($_product);
        
                    if ( $isTreez) {
                        $products_min = true;
                    }	
            }
        
            if( $products_min) {
                unset( $available_gateways['tbank'] );
                //unset( $available_gateways['bacs'] );
            }
            return $available_gateways;
        }
    }

    function plnt_disable_payment_lechuza( $available_gateways ) {
        $products_min = false;
        if (is_admin()) {
            return $available_gateways;
        } else {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
              $_product = $cart_item['data'];
              $isLechuza = check_is_lechuza($_product);
  
              if ( $isLechuza) {
                  $products_min = true;
              }	
            }
        
            if( $products_min) {
                unset( $available_gateways['tbank'] );
            }
            return $available_gateways;
        }
    }

     // проверка наличия товара treez & Lechuza под заказ в корзине

     function plnt_is_treez_backorder() {
        //$qty = 0; // обязательно сначала ставим 0
        $isTreezBackorders = false;
        
        if( is_checkout( ) && ! is_wc_endpoint_url()) {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $_product = $cart_item['data'];
                //$_product_id = $_product->id;
                //$qty = $cart_item[ 'quantity' ];
                //$stock_qty = $_product->get_stock_quantity();
                
                if (check_is_treez($_product) || check_is_lechuza($_product)) {
                    $isTreezBackorders = true;
                }	
            }
        }      
        
        return $isTreezBackorders;
    }

/*--------------------------------------------------------------
# Checkout form fields
--------------------------------------------------------------*/
// редактируем поля формы
add_filter( 'woocommerce_checkout_fields', 'plnt_override_checkout_fields' );

function plnt_override_checkout_fields( $fields ) {
    //убираем ненужные поля
    unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_city'] );
    unset( $fields['billing']['billing_state'] );
    unset( $fields['billing']['billing_postcode'] );

    //меняем порядок вывода полей
    $fields['billing']['billing_phone']['priority'] = 20;
    $fields['billing']['billing_email']['priority'] = 20;
    $fields['billing']['billing_address_2']['priority'] = 40;

    //добавляем placeholder
    $fields['billing']['billing_first_name']['placeholder'] = 'Имя (обязательно)';
    $fields['billing']['billing_phone']['placeholder'] = 'Телефон (обязательно)';
    $fields['billing']['billing_email']['placeholder'] = 'Email (обязательно)';
    //делаем обязательным
    $fields['billing']['billing_first_name']['required'] = true;
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_email']['required'] = true;
    $fields['billing']['billing_address_1']['required'] = false;
    $fields['billing']['billing_address_2']['required'] = false;
    

    return $fields;
}

//убираем заголовки
add_filter( 'woocommerce_checkout_fields', 'hide_all_billing_field_labels' );
function hide_all_billing_field_labels( $fields ) {
    foreach ( $fields['billing'] as $key => &$field ) {
        $field['label'] = ''; // Удаляем заголовок
    }

    if ( isset( $fields['order']['order_comments'] ) ) {
        $fields['order']['order_comments']['label'] = '';
    }

    return $fields;
}

//добавляем поле с примечанием в админку
add_action( 'woocommerce_checkout_create_order', 'plnt_save_order_comments_field', 10, 2 );
function plnt_save_order_comments_field( $order, $data ) {
	if ( isset( $_POST['order_comments'] ) ) {
		$order->update_meta_data( '_order_comments', sanitize_textarea_field( $_POST['order_comments'] ) );
	}
}

/*--------------------------------------------------------------
# Dont call me radio buttons
--------------------------------------------------------------*/

add_filter( 'woocommerce_checkout_fields', 'plnt_add_dontcallme_field_to_checkout' );

function plnt_add_dontcallme_field_to_checkout( $fields ) {
    $fields['billing']['dontcallme'] = array(
        'type'        => 'radio',
        'label'       => 'Не нужно звонков, напишите сразу в WhatsApp;)',
        'required'    => true,
        'class'       => array( 'form-row dontcallme' ),
        'options'     => array(
            'Да' => 'Да',
            'Нет'   => 'Нет',
        ),
        'default'     => 'Нет',
    );

    $fields['billing']['dontcallme']['priority'] = 30;

    return $fields;
}

// // сохряняем новое поле в заказе


add_action( 'woocommerce_checkout_update_order_meta', 'plnt_save_dontcallme_fields', 25 );

function plnt_save_dontcallme_fields( $order_id ){

    if( ! empty( $_POST[ 'dontcallme' ] ) ) {
        update_post_meta( $order_id, 'dontcallme', sanitize_text_field( $_POST[ 'dontcallme' ] ) );
    }
}

// // добавляем поле в админку

add_action( 'woocommerce_admin_order_data_after_billing_address', 'plnt_print_doncallme_field_value', 20 );

function plnt_print_doncallme_field_value( $order ){

    $method = get_post_meta( $order->get_id(), 'dontcallme', true );

    echo '<div class="address">
        <p' . ( ! $method ? ' class="none_set"' : '' ) . '>
            <strong>Не нужно звонков, напишите сразу в WhatsApp;)</strong>
            ' . ( $method ? $method : 'Не указан.' ) . '
        </p>
    </div>';
}


// // добавляем новые поля в письма

add_filter( 'woocommerce_get_order_item_totals', 'plnt_dontcallme_field_in_email', 20, 2 );
    
function plnt_dontcallme_field_in_email( $rows, $order ) {

    // удалите это условие, если хотите добавить значение поля и на страницу "Заказ принят"
    // if( is_order_received_page() ) {
    // 	return $rows;
    // }

    $rows[ 'dontcallme' ] = array(
        'label' => 'Не нужно звонков, напишите сразу в WhatsApp;)',
        'value' => get_post_meta( $order->get_id(), 'dontcallme', true )
    );

    return $rows;

}

/*--------------------------------------------------------------
# INN field
--------------------------------------------------------------*/

add_filter( 'woocommerce_checkout_fields', 'plnt_add_inn_field_to_checkout' );

function plnt_add_inn_field_to_checkout( $fields ) {
    $fields['order']['additional_inn'] = array(
        'type'        => 'text',
        'label'       => '',
        'placeholder' => 'ИНН (для оплаты по счету)',
        'required'    => false,
        'class'       => array( 'form-row inn' )
    );

    return $fields;
}

// // сохряняем новое поле в заказе

add_action( 'woocommerce_checkout_update_order_meta', 'plnt_save_inn_fields', 25 );

function plnt_save_inn_fields( $order_id ){

    if( ! empty( $_POST[ 'additional_inn' ] ) ) {
        update_post_meta( $order_id, 'additional_inn', sanitize_text_field( $_POST[ 'additional_inn' ] ) );
    }
}

// // добавляем поле в админку

add_action( 'woocommerce_admin_order_data_after_billing_address', 'plnt_print_inn_field_value', 20 );

function plnt_print_inn_field_value( $order ){

    $method = get_post_meta( $order->get_id(), 'additional_inn', true );

    echo '<div class="address">
        <p' . ( ! $method ? ' class="none_set"' : '' ) . '>
            <strong>ИНН (для оплаты по счету)</strong>
            ' . ( $method ? $method : 'Не указан.' ) . '
        </p>
    </div>';
}


// // добавляем новые поля в письма

add_filter( 'woocommerce_get_order_item_totals', 'plnt_inn_field_in_email', 20, 2 );
    
function plnt_inn_field_in_email( $rows, $order ) {

    // удалите это условие, если хотите добавить значение поля и на страницу "Заказ принят"
    // if( is_order_received_page() ) {
    // 	return $rows;
    // }

    $rows[ 'additional_inn' ] = array(
        'label' => 'ИНН (для оплаты по счету)',
        'value' => get_post_meta( $order->get_id(), 'additional_inn', true )
    );

    return $rows;

}

/*--------------------------------------------------------------
# Billing adress field
--------------------------------------------------------------*/
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

    add_action( 'woocommerce_checkout_update_order_meta', 'true_save_combined_address' );
    function true_save_combined_address( $order_id ) {
        $fields = [];

        if ( ! empty( $_POST['billing_address_2'] ) ) {
            $fields[] = 'квартира ' . sanitize_text_field( $_POST['billing_address_2'] );
        }
        if ( ! empty( $_POST['billing_address_3'] ) ) {
            $fields[] = 'подъезд ' . sanitize_text_field( $_POST['billing_address_3'] );
        }
        if ( ! empty( $_POST['billing_address_4'] ) ) {
            $fields[] = 'этаж ' . sanitize_text_field( $_POST['billing_address_4'] );
        }
        if ( ! empty( $_POST['billing_address_5'] ) ) {
            $fields[] = sanitize_text_field( $_POST['billing_address_5'] );
        }

        $address = implode( ', ', $fields );

        update_post_meta( $order_id, '_billing_address_2', $address );
    }

/*--------------------------------------------------------------
# T Bank
--------------------------------------------------------------*/
    add_filter('woocommerce_available_payment_gateways', 'plnt_move_tbank_up');
    function plnt_move_tbank_up($available_gateways) {
        if (isset($available_gateways['tbank'])) {
            $tbank = $available_gateways['tbank'];
            unset($available_gateways['tbank']);
            $new_gateways = ['tbank' => $tbank] + $available_gateways;
            return $new_gateways;
        }

        return $available_gateways;
    }

/*--------------------------------------------------------------
# Payment method
--------------------------------------------------------------*/
function check_is_plants_in_cart() {
  global $plants_cat_id;
  $isPlants = false;
  if( is_checkout( ) && ! is_wc_endpoint_url()) {
  foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product = $cart_item['data'];
        $idCats = $product->get_category_ids();
        
        if (in_array($plants_cat_id, $idCats)) {
            $isPlants = true;
            break;
        }	
    }
  }

  return $isPlants;
}

add_filter( 'woocommerce_gateway_title', function( $title, $gateway_id ) {
    if ( $gateway_id === 'cod' & !check_is_plants_in_cart()) {
        $title = 'Оплату после подтверждения заказа менеджером'; // 👉 своё название
    }
    return $title;
}, 10, 2 );

add_filter( 'woocommerce_gateway_description', function( $description, $gateway_id ) {
    if ( $gateway_id === 'cod' & !check_is_plants_in_cart()) {
        $description = 'Наш менеджер свяжется с Вами после оформления заказа. После вашего подтверждения мы пришлём ссылку на оплату картой.';
    }
    return $description;
}, 10, 2 );


/*--------------------------------------------------------------
# Thankyou page & email
--------------------------------------------------------------*/

    // уведомление Спасибо за заказ

    add_filter( 'woocommerce_thankyou_order_received_text', 'plnt_custom_ty_msg' );

        function plnt_custom_ty_msg ( $thank_you_msg ) {
            $emoji = '<img draggable="false" role="img" class="emoji" alt="😉" height="20px" width="20px" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f609.svg">';
            $thank_you_msg =  'Спасибо за ваш заказ! Наши менеджеры пляшут от радости! Как закончат танцевать, сразу вам перезвонят ' . $emoji ;

        return $thank_you_msg;
    }

    // Чтобы в письмах выводилась стомость доставки 0 руб при самовывозе
    add_filter( 'woocommerce_get_order_item_totals', function( $totals, $order ) {
        if ( isset( $totals['shipping'] ) ) {
            // Узнаём выбранный метод
            $methods = $order->get_shipping_methods();
            if ( $methods ) {
                $method = current( $methods ); // WC_Order_Item_Shipping
                $method_id = $method->get_method_id(); // например: local_pickup
                $shipping_total = (float) $order->get_shipping_total();
                $shipping_tax   = (float) $order->get_shipping_tax();

                // Для самовывоза (local_pickup) показывать 0 ₽ вместо названия
                if ( false !== stripos( $method_id, 'local_pickup' ) ) {
                    // Если хотите просто "0 ₽"
                    $totals['shipping']['value'] = wc_price( 0, [ 'currency' => $order->get_currency() ] );

                    // Если хотите "Самовывоз — 0 ₽", раскомментируйте:
                    // $totals['shipping']['value'] = sprintf(
                    //     '%s — %s',
                    //     wp_kses_post( $method->get_name() ),
                    //     wc_price( 0, [ 'currency' => $order->get_currency() ] )
                    // );
                } else {
                    // Для других методов можно принудительно показать числовую стоимость
                    // (учитывая налог, если нужно)
                    $amount = $shipping_total + $shipping_tax;
                    $totals['shipping']['value'] = wc_price( $amount, [ 'currency' => $order->get_currency() ] );
                }
            }
        }
        return $totals;
    }, 10, 2 );

    //правим стили фотографии товара в письмах
    add_filter( 'woocommerce_order_item_thumbnail', function( $image_html, $item ) {
        $product = is_callable( [$item, 'get_product'] ) ? $item->get_product() : null;
        if ( ! $product ) {
            return $image_html;
        }

        $attachment_id = $product->get_image_id();
        $src = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';

        if ( ! $src ) {
            return $image_html;
        }

        // Возвращаем «чистый» <img> 48×48, без margin-right, с выравниванием по верху
        return sprintf(
            '<img src="%s" alt="%s" width="48" height="48" style="display:block;vertical-align:top;border:0;outline:0;text-decoration:none;margin-right:0" />',
            esc_url( $src ),
            esc_attr( $product->get_name() )
        );
    }, 999, 2 );




/*--------------------------------------------------------------
# Additional fields for admin
--------------------------------------------------------------*/
    add_action( 'add_meta_boxes', 'plnt_add_custom_fields_meta_box' );
    function plnt_add_custom_fields_meta_box() {
        add_meta_box(
            'plnt_custom_order_fields',
            'Дополнительные поля заказа',
            'plnt_render_custom_fields_meta_box',
            'shop_order',
            'side',
            'default'
        );
    }

    function plnt_render_custom_fields_meta_box( $post ) {
        $fields = [
            'plnt_client_status'      => ['Статус клиента', 'select'],
            'plnt_client_origin'  => ['Откуда пришел клиент', 'select'],
            'plnt_payment_method'      => ['Способ оплаты', 'select'],
            'plnt_paid'     => ['Оплачен?', 'select'],
            'plnt_comment'     => ['Комментарий', 'text'],
        ];

        $select_field_options = [
            'plnt_payment_method' => [
                'site'   => 'На сайте',
                'ssylka'   => 'По ссылке',
                'nal'    => 'Наличными',
                'perevod' => 'Переводом',
                'schet' => 'По счету',
                'other' => 'Иное',
            ],
            'plnt_client_status' => [
                'new' => 'Новый',
                'requring'  => 'Повтор',
                'other' => 'Иное',
            ],
            'plnt_client_origin' => [
                'site' => 'Сайт',
                'street' => 'С улицы',
                'avito' => 'Авито',
                'one-click' => 'Один клик',
                'preorder'  => 'Предзаказ',
                'messenger' => 'Мессенджер',
                'mail' => 'Письмо',
                'call' => 'Звонок',
                'other' => 'Иное',
            ],
            'plnt_paid' => [
                'yes' => 'Да',
                'no'  => 'Нет',
                'other' => 'Иное',
            ],
        ];

        foreach ( $fields as $key => $field ) {
            $label = $field[0];
            $type  = $field[1];
            $value = get_post_meta( $post->ID, '_' . $key, true );

            echo '<p><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label><br />';

            if ( $type === 'select' ) {
                echo '<select name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" style="width:100%">';
                echo '<option value="">— Выберите —</option>';
                if ( isset( $select_field_options[ $key ] ) ) {
                    foreach ( $select_field_options[ $key ] as $option_value => $option_label ) {
                        $selected = selected( $value, $option_value, false );
                        echo '<option value="' . esc_attr($option_value) . '" ' . $selected . '>' . esc_html($option_label) . '</option>';
                    }
                }
                echo '</select>';
            } else {
                echo '<input type="text" style="width:100%" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
            }

            echo '</p>';
        }
    }

    function plnt_save_custom_fields_meta_box( $post_id ) {
        $fields = [
            'plnt_payment_method',
            'plnt_client_status',
            'plnt_client_origin',
            'plnt_paid',
            'plnt_comment',
        ];

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }
    }

    add_action( 'save_post_shop_order', 'plnt_save_custom_fields_meta_box', 20, 2 );





