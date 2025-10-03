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

function swiper_catalog_card_imgs_init () {
    //console.log('hi swiper_catalog_card_imgs_init');
    swiper_catalog_card_imgs = new Swiper('.product__image-slider-wrap', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
                    enabled: true,
                },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        // autoplay: {
        // 	delay: 500,
        // 	disableOnInteraction: false,
        // },
        grabCursor: true,
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 0,
        loop: true,
        freeMode: false,
        // effect: "fade",
        crossFade: true,
        observer: true,
        observeParents: true,
        observeSlideChildren: true,
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

    // swiper_catalog_card_imgs.forEach((element) => {
    // 	element.autoplay.stop();
      // });

    // const sliders = document.querySelectorAll(".product__image-slider-wrap");
    // sliders.forEach((slider) => {
    // 	slider.addEventListener("mouseenter", function () {
    // 		slider.swiper.autoplay.start();
    // 	});
    // 	slider.addEventListener("mouseleave", function () {
    // 		slider.swiper.autoplay.stop();
    // 		slider.swiper.slideTo(0, 0, false);
    // 	});
    // });
}

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
    let swiper_product_slider;
    function swiper_product_slider_init() {
    swiper_product_slider = new Swiper('.product-slider-swiper', {
        // on: {
        //     init: function () {
        //       console.log('swiper initialized');
        //     },
        //     slidesUpdated: function () {
        //       console.log('slidesUpdated');
        //     },
        //     update: function () {
        //       console.log('swiperUpdated');
        //     },
        // },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'progressbar'
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        // scrollbar: {
        //     el: '.swiper-scrollbar',
        //     draggable: true,
        // },
        slidesPerView: 5,
        slidesPerGroup: 1,
        spaceBetween: 30,
        loop: true,
        freeMode: true,
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 20,
                navigation: {
                    enabled: false,
                },
                freeMode: true,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 20,
                navigation: {
                    enabled: true,
                },
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 30,
                navigation: {
                    enabled: true,
                },
            }
        }
    });
    }

/*--------------------------------------------------------------
# Popular slider
--------------------------------------------------------------*/
function swiper_popular_slider_init() {
    swiper_popular_slider = new Swiper('.popular-slider-swiper', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'progressbar'
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        // scrollbar: {
        //     el: '.swiper-scrollbar',
        //     draggable: true,
        // },
        slidesPerView: 5,
        slidesPerGroup: 1,
        spaceBetween: 30,
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
                slidesPerView: 5,
                spaceBetween: 30,
                navigation: {
                    enabled: true,
                },
            }
        }
    });
}

swiper_popular_slider_init();

/*--------------------------------------------------------------
# About Us
--------------------------------------------------------------*/

// const swiper_about_photo = new Swiper('.about__swiper-photo', {
//     pagination: {
//         el: '.swiper-pagination',
//         clickable: true,
//     },
//     navigation: {
//         nextEl: '.swiper-button-next',
//         prevEl: '.swiper-button-prev',
//     },
//     slidesPerView: 1,
//     slidesPerGroup: 1,
//     spaceBetween: 10,
//     loop: true,
//     breakpoints: {
//         320: {
//         slidesPerView: 1,
//         slidesPerGroup: 1,
//         navigation: {
//             enabled: false,
//         },
//         },
//         768: {
//         slidesPerView: 2,
//         slidesPerGroup: 2,
//         },
//         1023: {
//         slidesPerView: 1,
//         slidesPerGroup: 1,
//         }
//     }
// });

// const swiper_about_feedback = new Swiper('.about__swiper-feedback', {
//     pagination: {
//         el: '.swiper-pagination',
//         clickable: true,
//     },
//     navigation: {
//         nextEl: '.swiper-button-next',
//         prevEl: '.swiper-button-prev',
//     },
//     slidesPerView: 3,
//     slidesPerGroup: 3,
//     spaceBetween: 10,
//     loop: true,
//     breakpoints: {
//         320: {
//         slidesPerView: 1,
//         slidesPerGroup: 1,
//         navigation: {
//             enabled: false,
//         },
//         },
//         768: {
//         slidesPerView: 2,
//         slidesPerGroup: 2,
//         },
//         1023: {
//         slidesPerView: 3,
//         slidesPerGroup: 3,
//         }
//     }
// });
/*--------------------------------------------------------------
# Filter Metki
--------------------------------------------------------------*/
// функция используется в плагнах Load More и BeRocket filters, чтобы повторно инициироваться при аякс обновлении каталога при приминении фильтров

