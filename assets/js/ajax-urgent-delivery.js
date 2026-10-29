const DELIVERY = window.PLNT_Delivery_Data || {};
const disableUrgentDelivery = DELIVERY.disableUrgentDelivery === true || DELIVERY.disableUrgentDelivery === '1' || DELIVERY.disableUrgentDelivery === 1;
const disableUrgentLargeDelivery = DELIVERY.disableUrgentLargeDelivery === true || DELIVERY.disableUrgentLargeDelivery === '1' || DELIVERY.disableUrgentLargeDelivery === 1;
const isLargeDelivery = DELIVERY.isLargeDelivery === true || DELIVERY.isLargeDelivery === '1' || DELIVERY.isLargeDelivery === 1;

let isUrgent;
let isLate;
let isHoliday; //скрываем подние интервалы доставки
let isExpensive; //увеличиваем стоимость доставки
// let holidays = []
// let notWorking = []

let holidays = []; //format dd.mm - дни для сокращенного интервала
let notWorking = [] //format dd.mm - дни для отсутсвия доставки 
let notWorkingAllowPickup = false;
let expensiveDays = ['06.03', '07.03', '08.03'] //format dd.mm - дни для дорогой доставки 
let deliveryDatesInfo = [];
let deliveryIntervalsInfo = []
let shippingMethodValues = [];
let checkedShippingMethod = '';
let checkedDate = '';
let checkedInterval = '';
let today;
let urgentTariffDate; // после 20:00 срочный тариф действует на завтра
let isUrgentCourierTariff = DELIVERY.isUrgentCourierTariff == '1';
let deliveryExpensiveInterval = DELIVERY.deliveryExpensiveInterval
let isSmallHolidayTariffOn = DELIVERY.isSmallHolidayTariffOn == '1';
let deliveryExpensiveDayMarkup = DELIVERY.deliveryExpensiveDayMarkup;
console.debug('isUrgentCourierTariff ',isUrgentCourierTariff);
console.debug('isSmallHolidayTariffOn ',isSmallHolidayTariffOn);
console.debug('deliveryExpensiveDayMarkup ',deliveryExpensiveDayMarkup);

let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
let addressFields = document.querySelector('#billing_address_1_field');
let additionalAddress = document.querySelector('.additional-address-field');
let innField = document.querySelector('#additional_inn');
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelector('.delivery_wrap');
// let deliveryDates = document.querySelector('.delivery_dates');
let deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
let deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
let deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');


function isTodayDeliveryDisabled() {
  // Самовывоз не ограничиваем
  if (getCheckedShippingMethod() === DELIVERY.localPickupId) {
    return false;
  }

  // Общий запрет срочной доставки
  if (disableUrgentDelivery) {
    return true;
  }

  // Отдельный запрет для крупногабаритных заказов
  return isLargeDelivery && disableUrgentLargeDelivery;
}

function selectDefaultDeliveryDate() {
  const dates = Array.from(deliveryDatesInput);
  // Даты в PHP формируются по московскому времени.
  const tomorrow = new Date(new Date().toLocaleString('en-US', { timeZone: 'Europe/Moscow' }));
  tomorrow.setDate(tomorrow.getDate() + 1);

  const tomorrowValue =
    `${String(tomorrow.getDate()).padStart(2, '0')}.` +
    `${String(tomorrow.getMonth() + 1).padStart(2, '0')}`;

  const preferred = dates.find(input =>
    input.value === tomorrowValue && !input.disabled
  );

  const fallback = dates.find(input => !input.disabled);

  const selected = preferred || fallback;

  dates.forEach(input => {
    input.checked = input === selected;
  });
}

function applyUrgentDeliveryRestrictions() {
  const disabled = isTodayDeliveryDisabled();

  deliveryDatesInput.forEach(input => {
    if (input.value !== today) return;

    const shouldDisable = disabled || (
      notWorking.includes(input.value) &&
      !(notWorkingAllowPickup && getCheckedShippingMethod() == DELIVERY.localPickupId)
    );
    input.disabled = shouldDisable;

    const label = Array.from(deliveryDatesLables)
      .find(el => el.htmlFor === input.id);

    if (label) {
      label.classList.toggle('d-none', shouldDisable);
    }

    if (shouldDisable) {
      input.checked = false;
    }
  });
}

