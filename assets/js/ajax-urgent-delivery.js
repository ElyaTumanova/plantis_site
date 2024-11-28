let isUrgent = '0';

function plntChekUrgentDelivery() {
  let deliveryDates = document.querySelectorAll('.delivery_dates input');
  console.log(deliveryDates);
  console.log(deliveryDates[0]);
  deliveryDates[0].setAttribute('checked','checked');

  deliveryDates.forEach((date) => {
    date.addEventListener('click', function(event){
      console.log(event.target.value);
      if(event.target.value == 'today') {
        isUrgent = '1';
      } else {
        isUrgent = '0';
      }
      console.log(isUrgent);
      plntAjaxGetUrgent();
      });

  })
};


plntChekUrgentDelivery();


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