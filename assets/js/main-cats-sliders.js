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
    // console.log('catsTerm ',catsTerm);
    // console.log('taxonomyType ',taxonomyType);

}

function ajaxGetMainCatTerm() {
    jQuery( function($){
        // console.log('ajaxGetMainCatTerm init');
        // console.log('catsTerm ajax',catsTerm);
        // console.log('taxonomyType ajax',taxonomyType);
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
                // console.log('ajaxGetMainCatTerm success');
                $('.main__cats-slider').html(data.out);
                // console.log(data.out);
                let slider = document.querySelectorAll(".product-slider-swiper .product");
                slider.forEach((slide) => {
                    slide.classList.add('swiper-slide');
                });
                swiper_product_slider_init();
            }
        });
    });

}
//document.addEventListener('DOMContentLoaded',()=>{showSlider(0)});
document.addEventListener('DOMContentLoaded', swiper_product_slider_init);

navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});

