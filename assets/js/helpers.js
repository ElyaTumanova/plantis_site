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

  bindEvents() {
    window.addEventListener('resize', () => {
        this.setCssProperty()
    });
    // window.addEventListener('scroll', () => {
    //     this.setCssProperty()
    // });
  }

  init() {
    if (!this.element) return
    this.setCssProperty()
    this.bindEvents()
  }
}

new ViewportPosition ('.catalog-dropdown', '--catalogDropdownTopOffset')
new ViewportPosition ('.card__image-wrap', '--cardImageWrapTopOffset')
new ViewportPosition ('.gc-content-area', '--gcContentAreaTopOffset')
new ViewportPosition ('.gc-step-panel__body', '--gcSliderStageTopOffset')
new ViewportPosition ('.gc-card-mob-wrap', '--gcCardMobTopOffset')


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


  bindEvents() {
    window.addEventListener('resize', () => {
        this.setCssProperty()
    });
    // window.addEventListener('scroll', () => {
    //     this.setCssProperty()
    // });
  }

  init() {
    if (!this.element) return
    this.setCssProperty()
    this.bindEvents()
  }
}

new ElementHeight ('.header__main-top', '--headerMainTopHeight')
new ElementHeight ('.header__nav', '--headerNavHeight')
new ElementHeight ('.header__desktop', '--headerHeight')
new ElementHeight ('.header__main', '--headerMainHeight')
new ElementHeight ('.header__mob', '--headerMobHeight')
new ElementHeight ('.card__actions-wrap-inner', '--cardActionsHeight')
new ElementHeight ('.gc-navbar', '--gcNavBarHeight')

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

  bindEvents() {
    window.addEventListener('resize', () => {
        this.setCssProperty()
    });
    // window.addEventListener('scroll', () => {
    //     this.setCssProperty()
    // });
  }

  init() {
    if (!this.element) return
    this.setCssProperty()
    this.bindEvents()
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


class ScrollTopToggle {
  constructor(selector, className = 'scrollhidden') {
    this.element = document.querySelector(selector);
    this.className = className;

    if (!this.element) return;

    this.update = this.update.bind(this);

    window.addEventListener('scroll', this.update);
    this.update();
  }

  update() {
    this.element.classList.toggle(
      this.className,
      window.scrollY > 0
    );
  }
}

new ScrollTopToggle('.header__main')
new ScrollTopToggle('.search__wrap')
new ScrollTopToggle('.search-result')
new ScrollTopToggle('.header__nav')


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
    this.checkOverflow()
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

  checkOverflow = () => {
    this.buttonElement.hidden =
      this.areaElement.scrollHeight <= this.collapsedHeight
  }

  bindEvents() {
    this.buttonElement.addEventListener('click', this.onButtonClick)
    window.addEventListener('resize', this.checkOverflow)
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

/* обновление Backorder Date чтобы не кешировалось*/
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

/* copy share link for wishlist */
document.addEventListener('click', async (e) => {
  const block = e.target.closest('.yith-wcwl-after-share-section');

  if (!block) {
    return;
  }

  const input = block.querySelector('.copy-target');

  if (!input) {
    return;
  }

  try {
    await navigator.clipboard.writeText(input.value);

    block.classList.add('is-copied');

    setTimeout(() => {
      block.classList.remove('is-copied');
    }, 2000); 
  } catch (err) {
    input.select();
    document.execCommand('copy');
  }
});



/* debug */

// (function () {
//   const LABEL_CLASS = '__debug-height-label';

//   function render() {
//     // удаляем старые подписи
//     document.querySelectorAll('.' + LABEL_CLASS).forEach(el => el.remove());

//     document.querySelectorAll('div').forEach((el, index) => {
//       const height = Math.round(el.getBoundingClientRect().height);

//       // случайный цвет для каждого div
//       const hue = (index * 47) % 360;
//       const color = `hsl(${hue} 80% 50%)`;

//       el.style.outline = `2px solid ${color}`;
//       el.style.outlineOffset = '-1px';

//       const style = getComputedStyle(el);

//       // чтобы подпись можно было позиционировать
//       if (style.position === 'static') {
//         el.style.position = 'relative';
//       }

//       const label = document.createElement('span');
//       label.className = LABEL_CLASS;
//       label.textContent = `${height}px`;

//       Object.assign(label.style, {
//         position: 'absolute',
//         top: '0',
//         left: '0',
//         zIndex: '999999',
//         fontSize: '10px',
//         lineHeight: '1',
//         padding: '2px 4px',
//         background: color,
//         color: '#fff',
//         pointerEvents: 'none',
//         fontFamily: 'monospace',
//       });

//       el.append(label);
//     });
//   }

//   render();

//   window.addEventListener('resize', render);

//   // чтобы можно было вызвать вручную
//   window.debugDivHeights = render;
// })();
