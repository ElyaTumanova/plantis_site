//все переменные

let deliveryDate = document.querySelector('#datepicker_field');
let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
let deliveryIntervalInput = document.querySelector('input[name=additional_delivery_interval]');

let addressFields = document.querySelector('#billing_address_1_field');
let additionalAddress = document.querySelector('.additional-address-field');

let inn_field = document.querySelector('#additional_inn');

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

    if ( checkedShippingMethod == localPickupId) {
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
