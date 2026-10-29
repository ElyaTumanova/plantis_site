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

        const oldSlider = document.querySelector('.front__popular [data-js-product-slider]');

        if (oldSlider && productSliders) {
          productSliders.remove(oldSlider);
        }

        $('.front__popular [data-js-product-slider]').replaceWith(response.data.out);
        $('.front__popular .front__products-all').replaceWith(response.data.out_link);

        const newSlider = document.querySelector('.front__popular [data-js-product-slider]');

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
