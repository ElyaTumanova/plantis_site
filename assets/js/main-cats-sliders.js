let navItems = document.querySelectorAll('.main__cats-nav-title');
let catsTerm;
let taxonomyType;

function showSlider(sliderNmber) {
    navItems.forEach((el,index) => {
        if(index === sliderNmber) {
            el.classList.add('main__cats-nav-title_active');
            catsTerm = el.dataset.term;
            taxonomyType = el.dataset.type;
            ajaxGetMainCatTerm();
        } else {
            el.classList.remove('main__cats-nav-title_active');
        }
    });

}

function ajaxGetMainCatTerm() {
    jQuery( function($){
        $.ajax({
            type: 'POST',
            url: woocommerce_params.ajax_url,
            data: {
                'action': 'get_main_cats_term',
                'term': catsTerm,
                'type': taxonomyType,
            },
            dataType: 'json',
            beforeSend: function(xhr){
            },
            success: function (data) {
                $('.main__cats-slider').html(data.out);
                let slider = document.querySelectorAll(".product-slider-swiper .product");
                slider.forEach((slide) => {
                    slide.classList.add('swiper-slide');
                    console.log('heheh')
                });
                swiper_product_slider_init();
            }
        });
    });

}
showSlider(0);
navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});