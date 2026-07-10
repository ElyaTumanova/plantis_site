/* отключаем скролл к началу страницы при апдейте корзины */
jQuery(function ($) {
  let cartScrollTop = 0;

  $(document).on('click', '.plus, .minus', function () {
    cartScrollTop = window.scrollY;
  });

  $(document.body).on('updated_wc_div', function () {
    $('.woocommerce-message').remove();

    requestAnimationFrame(() => {
      window.scrollTo(0, cartScrollTop);
    });
  });
});

/* отслеживаем кнопку Оформить заказ на чекауте */

let summaryScrollTimer;

function updateCheckoutSummaryState() {
  const target = document.querySelector('[data-js-button-visible]');
  const woocommerce = document.querySelector('.woocommerce');

  if (!target || !woocommerce) return;

  const rect = target.getBoundingClientRect();

  const isVisible =

    rect.top < window.innerHeight &&
    rect.bottom > 0;

  woocommerce.classList.toggle('checkout-button-visible', isVisible);
}

function initCheckoutObserver() {
  clearTimeout(summaryScrollTimer);

  summaryScrollTimer = setTimeout(() => {
    updateCheckoutSummaryState();

    window.removeEventListener('scroll', updateCheckoutSummaryState);
    window.addEventListener('scroll', updateCheckoutSummaryState, { passive: true });

    window.removeEventListener('resize', updateCheckoutSummaryState);
    window.addEventListener('resize', updateCheckoutSummaryState);
  }, 300);
}

document.addEventListener('DOMContentLoaded', initCheckoutObserver);

jQuery(document.body).on(
  'updated_checkout updated_wc_div updated_cart_totals wc_fragments_refreshed',
  initCheckoutObserver
);