let navItems = document.querySelectorAll('.cats-nav__title');
let catsTerm;
let taxonomyType;

function showSlider(sliderNmber) {
    navItems.forEach((el,index) => {
        if(index === sliderNmber) {
            el.classList.add('cats-nav__title--active');
            catsTerm = el.dataset.term;
            taxonomyType = el.dataset.type;
            ajaxGetMainCatTerm();
        } else {
            el.classList.remove('cats-nav__title--active');
        }
    });
    // console.log('catsTerm ',catsTerm);
    // console.log('taxonomyType ',taxonomyType);

}

function ajaxGetMainCatTerm() {
  jQuery(function ($) {
    const sliderWrap = document.querySelector('.front__popular .product-slider-wrap')

    sliderWrap?.classList.add('loading')

    $.ajax({
      type: 'POST',
      url: woocommerce_params.ajax_url,
      data: {
          action: 'get_main_cats_term',
          term: catsTerm,
          type: taxonomyType,
      },
      dataType: 'json',

      success: function (response) {
        if (!response.success) {
            return;
        }

        const oldSlider = document.querySelector('.front__popular .product-slider-swiper');

        if (oldSlider && productSliders) {
          productSliders.remove(oldSlider);
        }

        $('.front__popular .product-slider-wrap').replaceWith(response.data.out);
        $('.front__popular .front__products-all').replaceWith(response.data.out_link);

        const newSlider = document.querySelector('.front__popular .product-slider-swiper');

        if (newSlider && productSliders) {
          productSliders.add(newSlider);
        }
      },
      complete: function () {
        const newSliderWrap = document.querySelector('.front__popular .product-slider-wrap')
        newSliderWrap?.classList.remove('loading')
      },
    });
  });
}


navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});

// (function ($) {
//   function ajaxPing(cb) {
//     const url = window.ajaxurl || '/wp-admin/admin-ajax.php'; // WP сам задаёт ajaxurl в админке; на фронте лучше локализовать (см. ниже)
//     const t0 = performance.now();

//     $.ajax({
//       url,
//       type: 'POST',
//       data: { action: 'ping' },
//       timeout: 8000, // 8s
//       success: function (resp, status, xhr) {
//         const duration = performance.now() - t0;
//         if (cb) cb(null, resp, { duration });
//         console.log('ping ok:', resp, `${duration.toFixed(1)}ms`);
//       },
//       error: function (xhr, status, err) {
//         if (cb) cb(err || status);
//         console.warn('ping error:', status, err);
//       }
//     });
//   }

//   // пример вызова сразу при загрузке
//   $(function () {
//     ajaxPing();
//     // или по клику: $('#btn-ping').on('click', ajaxPing);
//   });
// })(jQuery);
