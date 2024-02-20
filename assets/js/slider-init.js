const swiper_about_us = new Swiper('.swiper_about-us', {
    pagination: {
        el: '.swiper-pagination',
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 3,
    // spaceBetween: 10,
    // breakpoints: {
    //     320: {
    //     slidesPerView: 1,
    //     },
    //     640: {
    //     slidesPerView: 2,
    //     },
    //     768: {
    //     slidesPerView: 3,
    //     }
    // }
});