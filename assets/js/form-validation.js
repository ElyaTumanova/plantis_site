class FormsValidation {
  errorMessages = {
    valueMissing: () => 'Пожалуйста, заполните это поле',
    patternMismatch: ({ title }) => title || 'Данные не соответствуют формату',
    tooShort: ({ minLength }) => `Слишком короткое значение, минимум символов — ${minLength}`,
    tooLong: ({ maxLength }) => `Слишком длинное значение, ограничение символов — ${maxLength}`,
  }

  constructor (formSelector, errorMessageSelector) {
    this.formSelector = formSelector
    this.form = null
  }

  onBlur(event) {
    const { target } = event
    const isFormField = target.closest(this.formSelector)
    const isRequired = target.required

    if (isFormField && isRequired) {
      this.validateField(target)
    }
  }

  onSubmit(event) {
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

  manageErrors(fieldControlElement, errorMessages) {
    const fieldErrorsElement = fieldControlElement.parentElement.querySelector(this.selectors.fieldErrors)

    fieldErrorsElement.innerHTML = errorMessages
      .map((message) => `<span class="field__error">${message}</span>`)
      .join('')
  }

  bindEvents() {
    document.addEventListener('blur', (event) => {
      this.onBlur(event)
    }, { capture: true })
    // document.addEventListener('change', (event) => this.onChange(event))
    document.addEventListener('submit', (event) => this.onSubmit(event))
  }

  initDom () {
    this.form = document.querySelector(this.formSelector)
  }

  init() {
    this.initDom()
    this.bindEvents()
  }

}

const giftFormValidation = new FormsValidation('.gift-cards_form')
giftFormValidation.init()