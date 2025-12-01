class Popup {
  constructor(popupName) {
    // Инициализация полей класса
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

  togglePopup () {
    this.popup.classList.toggle('popup_active')
    this.body.classList.toggle ('fix-body')
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

class LogRegPopup extends Popup {

}


document.addEventListener('DOMContentLoaded', initPopups())

function initPopups() {
  new Popup ('page-popup')
  new LogRegPopup ('login-popup')
  new LogRegPopup ('register-popup')
}