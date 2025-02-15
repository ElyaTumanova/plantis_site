let isUrgent;
let isLate;
let isHideInterval;
let isShortDay; //скрываем подние интервалы доставки
let shortDays = []; //format dd.mm
let isHoliday; //увеличиваем стоимость доставки
let holidays = ['15.02','18.02', '19.02']; //format 'dd.mm'
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelector('.delivery_dates');
let deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryDatesInfo = [];
let deliveryIntervalsInfo = []
let shippingMethodValues = [];
let checkedShippingMethodInput;
let checkedShippingMethod;

let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
//let deliveryInterval = document.querySelectorAll('#additional_delivery_interval_field input');
let deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');
let today;


function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        renderDeliveryDates(event.target.value);
        renderDeliveryIntervals(event.target.value);
        // console.log(event.target.value);
        // ajaxGetUrgent();
    }
}

function getCheckedShippingMethod (){
  checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
  return checkedShippingMethodInput.value;
}

function renderDeliveryDates(shippingValue) {
  deliveryDatesInfo.forEach((info) => {
    let isHolidayMarkup = 0;
    if(holidays.includes(info.text)) { 
      isHolidayMarkup = 1;
    }
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == deliveryInMKAD || shippingValue == deliveryInMKADUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadUrg + deliveryHolidayMarkup*isHolidayMarkup}₽` : `${deliveryCostInMkad + deliveryHolidayMarkup*isHolidayMarkup}₽` ;
      }
      if(shippingValue == deliveryOutMKAD || shippingValue == deliveryOutMKADUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadUrg + deliveryHolidayMarkup*isHolidayMarkup}₽` : `${deliveryCostOutMkad + deliveryHolidayMarkup*isHolidayMarkup}₽` ;
      }
      if(shippingValue == deliveryInMKADSmall || shippingValue == deliveryInMKADSmallUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadSmallUrg + deliveryHolidayMarkup*isHolidayMarkup}₽` : `${deliveryCostInMkadSmall + deliveryHolidayMarkup*isHolidayMarkup}₽` ;
      }
      if(shippingValue == deliveryOutMKADSmall || shippingValue == deliveryOutMKADSmallUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadSmallUrg + deliveryHolidayMarkup*isHolidayMarkup}₽` : `${deliveryCostOutMkadSmall + deliveryHolidayMarkup*isHolidayMarkup}₽` ;
      }
      if(shippingValue == deliveryInMKADLarge || shippingValue == deliveryInMKADLargeUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadLargeUrg}₽` : `${deliveryCostInMkadLarge}₽` ;
      }
      if(shippingValue == deliveryOutMKADLarge || shippingValue == deliveryOutMKADLargeUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadLargeUrg}₽` : `${deliveryCostOutMkadLarge}₽` ;
      }
      if(shippingValue == deliveryInMKADMedium || shippingValue == deliveryInMKADMediumUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadMediumUrg + deliveryHolidayMarkup*isHolidayMarkup}₽` : `${deliveryCostInMkadMedium + deliveryHolidayMarkup*isHolidayMarkup}₽` ;
      }
      if(shippingValue == deliveryOutMKADMedium || shippingValue == deliveryOutMKADMediumUrg) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadMediumUrg + deliveryHolidayMarkup*isHolidayMarkup}₽` : `${deliveryCostOutMkadMedium + deliveryHolidayMarkup*isHolidayMarkup}₽` ;
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
        if (isUrgent == '1') {
          priceEl.innerHTML = `+0₽`;
        } else {
          priceEl.innerHTML = info.for == `additional_delivery_interval_18:00 - 21:00` ? `+${deliveryLateMarkup}₽` : `+0₽` ;
        }
      }
  })
}

function ajaxGetUrgent(date) {
  if (date) {
    if(date == today) {
      isUrgent = '1';
    } else {
      isUrgent = '0';
    }
  }
  // console.log(isUrgent);

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

function ajaxGetHoliday(date) {
  if (holidays.includes(date)) {
    isHoliday = '1'
  } else {
    isHoliday = '0'
  };

  jQuery( function($){
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: {
                'action': 'get_holiday_shipping',
                'isHoliday': isHoliday,
            },
            success: function (result) {
                // Trigger refresh checkout
                $('body').trigger('update_checkout');
            }
        });
  });

  console.log(isHoliday);
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

  if (hour >= 20) {
    today = `${(new Date().getDate()< 10 ? '0' : '') + (new Date().getDate() + 1)}.${(new Date().getUTCMonth()< 10 ? '0' : '') + (new Date().getUTCMonth() + 1)}`;
  } else {
    today = `${(new Date().getDate()< 10 ? '0' : '') + new Date().getDate()}.${(new Date().getUTCMonth()< 10 ? '0' : '') + (new Date().getUTCMonth() + 1)}`;
  };

  //console.log(today);

  if(hour >=20 && hour<24) {
    isHideInterval = false;
  } else {
    isHideInterval = true;
  }

  isLate = 0;
}

function setDatesIntervals() {
  deliveryDatesLables.forEach((label) => {
    let dateInfo = {
      label: label,
      for: label.htmlFor,
      text: label.textContent};
    //console.log(dateInfo);
    deliveryDatesInfo.push(dateInfo);
  });

  deliveryDatesInput[0].setAttribute('checked','checked');
  deliveryIntervalInput[0].setAttribute('checked','checked');

  deliveryDatesInput.forEach((date) => {
    date.addEventListener('click', function(event){
      ajaxGetUrgent(event.target.value);
      checkShortDays(event.target.value);
      ajaxGetHoliday(event.target.value);
      shippingValue = getCheckedShippingMethod();
      renderDeliveryIntervals(shippingValue);
    });
  })

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

function checkShortDays(date) {
  if (shortDays) {
    if (shortDays.includes(date)) {
      isShortDay = '1'
    } else {
      isShortDay = '0'
    };
  }
}

if (checkoutForm) {

  setInitalState();
  checkShortDays(deliveryDatesInput[0].value);
  setDatesIntervals();

  checkedShippingMethod = getCheckedShippingMethod();

  renderDeliveryIntervals(checkedShippingMethod);
  renderDeliveryDates(checkedShippingMethod);

  checkoutForm.addEventListener('change', onChangeShippingMethod);

  ajaxGetUrgent();
  ajaxGetHoliday(deliveryDatesInput[0].value);

  console.log(deliveryCostInMkadLarge);
  
}