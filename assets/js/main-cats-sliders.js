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
                $('.main__cats-slider').html(data.out);
                swiper_product_slider_init();
            }
        });
    });
}
showSlider(0);
navItems.forEach((el,index) => {
    el.addEventListener('click',() => showSlider(index));
});