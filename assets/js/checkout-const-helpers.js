//все переменные
let isUrgent;
let isLate;
let isHideInterval;
let urgentDelivery;
let isShortDay; //для короткого рабочего дня, чтобы скрыть поздний интервал доставки
let shortdays = [new Date(2025-03-30)]; //format YYYY-MM-DD

console.log(shortdays);

let deliveryCost;
let deliveryCostUrg;

let deliveryIntervalsInfo = []
let shippingMethodValues = [];
let checkedShippingMethodInput;
let checkedShippingMethod;

//checkot form fields
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
let deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');

let deliveryDates = document.querySelector('.delivery_dates');
let deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
let addressFields = document.querySelector('#billing_address_1_field');
let additionalAddress = document.querySelector('.additional-address-field');
let innField = document.querySelector('#additional_inn');


function getDeliveryCosts(shippingValue) {

    if(shippingValue == deliveryInMKAD || shippingValue == deliveryInMKADUrg) {
        deliveryCost = deliveryCostInMkad;
        deliveryCostUrg = urgentDelivery ? deliveryCostInMkadUrg : deliveryCostInMkad;
    }
    if(shippingValue == deliveryOutMKAD || shippingValue == deliveryOutMKADUrg) {
        deliveryCost = deliveryCostOutMkad;
        deliveryCostUrg = urgentDelivery ? deliveryCostOutMkadUrg : deliveryCostOutMkad;
    }
  
    if(shippingValue == deliveryInMKADSmall || shippingValue == deliveryInMKADSmallUrg) {
        deliveryCost = deliveryCostInMkadSmall;
        deliveryCostUrg = urgentDelivery ? deliveryCostInMkadSmallUrg : deliveryCostInMkadSmall;
    }
    if(shippingValue == deliveryOutMKADSmall || shippingValue == deliveryOutMKADSmallUrg) {
        deliveryCost = deliveryCostOutMkadSmall;
        deliveryCostUrg = urgentDelivery ? deliveryCostOutMkadSmallUrg : deliveryCostOutMkadSmall;
    }
    if(shippingValue == deliveryInMKADLarge || shippingValue == deliveryInMKADLargeUrg) {
        deliveryCost = deliveryCostInMkadLarge;
        deliveryCostUrg = urgentDelivery ? deliveryCostInMkadLargeUrg : deliveryCostInMkadLarge;
    }
    if(shippingValue == deliveryOutMKADLarge || shippingValue == deliveryOutMKADLargeUrg) {
        deliveryCost = deliveryCostOutMkadLarge;
        deliveryCostUrg = urgentDelivery ? deliveryCostOutMkadLargeUrg : deliveryCostOutMkadLarge;
    }
    if(shippingValue == deliveryInMKADMedium || shippingValue == deliveryInMKADMediumUrg) {
        deliveryCost = deliveryCostInMkadMedium;
        deliveryCostUrg = urgentDelivery ? deliveryCostInMkadMediumUrg : deliveryCostInMkadMedium;
    }
    if(shippingValue == deliveryOutMKADMedium || shippingValue == deliveryOutMKADMediumUrg) {
        deliveryCost = deliveryCostOutMkadMedium;
        deliveryCostUrg = urgentDelivery ? deliveryCostOutMkadMediumUrg : deliveryCostOutMkadMedium;
    }
    
    console.log(deliveryCost);
    console.log(deliveryCostUrg);
}

function getCheckedShippingMethod (){
    checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
    return checkedShippingMethodInput.value;
}