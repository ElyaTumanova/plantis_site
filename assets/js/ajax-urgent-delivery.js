let isUrgent;
let isLate;
let isHideInterval;
let isHoliday; //скрываем подние интервалы доставки
let holidays = []; //format dd.mm
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelector('.delivery_dates');
let deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryDatesInfo = [];
let deliveryIntervalsInfo = []
let shippingMethodValues = [];
let checkedShippingMethod = '';
let checkedDate = '';
let checkedInterval = '';

let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
let deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');
let today;

//определяем параметры оформления заказа, влияющие на стоимость доставки и вызываем аякс, отрисовываем поля дат и интервалов доставки
function getOrderParametrs(event) {
  console.debug(event);
  if(event && event.target.className == "shipping_method") {
     checkedShippingMethod = event.target.value
  } else {
    checkedShippingMethod = getCheckedShippingMethod();
  }
  console.debug(checkedShippingMethod);
  checkedDate = getCheckedDate();
  console.debug(checkedDate);
  checkedInterval = getCheckedInterval();
  console.debug(checkedInterval)

  if(checkedDate == today) {
    isUrgent = '1';
  } else {
    isUrgent = '0';
  }

   if(checkedInterval == '18:00 - 21:00') {
    isLate = '1'
  } else {
    isLate = '0'
  }

  checkHoliday(checkedDate);

  renderDeliveryDates(checkedShippingMethod);
  renderDeliveryIntervals(checkedShippingMethod);

  ajaxGetUrgent();

}

function getCheckedShippingMethod (){
  let checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input[checked="checked"]');
  return checkedShippingMethodInput.value;
}

function getCheckedDate (){
 let dateInputs = document.querySelectorAll('.delivery_dates input');
 let checkedDateInput = Array.from(dateInputs).find((el)=>el.checked == true); 
 return checkedDateInput.value;
}

function getCheckedInterval (){
 let dateIntervals = document.querySelectorAll('.additional_delivery_interval input');
 let checkedIntervalInput = Array.from(dateIntervals).find((el)=>el.checked == true); 
 if(checkedIntervalInput) {
   return checkedIntervalInput.value;
 } else {
  return ''
 }
}

//функция отрисовывает поля дат доставки с добавлением стоимости доставки
function renderDeliveryDates(shippingValue) {
  deliveryDatesInfo.forEach((info) => {
    info.label.innerHTML=`${info.text}`;
    let priceEl = document.createElement('span');
    info.label.appendChild(priceEl);
      if(shippingValue == deliveryInMKAD) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${Number(deliveryCostInMkad) + Number(deliveryUrgMarkup) + Number(deliveryMarkupInMkad)}₽` : `${Number(deliveryCostInMkad) + Number(deliveryMarkupInMkad)}₽` ;
      }
      if(shippingValue == deliveryOutMKAD) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${Number(deliveryCostOutMkad) + Number(deliveryUrgMarkup) + Number(deliveryMarkupOutMkad)}₽` : `${Number(deliveryCostOutMkad) + Number(deliveryMarkupOutMkad)}₽` ;
      }
  })
}

//функция отрисовывает поля интервалов доставки с добавлением стоимости доставки
function renderDeliveryIntervals(shippingValue) {
  deliveryIntervalsInfo.forEach((info) => {
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == localPickupId || shippingValue == deliveryFreeId || shippingValue == deliveryPochtaId ||shippingValue == deliveryCourierId || shippingValue == deliveryLongId) {
      } else {
        if (isUrgent == '1') {
          priceEl.innerHTML = `+0₽`;
        } else {
          priceEl.innerHTML = info.for == `additional_delivery_interval_18:00 - 21:00` ? `+${deliveryLateMarkup}₽` : `+0₽` ;
        }
      }
  })
  
}

//аякс запрос
function ajaxGetUrgent() {
  console.debug('hi ajaxGetUrgent');
  console.debug('isUrgent ajax', isUrgent);
  console.debug('isLate ajax', isLate);

  jQuery( function($){
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: {
                'action': 'get_urgent_shipping',
                'isUrgent': isUrgent,
                'isLate': isLate
            },
            success: function (result) {
                // Trigger refresh checkout
                $('body').trigger('update_checkout');
            }
        });
  });
};

//определяем начальное состояние при загрузке формы
function setInitalState() {
  let hour = new Date().getHours();

  if (hour >= 18 && hour <20) { 
    isUrgent = 0;
  } else {
    isUrgent = 1;
  }

  isLate = 0;

  if (hour >= 20) {
    today = `${((new Date().getDate()+1) < 10 ? '0' : '') + (new Date().getDate() + 1)}.${(new Date().getUTCMonth()< 10 ? '0' : '') + (new Date().getUTCMonth() + 1)}`;
  } else {
    today = `${(new Date().getDate()< 10 ? '0' : '') + new Date().getDate()}.${(new Date().getUTCMonth()< 10 ? '0' : '') + (new Date().getUTCMonth() + 1)}`;
  };

 //console.debug(today);

  if(hour >=20 && hour<24) {
    isHideInterval = false;
  } else {
    isHideInterval = true;
  }

  checkHoliday(deliveryDatesInput[0].value);

  deliveryDatesInput[0].setAttribute('checked','checked');
  deliveryIntervalInput[0].setAttribute('checked','checked');

}

//функция собирает исходные значения полей дат и интервалов доставки, чтобы потом пересивовать их
function getDatesIntervalsInfo() {
  deliveryDatesLables.forEach((label) => {
    let dateInfo = {
      label: label,
      for: label.htmlFor,
      text: label.textContent};
    deliveryDatesInfo.push(dateInfo);
  });

  if(deliveryLateMarkup) {    
    deliveryIntervalLabels.forEach((label) => {
      let intervalInfo = {
        label: label,
        for: label.htmlFor,
        text: label.textContent
        };
      deliveryIntervalsInfo.push(intervalInfo);
    });
  }
}

function checkHoliday(date) {
  if (holidays) {
    if (holidays.includes(date)) {
      isHoliday = '1'
    } else {
      isHoliday = '0'
    };
  }
}

if (checkoutForm) {

  setInitalState();

  document.addEventListener('DOMContentLoaded', getDatesIntervalsInfo )

  document.addEventListener('DOMContentLoaded', getOrderParametrs )

  checkoutForm.addEventListener('change', getOrderParametrs);
  
}