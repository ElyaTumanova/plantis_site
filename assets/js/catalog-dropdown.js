class CatalogDropdown {
  selectors = {
    catalogDropdownHeaderWrap: '.header__main-catalog-dropdown',
    catalogDropdown: '.catalog-dropdown',
    openBtn: '.catalog-dropdown-open-btn',
    catsOpen: '.catalog-dropdown__cats-item',
    catsOpenList: '.catalog-dropdown__cats-list',
    catsMenu: '.catalog-dropdown__sub-cats-wrap',
    catsMenuBody: '.catalog-dropdown__sub-cats-body',
    header: '.header__desktop',
    itemsWithImage:'.cats-sub-menu__item-image',
    menuImage: '.catalog-dropdown__image img',
    catsMenuHeader: '.catalog-dropdown__sub-cats-header',
    catsMenuHeaderLink: '.catalog-dropdown__sub-cats-header .cats-sub-menu__item-image',
    burgerMenu: '.burger-menu',
  }

  stateClasses = {
    isOpen: 'is-open',
    wideBody: 'catalog-dropdown--wide-body'
  }

  menuDataValues = {
    firstCat: 'menu_item_plants',
    wideBodyCat: 'menu_az_palnts'
  }

  constructor (rootSelector, isTouchDevice) {
    this.isTouchDevice = isTouchDevice
    this.root = document.querySelector(rootSelector)
    this.catalogDropdownHeaderWrap = document.querySelector(this.selectors.catalogDropdownHeaderWrap)
    this.header = document.querySelector(this.selectors.header)
    this.openBtn = this.root.querySelector(this.selectors.openBtn)
    this.catalogDropdown = this.root.querySelector(this.selectors.catalogDropdown)
    this.catsOpenList = this.root.querySelector(this.selectors.catsOpenList)
    this.catsOpen = this.root.querySelectorAll(this.selectors.catsOpen)
    this.catsMenu = this.root.querySelectorAll(this.selectors.catsMenu)
    this.catsMenuHeader = this.root.querySelectorAll(this.selectors.catsMenuHeader)
    this.itemsWithImage = this.root.querySelectorAll(this.selectors.itemsWithImage)
    this.menuImage = this.root.querySelector(this.selectors.menuImage)

    this.imageLinks = []
    this.menuTimeout = null
    this.init()
  }

  getCatImagesAjax () {
    const imageCatId = []
    this.itemsWithImage.forEach((link)=>{
        imageCatId.push(link.getAttribute('data-cat_id'));
    })

    const data = new URLSearchParams();
    data.append('action', 'get_menu_cats_image');
    data.append('cat_id', imageCatId);

    fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: data
    })
    .then(response => {
      if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(result => {
      console.debug('✅ AJAX success:', result);
      if (result.success) {
        this.imageLinks = result.data.image_url;
      }
    })
    .catch(error => {
      console.error('❌ AJAX error:', error);
    })
    .finally(() => {
      console.debug('⚙️ AJAX complete');
    });
  }

  getCatImage(catId) {
    const imageLink = this.imageLinks?.[`id_${catId}`];

    if (imageLink) {
      this.menuImage.setAttribute('src', imageLink);
      this.menuImage.classList.remove('d-none');
    } else {
      this.menuImage.removeAttribute('src');
      this.menuImage.classList.add('d-none');
    }
  }

  openMenu(menu) {
    this.catsMenu.forEach(el => {
      if(el.dataset.menu === menu) {
        el.classList.add(this.stateClasses.isOpen)

        const catsMenuHeaderLink = el.querySelector(this.selectors.catsMenuHeaderLink)
        this.getCatImage(catsMenuHeaderLink?.dataset.cat_id)
        
        const catsMenuBody = el.querySelector(this.selectors.catsMenuBody)

        catsMenuBody.scrollTo({
          top: 0,
        })

      } else {
        el.classList.remove(this.stateClasses.isOpen)
      }
    })
    this.catsOpen.forEach(el => {
      if(el.dataset.menu === menu) {
        el.classList.add(this.stateClasses.isOpen)
      } else {
        el.classList.remove(this.stateClasses.isOpen)
      }
    })
  }

  closeAllMenu() {
    this.catsMenu.forEach(el => {
      el.classList.remove(this.stateClasses.isOpen)
    })
  }

  scheduleOpenMenu = (menu) => {
    clearTimeout(this.menuTimeout)

    this.menuTimeout = setTimeout(() => {
      this.openMenu(menu)

      this.catalogDropdown.classList.toggle(
        this.stateClasses.wideBody,
        menu === this.menuDataValues.wideBodyCat
      )

      this.menuTimeout = null
    }, 180)
  }

  openCatalog() {
    this.openBtn.classList.add(this.stateClasses.isOpen)
    this.catalogDropdownHeaderWrap.classList.add(this.stateClasses.isOpen)
    this.openMenu(this.menuDataValues.firstCat)
    document.addEventListener('click', this.overlayClose)
    if(this.imageLinks.length === 0) {this.getCatImagesAjax()}
  }

  closeCatalog = () => {
    clearTimeout(this.menuTimeout)
    this.openBtn.classList.remove(this.stateClasses.isOpen)
    this.catalogDropdownHeaderWrap.classList.remove(this.stateClasses.isOpen)
    document.removeEventListener('click', this.overlayClose)
  }

  overlayClose = (event) => {
    const isOverlay = !this.catalogDropdown.contains(event.target) && !this.openBtn.contains(event.target)
    const isHeader = this.header.contains(event.target)
    if(isOverlay) {
      if(!isHeader) {
        event.preventDefault()
      }
      this.closeCatalog()
    }
  }

  onItemHover = (event) => {
    this.getCatImage(event.target.dataset.cat_id)
  }

  onOpenBtnClick = () => {
    if(!this.openBtn.classList.contains(this.stateClasses.isOpen)) {
      this.openCatalog()
    } else {
      this.closeCatalog()
    }
  }

  onCatsOpenEnter = (evt) => {
    const menu = evt.currentTarget.dataset.menu
    this.scheduleOpenMenu(menu)
  }

  onCatsOpenClick = (evt) => {
    if (evt.target.closest('a')) {
      return
    }
    const menu = evt.currentTarget.dataset.menu
    this.openMenu(menu)
  }

  onCatsMenuHeaderClick = (evt) => {
    // event.preventDefault()
    const menu = evt.target.closest(this.selectors.catsMenu).dataset.menu
    console.log(menu)
    this.closeAllMenu()
  }
  onCatsMenuSwipeRight = (evt) => {
    this.closeAllMenu()
  }
  
  bindEvents() {
    //const isTouchDevice = window.matchMedia('(hover: none) and (pointer: coarse)').matches
    if (!this.isTouchDevice) {
      this.openBtn.addEventListener('click', this.onOpenBtnClick)
      this.catsOpen.forEach(element => {
        element.addEventListener('mouseenter', this.onCatsOpenEnter)
      });
      this.itemsWithImage.forEach(element => {
        element.addEventListener('mouseenter', this.onItemHover)
      });
      this.catsOpenList.addEventListener('mouseleave', () => {clearTimeout(this.menuTimeout)})
      this.catalogDropdown.addEventListener('mouseleave',this.closeCatalog)
    } else {
      this.catsOpen.forEach(element => {
        element.addEventListener('click', this.onCatsOpenClick)
      });
      this.catsMenuHeader.forEach(element => {
        element.addEventListener('click', this.onCatsMenuHeaderClick)
      });
      this.catsMenu.forEach(element => {
        let startX = 0
        let startY = 0

        element.addEventListener('touchstart', (evt) => {
          startX = evt.changedTouches[0].clientX
          startY = evt.changedTouches[0].clientY
        })

        element.addEventListener('touchend', (evt) => {
          const endX = evt.changedTouches[0].clientX
          const endY = evt.changedTouches[0].clientY

          const diffX = endX - startX
          const diffY = Math.abs(endY - startY)

          if (diffX > 50 && diffY < 30) {
            this.onCatsMenuSwipeRight(evt)
          }
        })
      })
    }
  }

  init() {
    this.bindEvents()
  }

}

new CatalogDropdown('.header__desktop', false)
new CatalogDropdown('.burger-menu', true)