//определяем параметры оформления заказа, влияющие на стоимость доставки и вызываем аякс, отрисовываем поля дат и интервалов доставки
function getOrderParametrs(event) {
  console.debug(event);
  if(event && event.target.className == "shipping_method") {
     checkedShippingMethod = event.target.value
  } else {
    checkedShippingMethod = getCheckedShippingMethod();
  }
  console.debug('checkedShippingMethod ',checkedShippingMethod);
  if(notWorkingAllowPickup) {disableNotWorkingDays(checkedShippingMethod)}
  applyUrgentDeliveryRestrictions();
  if (!getCheckedDate() && !(DELIVERY.isBackorder || DELIVERY.isTreezBackorders)) {
    selectDefaultDeliveryDate();
  }
  
  checkedDate = getCheckedDate();
  console.debug('checkedDate ', checkedDate)
  
  if(checkedDate && checkedDate == urgentTariffDate) {
    isUrgent = '1';
  } else {
    isUrgent = '0';
  }

   if(expensiveDays && deliveryExpensiveDayMarkup && expensiveDays.includes(checkedDate)) {
    isExpensive = 1
  } else { isExpensive = 0}
  
  hideCheckoutFields(event);
  
  checkHoliday(checkedDate);
  // определеяем checkedInterval после hideCheckoutFields, так как там идет сбрас выбранного интервала и после checkHoliday, так как идет выбор доступного интервала
  checkedInterval = getCheckedInterval();
  console.debug('checkedInterval ', checkedInterval)
  if(checkedInterval == deliveryExpensiveInterval) {
    isLate = '1'
  } else {
    isLate = '0'
  }

  console.debug('isUrgent ', isUrgent);
  console.debug('isLate', isLate);
  console.debug('isExpensive', isExpensive);
  
  renderDeliveryDates(checkedShippingMethod);
  renderDeliveryIntervals(checkedShippingMethod);

  if(!event || event.target.className == "shipping_method" || 
    event.target.name == "delivery_dates" || 
    event.target.name == "additional_delivery_interval" ||
    notWorking.length > 0 ||  holidays.length > 0)
    //|| event.target == document
   {
      console.debug('нужен пересчет')
      ajaxGetUrgent();
    } 
    else {
      console.debug('не нужен пересчет')
    }
}

function getCheckedShippingMethod() {
  const checkedShippingMethodInput = document.querySelector('.woocommerce-shipping-methods input:checked');

  return checkedShippingMethodInput ? checkedShippingMethodInput.value : '';
}

function getCheckedDate (){
 let checkedDateInput = Array.from(deliveryDatesInput).find((el)=>el.checked == true); 
 if(checkedDateInput) {
   console.debug('checkedDate ',checkedDateInput.value)
   return checkedDateInput.value;
 } else {
  return ''
 }
}

function getCheckedInterval (){
 let checkedIntervalInput = Array.from(deliveryIntervalInput).find((el)=>el.checked == true); 
 if(checkedIntervalInput) {
   return checkedIntervalInput.value;
 } else {
  return ''
 }
}

