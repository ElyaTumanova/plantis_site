/* счетчик для кнопки Оичстить фильтры */
const isPriceFilterActive = (widget) => {
  const slider = widget.querySelector('.bapf_slidr_main')

  if (!slider) return false

  const min = Number(slider.dataset.min)
  const max = Number(slider.dataset.max)
  const start = Number(slider.dataset.start)
  const end = Number(slider.dataset.end)

  return start !== min || end !== max
}

const updateActiveFiltersCount = () => {
  const widgets = document.querySelectorAll('.berocket_single_filter_widget')
  const countElement = document.querySelector('.plnt-reset-filters__count')

  if (!countElement) return

  let count = 0

  widgets.forEach((widget) => {
    if (widget.querySelector('li.checked')) {
      count++
    }

    if (isPriceFilterActive(widget)) {
      count++
    }
  })

  countElement.textContent = count > 0 ? `(${count})` : ''
}

document.addEventListener('DOMContentLoaded', updateActiveFiltersCount)

/* добавляем inputmode к фильтру цены */

const setPriceInputMode = () => {
  document
    .querySelectorAll('.bapf_slidr input[type="text"]')
    .forEach(input => {
      input.setAttribute('inputmode', 'decimal')
    })
}
document.addEventListener('DOMContentLoaded', setPriceInputMode)


/* обновление после аякса */

function plntDispatchCatalogUpdated() {
  document.dispatchEvent(
    new CustomEvent('plnt_catalog_updated')
  );
}

jQuery(document).on('plnt_catalog_updated', () => {
  console.log('ajax plnt_catalog_updated')
  swiper_filter_metki_init()
  swiper_catalog_card_imgs_init()
  updateActiveFiltersCount()
  setPriceInputMode()
  setSearchFilterField()
  setdiametrFilterScroll()
  initCatalogSidebarSticky()
  plntUpdateCatalogBanners()
})

// jQuery(document).on('berocket_ajax_filtering_end', () => {
//   updateActiveFiltersCount()
//   setPriceInputMode()
//   // swiper_catalog_card_imgs_init()
// })

// jQuery(document).ajaxComplete(() => {
//   console.log('ajax complete')
//   swiper_filter_metki_init()
//   updateActiveFiltersCount()
//   setPriceInputMode()
//   setSearchFilterField()
//   // swiper_catalog_card_imgs_init()
// })


/* название товара для формы купить в один клик */
document.addEventListener('click', (event) => {
  const button = event.target.closest('.card__one-click-btn')

  if (!button) return

  const productCard = button.closest('.product')
  let productName = ''

  if (productCard?.querySelector('.card__grid')) {
    productName = productCard?.querySelector('.product_title.entry-title')?.textContent.trim()
  } else {
    productName = productCard?.querySelector('.woocommerce-loop-product__title')?.textContent.trim()
  }

  const popup = document.querySelector('.buy-one-click-popup')
  const input = popup?.querySelector('input[name="product-name"]')

  if (input && productName) {
    input.value = productName
  }
})

/*--------------------------------------------------------------
# Buttons to change grid columns in catalog
--------------------------------------------------------------*/

const gridButton = document.getElementById('catalog__btn-grid');
const rowsButton = document.getElementById('catalog__btn-rows');
const catalogWrap = document.querySelector('.catalog__grid');

