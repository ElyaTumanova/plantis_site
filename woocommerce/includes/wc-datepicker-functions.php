<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Conditional Show hide checkout fields based on chosen shipping methods*/

add_action( 'wp_footer', 'new_custom_checkout_field_script' );
function new_custom_checkout_field_script() {
	
	if( !is_page( 'checkout' ) ) {
		return;
	}

    // HERE your shipping methods rate IDs
    global $local_pickup;
    //TO BE DELETED
	// global $urgent_delivery_inMKAD;
	// global $urgent_delivery_outMKAD;
	// global $urgent_delivery_inMKAD_small;
	// global $urgent_delivery_outMKAD_small;

    global $payment_inn_chekbox;
    global $inn_field;

    $required_text = esc_attr__( 'required', 'woocommerce' );
    $required_html = '<abbr class="required" title="' . $required_text . '">*</abbr>';
    ?>
    <script>
        //все переменные
        let checkoutForm = document.querySelector('form[name="checkout"]');
    
		let deliveryDate = document.querySelector('#datepicker_field');
		let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
        let deliveryIntervalInput = document.querySelector('input[name=additional_delivery_interval]');

        let addressFields = document.querySelector('#billing_address_1_field');
        let additionalAddress = document.querySelector('.additional-address-field');

        let inn_field = document.querySelector('#<?php echo $inn_field; ?>');

        let localPickup = '<?php echo $local_pickup; ?>';

        //TO BE DELETED
        //let urgentPickup1 = '<?php //echo $urgent_delivery_inMKAD; ?>';
        //let urgentPickup2 = '<?php //echo $urgent_delivery_outMKAD; ?>';
        //let urgentPickup3 = '<?php //echo $urgent_delivery_inMKAD_small; ?>';
        //let urgentPickup4 = '<?php //echo $urgent_delivery_outMKAD_small; ?>';
        //let urgentPickups = [urgentPickup1, urgentPickup2, urgentPickup3, urgentPickup4];

        let checkedShippingMethod = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]').value;

        /*--------------------------------------------------------------
        # Hiding fields
        --------------------------------------------------------------*/
        function plnt_hide_checkout_fields(event){
            //console.log('hi plnt_hide_checkout_fields');
            //console.log(deliveryIntervalInput)
            // if (event) {console.log(event)};
            if(event && event.target.className == "shipping_method") {
                // console.log(event);
                checkedShippingMethod = event.target.value;
            }

            //TO BE DELETED
            // if (urgentPickups.includes(checkedShippingMethod))  
            // {
            //     if (deliveryDate) {deliveryDate.classList.add('d-none')};
            //     if (deliveryInterval) {deliveryInterval.classList.add('d-none')};
            //     if (deliveryIntervalInput) {deliveryIntervalInput.checked = false};
            //     if (addressFields) {addressFields.classList.remove('d-none');}
            //     if (additionalAddress) {additionalAddress.classList.remove('d-none');}
            // } else 

            if ( checkedShippingMethod == localPickup) {
                if (deliveryDate) {deliveryDate.classList.remove('d-none')};
                if (deliveryInterval) {deliveryInterval.classList.add('d-none')};
                if (deliveryIntervalInput) {deliveryIntervalInput.checked = false};
                if (addressFields) {addressFields.classList.add('d-none');}
                if (additionalAddress) {additionalAddress.classList.add('d-none');}
            } else {
                if (deliveryDate) {deliveryDate.classList.remove('d-none')};
                if (deliveryInterval) {deliveryInterval.classList.remove('d-none')};
                if (addressFields) {addressFields.classList.remove('d-none');}
                if (additionalAddress) {additionalAddress.classList.remove('d-none');}
            }

            if(event && event.target.id == "payment_method_cheque") {
                //console.log(event);
                if (inn_field) {inn_field.classList.remove('d-none')};
            } else {
                if (inn_field) {inn_field.classList.add('d-none')};
            };
        }

        plnt_hide_checkout_fields(event);
        
        checkoutForm.addEventListener('change', plnt_hide_checkout_fields);

        /*--------------------------------------------------------------
        # Datepicker
        --------------------------------------------------------------*/
        // Utility function for datepicker init
        <?php $weekend_string = carbon_get_theme_option('weekend');?>
        //выходной
        let weekend_str = '<?php echo $weekend_string; ?>';
        let weekend_arr = weekend_str.split(',');
        // console.log(weekend_arr);
        let weekend = [];
        weekend_arr.forEach(element => {
            weekend.push(new Date(element));
        });

        let date = new Date();
        let hour = date.getHours();

        console.log(hour);

        function datepicker_options () {  
            //console.log('hi datepicker_options');     

            //определяем первую доступную дату
            //let startDate = new Date();
            let startDate;

            // определяем дату, которая будет выбрана по умолчанию
            let selectedDate = [];

            
            if (hour >= 20) {  
                startDate = date.setDate(date.getDate() + 1);
                //selectedDate = startDate;
            } else {
                startDate = date;
                //selectedDate = startDate + 1;                   
            };

            // тут ошибка??
            if (hour >= 18 && hour <20) {  
                selectedDate = new Date().setDate(startDate.getDate() + 2);
            } else {
                selectedDate = new Date().setDate(startDate.getDate() + 1);       
            };

            //console.log('initial');
            //console.log(new Date(selectedDate));

            //очищаем дату для срочной доставки  TO BE DELETED
            // if (urgentPickups.includes(checkedShippingMethod)) {
            //     selectedDate = [];
            // };

            // проверяем, что первая доступная дата не попадает на выходной
            const weekendTimeStamps = weekend.map(function (element) {
                return element.getTime();
            });
            let isSelectedDayWeekend = false;
            function checkSelectedDay (checkDate) {
                let newSelectedDate = checkDate;
                isSelectedDayWeekend = weekendTimeStamps.includes((new Date(checkDate)).setHours(3,0,0,0));
                if (isSelectedDayWeekend) {
                    newSelectedDate = date.setDate(new Date(checkDate).getDate() + 1);
                    // console.log('new date')
                    // console.log(new Date(newSelectedDate));
                    return checkSelectedDay (newSelectedDate);
                }
                // console.log('after if');
                // console.log(new Date(newSelectedDate));
                return selectedDate = newSelectedDate;
            };

            checkSelectedDay (selectedDate);
            //console.log('finally');
            //console.log(new Date(selectedDate));

            //кнопка ОК
            let button = {
                content: 'OK',
                className: 'custom-button-classname',
                onClick: (datepicker) => {
                    datepicker.hide();
                }
            }

            // datepicker options
            let datePickerOpts = {
                selectedDates: selectedDate,
                minDate: startDate,
                maxDate: (function(){
                    let date = new Date();
                    date.setDate(date.getDate() + 30);
                    return date;
                })(),
                isMobile: true,
                //autoClose: true,

                buttons: [button] 
            }

            return datePickerOpts;
        }

        // Datepicker init
        let datepickerCal;
        let datePickerOpts;

        let today = `${new Date().getDate()}.${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;
        let tomorrow = `${new Date().getDate() + 1}.${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;

        let isUrgent = '0';

        function datepicker_init () {
            //console.log('hi datepicker_init');

            //определяем параметры календаря
            datePickerOpts = datepicker_options ();
            datepickerCal.update(datePickerOpts);
            if (weekend) {
                datepickerCal.disableDate(weekend);
            }

            
            // проверяем срочная ли доставка и запускам аякс
            let selectedDateFormatted = `${new Date(datePickerOpts.selectedDates).getDate()}.${new Date(datePickerOpts.selectedDates).getUTCMonth() + 1}.${new Date(datePickerOpts.selectedDates).getUTCFullYear()}`;
            if (selectedDateFormatted == today || selectedDateFormatted == tomorrow && hour >= 18 ) {
                isUrgent = '1'
            } else {
                isUrgent = '0'
            }
            plntAjaxGetUrgent();           
        }

        setTimeout(() => {
            datepickerCal = new AirDatepicker('#datepicker', {
                onSelect({date, formattedDate, datepicker}) {
                    //console.log('hi date');
                    
                    // проверяем срочная ли доставка и запускам аякс
                    if (formattedDate == today || formattedDate == tomorrow && hour >= 18) {
                        isUrgent = '1'
                    } else (
                        isUrgent = '0'
                    );
                    plntAjaxGetUrgent();
                }});

            datepicker_init ();
        }, 1000);  
   
        checkoutForm.addEventListener('change', onChangeShippingMethod);

        function onChangeShippingMethod(event) {
            if(event && event.target.className == "shipping_method") {
                datepicker_init();
            }
        }

    </script>
    <?php
}
