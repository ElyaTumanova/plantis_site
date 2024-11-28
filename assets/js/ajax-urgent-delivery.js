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