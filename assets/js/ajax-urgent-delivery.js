let isUrgent = '0';
let checkoutForm = document.querySelector('form[name="checkout"]');
let deliveryDates = document.querySelectorAll('.delivery_dates input');
let deliveryDatesLables = document.querySelectorAll('.delivery_dates .woocommerce-input-wrapper label');



console.log(deliveryDatesLables);
//console.log(deliveryInMKAD);

function plntChekUrgentDelivery() {
  // console.log(deliveryDates);
  // console.log(deliveryDates[1]);
  deliveryDates[1].setAttribute('checked','checked');

  deliveryDates.forEach((date) => {
    date.addEventListener('click', function(event){
      //console.log(event.target.value);
      if(event.target.value == 'today') {
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
        renderDeliveryDates(event);
    }
}

function renderDeliveryDates(event) {
  console.log(event.target.value);
  if(event.target.value == deliveryInMKAD) {
    deliveryDatesLables.forEach((label) => {
      let text = label.textContent;
      console.log(label);
      console.log(text);
      if(label.htmlFor == 'delivery_dates_today') {
        label.innerHTML('lalal');
      } else {
        label.innerHTML ('hohoho');
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
  
    let urgentText = document.querySelector('.checkout__urgent-text');
    console.log(urgentText);
    if (urgentText) {
      if (isUrgent == '1') {
        console.log('hi text');
        urgentText.innerHTML = "Доставка для выбранной даты является срочной, поэтому стоимость доставки увеличена.";
        } else {
        urgentText.innerHTML = "";
      }
    }
  };