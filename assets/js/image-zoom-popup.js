/**
 * Image Zoom Popup (single + swiper) — ONE manager
 * Один инстанс на страницу (без конфликтов)
 * Открытие по кнопке и/или по клику на картинку
 * Zoom по клику на картинку в попапе
 * Drag/Pan только когда zoom включён (is-zoomed)
 * Для swiper: при zoom отключаем свайп, при reset включаем
 * Закрытие по любому клику внутри попапа, кроме клика по .img-popup__img
 */

class BasePopup {
  constructor({ popupSelector, lockClass = 'is-locked' } = {}) {
    this.popup = document.querySelector(popupSelector)
    if (!this.popup) return

    this.lockClass = lockClass
    this.lastActiveEl = null

    this.close = this.close.bind(this)
    this.onKeyDown = this.onKeyDown.bind(this)

    // lock scroll once
    if (!document.querySelector('style[data-img-popup-lock]')) {
      const st = document.createElement('style')
      st.setAttribute('data-img-popup-lock', '1')
      st.textContent = `.is-locked { overflow: hidden !important; }`
      document.head.appendChild(st)
    }

    // Закрытие по любому клику кроме img и swiper navigation
    this.popup.addEventListener('click', (e) => {
      // клик по картинке — не закрываем
      if (
      e.target.closest('.img-popup__img') ||
      e.target.closest('.img-popup__prev') ||
      e.target.closest('.img-popup__next') ||
      e.target.closest('.img-popup__thumbs')
    ) {
      return
    }
      e.preventDefault()
      this.close()
    })

    document.addEventListener('keydown', this.onKeyDown)
  }

  open() {
    if (!this.popup) return
    this.lastActiveEl = document.activeElement

    this.popup.classList.add('is-open')
    this.popup.setAttribute('aria-hidden', 'false')
    document.documentElement.classList.add(this.lockClass)
    document.body.classList.add(this.lockClass)

    this.popup.querySelector('.img-popup__close,[data-popup-close]')?.focus?.()
  }

  close() {
    if (!this.popup) return

    this.popup.classList.remove('is-open')
    this.popup.setAttribute('aria-hidden', 'true')
    document.documentElement.classList.remove(this.lockClass)
    document.body.classList.remove(this.lockClass)

    this.onCloseCleanup?.()
    this.lastActiveEl?.focus?.()
  }

  onKeyDown(e) {
    if (e.key === 'Escape' && this.popup?.classList.contains('is-open')) {
      this.close()
    }
  }
}

class ImagePopupManager {
  constructor(options = {}) {
    this.cfg = {
      singlePopupSelector: '#img-popup',
      sliderPopupSelector: '#img-popup-slider',
      zoomScale: 2,
      ...options,
    }

    // popups
    this.single = new BasePopup({ popupSelector: this.cfg.singlePopupSelector })
    this.singleImg = this.single?.popup?.querySelector('.img-popup__img')

    this.slider = new BasePopup({ popupSelector: this.cfg.sliderPopupSelector })
    this.sliderEl = this.slider?.popup?.querySelector('.img-popup__swiper')
    this.sliderWrapper = this.slider?.popup?.querySelector('.img-popup__swiper .swiper-wrapper')
    this.thumbsEl = this.slider?.popup?.querySelector('.img-popup__thumbs')
    this.thumbsWrapper = this.slider?.popup?.querySelector('.img-popup__thumbs .swiper-wrapper')
    this.sliderPrev = this.slider?.popup?.querySelector('.img-popup__prev')
    this.sliderNext = this.slider?.popup?.querySelector('.img-popup__next')

    this.hasSwiper =
      !!this.slider?.popup &&
      !!this.sliderEl &&
      !!this.sliderWrapper &&
      typeof Swiper !== 'undefined'

    if (this.hasSwiper) {
      this.thumbsSwiper = new Swiper(this.thumbsEl, {
        direction: 'vertical',
        slidesPerView: 'auto',
        spaceBetween: 10,
        watchSlidesProgress: true,
      })

      this.swiper = new Swiper(this.sliderEl, {
        slidesPerView: 1,
        loop: false,
        navigation: {
          prevEl: this.sliderPrev,
          nextEl: this.sliderNext,
        },
        thumbs: {
          swiper: this.thumbsSwiper,
        },
        preventClicks: false,
        preventClicksPropagation: false,
        on: {
          slideChange: () => this.resetZoomOnActive(),
        },
      })
    }

    this.activeMode = null // 'single' | 'slider'
    this.triggers = []

    // pan state
    this.isPanning = false
    this.panStartX = 0
    this.panStartY = 0
    this.translateX = 0
    this.translateY = 0
    this.moved = false
    this.justDragged = false

    // bind
    this.onDocClick = this.onDocClick.bind(this)
    this.onPopupImgClick = this.onPopupImgClick.bind(this)
    this.onPanDown = this.onPanDown.bind(this)
    this.onPanMove = this.onPanMove.bind(this)
    this.onPanUp = this.onPanUp.bind(this)

    // open handlers
    document.addEventListener('click', this.onDocClick)

    // zoom by click inside popup
    this.single?.popup?.addEventListener('click', this.onPopupImgClick)
    this.slider?.popup?.addEventListener('click', this.onPopupImgClick)

    // pan pointer handlers
    ;[this.single?.popup, this.slider?.popup].forEach((p) => {
      if (!p) return
      p.addEventListener('pointerdown', this.onPanDown)
      p.addEventListener('pointermove', this.onPanMove)
      p.addEventListener('pointerup', this.onPanUp)
      p.addEventListener('pointercancel', this.onPanUp)
    })

    // cleanup
    if (this.single) {
      this.single.onCloseCleanup = () => {
        this.activeMode = 'single'
        this.resetZoomOnActive()
        if (this.singleImg) {
          this.singleImg.src = ''
          this.singleImg.alt = ''
        }
      }
    }

    if (this.slider) {
      this.slider.onCloseCleanup = () => {
        this.activeMode = 'slider'
        this.resetZoomOnActive()
        if (this.hasSwiper) {
          this.sliderWrapper.innerHTML = ''
          this.thumbsWrapper.innerHTML = ''

          this.swiper.update()
          this.thumbsSwiper.update()

          this.swiper.allowTouchMove = true
        }
      }
    }
  }

