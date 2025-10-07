let isUrgent;
let isLate;
let isHoliday; //скрываем подние интервалы доставки
let holidays = []; //format dd.mm
let deliveryDatesInfo = [];
let deliveryIntervalsInfo = []
let shippingMethodValues = [];
let checkedShippingMethod = '';
let checkedDate = '';
let checkedInterval = '';

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

  if(checkedDate == today) {
    isUrgent = '1';
    // isLate = '0';
  } else {
    isUrgent = '0';
  }
  
  plnt_hide_checkout_fields(event);

  checkedInterval = getCheckedInterval();
  console.debug(checkedInterval)


  if(checkedInterval == '18:00 - 21:00') {
    isLate = '1'
  } else {
    isLate = '0'
  }


  console.debug(isUrgent);
  console.debug(isLate);

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

  const data = new URLSearchParams();
  data.append('action', 'get_urgent_shipping');
  data.append('isUrgent', isUrgent);
  data.append('isLate', isLate);

  fetch('/wp-admin/admin-ajax.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: data
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
  })
  .then(result => {
    console.debug('✅ AJAX success:', result);
    if (result.success) {
      document.body.dispatchEvent(new Event('update_checkout'));
    }
  })
  .catch(error => {
    console.error('❌ AJAX error:', error);
  })
  .finally(() => {
    console.debug('⚙️ AJAX complete');
  });
}

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
    today = `${((new Date().getDate()+1) < 10 ? '0' : '') + (new Date().getDate() + 1)}.${((new Date().getUTCMonth() + 1)< 10 ? '0' : '') + (new Date().getUTCMonth() + 1)}`;
  } else {
    today = `${(new Date().getDate()< 10 ? '0' : '') + new Date().getDate()}.${((new Date().getUTCMonth() + 1)< 10 ? '0' : '') + (new Date().getUTCMonth() + 1)}`;
  };

 console.debug(today);
 console.debug(new Date().getUTCMonth() + 1);
 console.debug(new Date().getDate());

 
  checkHoliday(deliveryDatesInput[0].value);

  deliveryDatesInput[0].checked = true;
  deliveryIntervalInput[0].checked = true;

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