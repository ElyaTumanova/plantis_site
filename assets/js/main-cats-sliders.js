let navItems = document.querySelectorAll('.main__cats-nav-title');
let catsTerm;
let taxonomyType;

function showSlider(sliderNmber) {
    // console.log(event.target);
    // console.log(sliderNmber);
    navItems.forEach((el,index) => {
        if(index === sliderNmber) {
            el.classList.add('main__cats-nav-title_active');
            console.log(el.dataset);
            //console.log(el.data-type);
            catsTerm = el.el.dataset.term;
            taxonomyType = el.el.dataset.type;
            ajaxGetMainCatTerm();
        } else {
            el.classList.remove('main__cats-nav-title_active');
        }

    });

}

function ajaxGetMainCatTerm() {

    let slides_test = ['<li>lalala</li>'];
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
                $('.main__cats-slider .products').html(data.out);
                let slider = document.querySelectorAll(".product-slider-swiper .product");
                slider.forEach((slide) => {
                    //console.log(slide);
                    slide.classList.add('swiper-slide');
                });
                swiper_product_slider_init();
            }
        });
    });

}
//showSlider(0);
navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});