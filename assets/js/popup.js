class Popup {
  constructor(popupName) {
    // Инициализация полей класса
    this.popupName = popupName
    this.popup = document.querySelector(`.${popupName}`)
    this.openBtns = document.querySelectorAll(`.${popupName}-open-btn`)
    this.body = document.querySelector('body')
    this.overlay = this.popup.querySelector('.popup-overlay')
    this.closeBtn = this.popup.querySelector('.popup__close')
    this.container = this.popup.querySelector('.page-popup__container')
    this.contactForm = this.popup.querySelector('.wpcf7-form')
    this.preloader = this.popup.querySelector('.preloader')

    this.addAllListeners()
  }

  sayHello() {
    console.log('hello '+ this.popupName)
  }

  togglePopup () {
    this.popup.classList.toggle('popup_active')
    this.body.classList.toggle ('fix-body')
    console.log('toggle ' + this.popupName)
  }

  cleanForm () {
    if(this.contactForm !=null) {
      this.contactForm.reset()
    }
  }

  closePopup () {
    this.togglePopup()
    this.cleanForm ()
  }

  addOpenListeners () {
    // открытие попапа по любой из кнопок
    this.openBtns.forEach(button => {
      button.addEventListener ('click', (evt)=>{
        this.togglePopup()
      })
    })
  }

  addCloseListeners() {
    this.overlay.addEventListener('click', (evt) => {
      this.closePopup()
    })

    this.closeBtn.addEventListener('click', (evt) => {
      this.closePopup()
    })

    document.addEventListener('keydown', (e) =>{
        if((e.key=='Escape'||e.key=='Esc')){
            if(this.popup.classList.contains('popup_active')) {
                this.closePopup()
            } 
        }
    }, true);
  }
  
  addContactFormListeners() {
    if(this.contactForm !=null) {
      this.contactForm.addEventListener('submit', (evt) => {this.preloader.classList.add('active')})

      document.addEventListener('wpcf7submit', (evt) => {
          // Универсальный обработчик отправки
          this.container.style.visibility = 'hidden';
          this.preloader.classList.remove('active');
          setTimeout(() => {
            this.popup.classList.remove('popup_active');
            this.body.classList.remove('fix-body');
            this.cleanForm();
            this.container.style.visibility = 'visible';
          }, 4000);
      }, false);
    }
  }

  addAllListeners() {
    this.addOpenListeners()
    this.addCloseListeners()
    this.addContactFormListeners()
  }
}

class LoginPopup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.siblingPopup = null
    this.errorMsg = this.popup.querySelector('.woocommerce-error')
  }

  setSiblingPopup(popupInstance) {
    this.siblingPopup = popupInstance
    console.log(this.siblingPopup)
    this.siblingPopup.sayHello()
  }

  closePopup() {
    super.closePopup()

    if (this.errorMsg) {
      this.togglePopup()
    }
  }
}

class RegistrPopup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.siblingPopup = null
    this.loginOnRegPopupBtn = this.popup.querySelectorAll('.register-form__login-btn')
  }

  setSiblingPopup(popupInstance) {
    this.siblingPopup = popupInstance
    console.log(this.siblingPopup)
    this.siblingPopup.sayHello()
  }

  togglePopup () {
    super.togglePopup()
    this.siblingPopup.togglePopup()
  }

  // addOpenListeners() {
  //   super.addOpenListeners()

  //   this.loginOnRegPopupBtn.forEach((btn)=>
  //     btn.addEventListener ("click", (evt)=>{
  //       this.togglePopup ()
  //     })
  //   );
  // }

}


document.addEventListener('DOMContentLoaded', initPopups)

function initPopups() {
  const poup = new Popup ('page-popup')
  const loginPoup = new LoginPopup ('login-popup')
  const registrPoup = new RegistrPopup ('register-popup')

  loginPoup.setSiblingPopup(registrPoup)
  registrPoup.setSiblingPopup(loginPoup)
}