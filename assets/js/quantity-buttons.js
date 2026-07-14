const ajaxQuantitySelector = '.ajax-quantity';
const productCardQuantitySelector = '.card__summary-wrap .quantity';
const removeBtn = '.plnt_remove_from_cart_button';

document.addEventListener('DOMContentLoaded',() => {
    new AjaxQuantityCollection()
    new ProductCardQuantityCollection()
    new AjaxCartRemoveCollection()
  }
);

class Quantity {
  constructor(element) {
    this.element = element;
    this.input = element.querySelector('input.qty');

    if (!this.input) {
      return;
    }

    this.plusButton = element.querySelector('.plus');
    this.minusButton = element.querySelector('.minus');

    const min = parseInt(this.input.min, 10);
    const max = parseInt(this.input.max, 10);
    const step = parseInt(this.input.step, 10);

    this.min = Number.isNaN(min) ? 0 : min;
    this.max = max;
    this.step = Number.isNaN(step) ? 1 : step;

    this.timer = null;

    // this.bindEvents();
    this.updateButtonsUi(this.getValue());
  }

  // bindEvents() {
  //   this.element.addEventListener('click', (event) => {
  //     this.onClick(event);
  //   });

  //   this.input.addEventListener('input', () => {
  //     this.onInput();
  //   });

  //   this.input.addEventListener('change', () => {
  //     this.onChange();
  //   });
  // }

  onClick(event) {
    const button = event.target.closest('.plus, .minus');

    if (!button || !this.element.contains(button)) {
      return;
    }

    event.preventDefault();

    const value = this.getValue();
    let newValue = value;

    if (button === this.plusButton) {
      if (this.max && value >= this.max) {
        newValue = this.max;
      } else {
        newValue = value + this.step;
      }
    }

    if (button === this.minusButton) {
      if (value <= this.min) {
        newValue = this.min;
      } else if (value > 0) {
        newValue = value - this.step;
      }
    }

    if (newValue === value) {
      this.updateButtonsUi(newValue);
      return;
    }

    this.setValue(newValue);
    this.queueQuantityChange(newValue);
  }

  onInput() {
    this.queueQuantityChange(this.getValue());
  }

  onChange() {
    let value = this.getValue();

    if (value < this.min) {
      value = this.min;
    }

    if (this.max && value > this.max) {
      value = this.max;
    }

    this.setValue(value);
    this.queueQuantityChange(value);
  }

  queueQuantityChange(value) {
    clearTimeout(this.timer);

    this.timer = setTimeout(() => {
      this.handleQuantityChange(value);
    }, 300);
  }

  handleQuantityChange(value) {
    this.updateButtonsUi(value);
  }

  getValue() {
    const value = parseInt(this.input.value, 10);

    return Number.isNaN(value)
      ? this.min
      : value;
  }

  setValue(value) {
    this.input.value = value;
    this.input.setAttribute('value', value);
  }

  updateButtonsUi(value) {
    if (this.minusButton) {
      const isDisabled = value <= this.min;

      this.minusButton.classList.toggle(
        'is-disabled',
        isDisabled
      );

      this.minusButton.disabled = isDisabled;
    }
    if (this.plusButton) {
      const isDisabled = value >= this.max;

      this.plusButton.classList.toggle(
        'is-disabled',
        isDisabled
      );

      this.plusButton.disabled = isDisabled;
    }
  }
}

class AjaxQuantity extends Quantity {
  constructor(element) {
    super(element);

    if (!this.input) {
      return;
    }
    
    this.cartItemKey = this.getCartItemKeyFromInput();

    this.savedQuantity = this.getValue();

    this.productElement = this.element.closest('[data-js-metrika-product]')
    
    this.productId = this.productElement?.dataset.product_id || ''
  }

  handleQuantityChange(value) {
    super.handleQuantityChange(value);
    this.updateCart();
  }

  getCartItemKeyFromInput() {
    const match = this.input.name.match(
      /^cart\[([^\]]+)\]\[qty\]$/
    );

