const rootSelector = 'form[name="checkout"]'

const DELIVERY = window.PLNT_Delivery_Data || {};

//formatting
DELIVERY.deliveryCostInMkad = Number(DELIVERY.deliveryCostInMkad)
DELIVERY.deliveryCostOutMkad = Number(DELIVERY.deliveryCostOutMkad)
DELIVERY.deliveryUrgMarkup = Number(DELIVERY.deliveryUrgMarkup)
DELIVERY.deliveryExpensiveIntervalMarkup = Number(DELIVERY.deliveryExpensiveIntervalMarkup)
DELIVERY.deliveryExpensiveDayMarkup = Number(DELIVERY.deliveryExpensiveDayMarkup)
DELIVERY.deliveryMarkupInMkad = Number(DELIVERY.deliveryMarkupInMkad)
DELIVERY.deliveryMarkupOutMkad = Number(DELIVERY.deliveryMarkupOutMkad)

DELIVERY.isUrgentCourierTariff = DELIVERY.isUrgentCourierTariff == '1';
DELIVERY.isSmallHolidayTariffOn = DELIVERY.isSmallHolidayTariffOn == '1';
DELIVERY.isBackorder = DELIVERY.isBackorder == '1';
DELIVERY.isTreezBackorders = DELIVERY.isTreezBackorders == '1';

// config
const expensiveDays = ['07.05', '08.03'] //format dd.mm - дни для дорогой доставки
const noLateIntervalDays = ['30.04'] //format dd.mm - дни для сокращенного интервала
const noDeliveryDays = ['01.05'] //format dd.mm - дни когда нет доставки


class Checkout {
  selectors = {
    root: rootSelector,
    shippingMethods: '.woocommerce-shipping-methods input',
    paymentMethods: '.wc_payment_methods input[name="payment_method"]',
    deliveryDates: '.delivery_dates',
    deliveryDatesInput: '.delivery_dates input',
    deliveryDatesLabels:'.delivery_dates .woocommerce-input-wrapper label',
    deliveryInterval: '#additional_delivery_interval_field',
    deliveryIntervalInput: 'input[name=additional_delivery_interval]',
    deliveryIntervalLabels:'#additional_delivery_interval_field .woocommerce-input-wrapper label',
    addressField: '#billing_address_1_field',
    additionalAddressField: '.additional-address-field',
    innField: '#additional_inn',
  }

  stateClasses = {
    dNone: 'd-none',
    isDisabled: 'disabled',
  }

  constructor (rootElement) {
    // elements
    this.rootElement = rootElement
    this.initDom()
    this.deliveryDatesData = this.getLabelsData(this.deliveryDatesLabelElements)
    this.deliveryIntervalsData = this.getLabelsData(this.deliveryIntervalLabelElements)
    //helpers
    this.today = this.getToday()
    this.isBackorder = DELIVERY.isBackorder || DELIVERY.isTreezBackorders

    //initial state
    this.checkoutState = this.getProxyCheckoutState(this.getCheckoutParametrs())
    this.state = this.getProxyAjaxState(this.getAjaxParametrs())
    console.log({...this.state})
    console.log({...this.checkoutState})

    //initial UI

    this.updateUI()
    this.ajaxGetUrgent()

    this.bindEvents()
    this.debug()
  }

  //helpers

  initDom = () => {

    this.shippingMethodsInputsElements = this.rootElement.querySelectorAll(this.selectors.shippingMethods)
    this.paymentMethodsElements = this.rootElement.querySelectorAll(this.selectors.paymentMethods)
    
    this.deliveryDatesElement = this.rootElement.querySelector(this.selectors.deliveryDates)
    this.deliveryDatesInputElements = this.rootElement.querySelectorAll(this.selectors.deliveryDatesInput)
    this.deliveryDatesLabelElements = this.rootElement.querySelectorAll(this.selectors.deliveryDatesLabels)
    
    
    this.deliveryIntervalElement = this.rootElement.querySelector(this.selectors.deliveryInterval)
    this.deliveryIntervalInputElements = this.rootElement.querySelectorAll(this.selectors.deliveryIntervalInput)
    this.deliveryIntervalLabelElements = this.rootElement.querySelectorAll(this.selectors.deliveryIntervalLabels)
    
    
    this.addressFieldElement = this.rootElement.querySelector(this.selectors.addressField)
    this.additionalAddressFieldElement = this.rootElement.querySelector(this.selectors.additionalAddressField)
    this.innFieldElement = this.rootElement.querySelector(this.selectors.innField)
  }

  getCheckedInputValue(inputElements) {
    const checkedInput = [...inputElements].find((input) => input.checked)
    return checkedInput?.value
  }