  addTrigger(cfg) {
    this.triggers.push({
      itemSelector: cfg.itemSelector,
      imgSelector: cfg.imgSelector,
      openSelector: cfg.openSelector || null,
      openOnImageClick: cfg.openOnImageClick ?? true,
      ignoreIfInsideLink: cfg.ignoreIfInsideLink ?? true,
      galleryRootSelector: cfg.galleryRootSelector ?? null,
    })
  }

  getFullSrc(img) {
    // 1) если заранее прокинул full — идеально
    if (img.dataset.full) return img.dataset.full

    // 2) если есть srcset — берём самый большой w-кандидат
    const srcset = img.getAttribute('srcset')
    if (srcset) {
      const best = srcset
        .split(',')
        .map(s => s.trim().split(/\s+/))     // [url, "768w"]
        .filter(p => p.length >= 2 && /w$/.test(p[1]))
        .map(p => ({ url: p[0], w: parseInt(p[1], 10) }))
        .sort((a,b) => b.w - a.w)[0]
      if (best?.url) return best.url
    }

    // 3) fallback
    return img.currentSrc || img.src
  }


  getGalleryItems(clickedImg, tcfg) {
    const rootSel = tcfg.galleryRootSelector
    if (!rootSel) {
      const imgs = [...document.querySelectorAll(tcfg.imgSelector)]
      return imgs.map((img) => ({ src: this.getFullSrc(img), alt: img.alt || '', el: img }))
    }

    const root = clickedImg.closest(rootSel)
    const scope = root || clickedImg.closest(tcfg.itemSelector) || document
    const imgs = [...scope.querySelectorAll(tcfg.imgSelector)]
    if (!imgs.length) return [{ src: this.getFullSrc(clickedImg), alt: clickedImg.alt || '', el: clickedImg }]
    return imgs.map((img) => ({ src: this.getFullSrc(img), alt: img.alt || '', el: img }))
  }

  shouldUseSlider(items) {
    return this.hasSwiper && items.length > 1
  }

  buildSlides(items) {
    this.sliderWrapper.innerHTML = ''
    this.thumbsWrapper.innerHTML = ''

    items.forEach(({ src, alt }) => {
      const slide = document.createElement('div')
      slide.className = 'swiper-slide'
      slide.innerHTML = `<img class="img-popup__img" src="${src}" alt="${alt || ''}" decoding="async">`
      this.sliderWrapper.appendChild(slide)

      const thumb = document.createElement('div')
      thumb.className = 'swiper-slide'
      thumb.innerHTML = `<img src="${src}" alt="${alt || ''}" decoding="async">`
      this.thumbsWrapper.appendChild(thumb)
    })
  }

  getActiveImg() {
    if (this.activeMode === 'slider') {
      return this.slider?.popup?.querySelector('.swiper-slide-active .img-popup__img')
    }
    if (this.activeMode === 'single') return this.singleImg
    return null
  }

  resetPanState() {
    this.isPanning = false
    this.panStartX = 0
    this.panStartY = 0
    this.translateX = 0
    this.translateY = 0
    this.moved = false
    this.justDragged = false
  }

  resetZoomOnActive() {
    const img = this.getActiveImg()
    if (!img) {
      this.resetPanState()
      return
    }

    this.resetPanState()
    img.classList.remove('is-zoomed', 'is-dragging')
    img.style.transform = 'translate(0px, 0px) scale(1)'

    if (this.activeMode === 'slider' && this.swiper) {
      this.swiper.allowTouchMove = true
    }
  }

  applyTransform(img, scale) {
    img.style.transform = `translate(${this.translateX}px, ${this.translateY}px) scale(${scale})`
  }

