let navItems = document.querySelectorAll('.main__cats-nav-title');
let catsTerm;
//let catsSliders = document.querySelectorAll('.main__cats-slider');

function showSlider(sliderNmber) {
    // console.log(event.target);
    // console.log(sliderNmber);
    navItems.forEach((el,index) => {
        if(index === sliderNmber) {
            el.classList.add('main__cats-nav-title_active');
            console.log(el.id);
            catsTerm = el.id;
            ajaxGetMainCatTerm();
        } else {
            el.classList.remove('main__cats-nav-title_active');
        }

    });
    // catsSliders.forEach((el,index) => {
    //     if(index === sliderNmber) {
    //         el.classList.add('main__cats-slider_open');
    //     } else {
    //         el.classList.remove('main__cats-slider_open');
    //     }
    // });

}

function ajaxGetMainCatTerm() {
    jQuery( function($){
        $.ajax({
            type: 'POST',
            url: woocommerce_params.ajax_url,
            data: {
                'action': 'get_main_cats_term',
                'term': catsTerm,
            },
            dataType: 'json',
            beforeSend: function(xhr){
            },
            success: function (data) {
                $('.main__cats-slider .products').html(data.out);
                console.log('hihhiih');

                let swiper_product_sliders = document.querySelectorAll(".product-slider-swiper");
                swiper_product_sliders.forEach((slider) => {
                    consiloe.log(slider);
                    slider.swiper.update();
                });

            }
        });
    });
}
//showSlider(0);
navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});