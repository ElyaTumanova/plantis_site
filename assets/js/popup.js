class Popup {
  constructor(popup, openBtns) {
    // Инициализация полей класса
    this.popup = popup
    this.openBtns = openBtns
    this.body = document.querySelector('body')
    this.overlay = this.popup.querySelector('.popup-overlay')
    this.closeBtn = this.popup.querySelector('.page-popup__close')
    this.container = this.popup.querySelector('.page-popup__container')
    this.contactForm = this.popup.querySelector('.wpcf7-form')
    this.preloader = this.popup.querySelector('.preloader')

    this.addListeners()
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

  addListeners() {
    // открытие попапа по любой из кнопок
    this.openBtns.forEach(button => {
      button.addEventListener ('click', (evt)=>{
        this.togglePopup()
      })
    })

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
}

document.addEventListener('DOMContentLoaded', initPopups())

function initPopups() {
  const pagePopup = document.querySelector('.page-popup')
  const pagePopupOpenBtns = document.querySelectorAll('.page-popup-open-btn')
  new Popup (pagePopup, pagePopupOpenBtns) 
}