    return match ? match[1] : '';
  }

  updateCart() {
    const quantity = this.getValue();

    if (!this.cartItemKey) {
      return;
    }

    const previousQuantity = this.savedQuantity;

    this.element.classList.add('is-processing');

    plntAjaxCartUpdate(this.cartItemKey, quantity)
      .then(() => {
        const difference =
          quantity - previousQuantity;

        if (
          difference !== 0 &&
          this.productElement
        ) {
          const product =
            MetrikaProduct.fromElement(
              this.productElement,
              Math.abs(difference)
            );

          if (difference > 0) {
            window.plntYandexEcommerce?.add(
              product
            );
          } else {
            window.plntYandexEcommerce?.remove(
              product
            );
          }
        }

        this.savedQuantity = quantity;

        document.dispatchEvent(
          new CustomEvent(
            'plnt:cart-quantity-changed',
            {
              detail: {
                productId: this.productId,
                quantity,
              },
            }
          )
        );

        return plntGetCartPageFragments();
      })
      .catch((error) => {
        console.error(
          'AjaxQuantity updateCart:',
          error
        );
      })
      .finally(() => {
        if (this.element.isConnected) {
          this.element.classList.remove(
            'is-processing'
          );
        }
      });
  }
}

class ProductCardQuantity extends AjaxQuantity {
  constructor(element) {
    super(element);

    if (!this.input) {
      return;
    }

    this.updateElements()
    
    const stock = parseInt(
      this.addToCartButton?.dataset.stockQuantity,
      10
    );

    this.stock = Number.isNaN(stock)
      ? 0
      : stock;

    this.backorderInfo =
      this.productCard?.querySelector(
        '.card__banner--backorder-info'
      );

    const value = this.getValue();

    this.updateAddToCartQuantity(value);
    this.updateBackorderInfo(value);
  }

  updateElements() {
    this.productCard = this.element.closest(
      '.card__summary-wrap'
    );

    this.addToCartButton =
      this.productCard?.querySelector(
        '.add-to-cart-wrap .product_type_simple'
      );

    this.isInCart =
      this.addToCartButton?.classList.contains(
        'remove_from_cart_button'
      ) ?? false;

    this.getCartItemKeyFromButton(
      this.isInCart
    );
  }

  getCartItemKeyFromButton (isInCart) {
    if(isInCart) {
      this.cartItemKey = this.addToCartButton?.dataset.cart_item_key;
    } else {
      this.cartItemKey = ''
    }
  }

  handleQuantityChange(value) {
    super.handleQuantityChange(value);

    this.updateAddToCartQuantity(value);
    this.updateBackorderInfo(value);
  }

  syncQuantity(value) {
    let quantity = parseInt(value, 10);

    if (Number.isNaN(quantity)) {
      return;
    }

    if (quantity < this.min) {
      quantity = this.min;
    }

    if (this.max && quantity > this.max) {
      quantity = this.max;
    }

    this.setValue(quantity);

    /* Сохраняем актуальное количество корзины, чтобы следующая метрика считала разницу правильно.*/
    this.savedQuantity = quantity;

    this.updateButtonsUi(quantity);
    this.updateAddToCartQuantity(quantity);
    this.updateBackorderInfo(quantity);
  }

  updateCartState() {
    this.updateElements();

    this.updateAddToCartQuantity(
      this.getValue()
    );
  }

  // bindEvents() {
  //   super.bindEvents()

  //   jQuery(document.body).on('wc_cart_button_updated', () => {
  //       console.log('wc_cart_button_updated');
  //       this.getCartItemKeyFromButton(true)
  //   });
  //   jQuery(document.body).on('removed_from_cart', () => {
  //       console.log('removed_from_cart');
  //       this.getCartItemKeyFromButton(false)
  //   });
  // }

  updateAddToCartQuantity(value) {
    if (!this.addToCartButton) {
      return;
    }

    this.addToCartButton.dataset.quantity = value;
  }

  updateBackorderInfo(value) {
    if (!this.backorderInfo || !this.stock) {
      return;
    }

    if (value > this.stock) {
      const isActive =
        this.backorderInfo.classList.contains(
          'is-active'
        );

      this.backorderInfo.classList.add(
        'is-active'
      );

      if (!isActive) {
        setTimeout(() => {
          this.backorderInfo.scrollIntoView({
            behavior: 'smooth',
            block: 'end',
          });
        }, 300);
      }

      return;
    }

    if (value <= this.stock) {
      this.backorderInfo.classList.remove(
        'is-active'
      );
    }
  }
}