  getLabelsData(elements) {
    return [...elements].map((label) => ({
      label,
      for: label.htmlFor,
      text: label.textContent.trim(),
    }));
  }

  getToday() {
    const hour = new Date().getHours();
    const baseDate = new Date();
    if (hour >= 20) baseDate.setDate(baseDate.getDate() + 1);

    const today = `${String(baseDate.getDate()).padStart(2,'0')}.${String(baseDate.getUTCMonth()+1).padStart(2,'0')}`;

    return today
  }


  //UI helpers
  toggleDisplayElement(element, isHidden) {
    element.classList.toggle(this.stateClasses.dNone, isHidden)
  }

  toggleDisableDateInterval(element, isDisabled) {
    let input = this.rootElement.querySelector(`#${CSS.escape(element.for)}`)
    input.disabled = isDisabled;
    element.label.classList.toggle(this.stateClasses.isDisabled, isDisabled)
    this.unCheckFields([input], isDisabled)
  }

  isHiddenInterval() {
    return (
      this.isBackorder ||
      this.checkoutState.isLocalPickup ||
      this.state.isUrgent ||
      this.checkoutState.shippingMethod == DELIVERY.deliveryPochtaId
    );
  }

  getShippingPriceByDate(shippingValue, date) {
    const isToday = date == this.today;
    let price = ''

    if(shippingValue == DELIVERY.deliveryInMKAD || shippingValue == DELIVERY.deliveryOutMKAD) {
      if(this.isCourierTariff(date)) return 'по тарифу КС'

      const baseTariff = shippingValue === DELIVERY.deliveryOutMKAD
      ? DELIVERY.deliveryCostOutMkad + DELIVERY.deliveryMarkupOutMkad
      : DELIVERY.deliveryCostInMkad + DELIVERY.deliveryMarkupInMkad;
      
      const expensiveMarkup = this.isExpensiveDay(date)
        ? DELIVERY.deliveryExpensiveDayMarkup
        : 0;

      const urgentMarkup = isToday
        ? DELIVERY.deliveryUrgMarkup
        : 0;

      price = baseTariff + expensiveMarkup + urgentMarkup
    }
    if (shippingValue == DELIVERY.deliveryLongId) {
      return 'по тарифу КС'
    }
    return price
  }

  //UI functions

  renderDeliveryDates(shippingValue = this.checkoutState.shippingMethod) {
    this.deliveryDatesData.forEach((element) => {
      element.label.innerHTML=`${element.text}`;
      let priceEl = document.createElement('span');
      element.label.appendChild(priceEl);
      priceEl.innerHTML = this.getShippingPriceByDate(shippingValue, element.text)
      
      if(noDeliveryDays.length > 0) {
        let isDisabled = !this.checkoutState.isLocalPickup && noDeliveryDays.includes(element.text)
        this.toggleDisableDateInterval(element, isDisabled)
      }
    })
  }

  renderDeliveryIntervals(shippingValue = this.checkoutState.shippingMethod) {
    this.deliveryIntervalsData.forEach((element) => {
      let priceEl = document.createElement('span')
      element.label.innerHTML=`${element.text}`
      element.label.appendChild(priceEl)
      if(this.isPaidDelivery()) {
        priceEl.innerHTML = element.for == `additional_delivery_interval_${DELIVERY.deliveryExpensiveInterval}` ? `+${DELIVERY.deliveryExpensiveIntervalMarkup}₽` : `+0₽`
      }

      if(noLateIntervalDays.length > 0) {
        let isDisabled = noLateIntervalDays.includes(this.checkoutState.deliveryDate) && element.text == DELIVERY.deliveryLateInterval
        this.toggleDisableDateInterval(element, isDisabled)
      }
    })
    
  }

  hideCheckoutFields() {
    const isIntervalHidden = this.isHiddenInterval();
    this.toggleDisplayElement(this.deliveryIntervalElement, isIntervalHidden)
    this.unCheckFields(this.deliveryIntervalInputElements, isIntervalHidden)

    this.toggleDisplayElement(this.deliveryDatesElement, this.isBackorder)
    this.unCheckFields(this.deliveryDatesInputElements, this.isBackorder)

    this.toggleDisplayElement(this.addressFieldElement, this.checkoutState.isLocalPickup)
    this.toggleDisplayElement(this.additionalAddressFieldElement, this.checkoutState.isLocalPickup)
    
    this.toggleDisplayElement(this.innFieldElement, this.checkoutState.paymentMethod != 'cheque')
  }

