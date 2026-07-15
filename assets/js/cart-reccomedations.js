document.addEventListener('click', (event) => {
  const toggleBtn = event.target.closest(
    '.cart__product-recs-header'
  );

  if (toggleBtn) {
    const recs = toggleBtn.closest(
      '.cart__product-recs'
    );

    const slider = recs?.querySelector(
      '[data-js-product-slider]'
    );

    if (!recs || !slider) {
      return;
    }

    const isActive = recs.classList.toggle(
      'is-active'
    );

    if (isActive) {
      slider.style.height = `${slider.scrollHeight}px`;
      slider.style.opacity = '1';
    } else {
      slider.style.height = `${slider.scrollHeight}px`;

      requestAnimationFrame(() => {
        slider.style.height = '0';
        slider.style.opacity = '0';
      });
    }

    return;
  }

  const replaceButton = event.target.closest(
    '.backorder_replace_btn'
  );

  if (replaceButton) {
    replaceBackorderProduct(replaceButton);
  }
});

document.addEventListener('transitionend', (event) => {
  const slider = event.target.closest(
    '[data-js-product-slider]'
  );

  if (
    !slider ||
    event.propertyName !== 'height'
  ) {
    return;
  }

  const recs = slider.closest(
    '.cart__product-recs'
  );

  if (recs?.classList.contains('is-active')) {
    slider.style.height = 'auto';
  }
});

function replaceBackorderProduct(btn) {
  const productId = btn.dataset.product_id;
  const cartItemKey = btn.dataset.cart_item;

  if (!productId || !cartItemKey) {
    return;
  }

  const formData = new FormData();

  formData.append(
    'action',
    'replace_backorder_product'
  );

  formData.append(
    'backorder_replace_prodId',
    productId
  );

  formData.append(
    'backorder_replace_cart_item',
    cartItemKey
  );

  fetch(woocommerce_params.ajax_url, {
    method: 'POST',
    body: formData,
    credentials: 'same-origin',
  })
    .then(async (response) => {
      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(
          result?.data?.message ||
          `HTTP error: ${response.status}`
        );
      }

      return result.data;
    })
    .then((data) => {
      data.metrika?.remove?.forEach((product) => window.plntYandexEcommerce?.remove(product));
      data.metrika?.add?.forEach((product) => window.plntYandexEcommerce?.add(product));
      plntApplyCartFragments(data.fragments);
      plntGetCartPageFragments();
    })
    .catch((error) => {
      console.error(
        'replaceBackorderProduct:',
        error
      );
    });
}


