class FormsValidation {
  constructor (formSelector) {
    this.formSelector = formSelector
    this.form = null
  }

  onBlur(event) {
    const { target } = event
    const isFormField = target.closest(this.formSelector)
    const isRequired = target.required
    console.log(isFormField)
    console.log(isRequired)

    if (isFormField && isRequired) {
      this.validateField(target)
    }
  }

  validateField(fieldControlElement) {
    const errors = fieldControlElement.validity
    const errorMessages = []

    console.log(errors)

    // Object.entries(this.errorMessages).forEach(([errorType, getErrorMessage]) => {
    //   if (errors[errorType]) {
    //     errorMessages.push(getErrorMessage(fieldControlElement))
    //   }
    // })


    // this.manageErrors(fieldControlElement, errorMessages)

    // const isValid = errorMessages.length === 0

    // fieldControlElement.ariaInvalid = !isValid

    // return isValid
  }

  initDom () {
    this.form = document.querySelector(this.formSelector)
  }

  init() {
    this.initDom()
  }

}

const giftFormValidation = new FormsValidation('.gift-cards_form')
giftFormValidation.init()