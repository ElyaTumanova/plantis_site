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
        console.log(deliveryIntervalInput)
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
    if (body.length === 0) return '';
    let out = '+7';
    if (body.length >= 1) { out += ' (' + body.slice(0, Math.min(3, body.length)); if (body.length >= 3) out += ')'; }
    if (body.length >= 4) out += ' ' + body.slice(3, Math.min(6, body.length));
    if (body.length >= 7) out += '-' + body.slice(6, Math.min(8, body.length));
    if (body.length >= 9) out += '-' + body.slice(8, Math.min(10, body.length));
    return out;
  }
  function attachPhoneMask(input, onChange) {
    if (input.dataset.phoneMaskAttached === '1') return;
    input.dataset.phoneMaskAttached = '1';

    function apply(d) {
        const formatted = formatFromDigits(d);
        input.value = formatted;                 // если d пусто → '', placeholder виден
        input.dataset.prevDigits = d;
        try { input.setSelectionRange(input.value.length, input.value.length); } catch (e) {}
    }

    function onInput(e) {
        const prev = input.dataset.prevDigits || '';
        const type = (e && e.inputType) || '';
        const isDelete = type.indexOf('delete') === 0 || input.dataset.backspace === '1';

        let d = digitsBody(input.value);
        // Backspace по служебным символам маски: удаляем ещё и предыдущую цифру
        if (isDelete && prev.length === d.length && prev.length > 0) d = prev.slice(0, -1);

        apply(d);
        input.dataset.backspace = '';
        if (typeof onChange === 'function') onChange();
    }

    function onKeydown(e) {
        if (e.key === 'Backspace') input.dataset.backspace = '1';
    }

    function onFocus() {
        const d = digitsBody(input.value);
        if (d.length === 0) {
        // Пустое поле → показываем префикс +7 и ставим курсор в конец
        input.value = '+7 ';
        try { input.setSelectionRange(input.value.length, input.value.length); } catch (e) {}
        input.dataset.prevDigits = '';
        } else {
        // Если что-то уже введено — просто переформатируем
        apply(d);
        }
    }

    function onBlur() {
        const d = digitsBody(input.value);
        if (d.length === 0) {
        // Ничего не ввели → очищаем, чтобы вернулся placeholder
        input.value = '';
        } // если цифры есть (даже неполный номер) — оставляем как есть
        if (typeof onChange === 'function') onChange();
    }

    input.setAttribute('maxlength', '18'); // "+7 (XXX) XXX-XX-XX"
    input.setAttribute('inputmode', 'tel');
    input.dataset.prevDigits = '';
    input.dataset.backspace = '';
    input.addEventListener('keydown', onKeydown, false);
    input.addEventListener('input',  onInput,  false);
    input.addEventListener('paste',  onInput,  false);
    input.addEventListener('focus',  onFocus,  false);
    input.addEventListener('blur',   onBlur,   false);

    // Инициализация: не подставляем "+7", чтобы placeholder был виден
    apply(digitsBody(input.value)); // если пусто → оставит ''
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