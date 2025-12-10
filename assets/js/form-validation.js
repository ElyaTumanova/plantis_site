class PhoneMask {
  constructor(input, onChange) {
    this.input = input;
    this.onChange = typeof onChange === 'function' ? onChange : null;

    this.handleInput = this.handleInput.bind(this);
    this.handleKeydown = this.handleKeydown.bind(this);
    this.handleFocus = this.handleFocus.bind(this);
    this.handleBlur = this.handleBlur.bind(this);

    this.init();
  }

  static attach(input, onChange) {
    if (!input) return null;
    return new PhoneMask(input, onChange);
  }

  init() {
    const input = this.input;

    input.dataset.prevDigits = '';
    input.dataset.backspace = '';
    input.setAttribute('maxlength', '18'); // "+7 (XXX) XXX-XX-XX"
    input.setAttribute('inputmode', 'tel');

    input.addEventListener('keydown', this.handleKeydown, false);
    input.addEventListener('input', this.handleInput, false);
    input.addEventListener('paste', this.handleInput, false);
    input.addEventListener('focus', this.handleFocus, false);
    input.addEventListener('blur', this.handleBlur, false);

    this.apply(this.digitsBody(input.value));
  }

  destroy() {
    const input = this.input;
    input.removeEventListener('keydown', this.handleKeydown, false);
    input.removeEventListener('input', this.handleInput, false);
    input.removeEventListener('paste', this.handleInput, false);
    input.removeEventListener('focus', this.handleFocus, false);
    input.removeEventListener('blur', this.handleBlur, false);
  }

  apply(d) {
    const input = this.input;
    input.value = this.formatFromDigits(d);
    input.dataset.prevDigits = d;

    try {
      input.setSelectionRange(input.value.length, input.value.length);
    } catch (e) {}
  }

  handleInput(e) {
    const input = this.input;
    const prev = input.dataset.prevDigits || '';
    const type = (e && e.inputType) || '';
    const isDelete =
      type.indexOf('delete') === 0 || input.dataset.backspace === '1';

    let d = this.digitsBody(input.value);

    if (isDelete && prev.length === d.length && prev.length > 0) {
      d = prev.slice(0, -1);
    }

    this.apply(d);
    input.dataset.backspace = '';

    if (this.onChange) this.onChange();
  }

  handleKeydown(e) {
    if (e.key === 'Backspace') {
      this.input.dataset.backspace = '1';
    }
  }

  handleFocus() {
    const input = this.input;

    if (!input.value.trim()) {
      input.value = '+7 ';
      try {
        input.setSelectionRange(input.value.length, input.value.length);
      } catch (e) {}
    }
    input.dataset.prevDigits = this.digitsBody(input.value);
  }

  handleBlur() {
    const input = this.input;

    if (this.digitsBody(input.value).length === 0) input.value = '';
    if (this.onChange) this.onChange();
  }

  digitsBody(value) {
    return value.replace(/\D/g, '');
  }

  formatFromDigits(d) {
    const digits = d.replace(/\D/g, '').replace(/^7/, '');

    let res = '+7 ';

    if (digits.length > 0) {
      res += '(' + digits.substring(0, 3);
    }
    if (digits.length >= 3) {
      res += ') ' + digits.substring(3, 6);
    }
    if (digits.length >= 6) {
      res += '-' + digits.substring(6, 8);
    }
    if (digits.length >= 8) {
      res += '-' + digits.substring(8, 10);
    }

    return res;
  }
}


class FormsValidation {
  errorMessages = {
    valueMissing: () => 'Пожалуйста, заполните это поле',
    patternMismatch: ({ title }) => title || 'Данные не соответствуют формату',
    tooShort: ({ minLength }) => `Слишком короткое значение, минимум символов — ${minLength}`,
    tooLong: ({ maxLength }) => `Слишком длинное значение, ограничение символов — ${maxLength}`,
  }

  constructor (formSelector, errorMessageSelector) {
    this.formSelector = formSelector
    this.errorMessageSelector = errorMessageSelector
    this.form = null
    this.sumbmitBtn = null
    this.phoneInput = null
  } 

  manageErrors(fieldControlElement, errorMessages) {
    if (!this.errorMessageSelector) return
    const fieldErrorsElement = fieldControlElement.parentElement.querySelector(this.errorMessageSelector)

    fieldErrorsElement.innerHTML = errorMessages
      .map((message) => `<span class="field__error">${message}</span>`)
      .join('')
  }

  validateField(fieldControlElement) {
    const errors = fieldControlElement.validity
    const errorMessages = []

    console.log(errors)

    Object.entries(this.errorMessages).forEach(([errorType, getErrorMessage]) => {
      if (errors[errorType]) {
        errorMessages.push(getErrorMessage(fieldControlElement))
      }
    })

    console.log(errorMessages)


    this.manageErrors(fieldControlElement, errorMessages)

    const isValid = errorMessages.length === 0

    // fieldControlElement.ariaInvalid = !isValid

    return isValid
  }

  validateForm() {
    const isFormValid = this.form.checkValidity()

    this.sumbmitBtn.disabled = !isFormValid
    this.sumbmitBtn.classList.toggle('is-disabled', !isFormValid)
  }

  onBlur(event) {
    const { target } = event
    const isFormField = target.closest(this.formSelector)
    const isRequired = target.required

    if (isFormField && isRequired) {
      this.validateField(target)
      this.validateForm()
    }
  }

  onSubmit(event) {
    event.preventDefault()
    const isFormElement = event.target.matches(this.formSelector)
    if (!isFormElement) {
      return
    }

    const requiredControlElements = [...event.target.elements].filter(({ required }) => required)
    let isFormValid = true
    let firstInvalidFieldControl = null

    requiredControlElements.forEach((element) => {
      const isFieldValid = this.validateField(element)

      if (!isFieldValid) {
        isFormValid = false

        if (!firstInvalidFieldControl) {
          firstInvalidFieldControl = element
        }
      }
    })

    if (!isFormValid) {
      event.preventDefault()
      firstInvalidFieldControl.focus()
    }
  }

  bindEvents() {
    document.addEventListener('blur', (event) => {
      this.onBlur(event)
    }, { capture: true })
    document.addEventListener('submit', (event) => this.onSubmit(event))
    document.addEventListener('DOMContentLoaded', (event) => this.validateForm())
  }

  initDom () {
    this.form = document.querySelector(this.formSelector)
    this.sumbmitBtn = this.form.querySelector('button[type="submit"]')
    this.phoneInput = this.form.querySelector('input[type="tel"]');
    console.log(this.phoneInput)
  }

  init() {
    this.initDom()
    this.bindEvents()
    this.phoneMask = PhoneMask.attach(phoneInput);
  }

}

class GiftFormValidation extends FormsValidation {
   constructor (formSelector, errorMessageSelector) {
     super (formSelector, errorMessageSelector)
    }
}

const giftFormValidation = new GiftFormValidation('.gift-cards_form','.field__errors')
giftFormValidation.init()