class BaseQuantityCollection {
  constructor(selector, QuantityClass) {
    this.selector = selector;
    this.QuantityClass = QuantityClass;

    this.instances = new WeakMap();
    this.$body = document.body;

    this.init();
    this.bindEvents();
  }

  init() {
    document
      .querySelectorAll(this.selector)
      .forEach((element) => {
        this.getInstance(element);
      });
  }

  bindEvents() {
    this.$body.addEventListener('click', (event) => {
      const button = event.target.closest(
        '.plus, .minus'
      );

      if (!button) {
        return;
      }

      const element = this.getQuantityElement(
        button
      );

      if (!element) {
        return;
      }

      this.getInstance(element)?.onClick(event);
    });

    this.$body.addEventListener('focusin', (event) => {
      if (!event.target.matches('input.qty')) {
        return;
      }

      const element = this.getQuantityElement(
        event.target
      );

      if (element) {
        this.getInstance(element);
      }
    });

    this.$body.addEventListener('input', (event) => {
      if (!event.target.matches('input.qty')) {
        return;
      }

      const element = this.getQuantityElement(
        event.target
      );

      if (!element) {
        return;
      }

      this.getInstance(element)?.onInput();
    });

    this.$body.addEventListener('change', (event) => {
      if (!event.target.matches('input.qty')) {
        return;
      }

      const element = this.getQuantityElement(
        event.target
      );

      if (!element) {
        return;
      }

      this.getInstance(element)?.onChange();
    });
  }

  getQuantityElement(target) {
    const element = target.closest(
      this.selector
    );

    if (!element ) {
      return null;
    }

    return element;
  }


  getInstance(element) {
    let instance = this.instances.get(element);

    if (instance) {
      return instance;
    }

    instance = new this.QuantityClass(element);

    if (!instance.input) {
      return null;
    }

    this.instances.set(element, instance);

    return instance;
  }
}

class AjaxQuantityCollection extends BaseQuantityCollection {
  constructor() {
    super(
      ajaxQuantitySelector,
      AjaxQuantity
    );
  }
}

class ProductCardQuantityCollection extends BaseQuantityCollection {
  constructor() {
    super(
      productCardQuantitySelector,
      ProductCardQuantity
    );

    this.bindWooCommerceEvents();
    this.bindQuantitySyncEvents();
  }

  updateProductCardInstances() {
    document
      .querySelectorAll(this.selector)
      .forEach((element) => {
        const instance =
          this.getInstance(element);

        instance?.updateCartState();
      });
  }

  syncQuantity(productId, quantity) {
    document
      .querySelectorAll(this.selector)
      .forEach((element) => {
        const instance =
          this.getInstance(element);

        if (!instance) {
          return;
        }

        /*
        * Кнопка могла быть заменена WooCommerce.
        */
        instance.updateElements();

        const cardProductId = instance.addToCartButton?.dataset.product_id;

        if (String(cardProductId) !== String(productId)) {
          return;
        }

        instance.syncQuantity(quantity);
      });
  }

  bindWooCommerceEvents() {
    jQuery(this.$body).on(
      'wc_cart_button_updated removed_from_cart',
      () => {
        setTimeout(() => {
          this.updateProductCardInstances();
        }, 0);
      }
    );
  }
  bindQuantitySyncEvents() {
    document.addEventListener(
      'plnt:cart-quantity-changed',
      (event) => {
        const {
          productId,
          quantity,
        } = event.detail || {};

        if (!productId) {
          return;
        }
        this.syncQuantity(
          productId,
          quantity
        );
      }
    );
  }
}

// class AjaxQuantityCollection {
//   constructor() {
//     this.init();
//     this.bindWooCommerceEvents();
//   }

//   init() {
//     const elements = document.querySelectorAll(
//       ajaxQuantitySelector
//     );

