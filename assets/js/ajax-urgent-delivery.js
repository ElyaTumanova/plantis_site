function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        //renderDeliveryDates(event.target.value);
        renderDeliveryIntervals(event.target.value);
        // console.log(event.target.value);
        // ajaxGetUrgent();

        getDeliveryCosts(event.target.value);
        checkedShippingMethod = event.target.value;
        console.log(checkedShippingMethod);
        console.log(isHideInterval);
        console.log(isUrgent);
    }
}

function getCheckedShippingMethod (){
  checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
  return checkedShippingMethodInput.value;
}

// function renderDeliveryDates(shippingValue) {
//   // console.log(shippingValue);
//   deliveryDatesInfo.forEach((info) => {
//     let priceEl = document.createElement('span');
//     info.label.innerHTML=`${info.text}`;
//     info.label.appendChild(priceEl);
//       if(shippingValue == deliveryInMKAD || shippingValue == deliveryInMKADUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadUrg}₽` : `${deliveryCostInMkad}₽` ;
//       }
//       if(shippingValue == deliveryOutMKAD || shippingValue == deliveryOutMKADUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadUrg}₽` : `${deliveryCostOutMkad}₽` ;
//       }
//       if(shippingValue == deliveryInMKADSmall || shippingValue == deliveryInMKADSmallUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadSmallUrg}₽` : `${deliveryCostInMkadSmall}₽` ;
//       }
//       if(shippingValue == deliveryOutMKADSmall || shippingValue == deliveryOutMKADSmallUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadSmallUrg}₽` : `${deliveryCostOutMkadSmall}₽` ;
//       }
//       if(shippingValue == deliveryInMKADLarge || shippingValue == deliveryInMKADLargeUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadLargeUrg}₽` : `${deliveryCostInMkadLarge}₽` ;
//       }
//       if(shippingValue == deliveryOutMKADLarge || shippingValue == deliveryOutMKADLargeUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadLargeUrg}₽` : `${deliveryCostOutMkadLarge}₽` ;
//       }
//       if(shippingValue == deliveryInMKADMedium || shippingValue == deliveryInMKADMediumUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadMediumUrg}₽` : `${deliveryCostInMkadMedium}₽` ;
//       }
//       if(shippingValue == deliveryOutMKADMedium || shippingValue == deliveryOutMKADMediumUrg) {
//         priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadMediumUrg}₽` : `${deliveryCostOutMkadMedium}₽` ;
//       }
//   })
// }

function renderDeliveryIntervals(shippingValue) {
  // console.log(shippingValue);
  deliveryIntervalsInfo.forEach((info) => {
    console.log(deliveryLateMarkup);
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == localPickupId || shippingValue == deliveryFreeId || shippingValue == deliveryCourierId || shippingValue == deliveryLongId) {
      } else {
        if (isUrgent == '1') {
          priceEl.innerHTML = `+0₽`;
        } else {
          priceEl.innerHTML = info.for == `additional_delivery_interval_18:00 - 21:00` ? `+${deliveryLateMarkup}₽` : `+0₽` ;
        }
      }
  })
}

function ajaxGetUrgent(date) {

  console.log(isUrgent);

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
  // console.log(isLate);

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

function setInitalState() {
  let hour = new Date().getHours();

  // console.log(isBackorder);
  if (isBackorder) {
    isUrgent = 0;
  } else {
    if (hour >= 18 && hour <20) { 
      isUrgent = 0;
    } else {
      isUrgent = 1;
    }
  }

  if (isBackorder) {
    urgentDelivery = false;
  } else {
    if (hour >= 18 && hour <20) { 
      urgentDelivery = false;
    } else {
      urgentDelivery = true;
    }
  }
   


  if(hour >=20 && hour<24) {
    isHideInterval = false;
  } else {
    isHideInterval = true;
  }

  isLate = 0;
}

function setDatesIntervals() {
  // deliveryDatesLables.forEach((label) => {
  //   let dateInfo = {
  //     label: label,
  //     for: label.htmlFor,
  //     text: label.textContent};
  //   //console.log(dateInfo);
  //   deliveryDatesInfo.push(dateInfo);
  // });

  // deliveryDatesInput[0].setAttribute('checked','checked');
  deliveryIntervalInput[0].setAttribute('checked','checked');

  // deliveryDatesInput.forEach((date) => {
  //   date.addEventListener('click', function(event){
  //     ajaxGetUrgent(event.target.value);
  //     //checkShortDay(event.target.value);
  //     shippingValue = getCheckedShippingMethod();
  //     renderDeliveryIntervals(shippingValue);
  //   });
  // })

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
    deliveryIntervalInput.forEach(el =>{
      el.addEventListener('click', ajaxGetLateDelivery);
    })
  }
}

function checkShortDay(date) {
  if (shortdays) {
    if (shortdays.includes(date)) {
      isShortDay = '1'
    } else {
      isShortDay = '0'
    };
  }
}

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

if (checkoutForm) {
  setInitalState();
  checkedShippingMethod = getCheckedShippingMethod();
  console.log(checkedShippingMethod);
  getDeliveryCosts(checkedShippingMethod);


  checkShortDay(deliveryDatesInput[0].value);
  setDatesIntervals();

  renderDeliveryIntervals(checkedShippingMethod);
  //renderDeliveryDates(checkedShippingMethod);

  checkoutForm.addEventListener('change', onChangeShippingMethod);
  
  datepicker_create ();

  console.log(isHideInterval);
  console.log(isUrgent);

  //ajaxGetUrgent();
  
}