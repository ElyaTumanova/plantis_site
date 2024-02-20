const swiper_about_feedback = new Swiper('.about__swiper-feedback', {
    pagination: {
        el: '.swiper-pagination',
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 3,
    spaceBetween: 10,
    breakpoints: {
        320: {
        slidesPerView: 1,
        },
        768: {
        slidesPerView: 2,
        },
        1023: {
        slidesPerView: 3,
        }
    }
});

const swiper_about_feedback = new Swiper('.about__swiper-photo', {
    pagination: {
        el: '.swiper-pagination',
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 1,
    spaceBetween: 10,
    breakpoints: {
        320: {
        slidesPerView: 1,
        },
        768: {
        slidesPerView: 2,
        },
        1023: {
        slidesPerView: 1,
        }
    }
});