const DELIVERY = window.PLNT_Delivery_Data || {};
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
let isUrgentCourierTariff = DELIVERY.isUrgentCourierTariff == '1';
let deliveryLateInterval = DELIVERY.deliveryLateInterval
let isSmallHolidayTariffOn = DELIVERY.isSmallHolidayTariffOn
console.log(isSmallHolidayTariffOn);

let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
let addressFields = document.querySelector('#billing_address_1_field');
let additionalAddress = document.querySelector('.additional-address-field');
let innField = document.querySelector('#additional_inn');
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelector('.delivery_dates');
let deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
let deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');

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
  
  hideCheckoutFields(event);

  checkedInterval = getCheckedInterval();
  console.debug(checkedInterval)


  if(checkedInterval == deliveryLateInterval) {
    isLate = '1'
  } else {
    isLate = '0'
  }


  console.debug(isUrgent);
  console.debug(isLate);

  checkHoliday(checkedDate);

  renderDeliveryDates(checkedShippingMethod);
  renderDeliveryIntervals(checkedShippingMethod);

  if(event.target.className == "shipping_method" || 
    event.target.name == "delivery_dates" || 
    event.target.name == "additional_delivery_interval" )
    //|| event.target == document
   {
      console.log('нужен пересчет')
      ajaxGetUrgent();
    } 
    else {
      console.log('не нужен пересчет')
    }
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
      if(shippingValue == DELIVERY.deliveryInMKAD) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${Number(DELIVERY.deliveryCostInMkad) + Number(DELIVERY.deliveryUrgMarkup) + Number(DELIVERY.deliveryMarkupInMkad)}₽` : `${Number(DELIVERY.deliveryCostInMkad) + Number(DELIVERY.deliveryMarkupInMkad)}₽` ;
        if (isUrgentCourierTariff) {
            if (info.for == `delivery_dates_${today}`) {
                priceEl.innerHTML = 'по тарифу КС';
            }
        }
      }
      if(shippingValue == DELIVERY.deliveryOutMKAD) {
        priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${Number(DELIVERY.deliveryCostOutMkad) + Number(DELIVERY.deliveryUrgMarkup) + Number(DELIVERY.deliveryMarkupOutMkad)}₽` : `${Number(DELIVERY.deliveryCostOutMkad) + Number(DELIVERY.deliveryMarkupOutMkad)}₽` ;
        if (isUrgentCourierTariff) {
            if (info.for == `delivery_dates_${today}`) {
                priceEl.innerHTML = 'по тарифу КС';
            }
        }
      }
  })
}

//функция отрисовывает поля интервалов доставки с добавлением стоимости доставки
function renderDeliveryIntervals(shippingValue) {
  deliveryIntervalsInfo.forEach((info) => {
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == DELIVERY.localPickupId || shippingValue == DELIVERY.deliveryFreeId || shippingValue == DELIVERY.deliveryPochtaId ||shippingValue == DELIVERY.deliveryCourierId || shippingValue == DELIVERY.deliveryLongId) {
      } else {
        if (isUrgent == '1') {
          priceEl.innerHTML = `+0₽`;
        } else {
          priceEl.innerHTML = info.for == `additional_delivery_interval_${deliveryLateInterval}` ? `+${DELIVERY.deliveryLateMarkup}₽` : `+0₽` ;
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

  if(DELIVERY.deliveryLateMarkup) {    
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

function hideInterval() {
  deliveryInterval.classList.add('d-none');
  deliveryIntervalInput.forEach((input)=>{
      input.checked = false;
  })
  console.debug(deliveryIntervalInput)
}

function showInterval() {
  deliveryInterval.classList.remove('d-none');
  if (checkedInterval == '') {
      deliveryIntervalInput[0].checked = true;
  }
}

function hideCheckoutFields(event){
  //console.log('hi hideCheckoutFields');
  if (deliveryInterval) {
      if (DELIVERY.isBackorder || DELIVERY.isTreezBackorders) {
          hideInterval()
      } else { 
          if ( checkedShippingMethod == DELIVERY.localPickupId || checkedShippingMethod == DELIVERY.deliveryPochtaId) {
              hideInterval()
          } else {
              if (isUrgent == '1') {
                  hideInterval();
              }
              if (isUrgent == '0') {
                  showInterval()
              }

          }
      }
  }

  //for delivery dates
  if (deliveryDates) {
      if (DELIVERY.isBackorder || DELIVERY.isTreezBackorders) {
          deliveryDates.classList.add('d-none');
          deliveryDatesInput.forEach((input)=>{
              input.checked = false;
          })
      } 
  }

  //for address 
  if (checkedShippingMethod == DELIVERY.localPickupId) {
      if (addressFields) {addressFields.classList.add('d-none');}
      if (additionalAddress) {additionalAddress.classList.add('d-none');}
  } else {
      if (addressFields) {addressFields.classList.remove('d-none');}
      if (additionalAddress) {additionalAddress.classList.remove('d-none');}
  }
  
  // for INN
  if (innField) {
      console.debug(document.querySelector('.wc_payment_methods input[checked="checked"]').value);
      if(event && event.target.id == "payment_method_cheque") {
          innField.classList.remove('d-none');
      } else {
          if(event.target.id == "payment_method_tbank" 
              || event.target.id == "payment_method_cop"
              || event.target.id == "payment_method_cod"
              || document.querySelector('.wc_payment_methods input[checked="checked"]').value != 'cheque'
          ) 
          {
              innField.classList.add('d-none')
              innField.value = ''
          }
      };
  }        

  // for holidays
  if (isHoliday === '1') {
      deliveryIntervalInput.forEach(el =>{
          if(el.defaultValue !== '11:00 - 16:00') {
              el.classList.add('d-none');
          }
      })
      deliveryIntervalLabels.forEach(el =>{
          if(el.htmlFor !== 'additional_delivery_interval_11:00 - 16:00') {
              el.classList.add('d-none');
          }
      })
  }
  if (isHoliday === '0') {
      deliveryIntervalInput.forEach(el =>{
          el.classList.remove('d-none');
      })
      deliveryIntervalLabels.forEach(el =>{
          el.classList.remove('d-none');
      })
  }
}

if (checkoutForm) {

  setInitalState();

  document.addEventListener('DOMContentLoaded', getDatesIntervalsInfo )

  document.addEventListener('DOMContentLoaded', getOrderParametrs )

  checkoutForm.addEventListener('change', getOrderParametrs);
  
}