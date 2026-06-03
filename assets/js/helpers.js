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
new ViewportPosition ('.card__image-wrap', '--cardImageWrapTopOffset')
// new ViewportPosition ('.burger-menu__body', '--burgerMenuBodyTopOffset')


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
new ElementHeight ('.header__desktop', '--headerHeight')
new ElementHeight ('.header__main', '--headerMainHeight')

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




class ExpandableContent {
  selectors = {
    area: '.expandable-content-area',
    button: '[data-js-expandable-content-button]'
  }

  stateClasses = {
    isExpanded: 'is-expanded',
  }

  animationParams = {
    duration: 500,
    easing: 'ease',
  }

  constructor(rootElement) {
    this.rootElement = rootElement
    this.areaElement = this.rootElement.querySelector(this.selectors.area)
    this.buttonElement = this.rootElement.querySelector(this.selectors.button)
    this.collapsedHeight = this.areaElement.offsetHeight
    this.bindEvents()
  }

  expand() {
    const { offsetHeight, scrollHeight } = this.areaElement

    this.areaElement.classList.add(this.stateClasses.isExpanded)
    this.areaElement.animate([
      {
        maxHeight: `${offsetHeight}px`,
      },
      {
        maxHeight: `${scrollHeight}px`,
      },
    ], this.animationParams)
  }

  collapse() {
    const { offsetHeight } = this.areaElement

    this.areaElement.classList.remove(this.stateClasses.isExpanded)

    this.areaElement.animate([
      { maxHeight: `${offsetHeight}px` },
      { maxHeight: `${this.collapsedHeight}px` },
    ], this.animationParams)

    this.rootElement.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
    })
  }

  onButtonClick = () => {
    if (this.areaElement.classList.contains(this.stateClasses.isExpanded)) {
      this.collapse()
    } else {
      this.expand()
    }
  }

  bindEvents() {
    this.buttonElement.addEventListener('click', this.onButtonClick)
  }
}


class ExpandableContentCollection {
  constructor() {
    this.init()
  }

  init() {
    document.querySelectorAll('.expandable-content').forEach((element) => {
      new ExpandableContent(element)
    })
  }
}

new ExpandableContentCollection()

function getBackorderDate() {
  const date = new Date()

  // Ищем следующую среду
  const targetDay = 3 // среда: 0 вс, 1 пн, 2 вт, 3 ср
  const currentDay = date.getDay()

  let daysUntilWednesday = (targetDay - currentDay + 7) % 7

  // аналог PHP "next wednesday" — если сегодня среда, берем следующую
  if (daysUntilWednesday === 0) {
    daysUntilWednesday = 7
  }

  // next wednesday + 2 weeks
  date.setDate(date.getDate() + daysUntilWednesday + 14)

  return date.toLocaleDateString('ru-RU', {
    day: '2-digit',
    month: '2-digit',
  })
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-backorder-date]').forEach((element) => {
    element.textContent = getBackorderDate()
  })
})