  //proxy state
  getProxyCheckoutState(initialState) {
    return new Proxy(initialState, {
      get: (target, prop) => {
        return target[prop]
      },
      set: (target, prop, newValue) => {
        const oldValue = target[prop]

        target[prop] = newValue

        if (newValue !== oldValue) {
          this.setAjaxState()
          this.updateUI()
        }
        return true
      },
    })
  }

  getProxyAjaxState(initialState) {
    return new Proxy(initialState, {
      get: (target, prop) => {
        return target[prop]
      },
      set: (target, prop, newValue) => {
        const oldValue = target[prop]

        target[prop] = newValue

        if (newValue !== oldValue) {
          this.ajaxGetUrgent()
        }
        return true
      },
    })
  }

  setAjaxState() {
    Object.assign(this.state, this.getAjaxParametrs());
    console.log({...this.state})
  }
  
  setCheckoutState() {
    Object.assign(this.checkoutState, this.getCheckoutParametrs());
    console.log({ ...this.checkoutState });
  }

  //state handlers
  updateUI() {
    this.hideCheckoutFields()
    this.renderDeliveryDates()
    this.renderDeliveryIntervals()
  }

  ajaxGetUrgent() {
    const data = new URLSearchParams();
    data.append('action', 'get_urgent_shipping');
    data.append('isUrgent', Number(this.state.isUrgent));
    data.append('isLate', Number(this.state.isLate) );
    data.append('isExpensive', Number(this.state.isExpensive) );

    console.log('тут fetch')
    console.log(Object.fromEntries(data));


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

  //HELPERS - CORE LOGIC
  isExpensiveDay(date) {
    return (expensiveDays && DELIVERY.deliveryExpensiveDayMarkup && expensiveDays.includes(date))
  }

  isCourierTariff(date) {
    return (
      (DELIVERY.isSmallHolidayTariffOn && date != this.today) 
      || (DELIVERY.isUrgentCourierTariff && date == this.today)
    )
  }

  isPaidDelivery() {
    return (
      (this.checkoutState.shippingMethod == DELIVERY.deliveryInMKAD || this.checkoutState.shippingMethod == DELIVERY.deliveryOutMKAD)
      && (!this.isCourierTariff(this.checkoutState.deliveryDate))
    )
  }

  //STATE PARAMETRS - CORE LOGIC
  getCheckoutParametrs = () => {
    return {
      'shippingMethod': this.getCheckedInputValue(this.shippingMethodsInputsElements),
      'deliveryDate': this.getCheckedInputValue(this.deliveryDatesInputElements),
      'deliveryInterval': this.getCheckedInputValue(this.deliveryIntervalInputElements),
      'paymentMethod': this.getCheckedInputValue(this.paymentMethodsElements),
      'isLocalPickup': this.getCheckedInputValue(this.shippingMethodsInputsElements) == DELIVERY.localPickupId
    }
  }

  getAjaxParametrs() {
    let isUrgent = this.checkoutState.deliveryDate == this.today && this.isPaidDelivery()
    let isLate = this.checkoutState.deliveryInterval == DELIVERY.deliveryExpensiveInterval && this.isPaidDelivery()
    let isExpensive = this.isExpensiveDay(this.checkoutState.deliveryDate) && this.isPaidDelivery()

    return {
      'isUrgent': isUrgent,
      'isLate': isLate,
      'isExpensive': isExpensive,
    }
  }


  //key events for state

  unCheckFields(elements, isHidden) {
    if (!isHidden) return;

    let hasChanged = false;

    elements.forEach((el) => {
      if (el.checked) {
        el.checked = false;
        hasChanged = true;
      }
    });

    if (hasChanged) {
      this.setCheckoutState();
    }
  }

  onFormChange = (evt) => {
    this.setCheckoutState()
  }

  onFormSubmit = (evt) => {
    event.preventDefault();

    const formData = new FormData(this.rootElement);

    const shippingMethod = formData.get('shipping_method[0]');
    const deliveryDate = formData.get('delivery_dates');
    const deliveryInterval = formData.get('additional_delivery_interval');

    console.log({
      shippingMethod,
      deliveryDate,
      deliveryInterval,
    });
  }

  bindEvents() {
    this.rootElement.addEventListener('change', this.onFormChange)
    this.rootElement.addEventListener('submit', this.onFormSubmit)

    jQuery(document.body).on('updated_checkout', this.initDom);
  }

  debug() {

  }
}

class CheckoutCollection {
   constructor() {
    this.init()
  }

  init() {
    document.querySelectorAll(rootSelector).forEach((element) => {
      new Checkout(element)
    })
  }
}

new CheckoutCollection


