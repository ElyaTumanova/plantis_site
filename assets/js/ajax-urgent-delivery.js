let isUrgent = '0';
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryDatesInfo = [];
let checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
let checkedShippingMethod;

let today = `${(new Date().getDate()< 10 ? '0' : '') + new Date().getDate()}.${new Date().getUTCMonth() + 1}`;
//console.log(today);

//console.log(checkedShippingMethod);


checkedShippingMethod = checkedShippingMethodInput.value;

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

function plntChekUrgentDelivery() {
  console.log('hi plntChekUrgentDelivery');
  deliveryDates[1].setAttribute('checked','checked');
  plntAjaxGetUrgent();

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
        priceEl.innerHTML = `${Number(deliveryCostInMkad) + Number(deliveryUrgentMarkup)}₽`
      } else {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${deliveryCostInMkad}₽`
      }
    })
  }
  if(dateFieldValue == deliveryOutMKAD) {
    deliveryDatesInfo.forEach((info) => {
      let priceEl = document.createElement('span');
      if(info.for == `delivery_dates_${today}`) {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${Number(deliveryCostOutMkad) + Number(deliveryUrgentMarkup)}₽`
      } else {
        info.label.innerHTML=`${info.text}`;
        info.label.appendChild(priceEl);
        priceEl.innerHTML = `${deliveryCostOutMkad}₽`
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

    jQuery( function($){
          $.ajax({
              type: 'POST',
              url: wc_checkout_params.ajax_url,
              data: {
                  'action': 'get_order_total',
              },
              success: function (result) {
                  // Trigger refresh checkout
                  //$('body').trigger('update_checkout');
                  console.log('get total');
                  console.log(result);
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