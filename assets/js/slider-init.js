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
    loop: true,
    breakpoints: {
        320: {
            navigation: {
                enabled: false,
            },
        },
        767: {
            navigation: {
                enabled: true,
            },
        }
    }
});

const swiper_main_sale = new Swiper('.main__sale-swiper', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        type: 'progressbar'
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 1,
    slidesPerGroup: 1,
    spaceBetween: 0,
    loop: true,
    freeMode: true,
    observer: true,
    observeParents: true,
    observeSlideChildren: true,
    breakpoints: {
        320: {
            slidesPerView: 2,
            spaceBetween: 10,
            navigation: {
                enabled: false,
            },
        },
        767: {
            navigation: {
                enabled: true,
            },
            slidesPerView: 2,
        },
        1125: {
            navigation: {
                enabled: true,
            },
            slidesPerView: 3,
        },
        1279: {
            navigation: {
                enabled: true,
            },
            slidesPerView: 1,
        }
    }
});

/*--------------------------------------------------------------
# Catalog
--------------------------------------------------------------*/

// слайдер инициирован в wc-catalog-functions, чтобы повторно инициироваться при аякс обновлении каталога при приминении фильтров

/*--------------------------------------------------------------
# Card
--------------------------------------------------------------*/
const swiper_card_cross_upsells = new Swiper('.cross-upsells-swiper', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        type: 'progressbar'
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 3,
    slidesPerGroup: 1,
    spaceBetween: 15,
    loop: true,
    freeMode: true,
    breakpoints: {
        320: {
            slidesPerView: 2,
            spaceBetween: 10,
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

const swiper_card_ukhod = new Swiper('.card-ukhod-swiper', {
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        type: 'progressbar'
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    slidesPerView: 6,
    slidesPerGroup: 1,
    spaceBetween: 15,
    loop: true,
    freeMode: true,
    breakpoints: {
        320: {
            slidesPerView: 2,
            spaceBetween: 10,
            navigation: {
                enabled: false,
            },
            freeMode: true,
        },
        768: {
            slidesPerView: 4,
            spaceBetween: 10,
            navigation: {
                enabled: true,
            },
        },
        1024: {
            slidesPerView: 6,
            spaceBetween: 15,
            navigation: {
                enabled: true,
            },
        }
    }
});

/*--------------------------------------------------------------
# Products slider
--------------------------------------------------------------*/
// function swiper_product_slider_init() {
//     console.log('hello');
//     const swiper_product_slider = new Swiper('.product-slider-swiper', {
//         pagination: {
//             el: '.swiper-pagination',
//             clickable: true,
//             type: 'progressbar'
//         },
//         navigation: {
//             nextEl: '.swiper-button-next',
//             prevEl: '.swiper-button-prev',
//         },
//         // scrollbar: {
//         //     el: '.swiper-scrollbar',
//         //     draggable: true,
//         // },
//         slidesPerView: 5,
//         slidesPerGroup: 1,
//         spaceBetween: 30,
//         loop: true,
//         freeMode: true,
//         breakpoints: {
//             320: {
//                 slidesPerView: 2,
//                 spaceBetween: 10,
//                 navigation: {
//                     enabled: false,
//                 },
//                 freeMode: true,
//             },
//             768: {
//                 slidesPerView: 4,
//                 spaceBetween: 10,
//                 navigation: {
//                     enabled: true,
//                 },
//             },
//             1024: {
//                 slidesPerView: 5,
//                 spaceBetween: 30,
//                 navigation: {
//                     enabled: true,
//                 },
//             }
//         }
//     });
// }

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
    loop: true,
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
    loop: true,
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
/*--------------------------------------------------------------
# Filter Metki
--------------------------------------------------------------*/
// слайдер инициирован в wc-catalog-functions, чтобы повторно инициироваться при аякс обновлении каталога при приминении фильтров


/*--------------------------------------------------------------
# Checkout
--------------------------------------------------------------*/
let deliveryWrapper = document.querySelector('#delivery_dates_field .woocommerce-input-wrapper');
if(deliveryWrapper){deliveryWrapper.classList.add('swiper-wrapper');}

let deliverySwiper = document.querySelector('.delivery_dates');

if (deliverySwiper) {
    let deliverySwiperPrev = document.createElement('div');
    deliverySwiperPrev.classList.add('swiper-button-prev');
    deliverySwiper.appendChild(deliverySwiperPrev);
    
    let deliverySwiperNext = document.createElement('div');
    deliverySwiperNext.classList.add('swiper-button-next');
    deliverySwiper.appendChild(deliverySwiperNext);
}

const swiper_delivery_dates = new Swiper('#delivery_dates_field', {

    slidesPerView: 'auto',
    slidesPerGroup: 1,
    spaceBetween: 0,
    
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

});