if(gridButton && catalogWrap) {
  const catalogGrid = catalogWrap.querySelector('.products');
    // console.log(catalogGrid.classList)
    if(catalogGrid.classList.contains('columns-3')) {
      rowsButton.disabled = false;
      gridButton.disabled = true;
    } 
    if(catalogGrid.classList.contains('in-row')) {
      gridButton.disabled = false;
      rowsButton.disabled = true;
    } 
    if (gridButton) {
    gridButton.addEventListener ("click", (evt)=>{
        make_2_grid_columns();
    });

    }
    if (rowsButton) {
      rowsButton.addEventListener ("click", (evt)=>{
          make_3_grid_columns();
      });
    }
    
    function make_2_grid_columns () {
        catalogGrid.classList.add ('columns-3');
        catalogGrid.classList.add ('columns-2-mob');
        catalogGrid.classList.remove ('in-row');
        gridButton.disabled = true;
        rowsButton.disabled = false;

        plntUpdateCatalogBanners()
    };
    
    function make_3_grid_columns () {
        catalogGrid.classList.remove ('columns-3');
        catalogGrid.classList.remove ('columns-2-mob');
        catalogGrid.classList.add ('in-row');
        gridButton.disabled = false;
        rowsButton.disabled = true;
        
        plntUpdateCatalogBanners()
    };
};

/*--------------------------------------------------------------
# Верхняя панель мобильного каталога
--------------------------------------------------------------*/

const catalogMobileSticky = document.querySelector(
  '.catalog__mobile-sticky'
);

if (catalogMobileSticky) {
  let lastScrollTop = Math.max(window.pageYOffset, 0);

  const updateCatalogStickyHeight = () => {
    catalogMobileSticky.style.setProperty(
      '--catalog-mobile-sticky-height',
      `${catalogMobileSticky.offsetHeight}px`
    );
  };

  updateCatalogStickyHeight();

  window.addEventListener(
    'resize',
    updateCatalogStickyHeight
  );

  window.addEventListener(
    'scroll',
    () => {
      const scrollTop = Math.max(window.pageYOffset, 0);

      if (scrollTop > lastScrollTop) {
        catalogMobileSticky.classList.remove(
          'catalog__mobile-sticky--active'
        );
      } else if (scrollTop < lastScrollTop) {
        catalogMobileSticky.classList.add(
          'catalog__mobile-sticky--active'
        );
      }

      if (scrollTop === 0) {
        catalogMobileSticky.classList.remove(
          'catalog__mobile-sticky--active'
        );
      }

      lastScrollTop = scrollTop;
    },
    { passive: true }
  );
}

/*--------------------------------------------------------------
# Sticky sidebar c фильтрами
--------------------------------------------------------------*/

