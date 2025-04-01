
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

function renderDeliveryIntervals(shippingValue,date) {
  deliveryIntervalsInfo.forEach((info) => {
    let priceEl = document.createElement('span');
    info.label.innerHTML=`${info.text}`;
    info.label.appendChild(priceEl);
      if(shippingValue == localPickupId || shippingValue == deliveryFreeId || shippingValue == deliveryPochtaId ||shippingValue == deliveryCourierId || shippingValue == deliveryLongId || date === '08.03') {
      } else {
        if (isUrgent == '1') {
          priceEl.innerHTML = `+0₽`;
        } else {
          priceEl.innerHTML = info.for == `additional_delivery_interval_18:00 - 21:00` ? `+${deliveryLateMarkup}₽` : `+0₽` ;
        }
      }
  })
  
}

function ajaxGetUrgent() {

  console.log(isUrgent);

  if (isUrgent == '1') {
    let urgentComent = document.createElement("div");
    urgentComent.classList.add('checkout__comment_urgent');
    urgentComent.append('Это срочная доставка');
    checkoutComment.append(urgentComent);
  }

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
  console.log('hi ajaxGetLateDelivery');
  if (event) {
    if(event.target.value == '18:00 - 21:00') {
      isLate = '1';
    } else {
      isLate = '0';
    }
  } else {
    isLate = '0'
  }
  console.log(isLate);

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
  deliveryIntervalInput[0].setAttribute('checked','checked');

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

if (checkoutForm) {
  setInitalState();
  checkedShippingMethod = getCheckedShippingMethod();
  console.log(checkedShippingMethod);
  getDeliveryCosts(checkedShippingMethod);

  setDatesIntervals();


  renderDeliveryIntervals(checkedShippingMethod);

  checkoutForm.addEventListener('change', onChangeShippingMethod);
  
  datepicker_create ();

  //console.log(isHideInterval);
  console.log(isBackorder);
  console.log(isUrgent);

  
}