function swiper_filter_metki_init() {
    swiper_filter_metki = new Swiper('.metki_swiper_wrap', {
        // navigation: {
        //     nextEl: '.myslider-next',
        //     prevEl: '.myslider-prev',
        // },
        scrollbar: {
            el: ".swiper-scrollbar",
            hide: false,
            draggable: true,
        },
        slidesPerView: 'auto',
        spaceBetween: 5,
        loop: false,
        breakpoints: {
            320: {
            navigation: {
                enabled: false,
            },
            scrollbar: {
                enabled: false,
            },
            loop: true,
            },
            767: {
            navigation: {
                enabled: true,
            },
            loop: false,
            }
        }
    });
}
swiper_filter_metki_init();
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

/*--------------------------------------------------------------
# Cart
--------------------------------------------------------------*/
function swiper_backorder_crossells_init(){
    swiper_backorder_crossells = new Swiper('.backorder-crossells-swiper', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'progressbar'
        },
        scrollbar: {
            el: ".swiper-scrollbar",
            hide: false,
            draggable: true,
        },
        // navigation: {
        //     nextEl: '.swiper-button-next',
        //     prevEl: '.swiper-button-prev',
        // },
        slidesPerView: 4,
        slidesPerGroup: 1,
        spaceBetween: 15,
        loop: false,
        freeMode: false,
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 10,
                pagination: {
                    enabled: true,
                },
                scrollbar: {
                    enabled: false,
                },
            },
            520: {
                slidesPerView: 3,
                pagination: {
                    enabled: true,
                },
                scrollbar: {
                    enabled: false,
                },
            },
            768: {
                slidesPerView: 4,
                pagination: {
                    enabled: false,
                },
                scrollbar: {
                    enabled: true,
                },
            },
            1024: {
                slidesPerView: 4,
                pagination: {
                    enabled: false,
                },
                scrollbar: {
                    enabled: true,
                },
            }
        }
    });
}

swiper_backorder_crossells_init();

function swiper_cart_upsells_init(){
    swiper_cart_upsells = new Swiper('.cart_upsells-swiper', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'progressbar'
        },
        scrollbar: {
            el: ".swiper-scrollbar",
            hide: false,
            draggable: true,
        },
        // navigation: {
        //     nextEl: '.swiper-button-next',
        //     prevEl: '.swiper-button-prev',
        // },
        slidesPerView: 4,
        slidesPerGroup: 1,
        spaceBetween: 15,
        loop: false,
        freeMode: false,
        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 10,
                pagination: {
                    enabled: true,
                },
                scrollbar: {
                    enabled: false,
                },
            },
            520: {
                slidesPerView: 3,
                pagination: {
                    enabled: true,
                },
                scrollbar: {
                    enabled: false,
                },
            },
            768: {
                slidesPerView: 4,
                pagination: {
                    enabled: false,
                },
                scrollbar: {
                    enabled: true,
                },
            },
            1024: {
                slidesPerView: 4,
                pagination: {
                    enabled: false,
                },
                scrollbar: {
                    enabled: true,
                },
            }
        }
    });
}

swiper_cart_upsells_init();

/*--------------------------------------------------------------
# Gift card
--------------------------------------------------------------*/
const swiper_gift = new Swiper('.gift-swiper-wrap', {
    slidesPerView: 'auto',
    spaceBetween: 5,
    loop: false,
    breakpoints: {
        320: {
        navigation: {
            enabled: false,
        },
        loop: false,
        },
        767: {
        navigation: {
            enabled: false,
        },
        loop: false,
        }
    }
});