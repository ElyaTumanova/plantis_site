let isUrgent;
let isLate = 0;
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryDatesInfo = [];
let deliveryIntervalsInfo = []
let shippingMethodValues = [];
let checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
let checkedShippingMethod;

let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
//let deliveryInterval = document.querySelectorAll('#additional_delivery_interval_field input');
let deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');
let today;

// console.log(deliveryLateMarkup);
// console.log(deliveryIntervalInput);
// console.log(deliveryIntervalLabels);


function plntChekUrgentDelivery() {
  //console.log('hi plntChekUrgentDelivery');
  deliveryDates[0].setAttribute('checked','checked');
  plntAjaxGetUrgent();
  console.log(isUrgent);
  

  deliveryDates.forEach((date) => {
    date.addEventListener('click', function(event){
      //console.log(event.target.value);
      if(event.target.value == today) {
        isUrgent = '1';
      } else {
        isUrgent = '0';
      }
     // console.log(isUrgent);
      plntAjaxGetUrgent();
      });
  })
};

function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        renderDeliveryDates(event.target.value);
        renderDeliveryIntervals(event.target.value);
        // console.log(event.target.value);
    }
}

function renderDeliveryDates(shippingValue) {
  // console.log(shippingValue);
  deliveryDatesInfo.forEach((info) => {
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == deliveryInMKAD || shippingValue == deliveryInMKADUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadUrg}₽` : `${deliveryCostInMkad}₽` ;
      }
      if(shippingValue == deliveryOutMKAD || shippingValue == deliveryOutMKADUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadUrg}₽` : `${deliveryCostOutMkad}₽` ;
      }
      if(shippingValue == deliveryInMKADSmall || shippingValue == deliveryInMKADSmallUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadSmallUrg}₽` : `${deliveryCostInMkadSmall}₽` ;
      }
      if(shippingValue == deliveryOutMKADSmall || shippingValue == deliveryOutMKADSmallUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadSmallUrg}₽` : `${deliveryCostOutMkadSmall}₽` ;
      }
      if(shippingValue == deliveryInMKADLarge || shippingValue == deliveryInMKADLargeUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadLargeUrg}₽` : `${deliveryCostInMkadLarge}₽` ;
      }
      if(shippingValue == deliveryOutMKADLarge || shippingValue == deliveryOutMKADLargeUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadLargeUrg}₽` : `${deliveryCostOutMkadLarge}₽` ;
      }
  })
}

function renderDeliveryIntervals(shippingValue) {
  // console.log(shippingValue);
  deliveryIntervalsInfo.forEach((info) => {
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == localPickupId || shippingValue == deliveryFreeId || shippingValue == deliveryCourierId || shippingValue == deliveryLongId) {
      } else {
        priceEl.innerHTML = info.for == `additional_delivery_interval_18:00 - 21:00` ? `+${deliveryLateMarkup}₽` : `+0₽` ;
      }
  })
}

function plntAjaxGetUrgent() {
  //console.log('hi ajax');
  //console.log(isUrgent);
  //let date = document.querySelector('#datepicker');
  
  //console.log(date);
  jQuery( function($){
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: {
                'action': 'get_urgent_shipping',
                'isUrgent': isUrgent,
            },
            success: function (result) {
                // Trigger refresh checkout
                $('body').trigger('update_checkout');
            }
        });
  });
};

function ajaxGetLateDelivery(event) {
  if(event.target.value == '18:00 - 21:00') {
    isLate = '1'
  } else {
    isLate = '0'
  }
  //console.log(isLate);

  jQuery( function($){
    $.ajax({
        type: 'POST',
        url: wc_checkout_params.ajax_url,
        data: {
            'action': 'get_late_shipping',
            'isLate': isLate,
        },
        success: function (result) {
            // Trigger refresh checkout
            $('body').trigger('update_checkout');
        }
    });
});
}

if (checkoutForm) {

  let hour = new Date().getHours();

  if (hour >= 18 && hour <20) {
    isUrgent = 0;
  } else {
    isUrgent = 1;
  }

  if (hour >= 20) {
    today = `${(new Date().getDate()< 10 ? '0' : '') + (new Date().getDate() + 1)}.${new Date().getUTCMonth() + 1}`;
  } else {
    today = `${(new Date().getDate()< 10 ? '0' : '') + new Date().getDate()}.${new Date().getUTCMonth() + 1}`;
  };
  //console.log(today);

  checkedShippingMethod = checkedShippingMethodInput.value;
  
 // console.log(checkedShippingMethod);

  deliveryDatesLables.forEach((label) => {
    let dateInfo = {
      label: label,
      for: label.htmlFor,
      text: label.textContent};
    //console.log(dateInfo);
    deliveryDatesInfo.push(dateInfo);
  });

  

  checkoutForm.addEventListener('change', onChangeShippingMethod);

  plntChekUrgentDelivery();
  renderDeliveryDates(checkedShippingMethod);

  if(deliveryLateMarkup) {
    deliveryIntervalLabels.forEach((label) => {
      let intervalInfo = {
        label: label,
        for: label.htmlFor,
        text: label.textContent
        };
      //console.log(intervalInfo);
      deliveryIntervalsInfo.push(intervalInfo);
    });
    renderDeliveryIntervals(checkedShippingMethod);
    deliveryIntervalInput.forEach(el =>{
      el.addEventListener('click', ajaxGetLateDelivery);
    })
  }
}