//     elements.forEach((element) => {
//       if (element.dataset.ajaxQuantityInit === '1') {
//         return;
//       }

//       element.dataset.ajaxQuantityInit = '1';

//       new AjaxQuantity(element);
//     });
//   }

//   bindWooCommerceEvents() {
//     /* После событий WooCommerce карточки и кнопки могут быть заменены новым HTML*/
//     jQuery(document.body).on('updated_wc_div wc_fragments_loaded wc_fragments_refreshed added_to_cart removed_from_cart',() => {
//         this.init();
//       }
//     );

//     // событие после кастомного AJAX-обновления корзины
//     document.addEventListener(
//       'plnt:cart-page-fragments-updated',
//       () => {
//         this.init();
//       }
//     );
//     document.addEventListener(
//       'plnt:cart-wish-sync',
//       () => {
//         this.init();
//       }
//     );
//     document.addEventListener(
//       'plnt:cart-updated',
//       () => {
//         this.init();
//       }
//     );
//   }
// }





// class ProductCardQuantityCollection {
//   constructor() {
//     this.init();
//     this.bindWooCommerceEvents();
//   }

//   init() {
//     const elements = document.querySelectorAll(
//       productCardQuantitySelector
//     );

//     elements.forEach((element) => {
//       if (
//         element.dataset.productCardQuantityInit === '1'
//       ) {
//         return;
//       }

//       element.dataset.productCardQuantityInit = '1';

//       new ProductCardQuantity(element);
//     });
//   }

//   bindWooCommerceEvents() {
//     /* После событий WooCommerce карточки и кнопки могут быть заменены новым HTML*/
//     jQuery(document.body).on(
//       [
//         'wc_fragments_loaded',
//         'wc_fragments_refreshed',
//         'added_to_cart',
//         'removed_from_cart',
//         'updated_wc_div',
//       ].join(' '),
//       () => {
//         this.init();
//       }
//     );

//   }
// }

class AjaxCartRemove {
  constructor(element) {
    this.element = element;
    this.productElement = element.closest('[data-js-metrika-product]');

    // this.onClick = this.onClick.bind(this);

    // this.bindEvents();
  }

  // bindEvents() {
  //   this.element.addEventListener('click', this.onClick);
  // }

  async onClick(event) {
    event.preventDefault();

    if (this.element.classList.contains('is-processing')) {
      return;
    }

    const cartItemKey = this.element.dataset.cart_item_key;
    const quantity = Number(this.element.dataset.product_quantity) || 1;

    if (!cartItemKey) {
      console.error('AjaxCartRemove: отсутствует data-cart_item_key');
      return;
    }

    this.element.classList.add('is-processing');

    try {
      /*
       * Устанавливаем количество позиции в корзине равным 0.
       */
      await plntAjaxCartUpdate(cartItemKey, 0);

      /*
       * Отправляем remove-событие в Яндекс Метрику.
       */
      if (this.productElement) {
        const product = MetrikaProduct.fromElement(
          this.productElement,
          quantity
        );

        window.plntYandexEcommerce?.remove(product);
      }

      /*
       * Обновляем HTML корзины.
       */
      await plntGetCartPageFragments();
    } catch (error) {
      console.error('AjaxCartRemove:', error);
    } finally {
      /*
       * После обновления фрагментов элемент может быть уже удалён из DOM.
       */
      if (this.element.isConnected) {
        this.element.classList.remove('is-processing');
      }
    }
  }
}

class AjaxCartRemoveCollection {
  constructor() {
    this.$body = document.body;

    this.bindEvents();
  }

  bindEvents() {
    this.$body.addEventListener(
      'click',
      (event) => {
        const element =
          event.target.closest(removeBtn);

        if (!element) {
          return;
        }

        const ajaxCartRemove =
          new AjaxCartRemove(element);

        ajaxCartRemove.onClick(event);
      }
    );
  }
}

// class AjaxCartRemoveCollection {
//   constructor() {
//     this.init();
//     this.bindWooCommerceEvents();
//   }

//   init() {
//     const elements =
//       document.querySelectorAll(
//         removeBtn
//       );

