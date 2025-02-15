<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*--------------------------------------------------------------
Contents
# Backorders
# Checkout page adjustments
# Billing adress field
# Delivery date & Interval fields
# Notifications
# Treez notifications
# Thankyou page
--------------------------------------------------------------*/

/*--------------------------------------------------------------
# Backorders
--------------------------------------------------------------*/

    // проверка наличия товара Backorders

    function plnt_is_backorder() {
        $qty = 0; // обязательно сначала ставим 0
        $isbackorders = false;
        
        if( is_checkout( ) && ! is_wc_endpoint_url()) {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $_product = $cart_item['data'];
                $_product_id = $_product->id;
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
                unset( $available_gateways['tinkoff'] ); //to do change to tinkoff
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
    //         После оформления заказа мы свяжемся с вами в <a href="https://plantis.shop/contacts/">рабочее время</a> и согласуем время доставки.
    //         <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a> <br>
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
                Вы выбрали крупногабаритный товар. Стоимость доставки увеличена. <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a></td></tr>';
                break; // Stop the loop
            } 	
        }
    }


    // сообщение о крупногабартной доставке (с машинкой)

    add_action('plnt_large_delivery_notice', 'plnt_large_delivery_notice');

    function plnt_large_delivery_notice() {
      
        // вес товаров в корзине
        $cart_weight = WC()->cart->cart_contents_weight;

        if ($cart_weight >= 11) {
            echo '<div class=large_delivery_notice>
            <img class=large_delivery_img src="https://plantis.shop/wp-content/uploads/2024/08/car.svg" alt="car">
            <p>Для заказа предусмотрена крупногабаритная доставка!</p></div>';
        }

        if (WC()->session->get('isHoliday' ) === '1') {
            $date = $_POST['date'];
            echo '<div class=large_delivery_notice>
            <p>'.$date.' доставка осуществляется по повышенному тарифу!</p></div>';
        }
        
    }

    
    //комментарий к выбранному способу доставки

    add_action( 'woocommerce_checkout_order_review', 'delivery_info', 10 );
   
    function delivery_info(){
        $min_small_delivery = carbon_get_theme_option('min_small_delivery');
        $shipping_costs = plnt_get_shiping_costs();
        global $delivery_courier;
        global $delivery_long_dist;
        global $urgent_deliveries;
        global $normal_deliveries;
        global $local_pickup;

        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        date_default_timezone_set('Europe/Moscow');
        $hour = date("H");

        echo '<div class="checkout__comment">';

        //Срочная доставка "День в день"

        if ( in_array($chosen_methods[0],$urgent_deliveries) ) {
            if($hour < 18) {
                echo '<div class="checkout__text checkout__text_urgent">
                    Срочную доставку можно оформить до 18:00. 
                    После оформления заказа мы свяжемся с вами для его подтверждения. 
                    <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки и самовывоза.</a></div>';
            }
        //при оформлении после 20:00-00:00 текущего дня
            if($hour >= 20) {
                echo '<div class="checkout__text checkout__text_normal-late">
                    При оформлении после 20:00 доставки на следующий день стоимость рассчитывается по тарифу срочной доставки. 
                    После оформления заказа мы свяжемся с вами в рабочее время для его подтверждения.</div>';
            }
        }
        
        //Доставка в пределах МКАД 
        
        if ( in_array($chosen_methods[0],$normal_deliveries) ) {
            //при оформлении до 20:00
            if ($hour < 20) {
                echo '<div class="checkout__text checkout__text_normal">
                    После оформления заказа мы свяжемся с вами в рабочее время с 10:00 до 20:00 для его подтверждения. 
                    <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки и самовывоза.</a></div>';
            //при оформлении после 20:00-00:00 текущего дня
            } else {
                echo '<div class="checkout__text checkout__text_normal-late">
                    При оформлении после 20:00 доставки на следующий день стоимость рассчитывается по тарифу срочной доставки. 
                    После оформления заказа мы свяжемся с вами в рабочее время для его подтверждения.</div>';
            }
        }
        
        //Доставка заказов до 2500 рублей
        if (WC()->cart->subtotal < $min_small_delivery) {
            if(!array_key_exists($delivery_courier,$shipping_costs)) {
                echo '<div class="checkout__text checkout__text_small-order">
                При заказе на сумму менее '.$min_small_delivery,' рублей стоимость доставки увеличена. 
                <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки.</a></div';
            } else if ($delivery_courier == $chosen_methods[0]) {
                echo '<div class="checkout__text checkout__text_small-order-holiday">
                В связи с высокой загрузкой курьеров в предпраздничные дни заказы стоимостью до '.$min_small_delivery,' рублей доставляются в любой день по тарифу курьерской службы. 
                Мы свяжемся с Вами после оформления заказа и произведем расчет стоимости доставки. 
                Также, вы можете самостоятельно бесплатно забрать заказ в нашем магазине, оформив самовывоз.
                <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                </div>';
            }  
        }

        //Доставка заказов свыше 5км от МКАД
        if ( $delivery_long_dist == $chosen_methods[0]) {
            echo '<div class="checkout__text checkout__text_long-dist">
                Доставка на расстояние свыше 5км от МКАД осуществляется по тарифам курьерской службы. 
                Мы свяжемся с Вами после оформления заказа в рабочее время с 10:00 до 20:00 и рассчитаем стоимость доставки.
                <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки и самовывоза.</a>
                </div>';
        }

        //Самовывоз
        if ( $local_pickup == $chosen_methods[0]) {
            echo '<div class="checkout__text checkout__text_local-pickup">
                После оформления заказа мы свяжемся с вами в рабочее время с 10:00 до 20:00 для его подтверждения.
                <a href="https://plantis.shop/delivery/">Подробнее об условиях доставки и самовывоза.</a>
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


/*--------------------------------------------------------------
# Treez notifications
--------------------------------------------------------------*/

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

    //уведомление о минимальной сумме заказа для Treez
    //add_action( 'woocommerce_checkout_order_review', 'min_amount_for_treez_info', 40 );
    add_action( 'woocommerce_review_order_before_shipping', 'min_amount_for_treez_info', 10 ); //встраиваем в таблицу, использовать теги таблицы

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
            echo '<tr> <td colspan="2" class="checkout__text checkout__text_treez checkout__text_alarm">
            Минимальная сумма заказа для кашпо и искусственных растений Treez <span>'.$min_treez_delivery,'</span> рублей (без учета стоимости других товаров).</td></tr>';
        }   
        if( $products_min) {
            echo '<tr> <td colspan="2" class="checkout__text checkout__text_treez checkout__text_alarm">
            Оплатить заказ с кашпо и искусственными растениями Treez можно будет после подтверждения их наличия. Наш менеджер свяжется с Вами после оформления заказа.</td></tr>';
        }   
    }

    //отключаем способ оплаты для Treez
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


