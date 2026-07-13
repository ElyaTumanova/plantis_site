class YandexEcommerce {
  constructor(counterId) {
    this.counterId = counterId;
    this.currency = 'RUB';

    window.dataLayer = window.dataLayer || [];
  }

  add(product) {
    this.pushProductAction('add', product);
  }

  remove(product) {
    this.pushProductAction('remove', product);
  }

  purchase(orderId, products) {
    window.dataLayer.push({
      ecommerce: {
        currencyCode: this.currency,

        purchase: {
          actionField: {
            id: orderId,
          },

          products,
        },
      },
    });

    console.debug(
      'Yandex Ecommerce: purchase',
      {
        orderId,
        products,
      }
    );
  }

  reachGoal(goalName) {
    if (typeof window.ym !== 'function') {
      return;
    }

    window.ym(
      this.counterId,
      'reachGoal',
      goalName
    );
  }

  pushProductAction(action, product) {
    if (
      !product.name ||
      product.quantity < 1
    ) {
      return;
    }

    window.dataLayer.push({
      ecommerce: {
        currencyCode: this.currency,

        [action]: {
          products: [
            product,
          ],
        },
      },
    });

    console.debug(
      `Yandex Ecommerce: ${action}`,
      product
    );
  }
}


class MetrikaProduct {
  static fromElement(element, quantity) {
    console.log(element)
    return {
      name: element.dataset.product_name,

      price: Number(
        element.dataset.product_price
      ),

      category:
        element.dataset.product_category,

      quantity: Number(quantity),
    };
  }
}


class AddToCartMetrika {
  constructor(metrika) {
    this.metrika = metrika;
    this.pendingProducts = new WeakMap();
    this.$body = jQuery(document.body);

    this.bindEvents();
  }

  bindEvents() {
    /*
     * Перед AJAX-запросом сохраняем товар
     * и фактическое выбранное количество.
     */
    this.$body.on(
      'adding_to_cart.plntMetrika',
      (
        event,
        $button,
        requestData
      ) => {
        const button = $button?.get(0);

        if (!button) {
          return;
        }

        const product =
          MetrikaProduct.fromElement(
            button,
            requestData.quantity
          );

        this.pendingProducts.set(
          button,
          product
        );
      }
    );

    /*
     * Отправляем добавление только после
     * успешного ответа WooCommerce.
     */
    this.$body.on(
      'added_to_cart.plntMetrika',
      (
        event,
        fragments,
        cartHash,
        $button
      ) => {
        const button = $button?.get(0);

        if (!button) {
          return;
        }

        const product =
          this.pendingProducts.get(button);

        if (!product) {
          return;
        }

        this.metrika.add(product);

        this.pendingProducts.delete(button);
      }
    );
  }
}


class RemoveFromCartMetrika {
  constructor(metrika) {
    this.metrika = metrika;
    this.pendingClassicCartProduct = null;
    this.$body = jQuery(document.body);
    this.$document = jQuery(document);

    this.bindEvents();
  }

  bindEvents() {
    /*
     * Удаление из мини-корзины
     * или через remove_from_cart_button.
     */
    this.$body.on(
      'removed_from_cart.plntMetrika',
      (
        event,
        fragments,
        cartHash,
        $button
      ) => {
        const button = $button?.get(0);

        if (!button) {
          return;
        }

        const product =
          MetrikaProduct.fromElement(
            button,
            button.dataset.product_quantity
          );

        this.metrika.remove(product);
      }
    );

    /*
     * Перед удалением со страницы корзины
     * сохраняем данные товара.
     */
    this.$document.on(
      'click.plntMetrika',
      [
        '.woocommerce-cart-form',
        '.product-remove > a',
      ].join(' '),
      (event) => {
        const removeLink =
          event.currentTarget;

        this.pendingClassicCartProduct =
          MetrikaProduct.fromElement(
            removeLink,
            removeLink.dataset
              .product_quantity
          );
      }
    );

    /*
     * Событие вызывается после успешного
     * удаления из классической корзины.
     */
    this.$body.on(
      'item_removed_from_classic_cart.plntMetrika',
      () => {
        if (
          !this.pendingClassicCartProduct
        ) {
          return;
        }

        this.metrika.remove(
          this.pendingClassicCartProduct
        );

        this.pendingClassicCartProduct =
          null;
      }
    );
  }
}

class CheckoutMetrika {
  constructor(metrika) {
    this.metrika = metrika;
    this.$checkoutForm =
      jQuery('form.checkout');

    this.bindEvents();
  }

  bindEvents() {
    if (!this.$checkoutForm.length) {
      return;
    }

    this.$checkoutForm.on(
      'checkout_place_order_success.plntMetrika',
      (
        event,
        response
      ) => {
        this.handleSuccess(response);
      }
    );
  }

  handleSuccess(response) {
    const products = [];

    this.$checkoutForm
      .find(
        [
          '.woocommerce-checkout-review-order-table',
          '.cart_item',
        ].join(' ')
      )
      .each(function () {
        products.push(
          MetrikaProduct.fromElement(
            this,
            this.dataset
              .product_quantity
          )
        );
      });

    this.metrika.purchase(
      response.order_id,
      products
    );

    this.metrika.reachGoal(
      'checkout-success'
    );
  }
}


class PlntYandexMetrika {
  constructor(counterId) {
    this.metrika = new YandexEcommerce(counterId);

    window.plntYandexEcommerce = this.metrika;

    this.addToCart =
      new AddToCartMetrika(
        this.metrika
      );

    this.removeFromCart =
      new RemoveFromCartMetrika(
        this.metrika
      );

    this.checkout =
      new CheckoutMetrika(
        this.metrika
      );
  }
}


jQuery(function () {
  new PlntYandexMetrika(103710881);
});