//     elements.forEach((element) => {
//       if (
//         element.dataset.ajaxCartRemoveInit ===
//         '1'
//       ) {
//         return;
//       }

//       element.dataset.ajaxCartRemoveInit = '1';

//       new AjaxCartRemove(element);
//     });
//   }

//   bindWooCommerceEvents() {
//     /* WooCommerce заменяет HTML корзины и мини-корзины после AJAX.*/
//     jQuery(document.body).on(
//       [
//         'updated_wc_div',
//         'wc_fragments_loaded',
//         'wc_fragments_refreshed',
//         'added_to_cart',
//         'removed_from_cart',
//       ].join(' '),
//       () => {
//         this.init();
//       }
//     );

//     /* Событие после кастомного AJAX-обновления корзины.*/
//     document.addEventListener(
//       'plnt:cart-page-fragments-updated',
//       () => {
//         this.init();
//       }
//     );
//   }
// }

class AddToCartButton {
  constructor() {
    this.$body = jQuery(document.body);

    this.bindEvents();
  }

  bindEvents() {
    this.$body.on(
      'click.plntAddToCartButton',
      '.remove_from_cart_button',
      (event) => {
        event.currentTarget.classList.add(
          'loading'
        );
      }
    );

    this.$body.on(
      [
        'removed_from_cart.plntAddToCartButton',
        'wc_fragments_refreshed.plntAddToCartButton',
        'updated_wc_div.plntAddToCartButton',
      ].join(' '),
      () => {
        this.removeLoading();
      }
    );

    this.$body.on(
      'added_to_cart.plntAddToCartButton',
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

        this.handleAddedToCart(button);
      }
    );

    this.$body.on(
      'removed_from_cart.plntAddToCartButton',
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

        this.handleRemovedFromCart(button);
      }
    );
  }

  removeLoading() {
    document
      .querySelectorAll(
        '.remove_from_cart_button.loading'
      )
      .forEach((button) => {
        button.classList.remove('loading');
      });
  }

  handleAddedToCart(button) {
    console.log(button)
    const productCategory = button.dataset.product_category;

    /* Для услуги «Пересадка» кнопку не превращаем в кнопку удаления. */
    if (productCategory === 'Пересадка') {
      const productId = button.dataset.product_id;

      sessionStorage.setItem('peresadkaProdId',productId);

      return;
    }

    const removeLink = button.dataset.remove_link;

    button.textContent = 'Добавлен';

    /* В каталоге кнопка может находиться внутри формы. */
    if (button.form) {
      button.form.action = removeLink;
    } else {
      button.href = removeLink;
    }

    button.classList.remove(
      'add_to_cart_button',
      'ajax_add_to_cart',
    );

    button.classList.add(
      'remove_from_cart_button',
      'added'
    );
  }

  handleRemovedFromCart(button) {
    const productId =  button.dataset.product_id;

    if (!productId) {
      return;
    }

    const addToCartLink = `?add-to-cart=${productId}`;

    /* Ищем все кнопки этого товара: в каталоге и в карточке товара.*/
    document
      .querySelectorAll(
        `[data-product_id="${productId}"]`
      )
      .forEach((productButton) => {
        if (
          !productButton.classList.contains(
            'remove_from_cart_button'
          )
        ) {
          return;
        }

        productButton.textContent = 'В корзину';

        if (productButton.form) {
          productButton.form.action =  addToCartLink;
        } else {
          productButton.href = addToCartLink;
        }

        productButton.classList.remove(
          'remove_from_cart_button',
          'added',
          'loading'
        );

        productButton.classList.add(
          'add_to_cart_button',
          'ajax_add_to_cart'
        );
      });
  }
}

new AddToCartButton()


// jQuery(function ($){
//   $( 'body' ).on( 'click', 'div.plus, div.minus', function() {

//   console.log('qty btns')

