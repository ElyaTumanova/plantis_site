class ViewportPosition {
  constructor (selector, propertyName) {
    this.selector = selector
    this.propertyName = propertyName
    this.element = document.querySelector(this.selector)
    this.init()
  }

  getPropertyValue () {
    return this.element.getBoundingClientRect().top
  }

  setCssProperty () {
    const propertyValue = this.getPropertyValue()
    document.documentElement.style.setProperty(this.propertyName, `${propertyValue}px`)
  }

  init() {
    this.setCssProperty()
    this.bindEvents()
  }

  bindEvents() {
    window.addEventListener('resize', () => {
        this.setCssProperty()
    });
    // window.addEventListener('scroll', () => {
    //     this.setCssProperty()
    // });
  }
}

new ViewportPosition ('.catalog-dropdown', '--catalogDropdownTopOffset')
new ViewportPosition ('.burger-menu__body', '--burgerMenuBodyTopOffset')


class ElementHeight {
  constructor (selector, propertyName) {
    this.selector = selector
    this.propertyName = propertyName
    this.element = document.querySelector(this.selector)
    this.init()
  }

  getPropertyValue() {
    if (!this.element) {
      return 0
    }

    return this.element.offsetHeight
  }

  setCssProperty () {
    const propertyValue = this.getPropertyValue()
    document.documentElement.style.setProperty(this.propertyName, `${propertyValue}px`)
  }

  init() {
    this.setCssProperty()
    this.bindEvents()
  }

  bindEvents() {
    window.addEventListener('resize', () => {
        this.setCssProperty()
    });
    // window.addEventListener('scroll', () => {
    //     this.setCssProperty()
    // });
  }
}

new ElementHeight ('.header__main-top', '--headerMainTopHeight')
new ElementHeight ('.header__nav', '--headerNavHeight')

class ElementWidth {
  constructor (selector, propertyName) {
    this.selector = selector
    this.propertyName = propertyName
    this.element = document.querySelector(this.selector)
    this.init()
  }

  getPropertyValue() {
    if (!this.element) {
      return 0
    }

    return this.element.offsetWidth
  }

  setCssProperty () {
    const propertyValue = this.getPropertyValue()
    document.documentElement.style.setProperty(this.propertyName, `${propertyValue}px`)
  }

  init() {
    this.setCssProperty()
    this.bindEvents()
  }

  bindEvents() {
    window.addEventListener('resize', () => {
        this.setCssProperty()
    });
    // window.addEventListener('scroll', () => {
    //     this.setCssProperty()
    // });
  }
}

new ElementWidth ('.burger-menu__modal', '--burgerMenuModalWidth')

class HoverDropdown {
  selectors = {
    open: '[data-js-dropdown-open]',
    dropdown: '.dropdown',
  }

  stateClasses = {
    isOpen: 'is-open',
  }

  constructor (root) {
    this.root = root
    this.open = this.root.querySelector(this.selectors.open)
    this.dropdown = this.root.querySelector(this.selectors.dropdown)
    this.menuTimeout = null

    this.init()
  }

  openDropdown = () => {
    this.dropdown.classList.add(this.stateClasses.isOpen)
    this.open.classList.add(this.stateClasses.isOpen)
  }

  toggleDropdown = () => {
    this.dropdown.classList.toggle(this.stateClasses.isOpen)
    this.open.classList.toggle(this.stateClasses.isOpen)
  }

  closeDropdown = () => {
    clearTimeout(this.menuTimeout)
    this.dropdown.classList.remove(this.stateClasses.isOpen)
    this.open.classList.remove(this.stateClasses.isOpen)
  }

  onOpenClick = () => {
    this.openDropdown()
  }

   scheduleOpenMenu = () => {
    clearTimeout(this.menuTimeout)

    this.menuTimeout = setTimeout(() => {
      this.openDropdown()

      this.menuTimeout = null
    }, 180)
  }

  bindEvents() {
    const isTouchDevice = window.matchMedia('(hover: none) and (pointer: coarse)').matches
    
    if(!isTouchDevice) {
      this.open.addEventListener('mouseenter', this.scheduleOpenMenu)
      this.open.addEventListener('mouseleave', this.closeDropdown)
    } else {
      this.open.addEventListener('click', this.toggleDropdown)
    }
  }

  init() {
    if(!this.open) {return}
    this.bindEvents()
  }
}

class HoverDropdownCollection {
  constructor(selector) {
    this.selector = selector
    this.init()
  }

  init() {
    document.querySelectorAll(this.selector).forEach((element) => {
      new HoverDropdown(element)
    })
  }
}

new HoverDropdownCollection ('.menu__list')

new HoverDropdownCollection('.header__info-menu')