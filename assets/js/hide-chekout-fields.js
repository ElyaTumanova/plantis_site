/*--------------------------------------------------------------
# Hiding fields
--------------------------------------------------------------*/

    //все переменные
    let checkoutForm = document.querySelector('form[name="checkout"]');
    let deliveryDates = document.querySelector('.delivery_dates');
    let deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
    let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
    let addressFields = document.querySelector('#billing_address_1_field');
    let additionalAddress = document.querySelector('.additional-address-field');
    let innField = document.querySelector('#additional_inn');

    function plnt_hide_checkout_fields(event){
        
        //console.log('hi plnt_hide_checkout_fields');
        if(event && event.target.className == "shipping_method") {
            // console.log(event);
            checkedShippingMethod = event.target.value;
        }

        //for delivery intervals
        if (deliveryInterval) {
            if (isBackorder) {
                deliveryInterval.classList.add('d-none');
                deliveryIntervalInput.forEach((input)=>{
                    input.checked = false;
                })
            } else { 
                if ( checkedShippingMethod == localPickupId) {
                    deliveryInterval.classList.add('d-none');
                    deliveryIntervalInput.forEach((input)=>{
                        input.checked = false;
                    });
                } else {
                    if (isUrgent == '1' && isHideInterval) {
                        deliveryInterval.classList.add('d-none');
                        deliveryIntervalInput.forEach((input)=>{
                            input.checked = false;
                        });
                    }
                    if (isUrgent == '0') {
                        deliveryInterval.classList.remove('d-none');
                    }
                    if (!isHideInterval) {
                        deliveryInterval.classList.remove('d-none');
                    }
                }
            }
        }

        //for delivery dates
        if (deliveryDates) {
            if (isBackorder) {
                deliveryDates.classList.add('d-none');
                deliveryDatesInput.forEach((input)=>{
                    input.checked = false;
                })
            } 
        }

        //for address 
        if (checkedShippingMethod == localPickupId) {
            if (addressFields) {addressFields.classList.add('d-none');}
            if (additionalAddress) {additionalAddress.classList.add('d-none');}
        } else {
            if (addressFields) {addressFields.classList.remove('d-none');}
            if (additionalAddress) {additionalAddress.classList.remove('d-none');}
        }
        
        // for INN
        if(event && event.target.id == "payment_method_cheque") {
            if (innField) {innField.classList.remove('d-none')};
        } else {
            if (innField) {innField.classList.add('d-none')};
        };

        // for short days
        if (isShortDay === '1') {
            deliveryIntervalInput.forEach(el =>{
                if(el.defaultValue !== '11:00 - 16:00') {
                    el.classList.add('d-none');
                }
            })
            deliveryIntervalLabels.forEach(el =>{
                if(el.htmlFor !== 'additional_delivery_interval_11:00 - 16:00') {
                    el.classList.add('d-none');
                }
            })
        }
        if (isShortDay === '0') {
            deliveryIntervalInput.forEach(el =>{
                el.classList.remove('d-none');
            })
            deliveryIntervalLabels.forEach(el =>{
                el.classList.remove('d-none');
            })
        }
    }

    if(checkoutForm) {
        plnt_hide_checkout_fields(event);
        
        checkoutForm.addEventListener('change', plnt_hide_checkout_fields);
    }

    