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

jQuery(document).on('berocket_ajax_filtering_end', () => {
  updateActiveFiltersCount()
  setPriceInputMode()
  // swiper_catalog_card_imgs_init()
})

jQuery(document).ajaxComplete(() => {
  updateActiveFiltersCount()
  setPriceInputMode()
  // swiper_catalog_card_imgs_init()
})


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