function initCatalogSidebarSticky() {
  const wrap = document.querySelector('.catalog__sidebar');
  const sidebar = wrap?.querySelector('.catalog__sidebar-inner');
  if (!wrap || !sidebar) return;

  const topGap = headerMainHeightValue + 20;
  const bottomGap = 20;

  let lastScrollY = window.scrollY;
  let offset = 0;
  let ticking = false;

  const update = () => {
    if (window.innerWidth <= 1024) {
      sidebar.style.removeProperty('--catalog-sidebar-top');
      lastScrollY = window.scrollY;
      ticking = false;
      return;
    }

    const scrollY = window.scrollY;
    const delta = scrollY - lastScrollY;
    const sidebarHeight = sidebar.offsetHeight;
    const availableHeight = window.innerHeight - topGap - bottomGap;
    const maxOffset = Math.max(0, sidebarHeight - availableHeight);
    const wrapTop = wrap.getBoundingClientRect().top;

    if (wrapTop <= topGap || offset > 0) {
      offset = Math.max(0, Math.min(maxOffset, offset + delta));
    }

    sidebar.style.setProperty('--catalog-sidebar-top', `${topGap - offset}px`);

    lastScrollY = scrollY;
    ticking = false;
  };

  const onScroll = () => {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(update);
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', update);

  new ResizeObserver(() => {
    const maxOffset = Math.max(0, sidebar.offsetHeight - (window.innerHeight - topGap - bottomGap));
    offset = Math.min(offset, maxOffset);
    update();
  }).observe(sidebar);

  update();
}

document.addEventListener('DOMContentLoaded', initCatalogSidebarSticky);


/* Видео */

document.querySelectorAll('.product').forEach(card => {
  const video = card.querySelector('.product__video')
  if (!video) return

  const slider = card.querySelector('.product__image-slider-wrap')

  slider.addEventListener('mouseenter', () => {
    video.play().catch(() => {})
  })

  slider.addEventListener('mouseleave', () => {
    video.pause()
    video.currentTime = 0
    video.load()
  })
})


/*--------------------------------------------------------------
# Баннеры внутри каталога
--------------------------------------------------------------*/


function plntUpdateCatalogBanners() {
  const catalog = document.querySelector('.catalog__grid .products')

  if (!catalog) return

  const banners = [
    ...catalog.querySelectorAll(':scope > .card__banner--catalog')
  ]

  if (!banners.length) return

  const products = [
    ...catalog.children
  ].filter(item => item.classList.contains('product'))

  const width = window.innerWidth
  const isMobile = width < 560
  const isRows = catalog.classList.contains('in-row')
  const isWide = width > 1280

  // Для новых баннеров запоминаем начало страницы.
  // PHP размещает их после position_base.
  banners.forEach(banner => {
    if (banner.dataset.pageStart !== undefined) return

    let productsBefore = 0
    let sibling = banner.previousElementSibling

    while (sibling) {
      if (sibling.classList.contains('product')) {
        productsBefore++
      }

      sibling = sibling.previousElementSibling
    }

    const basePosition = Number(banner.dataset.positionBase)

    banner.dataset.pageStart = String(
      Math.max(0, productsBefore - basePosition)
    )
  })

  // Определяем начало каждой загруженной страницы.
  const pageStarts = [
    ...new Set(
      banners.map(banner => Number(banner.dataset.pageStart))
    )
  ].sort((a, b) => a - b)

  // Рассчитываем позиции баннеров.
  const items = banners.map((banner, index) => {
    const pageStart = Number(banner.dataset.pageStart)

    const desktopPosition = Number(
      banner.dataset.positionDesktop
    )

    const mobilePosition = Number(
      banner.dataset.positionMobile
    )

    const rowsPosition = Number(
      banner.dataset.positionRows
    )

    const pageSize = Number(
      banner.dataset.pageSize
    )

    let localPosition

    if (isMobile) {
      // Менее 560 px — независимо от класса.
      localPosition = mobilePosition

    } else if (!isWide) {
      // От 560 до 1280 px — всегда сетка из двух колонок.
      localPosition = Math.ceil(desktopPosition / 2) * 2

    } else if (isRows) {
      // Более 1280 px и .in-row.
      localPosition = rowsPosition

    } else {
      // Более 1280 px, обычная сетка.
      localPosition = desktopPosition
    }

    return {
      banner,
      pageStart,
      pageSize,
      position: pageStart + localPosition,
      index
    }
  })

  // Сортируем по фактической позиции.
  items.sort((a, b) => {
    return a.position - b.position || a.index - b.index
  })

  const anchors = new Map()

  items.forEach(({ banner, position, pageStart, pageSize }) => {

    // Начало следующей страницы, если она загружена.
    const nextPageStart = pageStarts.find(
      start => start > pageStart
    ) ?? products.length

    // Не позволяем баннеру выйти за пределы страницы.
    const pageEnd = Math.min(
      nextPageStart,
      pageSize > 0 ? pageStart + pageSize : products.length,
      products.length
    )

    if (
      !Number.isInteger(position) ||
      position <= pageStart ||
      position > pageEnd
    ) {
      banner.hidden = true
      return
    }

    banner.hidden = false

    // Несколько баннеров после одного товара
    // размещаем последовательно.
    const anchor = anchors.get(position) || products[position - 1]

    anchor.after(banner)

    anchors.set(position, banner)
  })
}

// Первоначальное размещение.
document.addEventListener(
  'DOMContentLoaded',
  plntUpdateCatalogBanners
)

// Перерасчёт при изменении ширины экрана.
// window.addEventListener(
//   'resize',
//   plntUpdateCatalogBanners
// )
