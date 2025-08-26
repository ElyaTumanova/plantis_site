/*--------------------------------------------------------------
# Hiding fields
--------------------------------------------------------------*/

    //все переменные

    let deliveryInterval = document.querySelector('#additional_delivery_interval_field');
    let addressFields = document.querySelector('#billing_address_1_field');
    let additionalAddress = document.querySelector('.additional-address-field');
    let innField = document.querySelector('#additional_inn');

    function hideInterval() {
        deliveryInterval.classList.add('d-none');
        deliveryIntervalInput.forEach((input)=>{
            input.checked = false;
        })
    }

    function showInterval() {
        deliveryInterval.classList.remove('d-none');
        if (checkedInterval == '') {
            deliveryIntervalInput[0].checked = true;
        }
    }

    function plnt_hide_checkout_fields(event){
        //console.log('hi plnt_hide_checkout_fields');
        if (deliveryInterval) {
            if (isBackorder || isTreezBackorders) {
                hideInterval()
            } else { 
                if ( checkedShippingMethod == localPickupId || checkedShippingMethod == deliveryPochtaId) {
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
            if (isBackorder || isTreezBackorders) {
                deliveryDates.classList.add('d-none');
                deliveryDatesInput.forEach((input)=>{
                    input.checked = false;
                })
            } 
        }

        //for address 
        if (checkedShippingMethod == localPickupId) {
            if (addressFields) {addressFields.classList.add('d-none');}
            if (additionalAddress) {additionalAddress.classList.add('d-none');}
        } else {
            if (addressFields) {addressFields.classList.remove('d-none');}
            if (additionalAddress) {additionalAddress.classList.remove('d-none');}
        }
        
        // for INN
        if (innField) {
            console.log(document.querySelector('.wc_payment_methods input[checked="checked"]').value);
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

    if(checkoutForm) {
        document.addEventListener('DOMContentLoaded', plnt_hide_checkout_fields )
        //plnt_hide_checkout_fields(event);
        
        checkoutForm.addEventListener('change', plnt_hide_checkout_fields);
    }

/*--------------------------------------------------------------
# Phone number mask
--------------------------------------------------------------*/

// Маска телефона на странице оформления заказа WooCommerce

document.addEventListener('DOMContentLoaded', function () {
  // Подключаем маску к полям телефона (учтём перерисовки checkout через AJAX)
  function digitsBody(value) {
    let d = (value || '').replace(/\D/g, '');
    if (d.startsWith('8') || d.startsWith('7')) d = d.slice(1);
    return d.slice(0, 10);
  }
  function formatFromDigits(body) {
    let out = '+7';
    if (body.length > 0) { out += ' (' + body.slice(0,3); if (body.length >= 3) out += ')'; }
    if (body.length > 3) out += ' ' + body.slice(3,6);
    if (body.length > 6) out += '-' + body.slice(6,8);
    if (body.length > 8) out += '-' + body.slice(8,10);
    return out;
  }
  function attachPhoneMask(input) {
    if (input.dataset.phoneMaskAttached === '1') return;
    input.dataset.phoneMaskAttached = '1';

    function apply(d) {
      input.value = formatFromDigits(d);
      input.dataset.prevDigits = d;
      try { input.setSelectionRange(input.value.length, input.value.length); } catch(e){}
    }
    function onInput(e) {
      const prev = input.dataset.prevDigits || '';
      const type = (e && e.inputType) || '';
      const isDelete = type.indexOf('delete') === 0 || input.dataset.backspace === '1';
      let d = digitsBody(input.value);
      if (isDelete && prev.length === d.length && prev.length > 0) d = prev.slice(0, -1);
      apply(d);
      input.dataset.backspace = '';
    }
    function onKeydown(e){ if (e.key === 'Backspace') input.dataset.backspace = '1'; }
    function onFocus(){
      if (!input.value.trim()) {
        input.value = '+7 ';
        try { input.setSelectionRange(input.value.length, input.value.length); } catch(e){}
      }
      input.dataset.prevDigits = digitsBody(input.value);
    }
    function onBlur(){
      if (digitsBody(input.value).length < 10) input.value = ''; // незавершённый номер очищаем
    }

    input.setAttribute('maxlength','18'); // "+7 (XXX) XXX-XX-XX"
    input.setAttribute('inputmode','tel');
    input.dataset.prevDigits = '';
    input.dataset.backspace = '';
    input.addEventListener('keydown', onKeydown, false);
    input.addEventListener('input',  onInput,  false);
    input.addEventListener('paste',  onInput,  false);
    input.addEventListener('focus',  onFocus,  false);
    input.addEventListener('blur',   onBlur,   false);

    apply(digitsBody(input.value));
  }

  function initMask() {
    // Стандартные поля Woo: billing_phone (+ возможно shipping_phone)
    document.querySelectorAll('input[name="billing_phone"], input#billing_phone, input[name="shipping_phone"], input#shipping_phone')
      .forEach(attachPhoneMask);
  }

  initMask();

  // WooCommerce часто перерисовывает чек-аут — наблюдаем DOM и перевешиваем маску
  const target = document.querySelector('form.checkout') || document.body;
  const mo = new MutationObserver(function(){ initMask(); });
  mo.observe(target, { childList: true, subtree: true });
});