  toggleZoom(img) {
    if (!img) return

    const zoomed = img.classList.toggle('is-zoomed')

    if (!zoomed) {
      this.translateX = 0
      this.translateY = 0
      img.classList.remove('is-dragging')
      if (this.activeMode === 'slider' && this.swiper) this.swiper.allowTouchMove = true
      this.applyTransform(img, 1)
      return
    }

    if (this.activeMode === 'slider' && this.swiper) this.swiper.allowTouchMove = false
    this.applyTransform(img, this.cfg.zoomScale)
  }

  openSingle(clickedImg) {
    if (!this.single?.popup || !this.singleImg) return
    this.activeMode = 'single'

    this.singleImg.src = this.getFullSrc(clickedImg)
    this.singleImg.alt = clickedImg.alt || ''

    this.resetZoomOnActive()
    this.single.open()
  }

  openSlider(clickedImg, items) {
    if (!this.slider?.popup || !this.hasSwiper) return
    this.activeMode = 'slider'

    const idx = Math.max(0, items.findIndex((x) => x.el === clickedImg))
    this.buildSlides(items)
    this.swiper.update()
    this.swiper.slideTo(idx, 0)

    this.resetZoomOnActive()
    this.slider.open()
  }

  onDocClick(e) {
    for (const tcfg of this.triggers) {
      // open by button
      if (tcfg.openSelector) {
        const opener = e.target.closest(tcfg.openSelector)
        if (opener) {
          const item = opener.closest(tcfg.itemSelector)
          const img = item?.querySelector(tcfg.imgSelector)
          if (!img) return
          e.preventDefault()

          const items = this.getGalleryItems(img, tcfg)
          if (this.shouldUseSlider(items)) this.openSlider(img, items)
          else this.openSingle(img)
          return
        }
      }

      // open by image click
      if (!tcfg.openOnImageClick) continue

      const clickedImg = e.target.closest(tcfg.imgSelector)
      if (!clickedImg) continue
      if (tcfg.ignoreIfInsideLink && clickedImg.closest('a')) continue

      const item = clickedImg.closest(tcfg.itemSelector)
      if (!item) continue

      e.preventDefault()
      const items = this.getGalleryItems(clickedImg, tcfg)
      if (this.shouldUseSlider(items)) this.openSlider(clickedImg, items)
      else this.openSingle(clickedImg)
      return
    }
  }

  // zoom click inside popup (и чтобы не закрывало попап)
  onPopupImgClick(e) {
    const img = e.target.closest('.img-popup__img')
    if (!img) return

    // после drag не переключаем зум "кликом"
    if (this.justDragged) return

    // не даём BasePopup закрыть попап кликом по img
    e.preventDefault()
    e.stopPropagation()

    // определим режим
    if (this.slider?.popup && this.slider.popup.contains(e.target)) this.activeMode = 'slider'
    else if (this.single?.popup && this.single.popup.contains(e.target)) this.activeMode = 'single'

    this.toggleZoom(img)
  }

  // ===== PAN =====
  onPanDown(e) {
    const img = e.target.closest('.img-popup__img')
    if (!img) return
    if (!img.classList.contains('is-zoomed')) return

    // режим
    if (this.slider?.popup && this.slider.popup.contains(e.target)) this.activeMode = 'slider'
    else if (this.single?.popup && this.single.popup.contains(e.target)) this.activeMode = 'single'

    // в swiper при зуме свайп отключаем
    if (this.activeMode === 'slider' && this.swiper) this.swiper.allowTouchMove = false

    this.isPanning = true
    this.moved = false
    this.justDragged = false

    img.classList.add('is-dragging')

    this.panStartX = e.clientX
    this.panStartY = e.clientY

    img.setPointerCapture(e.pointerId)
    e.preventDefault()
  }

  onPanMove(e) {
    if (!this.isPanning) return

    const img = this.getActiveImg() || e.target.closest('.img-popup__img')
    if (!img || !img.classList.contains('is-zoomed')) return

    const dx = e.clientX - this.panStartX
    const dy = e.clientY - this.panStartY

    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) this.moved = true

    this.panStartX = e.clientX
    this.panStartY = e.clientY

    this.translateX += dx
    this.translateY += dy

    this.applyTransform(img, this.cfg.zoomScale)
  }

  onPanUp() {
    if (!this.isPanning) return
    this.isPanning = false

    const img = this.getActiveImg()
    img?.classList.remove('is-dragging')

    // если двигали — блокируем клик-зум на этом же "тапе"
    if (this.moved) {
      this.justDragged = true
      this.moved = false
      setTimeout(() => (this.justDragged = false), 0)
    }
  }
}

/* ===== INIT ===== */

document.addEventListener('DOMContentLoaded', () => {
  const manager = new ImagePopupManager({
    singlePopupSelector: '#img-popup',
    sliderPopupSelector: '#img-popup-slider',
    zoomScale: 2,
  })

  manager.addTrigger({
    itemSelector: '.product-gallery__main',
    imgSelector: '.product-gallery__slide img',
    openOnImageClick: true,
    ignoreIfInsideLink: false,
    galleryRootSelector: '.product-gallery__main',
  })
})
