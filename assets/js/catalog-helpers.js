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
    };
    
    function make_3_grid_columns () {
        catalogGrid.classList.remove ('columns-3');
        catalogGrid.classList.remove ('columns-2-mob');
        catalogGrid.classList.add ('in-row');
        gridButton.disabled = false;
        rowsButton.disabled = true;
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