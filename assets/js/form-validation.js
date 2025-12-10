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

  bindEvents() {
    document.addEventListener('blur', (event) => {
      this.onBlur(event)
    }, { capture: true })
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

class GiftFormValidation {
   constructor (formSelector, errorMessageSelector) {
     super ()
  }
}

const giftFormValidation = new FormsValidation('.gift-cards_form','.field__errors')
giftFormValidation.init()