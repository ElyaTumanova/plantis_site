/*--------------------------------------------------------------
# Front Page
--------------------------------------------------------------*/
const swiper_main_banners = new Swiper('.main__banners-swiper', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 1,
    slidesPerGroup: 1,
    spaceBetween: 0,
    breakpoints: {
        320: {
            navigation: {
                enabled: false,
            },
        },
        768: {
            navigation: {
                enabled: true,
            },
        }
    }
});

const swiper_main_sale = new Swiper('.main-sale-slider', {
    slideClass: 'product',
    wrapperClass: 'products',
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 1,
    slidesPerGroup: 1,
    spaceBetween: 0,
    breakpoints: {
        320: {
            navigation: {
                enabled: false,
            },
        },
        768: {
            navigation: {
                enabled: true,
            },
        }
    }
});



/*--------------------------------------------------------------
# About Us
--------------------------------------------------------------*/

const swiper_about_photo = new Swiper('.about__swiper-photo', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 1,
    slidesPerGroup: 1,
    spaceBetween: 10,
    breakpoints: {
        320: {
        slidesPerView: 1,
        slidesPerGroup: 1,
        navigation: {
            enabled: false,
        },
        },
        768: {
        slidesPerView: 2,
        slidesPerGroup: 2,
        },
        1023: {
        slidesPerView: 1,
        slidesPerGroup: 1,
        }
    }
});

const swiper_about_feedback = new Swiper('.about__swiper-feedback', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 3,
    slidesPerGroup: 3,
    spaceBetween: 10,
    breakpoints: {
        320: {
        slidesPerView: 1,
        slidesPerGroup: 1,
        navigation: {
            enabled: false,
        },
        },
        768: {
        slidesPerView: 2,
        slidesPerGroup: 2,
        },
        1023: {
        slidesPerView: 3,
        slidesPerGroup: 3,
        }
    }
});
