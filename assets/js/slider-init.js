/*--------------------------------------------------------------
# Front Page
--------------------------------------------------------------*/
const swiper_main_bunners = new Swiper('.main__banners-swiper', {
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
