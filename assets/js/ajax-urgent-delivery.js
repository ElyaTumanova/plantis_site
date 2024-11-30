let isUrgent = '0';
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryDatesInfo = [];
let checkedShippingMethod = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]').value;

let today = `${new Date().getDate()}.${new Date().getUTCMonth() + 1}`;
console.log(today);

//console.log(checkedShippingMethod);

deliveryDatesLables.forEach((label) => {
  let dateInfo = {
    label: label,
    for: label.htmlFor,
    text: label.textContent};
  //console.log(dateInfo);
  deliveryDatesInfo.push(dateInfo);
});

renderDeliveryDates(checkedShippingMethod);



// console.log(deliveryDatesInfo);
// console.log(deliveryDatesLables);
//console.log(deliveryInMKAD);

function plntChekUrgentDelivery() {
  // console.log(deliveryDates);
  // console.log(deliveryDates[1]);
  deliveryDates[1].setAttribute('checked','checked');

  deliveryDates.forEach((date) => {
    date.addEventListener('click', function(event){
      console.log(event.target.value);
      if(event.target.value == today) {
        isUrgent = '1';
      } else {
        isUrgent = '0';
      }
      //console.log(isUrgent);
      plntAjaxGetUrgent();
      });

  })
};


plntChekUrgentDelivery();


checkoutForm.addEventListener('change', onChangeShippingMethod);

function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        renderDeliveryDates(event.target.value);
    }
}

function renderDeliveryDates(dateFieldValue) {
  //console.log(dateFieldValue);
  if(dateFieldValue == deliveryInMKAD) {
    deliveryDatesInfo.forEach((info) => {
      let priceEl = document.createElement('span');
      if(info.for == `delivery_dates_${today}`) {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${deliveryCostInMkad + deliveryUrgentMarkup} ₽`
      } else {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${deliveryCostInMkad} ₽`
      }
    })
  }
  if(dateFieldValue == deliveryOutMKAD) {
    deliveryDatesInfo.forEach((info) => {
      let priceEl = document.createElement('span');
      if(info.for == `delivery_dates_${today}`) {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${deliveryCostOutMkad + deliveryUrgentMarkup} ₽`
      } else {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${deliveryCostOutMkad} ₽`
      }
    })
  }
  if(dateFieldValue == localPickup) {
    deliveryDatesInfo.forEach((info) => {
      if(info.for == `delivery_dates_${today}`) {
        info.label.innerHTML=`${info.text}`;
      } else {
        info.label.innerHTML=`${info.text}`;
      }
    })
  }
}


function plntAjaxGetUrgent() {
    console.log('hi ajax');
    console.log(isUrgent);
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
                  //console.log(isUrgent);
              }
          });
    });
  
    // let urgentText = document.querySelector('.checkout__urgent-text');
    // if (urgentText) {
    //   if (isUrgent == '1') {
    //     console.log('hi text');
    //     urgentText.innerHTML = "Доставка для выбранной даты является срочной, поэтому стоимость доставки увеличена.";
    //     } else {
    //     urgentText.innerHTML = "";
    //   }
    // }
  };