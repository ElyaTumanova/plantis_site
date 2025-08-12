/*--------------------------------------------------------------
# Hiding fields
--------------------------------------------------------------*/

    //все переменные

    let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
    let addressFields = document.querySelector('#billing_address_1_field');
    let additionalAddress = document.querySelector('.additional-address-field');
    let innField = document.querySelector('#additional_inn');

    function hideInterval() {
        deliveryInterval.classList.add('d-none');
        deliveryIntervalInput.forEach((input)=>{
            input.checked = false;
        })
    }

    function showInterval() {
        deliveryInterval.classList.remove('d-none');
        if (checkedInterval == '') {
            deliveryIntervalInput[0].checked = true;
        }
    }

    function plnt_hide_checkout_fields(event){
        //console.log('hi plnt_hide_checkout_fields');
        if (deliveryInterval) {
            // console.log(deliveryInterval);
            // console.log('isHideInterval', isHideInterval);
            if (isBackorder || isTreezBackorders) {
                hideInterval()
            } else { 
                if ( checkedShippingMethod == localPickupId || checkedShippingMethod == deliveryPochtaId) {
                    hideInterval()
                } else {
                    if (isUrgent == '1') {
                        hideInterval();
                    }
                    if (isUrgent == '0') {
                        showInterval()
                    }
                    // if (!isHideInterval) {
                    //     showInterval()
                    // }
                }
            }
        }

        //for delivery dates
        if (deliveryDates) {
            if (isBackorder || isTreezBackorders) {
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
        if (innField) {
            console.log(document.querySelector('.wc_payment_methods input[checked="checked"]').value);
            if(event && event.target.id == "payment_method_cheque") {
                innField.classList.remove('d-none');
            } else {
                if(event.target.id == "payment_method_tbank" 
                    || event.target.id == "payment_method_cop"
                    || event.target.id == "payment_method_cod"
                    || document.querySelector('.wc_payment_methods input[checked="checked"]').value != 'cheque'
                ) 
                {
                    innField.classList.add('d-none')
                    innField.value = ''
                }
            };
        }        

        // for holidays
        if (isHoliday === '1') {
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
        if (isHoliday === '0') {
            deliveryIntervalInput.forEach(el =>{
                el.classList.remove('d-none');
            })
            deliveryIntervalLabels.forEach(el =>{
                el.classList.remove('d-none');
            })
        }
    }

    if(checkoutForm) {
        document.addEventListener('DOMContentLoaded', plnt_hide_checkout_fields )
        //plnt_hide_checkout_fields(event);
        
        checkoutForm.addEventListener('change', plnt_hide_checkout_fields);
    }

    