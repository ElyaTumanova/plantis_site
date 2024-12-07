//все переменные

//let deliveryDate = document.querySelector('#datepicker_field');
let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
console.log(deliveryIntervalInput);

let addressFields = document.querySelector('#billing_address_1_field');
let additionalAddress = document.querySelector('.additional-address-field');

let innField = document.querySelector('#additional_inn');

/*--------------------------------------------------------------
# Hiding fields
--------------------------------------------------------------*/

function plnt_hide_checkout_fields(event){
    
    //console.log('hi plnt_hide_checkout_fields');
    if(event && event.target.className == "shipping_method") {
        // console.log(event);
        checkedShippingMethod = event.target.value;
    }
    
  
    if ( checkedShippingMethod == localPickupId) {
        if (deliveryInterval) {deliveryInterval.classList.add('d-none')};
        if (deliveryIntervalInput) {
            deliveryIntervalInput.forEach((input)=>{
                input.checked = false;
        })};
        if (addressFields) {addressFields.classList.add('d-none');}
        if (additionalAddress) {additionalAddress.classList.add('d-none');}
    } else {
        if (isUrgent == '1') {
            if (deliveryInterval) {deliveryInterval.classList.add('d-none')};
            if (deliveryIntervalInput) {
                deliveryIntervalInput.forEach((input)=>{
                    input.checked = false;
            })};
        }
    
        if (isUrgent == '0') {
            if (deliveryInterval) {deliveryInterval.classList.remove('d-none')};
        }
        if (addressFields) {addressFields.classList.remove('d-none');}
        if (additionalAddress) {additionalAddress.classList.remove('d-none');}
    }

    if(event && event.target.id == "payment_method_cheque") {
        if (innField) {innField.classList.remove('d-none')};
    } else {
        if (innField) {innField.classList.add('d-none')};
    };
}

if(checkoutForm) {
    plnt_hide_checkout_fields(event);
    
    checkoutForm.addEventListener('change', plnt_hide_checkout_fields);
}