//функция отрисовывает поля дат доставки с добавлением стоимости доставки
function renderDeliveryDates(shippingValue) {
  deliveryDatesInfo.forEach((info) => {
    //вычисляем надбавку по конкретной дате
    let thisDayExpensiveMarkup = 0;
    if(expensiveDays && deliveryExpensiveDayMarkup) {
      if(expensiveDays.includes(info.text)) {thisDayExpensiveMarkup = deliveryExpensiveDayMarkup}
    }
    info.label.innerHTML=`${info.text}`;
    let priceEl = document.createElement('span');
    info.label.appendChild(priceEl);
      if(shippingValue == DELIVERY.deliveryInMKAD) {
        priceEl.innerHTML = info.for == `delivery_dates_${urgentTariffDate}` ? `${Number(DELIVERY.deliveryCostInMkad) + Number(DELIVERY.deliveryUrgMarkup) + Number(DELIVERY.deliveryMarkupInMkad) + Number(thisDayExpensiveMarkup)}₽` : `${Number(DELIVERY.deliveryCostInMkad) + Number(DELIVERY.deliveryMarkupInMkad) + Number(thisDayExpensiveMarkup)}₽` ;
      }
      if(shippingValue == DELIVERY.deliveryOutMKAD) {
        priceEl.innerHTML = info.for == `delivery_dates_${urgentTariffDate}` ? `${Number(DELIVERY.deliveryCostOutMkad) + Number(DELIVERY.deliveryUrgMarkup) + Number(DELIVERY.deliveryMarkupOutMkad) + Number(thisDayExpensiveMarkup)}₽` : `${Number(DELIVERY.deliveryCostOutMkad) + Number(DELIVERY.deliveryMarkupOutMkad) + Number(thisDayExpensiveMarkup)}₽` ;
      }

      //если isUrgentCourierTariff и/или isSmallHolidayTariffOn - все перезатираем и делаем по тарифу КС
      if ((shippingValue == DELIVERY.deliveryInMKAD || shippingValue == DELIVERY.deliveryOutMKAD) && isUrgentCourierTariff) {
          if (info.for == `delivery_dates_${urgentTariffDate}`) {
              priceEl.innerHTML = 'по тарифу КС';
          }
      }
      if ((shippingValue == DELIVERY.deliveryInMKAD || shippingValue == DELIVERY.deliveryOutMKAD) && isSmallHolidayTariffOn) {
          if (info.for != `delivery_dates_${urgentTariffDate}`) {
              priceEl.innerHTML = 'по тарифу КС';
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
      if(shippingValue == DELIVERY.localPickupId || shippingValue == DELIVERY.deliveryFreeId || shippingValue == DELIVERY.deliveryPochtaId ||shippingValue == DELIVERY.deliveryCourierId || shippingValue == DELIVERY.deliveryLongId || isSmallHolidayTariffOn) {
      } else {
        if (isUrgent == '1') {
          priceEl.innerHTML = `+0₽`;
        } else {
          priceEl.innerHTML = info.for == `additional_delivery_interval_${deliveryExpensiveInterval}` ? `+${DELIVERY.deliveryExpensiveIntervalMarkup}₽` : `+0₽` ;
        }
      }
  })
  
}

//аякс запрос
function ajaxGetUrgent() {
  console.debug('hi ajaxGetUrgent');
  console.debug('isUrgent ajax', isUrgent);
  console.debug('isLate ajax', isLate);
  console.debug('isExpensive ajax', isExpensive);

  const data = new URLSearchParams();
  data.append('action', 'get_urgent_shipping');
  data.append('isUrgent', isUrgent);
  data.append('isLate', isLate);
  data.append('isExpensive', isExpensive);

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
  const baseDate = new Date(new Date().toLocaleString('en-US', { timeZone: 'Europe/Moscow' }));
  const hour = baseDate.getHours();

  isLate = 0;

  today = `${String(baseDate.getDate()).padStart(2, '0')}.${String(baseDate.getMonth() + 1).padStart(2, '0')}`;

  const tariffDate = new Date(baseDate);
  if (hour >= 20) tariffDate.setDate(tariffDate.getDate() + 1);
  urgentTariffDate = `${String(tariffDate.getDate()).padStart(2, '0')}.${String(tariffDate.getMonth() + 1).padStart(2, '0')}`;

  if(expensiveDays && deliveryExpensiveDayMarkup && expensiveDays.includes(today)) {
    isExpensive = 1
  } else { isExpensive = 0}

  console.debug('today ', today);
  console.debug('this month ', baseDate.getMonth() + 1);
  console.debug('this day ', new Date().getDate());
 
  deliveryIntervalInput[0].checked = true;

  if (notWorking.length > 0) {
    disableNotWorkingDays();
  }

  applyUrgentDeliveryRestrictions();
  selectDefaultDeliveryDate();

  isUrgent = getCheckedDate() === urgentTariffDate ? '1' : '0';
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

  if(DELIVERY.deliveryExpensiveIntervalMarkup) {    
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
      deliveryIntervalInput.forEach(el =>{
          if(el.defaultValue !== '11:00 - 16:00') {
              el.classList.add('d-none');
            } else {
              el.checked = true
              console.log(el)
            }
      })
      deliveryIntervalLabels.forEach(el =>{
          if(el.htmlFor !== 'additional_delivery_interval_11:00 - 16:00') {
              el.classList.add('d-none');
          }
      })
    } else {
      isHoliday = '0'
      deliveryIntervalInput.forEach(el =>{
          el.classList.remove('d-none');
      })
      deliveryIntervalLabels.forEach(el =>{
          el.classList.remove('d-none');
      })
    };
  }

  console.log('isHoliday ', isHoliday)

}

function hideInterval() {
  deliveryInterval.classList.add('d-none');
  deliveryIntervalInput.forEach((input)=>{
      input.checked = false;
  })
  // console.debug(deliveryIntervalInput)
}

function showInterval() {
  deliveryInterval.classList.remove('d-none');
  if (checkedInterval == '') {
      deliveryIntervalInput[0].checked = true;
  }
}

function hideCheckoutFields(event){
  console.log('hi hideCheckoutFields');
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
    if (
      DELIVERY.isBackorder ||
      DELIVERY.isTreezBackorders ||
      checkedShippingMethod == DELIVERY.deliveryPochtaId
    ) {
      deliveryDates.classList.add('d-none');

      deliveryDatesInput.forEach((input) => {
        input.checked = false;
      });
    } else {
      deliveryDates.classList.remove('d-none');

      // Если дата не выбрана, выбираем доступную
      if (!getCheckedDate()) {
        selectDefaultDeliveryDate();
      }
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
      console.debug(document.querySelector('.wc_payment_methods input[name="payment_method"]:checked').value);
      if(event && event.target.id == "payment_method_cheque") {
          innField.classList.remove('d-none');
      } else {
          if(event.target.id == "payment_method_tbank" 
              || event.target.id == "payment_method_cop"
              || event.target.id == "payment_method_cod"
              || document.querySelector('.wc_payment_methods input[name="payment_method"]:checked').value !== 'cheque'
          ) 
          {
              innField.classList.add('d-none')
              innField.value = ''
          }
      };
  }        
}

function disableNotWorkingDays (shippingValue) {
  if (!shippingValue) { shippingValue = getCheckedShippingMethod(); }
  if (notWorkingAllowPickup && shippingValue == DELIVERY.localPickupId) {
    enableNotWorkingDays();
  } else {
    deliveryDatesInput.forEach(date => {
      const unavailable = notWorking.includes(date.value);
      date.disabled = unavailable;
      if (unavailable) date.checked = false;
    });

    deliveryDatesLables.forEach(label => {
      const input = Array.from(deliveryDatesInput).find(date => date.id == label.htmlFor);
      label.classList.toggle('d-none', !!input && notWorking.includes(input.value));
    });
  }
  applyUrgentDeliveryRestrictions();
  if (!getCheckedDate()) selectDefaultDeliveryDate();
}

function enableNotWorkingDays() {
  deliveryDatesLables.forEach(date => {
    date.classList.remove('d-none');
  });
  deliveryDatesInput.forEach(date => {
    date.disabled = false;
  });
  applyUrgentDeliveryRestrictions();
}

if (checkoutForm) {

  setInitalState()

  document.addEventListener('DOMContentLoaded', getDatesIntervalsInfo )

  document.addEventListener('DOMContentLoaded', getOrderParametrs )

  checkoutForm.addEventListener('change', getOrderParametrs);

  // WooCommerce заменяет поля дат при update_checkout.
  // Повторно получаем элементы и применяем ограничения к новой разметке.
  jQuery(document.body).on('updated_checkout', function () {
    deliveryDates = document.querySelector('.delivery_wrap');
    deliveryDatesInput = document.querySelectorAll('.delivery_dates input');
    deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');
    deliveryInterval = document.querySelector('#additional_delivery_interval_field');
    deliveryIntervalInput = document.querySelectorAll('input[name=additional_delivery_interval]');
    deliveryIntervalLabels = document.querySelectorAll('#additional_delivery_interval_field .woocommerce-input-wrapper label');

    checkedShippingMethod = getCheckedShippingMethod();

    const previousDate = checkedDate;
    const oldChoice = Array.from(deliveryDatesInput).find(input => input.value === previousDate);

    if (oldChoice) oldChoice.checked = true;

    applyUrgentDeliveryRestrictions();

    if (!getCheckedDate() && !(DELIVERY.isBackorder || DELIVERY.isTreezBackorders)) {
      selectDefaultDeliveryDate();
    }

    checkedDate = getCheckedDate();
    // Никакого AJAX здесь: предотвращаем цикл пересчётов checkout.
  });
}