function initCartProductRecs() {
    document.querySelectorAll('.cart__product-recs').forEach((recs) => {
        const container = recs.querySelector('.cart__product-recs-wrap');

        if (!container) {
            return;
        }

        const toggleBtn = recs.querySelector('.cart__product-recs-header');
        const slider = recs.querySelector('[data-js-product-slider]');

        if (toggleBtn && slider) {
            toggleBtn.addEventListener('click', () => {
                const isActive = recs.classList.toggle('is-active');

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
            });

            slider.addEventListener('transitionend', (evt) => {
                if (evt.propertyName !== 'height') {
                    return;
                }

                if (recs.classList.contains('is-active')) {
                    slider.style.height = 'auto';
                }
            });
        }

        recs.querySelectorAll('.backorder_replace_btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                replaceBackorderProduct(btn);
            });
        });
    });
}

function replaceBackorderProduct(btn) {
    const prodId = btn.dataset.product_id;
    const cartItem = btn.dataset.cart_item;

    if (!prodId || !cartItem) {
        return;
    }

    jQuery(function ($) {
        $.ajax({
            type: 'POST',
            url: woocommerce_params.ajax_url,
            data: {
                action: 'replace_backorder_product',
                backorder_replace_prodId: prodId,
                backorder_replace_cart_item: cartItem,
            },
            success: function () {
                $('[name="update_cart"]')
                    .removeAttr('disabled')
                    .trigger('click');
            },
        });
    });
}

initCartProductRecs();