// 	var qty = $(this).parent().find( 'input' ),
// 	val = parseInt( qty.val() ),
// 	min = parseInt( qty.attr( 'min' ) ),
// 	max = parseInt( qty.attr( 'max' ) ),
// 	step = parseInt( qty.attr( 'step' ) );

 
// 	// дальше определяем новое значение количества в зависимости от нажатия кнопки
// 	var newVal;
// 	if ( $( this ).is( '.plus' ) ) {
// 		$( '[name="update_cart"]' ).attr("data-metrika_action",'add'); //для Yandex Metrika E-commerce в корзине #TODO если val = max
// 		if ( max && ( max <= val ) ) {
// 			newVal= max;
// 		} else {
// 			newVal= val + step ;
// 		}
// 	}

// 	if ( $( this ).is( '.minus' ) ) {
// 		$( '[name="update_cart"]' ).attr("data-metrika_action",'remove'); //для Yandex Metrika E-commerce в корзине
// 		if ( min && ( min >= val ) ) {
// 			newVal =  min;
// 		} else if ( val > 1 ) {
// 			newVal= val - step;
// 		}
// 	}

// 	qty.attr('value', newVal );  //устанавливаем новое значение для инпута
// 	qty.val(newVal);  //устанавливаем новое значение для инпута

// 	// меняем стили кнопок на активные/неактивные
// 	if (newVal === max) {
// 		$(".plus").attr('style','opacity:50%; cursor: default; pointer-events: none;');
// 	} else {
// 		$(".plus").attr('style','opacity:100%; cursor: pointer; pointer-events: auto;');
// 	}

// 	if (newVal === min) {
// 		$(".minus").attr('style','opacity:50%; cursor: default; pointer-events: none;');
// 	} else {
// 		$(".minus").attr('style','opacity:100%; cursor: pointer; pointer-events: auto;');
// 	}

// 	qty.parent().parent().find(".add_to_cart_button").attr( 'data-quantity', newVal ); //устанавливаем новое значение для атрибута кнопки добавить в корзину. div "quantity" должен находится в одном родительском узле с кнопкой в корзирну

// 	//уведомление для backorder
// 	var stock = parseInt(qty.parent().parent().find(".product_type_simple").attr( 'data-stock-quantity'));
// 	var backorderInfo = qty.parent().parent().parent().parent().parent().parent().find(".card__banner--backorder-info");
// 	if (stock >0) {
// 		if (newVal == (stock + 1)) {
// 			backorderInfo.addClass('is-active');
//       setTimeout(() => {
//         backorderInfo[0]?.scrollIntoView({
//           behavior: 'smooth',
//           block: 'end',
//         })
//       }, 300)
// 		} 
// 		if (newVal <= (stock)) {
// 			backorderInfo.removeClass('is-active');
// 		}
// 	} 


// 	// определеям товар, для которого изменили кол-во и находим его параметры, записанные в кнопку удаления remove - для Yandex Metrika E-commerce
// 	var $productRemove = $(this).parent().parent().parent().find('.plnt_product-remove > a')[0];
// 	if ($productRemove) {
// 		var $productData = $productRemove.dataset;
// 		// console.log($productData);
	
// 		$( '[name="update_cart"]' ).attr("data-product_name",$productData.product_name);
// 		$( '[name="update_cart"]' ).attr("data-product_category",$productData.product_category);
// 		$( '[name="update_cart"]' ).attr("data-product_price",$productData.product_price);
// 	}
	
// 	$( '[name="update_cart"]' ).removeAttr("disabled").trigger( 'click' ); // автообновление корзины без перезагрузки 
// });
// })


// // аналогичная функция - работает при изменении кол-ва в инпуте напрямую в карточке товара
// jQuery(function ($){	
// 	$( 'div.quantity .qty' ).change( function() {
//   console.log('qty div')
// 	const qty = $(this).val();
// 	$(this).parent().parent().find(".add_to_cart_button").attr( 'data-quantity', qty );

// 	//уведомление для backorder
// 	var stock = parseInt($(this).parent().parent().find(".product_type_simple").attr( 'data-stock-quantity'));
// 	var backorderInfo = $(this).parent().parent().parent().parent().find(".card__banner--backorder-info");
// 	if (stock >0) {
// 		if (qty == (stock + 1)) {
// 			backorderInfo.addClass('is-active');
// 		} 
// 		if (qty <= (stock)) {
// 			backorderInfo.removeClass('is-active');
// 		} 
// 	}

// 	} )
// });