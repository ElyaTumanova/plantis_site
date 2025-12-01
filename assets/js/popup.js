class Popup {
  constructor(popupName) {
    this.popupName = popupName
  }

  sayHello() {
    console.log('hello '+ this.popupName)
  }

  initDom() {
    this.popup = document.querySelector(`.${this.popupName}`)
    this.openBtns = document.querySelectorAll(`.${this.popupName}-open-btn`)
    this.body = document.querySelector('body')
    this.overlay = this.popup.querySelector('.popup-overlay')
    this.closeBtn = this.popup.querySelector('.popup__close')
    // this.container = this.popup.querySelector('.popup__container')
  }

  openPopup () {
    this.popup.classList.add('popup_active')
    this.body.classList.add ('fix-body')
    console.log('toggle ' + this.popupName)
  }

  closePopup () {
    this.popup.classList.remove('popup_active')
    this.body.classList.remove ('fix-body')
  }

  addOpenListeners () {
    // открытие попапа по любой из кнопок
    this.openBtns.forEach(button => {
      button.addEventListener ('click', (evt)=>{
        this.openPopup()
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

  addAllListeners() {
    this.addOpenListeners()
    this.addCloseListeners()
  }

  init() {
    this.initDom();
    this.addAllListeners();
  }
}

class CF7Popup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.contactForm = null
    this.preloader = null
    this.container = null
  }

  initDom() {
    super.initDom() 
    this.contactForm = this.popup.querySelector('.wpcf7-form')
    this.preloader = this.popup.querySelector('.preloader')
    this.container = this.popup.querySelector('.popup__container')
  }

  cleanForm () {
    if(this.contactForm !=null) {
      this.contactForm.reset()
    }
  }

  closePopup () {
    super.closePopup()
    this.cleanForm ()
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
    super.addAllListeners()
    this.addContactFormListeners()
  }
}

class LoginPopup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.errorMsg = null
    this.mobOpenBtn = null
  }

  initDom() {
    super.initDom() 
    this.errorMsg = this.popup.querySelector('.woocommerce-error')
    // this.mobOpenBtn = document.querySelector('.burger-menu__account')
  }


  closePopup() {
    super.closePopup()

    if (this.errorMsg) {
      this.openPopup()
    }
  }

  addOpenListeners() {
    super.addOpenListeners()

    if(this.mobOpenBtn) {
      this.mobOpenBtn.addEventListener ("click", (evt)=>{
        this.togglePopup()
        // menuMobClass.closeMobMenu() tbd
      })
    }
  }
}

class RegistrPopup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.loginPopup = null
  }

  initDom() {
    super.initDom() 
    this.loginOnRegPopupBtn = this.popup.querySelectorAll('.register-form__login-btn')
  }

  setLoginPopup(popupInstance) {
    this.loginPopup = popupInstance
    console.log(this.loginPopup)
    this.loginPopup.sayHello()
  }

  openPopup () {
    super.openPopup()
    this.loginPopup.closePopup()
  }

  addOpenListeners() {
    super.addOpenListeners()

    this.loginOnRegPopupBtn.forEach((btn)=>
      btn.addEventListener ("click", (evt)=>{
        this.closePopup()
        this.loginPopup.openPopup()
      })
    );
  }
}

class MenuMobPopup extends Popup {

  constructor (popupName) {
    super (popupName)
    this.menu = null
    this.menuOpenBtn = null
    this.catalogOpenBtn = null
    this.menuWrap = null
    this.catalowWrap = null
    this.headerCatalogOpenBtn = null
  }

  initDom() {
    super.initDom()
    this.menu = this.popup.querySelector('.modal-mob')
    this.menuOpenBtn = this.popup.querySelector('.burger-menu__nav_menu')
    this.catalogOpenBtn = this.popup.querySelector('.burger-menu__nav_catalog')
    this.menuWrap = this.popup.querySelector('.burger-menu__wrap')
    this.catalowWrap = this.popup.querySelector('.catalog-menu__wrap')
    this.headerCatalogOpenBtn = document.querySelector('.header__catalog_mob')

  }

  openPopup() {
    super.openPopup()
    this.menu.classList.add ('modal-mob_active')
    this.menu.scrollTo(0, 0)
    this.openMenu()
  }

  closePopup() {
    super.closePopup()
    this.menu.classList.remove ('modal-mob_active')
    this.menu.scrollTo(0, 0)
  }

  openMenu() {
    this.menuWrap.classList.add('burger-menu__wrap_open');
    this.menuOpenBtn.classList.add('burger-menu__nav-btn_active');
    
    this.catalowWrap.classList.remove('catalog-menu__wrap_open');
    this.catalogOpenBtn.classList.remove('burger-menu__nav-btn_active');
  }

  openCatalog() {
    this.menuWrap.classList.remove('burger-menu__wrap_open');
    this.menuOpenBtn.classList.remove('burger-menu__nav-btn_active');
    
    this.catalowWrap.classList.add('catalog-menu__wrap_open');
    this.catalogOpenBtn.classList.add('burger-menu__nav-btn_active');
  }

  static closeMobMenu() {
    console.log('hello super menu')
  }

  addMenuCatalogBtnsListeners() {
    this.menuOpenBtn.addEventListener ("click", (evt)=>{
        this.openMenu()
    })

    this.catalogOpenBtn.addEventListener ("click", (evt)=>{
        this.openCatalog()
    })
  }

  addAllListeners() {
    super.addAllListeners()
    addMenuCatalogBtnsListeners()
  }
}

document.addEventListener('DOMContentLoaded', initPopups)

function initPopups() {
  const popup = new CF7Popup ('page-popup')
  const loginPoup = new LoginPopup ('login-popup')
  const registrPoup = new RegistrPopup ('register-popup')
  registrPoup.setLoginPopup(loginPoup)

  const menuMobPopup = new MenuMobPopup ('burger-menu')

  popup.init()
  loginPoup.init()
  registrPoup.init()
  menuMobPopup.init()

}