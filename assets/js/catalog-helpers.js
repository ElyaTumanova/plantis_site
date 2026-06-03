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

jQuery(document).on('berocket_ajax_filtering_end', () => {
  updateActiveFiltersCount()
})

jQuery(document).ajaxComplete(() => {
  updateActiveFiltersCount()
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