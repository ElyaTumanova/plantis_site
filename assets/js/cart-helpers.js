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