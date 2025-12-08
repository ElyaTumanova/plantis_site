class FormsValidation {
  constructor (formSelector) {
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

  initDom () {
    this.form = document.querySelector(this.formSelector)
    console.log(this.formSelector)
    console.log(this.form)
  }

  init() {
    this.initDom()
  }

}

const giftFormValidation = new FormsValidation('.gift-cards_form')