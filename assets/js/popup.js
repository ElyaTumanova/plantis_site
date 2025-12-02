class Popup {
  constructor(popupName) {
    this.popupName = popupName
    this.popup = null
    this.openBtns = null
    this.body = null
    this.overlay = null
    this.closeBtn = null
  }

  sayHello() {
    console.log('hello '+ this.popupName)
  }

  initDom() {
    this.popup = document.querySelector(`.${this.popupName}`)

    if (!this.popup) {
      console.debug(`Popup .${this.popupName} не найден, init() пропускаем`)
      return
    }

    this.openBtns = document.querySelectorAll(`.${this.popupName}-open-btn`)
    this.body = document.querySelector('body')
    this.overlay = this.popup.querySelector('.popup-overlay')
    this.closeBtn = this.popup.querySelector('.popup__close')

    return true
  }

  openPopup () {
    this.popup.classList.add('popup_active')
    this.body.classList.add ('fix-body')
    console.debug('open ' + this.popupName)
  }

  closePopup () {
    this.popup.classList.remove('popup_active')
    this.body.classList.remove ('fix-body')
  }

  togglePopup() {
    console.debug('toogle ' + this.popupName)
    this.popup.classList.toggle('popup_active')
    this.body.classList.toggle ('fix-body')
  }

  addOpenListeners () {
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
    const ok = this.initDom()
    if (!ok) return
    this.addAllListeners()
  }
}

class CF7Popup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.contactForm = null
    this.preloader = null
    this.container = null
    this.serviceNameInput = null
  }

  initDom() {
    const ok = super.initDom()
    if (!ok) return false
    this.contactForm = this.popup.querySelector('.wpcf7-form')
    this.preloader = this.popup.querySelector('.preloader')
    this.container = this.popup.querySelector('.popup__container')
    this.serviceNameInput = this.popup.querySelector('.ukhod-popup-service-name')
    return true
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

  addServiceNameListeners() {
    if(this.serviceNameInput) {
      this.openBtns.forEach(btn => {
        btn.addEventListener('click', (evt) => {
          this.serviceNameInput.setAttribute('value',evt.target.name);
        });
      });
    }
  }

  addAllListeners() {
    super.addAllListeners()
    this.addContactFormListeners()
    this.addServiceNameListeners()
  }
}

class LoginPopup extends Popup {
  constructor (popupName) {
    super (popupName)
    this.errorMsg = null
    this.mobMenuLoginPopupOpenBtn = null
  }

  initDom() {
    const ok = super.initDom()
    if (!ok) return false
    this.errorMsg = this.popup.querySelector('.woocommerce-error')
    this.mobMenuLoginPopupOpenBtn = document.querySelector('.burger-menu__account')
    return true
  }


  closePopup() {
    super.closePopup()

    if (this.errorMsg) {
      this.openPopup()
    }
  }

  addOpenListeners() {
    super.addOpenListeners()

    if(this.mobMenuLoginPopupOpenBtn) {
      this.mobMenuLoginPopupOpenBtn.addEventListener ("click", (evt)=>{
        this.openPopup()
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
    const ok = super.initDom()
    if (!ok) return false
    this.loginOnRegPopupBtn = this.popup.querySelectorAll('.register-form__login-btn')
    return true
  }

  setLoginPopup(popupInstance) {
    this.loginPopup = popupInstance
    // this.loginPopup.sayHello()
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
    this.mobHeaderCatalogOpenBtn = null
    this.mobMenuLoginPopupOpenBtn = null
  }

  initDom() {
    const ok = super.initDom()
    if (!ok) return false
    this.menu = this.popup.querySelector('.modal-mob')
    this.menuOpenBtn = this.popup.querySelector('.burger-menu__nav_menu')
    this.catalogOpenBtn = this.popup.querySelector('.burger-menu__nav_catalog')
    this.menuWrap = this.popup.querySelector('.burger-menu__wrap')
    this.catalowWrap = this.popup.querySelector('.catalog-menu__wrap')
    this.mobHeaderCatalogOpenBtn = document.querySelector('.header__catalog_mob')
    this.mobMenuLoginPopupOpenBtn = document.querySelector('.burger-menu__account')
    return true
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


  addMenuCatalogBtnsListeners() {
    this.menuOpenBtn.addEventListener ("click", (evt)=>{
        this.openMenu()
    })

    this.catalogOpenBtn.addEventListener ("click", (evt)=>{
        this.openCatalog()
    })

    this.mobHeaderCatalogOpenBtn.addEventListener ("click", (evt)=>{
        this.openPopup()
        this.openCatalog()
    })
  }

  addCloseListeners() {
    super.addCloseListeners()
    if(this.mobMenuLoginPopupOpenBtn) {
      this.mobMenuLoginPopupOpenBtn.addEventListener ("click", (evt)=>{
        this.closePopup()
      })
    }
  }

  addAllListeners() {
    super.addAllListeners()
    this.addMenuCatalogBtnsListeners()
  }
} 

class SideCartPopup extends Popup {
 constructor (popupName) {
    super (popupName)
    this.sideCartDesctopOpenPopupBtn = null
  }

  initDom() {
    const ok = super.initDom()
    if (!ok) return false
    this.sideCartDesctopOpenPopupBtn = document.querySelector('.side-cart__open-btn')
    return true
  }

  togglePopup() {
    super.togglePopup()
    this.sideCartDesctopOpenPopupBtn.classList.toggle('side-cart__open-btn_active')
  }

  addToggleListeners() {
    this.sideCartDesctopOpenPopupBtn.addEventListener('click', this.togglePopup)
  }

  addAllListeners() {
    super.addAllListeners()
    this.addToggleListeners()
  }
}

function initPopups() {
  const popup = new CF7Popup ('page-popup')
  const loginPoup = new LoginPopup ('login-popup')
  const registrPoup = new RegistrPopup ('register-popup')
  registrPoup.setLoginPopup(loginPoup)

  const menuMobPopup = new MenuMobPopup ('burger-menu')
  const sideCartPopup = new SideCartPopup ('side-cart-popup')

  popup.init()
  loginPoup.init()
  registrPoup.init()
  menuMobPopup.init()
  sideCartPopup.init()

  console.debug('fn initPopups: popups initialized')
  debugPopup(sideCartPopup, 'sideCartDesctopOpenPopupBtn')
}

function debugPopup(instance, elementName = null) {
  if (!instance) {
    console.warn('debugPopup: не передан экземпляр попапа')
    return;
  }

  console.group(`Popup debug: "${instance.popupName}"`)

  console.log(instance)

  console.log('popupName:', instance.popupName);
  console.log('popup element:', instance.popup, instance.popup ? '✅ найден' : '❌ НЕТ')
  console.log('openBtns:', instance.openBtns ? instance.openBtns.length : 'null')
  console.log('body:', !!instance.body ? '✅' : '❌')
  console.log('overlay:', !!instance.overlay ? '✅' : '❌')
  console.log('closeBtn:', !!instance.closeBtn ? '✅' : '❌')

  if (instance.popup) {
    console.log('has .popup_active:', instance.popup.classList.contains('popup_active'))
  }

  if(elementName) {
    console.log('element:', !!instance[elementName] ? '✅' : '❌')
  }

  console.groupEnd();

  // чтобы можно было дальше с ним играться в консоли
  return instance;
}

document.addEventListener('DOMContentLoaded', initPopups)