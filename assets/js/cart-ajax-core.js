/* INIT CART AJAX UPDATE */

document.addEventListener('DOMContentLoaded', plntUpdateCartWishlist);

jQuery(document.body).on('removed_from_cart', () => {
    console.log('removed_from_cart');
    plntGetCartPageFragments();
});


jQuery(function($){
	$( document ).on( 'plnt:cart-page-fragments-updated', function(){
		console.log('hi plnt:cart-page-fragments-updated');
    productSliders.destroyAll();
    productSliders.init();
	});
})

/* CUSTOM AJAX CART UPDATE */
function plntAjaxCartUpdate(cartItemKey, quantity) {
  const formData = new FormData();

  formData.append('action', 'plnt_cart_update');
  formData.append('cart_item_key', cartItemKey);
  formData.append('qty', quantity);

  return fetch(woocommerce_params.ajax_url, {
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
      
      plntApplyCartFragments(data.fragments)

      updateCatalogButtons(data.fragments)
      return data;
    });
}

function plntApplyCartFragments(fragments) {
  Object.entries(fragments || {}).forEach(
    ([selector, html]) => {
      document
        .querySelectorAll(selector)
        .forEach((element) => {
          element.outerHTML = html;
        });
    }
  );
}

/* CART PAGE AJAX UPDATE */

async function plntGetCartPageFragments() {
  if (
    !document.body.classList.contains(
      'woocommerce-cart'
    )
  ) {
    return null;
  }

  const body = new URLSearchParams({
    action: 'plnt_get_cart_page_fragments',
  });

  try {
    const response = await fetch(
      woocommerce_params.ajax_url,
      {
        method: 'POST',
        credentials: 'same-origin',
        cache: 'no-store',
        body,
      }
    );

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const result = await response.json();

    if (!result.success) {
      throw new Error(
        'Не удалось получить фрагменты корзины'
      );
    }

    plntApplyCartFragments(result.data.fragments);

    document.dispatchEvent(
      new CustomEvent('plnt:cart-page-fragments-updated')
    );


    return result.data.fragments;
  } catch (error) {
    console.error(
      'Ошибка обновления страницы корзины:',
      error
    );

    return null;
  }
}

