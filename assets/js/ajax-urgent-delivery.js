let isUrgent = '0';
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryDatesInfo = [];
let shippingMethodValues = [];
let checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
let checkedShippingMethod;
let today;
let sessionShippingId;
let destination;


function plntChekUrgentDelivery() {
  //console.log('hi plntChekUrgentDelivery');
  deliveryDates[1].setAttribute('checked','checked');
  plntAjaxGetUrgent();

  deliveryDates.forEach((date) => {
    date.addEventListener('click', function(event){
      //console.log(event.target.value);
      if(event.target.value == today) {
        isUrgent = '1';
      } else {
        isUrgent = '0';
      }
      //console.log(isUrgent);
      plntAjaxGetUrgent();
      onChangeShippingDate();
      });
  })
};

function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        renderDeliveryDates(event.target.value);
        console.log(event.target.value);
        window.sessionStorage.setItem('sessionShippingId', event.target.value);
    }
}

function renderDeliveryDates(shippingValue) {
  //console.log(shippingValue);
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

function onChangeShippingDate() {
  setTimeout(() => {
    let shippingMethodInputs = document.querySelectorAll('.woocommerce-shipping-methods input');
    console.log(shippingMethodInputs);

  
  sessionShippingId = window.sessionStorage.getItem('sessionShippingId');
  console.log(sessionShippingId);
  if (deliveryIdsInMkad.includes(sessionShippingId)) {
    destination = 'inMkad';
    shippingMethodInputs.forEach((input) => {
      
      if (deliveryIdsInMkad.includes(input.value)) {
        //console.log(document.querySelector(`input[value=${input.value}]`));
        //document.querySelector(`#${input.value}`);
      };
    });
  } else if (deliveryIdsOutMkad.includes(sessionShippingId)) {
    destination = 'outMkad';
  } else {
    destination = 'other';
  }
  // shippingMethodInputs[3].setAttribute('checked','checked');
  
}, 1000)
}

if (checkoutForm) {

  let hour = new Date().getHours();
  if (hour >= 18) {
    today = `${(new Date().getDate()< 10 ? '0' : '') + (new Date().getDate() + 1)}.${new Date().getUTCMonth() + 1}`;
  } else {
    today = `${(new Date().getDate()< 10 ? '0' : '') + new Date().getDate()}.${new Date().getUTCMonth() + 1}`;
  };
  //console.log(today);

  checkedShippingMethod = checkedShippingMethodInput.value;
  window.sessionStorage.setItem('sessionShippingId', checkedShippingMethod);
  
  console.log(checkedShippingMethod);

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
}