/* CART FRAGMENTS RELOAD SYNC */
  async function plntUpdateCartWishlist() {
    const body = new URLSearchParams({
      action: 'plnt_get_cart_wish',
    });

    try {
      const response = await fetch(
        woocommerce_params.ajax_url,
        {
          method: 'POST',
          credentials: 'same-origin',
          cache: 'no-store',
          body,
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`)
      }

      const data = await response.json()
      console.log(data)

      const fragments = data.fragments ?? {}

   

      updateCatalogButtons(fragments)
      updateWishBtns(data.wish_ids)
      updateWishCount(data.wish_count)

      plntApplyCartFragments(fragments)

       document.dispatchEvent(
        new CustomEvent('plnt:cart-wish-sync')
      );
    } catch (error) {
      console.error('Ошибка получения избранного:', error);

      return null;
    }
  }

  //HELPERS
  function updateCatalogButtons(fragments) {

    const miniCartHtml = fragments['div.mini-cart']
    if(!miniCartHtml) {
      return
    }
    const miniCartRoot = jQuery(miniCartHtml).get(0)

    console.log('hi updateCatalogButtons')

    // Собираем product_id из мини-корзины (O(n))
    const productsInCart = new Set(
      Array.from(
        miniCartRoot.querySelectorAll('.woocommerce-mini-cart-item .remove_from_cart_button')
      )
        .map(el => el.dataset.product_id)
        .filter(Boolean)
    );

    // // Один проход по всем кнопкам (и add, и remove)
    const buttons = document.querySelectorAll('.product .add_to_cart_button, .product .remove_from_cart_button');

    buttons.forEach(btn => {
      const pid = btn.dataset.product_id;
      if (!pid) return;

      // исключение "Пересадка"
      if (btn.dataset.categoryName === 'Пересадка') return;

      const inCart = productsInCart.has(pid);

      // Родитель (иногда form, иногда нет)
      const parent = btn.parentElement;
      const isForm = parent && parent.matches && parent.matches('form');

      if (inCart) {
        // поставить remove link если он есть
        const removeLink = btn.dataset.remove_link;
        if (removeLink) {
          if (isForm) parent.setAttribute('action', removeLink);
          if (btn.matches('a')) btn.setAttribute('href', removeLink);
        }

        // классы/текст (не сносим все классы целиком)
        btn.classList.remove('add_to_cart_button', 'ajax_add_to_cart');
        btn.classList.add('remove_from_cart_button', 'added');
        btn.textContent = 'Добавлен';
      } else {
        // вернуть add-to-cart ссылку/экшен
        const addLink = `?add-to-cart=${pid}`;
        if (isForm) parent.setAttribute('action', addLink);
        if (btn.matches('a')) btn.setAttribute('href', addLink);

        btn.classList.remove('remove_from_cart_button', 'added');
        btn.classList.add('add_to_cart_button', 'ajax_add_to_cart');
        btn.textContent = 'В корзину';
      }
    });
  }

  function updateWishBtns(wishListItemsStr) {
    // console.log('hi updateWishBtns');
    // console.log(wishListItemsStr);
    if(wishListItemsStr) {
      let wishListItems = wishListItemsStr.split(',');
      //console.log(wishListItems);
      let addToWishBtns = document.querySelectorAll('.yith-wcwl-add-button .add_to_wishlist');
      let removeToWishBtns = document.querySelectorAll('.yith-wcwl-add-button .delete_item');

      //console.log(wishBtns);
      addToWishBtns.forEach(button => {
          //console.log(button);
          //console.log(button.dataset.productId);
          if(wishListItems.includes(button.dataset.productId)) {
          //console.log(button);
          button.setAttribute('href', `?remove_from_wishlist=${button.dataset.productId}`);
          button.setAttribute('class', 'delete_item');
          let img = button.querySelector('img');
          img.setAttribute('src','https://plantis-shop.ru/wp-content/uploads/2025/07/heart_black_new.svg');
          };
      });

      removeToWishBtns.forEach(button => {
          //console.log(button);
          //console.log(button.dataset.productId);
          if(wishListItems.includes(button.dataset.productId)) {
              return;
          } else {
              //console.log(button);
              button.setAttribute('href', `?add_to_wishlist=${button.dataset.productId}`);
              button.setAttribute('class', 'add_to_wishlist single_add_to_wishlist');
              let img = button.querySelector('img');
              img.setAttribute('src','https://plantis-shop.ru/wp-content/uploads/2025/07/heart_gray_new.svg');
          };
      });
    }
  }

  function updateWishCount(count) {
    const wishCount = Number(count) || 0;

    document
      .querySelectorAll('.yith-wcwl-items-count > i')
      .forEach((element) => {
        element.textContent = wishCount;
      });

    document
      .querySelectorAll(
        '.header__actions--wishlist .header__actions-count'
      )
      .forEach((element) => {
        console.log(element)
        element.classList.toggle(
          'header__actions-count--active',
          wishCount > 0
        );
      });
  }

  function parseMiniCartElement() {
    console.log('hi parseMiniCartElement')
    const miniCartElement = document.querySelector('div.mini-cart');

    // Массив товаров: product_id + quantity
    const productsInCart = Array.from(
      miniCartElement.querySelectorAll('.woocommerce-mini-cart-item .remove_from_cart_button')
    )
    .map((button) => ({
      product_id: button.dataset.product_id,
      quantity: Number(
        button.dataset.product_quantity
      ) || 1,
    }))
    .filter((product) => product.product_id);
    
    return productsInCart;
  }
/* 
 */
