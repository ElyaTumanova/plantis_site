/* Front Page*/
  const swiper_main_banners = new Swiper('.hero-banner-swiper', {
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
          315: {
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

  /* Cats tiles */
  const swiper_cats_tiles = new Swiper('.cats-tiles .swiper', {
    slidesPerView: 2.3,
    spaceBetween: 8,

    navigation: {
      prevEl: '.cats-tiles .swiper-button-prev',
      nextEl: '.cats-tiles .swiper-button-next',
    },

    scrollbar: {
      el: '.cats-tiles .swiper-scrollbar',
      draggable: true,
    },

    breakpoints: {
      768: {
        slidesPerView: 3.5,
        spaceBetween: 12,
      },
      1024: {
        slidesPerView: 6,
        spaceBetween: 12,
      },
    },
  })

  const swiper_cats_assort = new Swiper('.front__assort .swiper', {
    slidesPerView: 1.6,
    spaceBetween: 8,

    navigation: {
      prevEl: '.front__assort .swiper-button-prev',
      nextEl: '.front__assort .swiper-button-next',
    },

    scrollbar: {
      el: '.front__assort .swiper-scrollbar',
      draggable: true,
    },

    breakpoints: {
      768: {
        slidesPerView: 2.5,
        spaceBetween: 12,
      },
      900: {
        slidesPerView: 3,
        spaceBetween: 12,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 12,
      },
    },
  })


/* Catalog */
// слайдер инициирован в wc-catalog-functions, чтобы повторно инициироваться при аякс обновлении каталога при приминении фильтров

  function swiper_catalog_card_imgs_init() {
    console.log('hi swiper_catalog_card_imgs_init')
    document.querySelectorAll('.product__image-slider-wrap').forEach((wrap) => {
      if (wrap.classList.contains('swiper-initialized')) {
        wrap.swiper?.update()
        wrap.swiper?.pagination?.render()
        wrap.swiper?.pagination?.update()
        return
      }

      const slidesCount = wrap.querySelectorAll('.swiper-slide').length

      const paginationEl = wrap.querySelector('.swiper-pagination')
      const nextEl = wrap.querySelector('.swiper-button-next')
      const prevEl = wrap.querySelector('.swiper-button-prev')

      if (slidesCount <= 1) {
        wrap.classList.add('is-single-slide')

        if (paginationEl) paginationEl.style.display = 'none'
        if (nextEl) nextEl.style.display = 'none'
        if (prevEl) prevEl.style.display = 'none'

        return
      }

      wrap.classList.remove('is-single-slide')

      if (paginationEl) paginationEl.style.display = ''
      if (nextEl) nextEl.style.display = ''
      if (prevEl) prevEl.style.display = ''

      const sw = new Swiper(wrap, {
        pagination: paginationEl
          ? {
              el: paginationEl,
              clickable: true,
            }
          : undefined,

        grabCursor: true,
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 0,

        loop: true,

        observer: true,
        observeParents: true,
        observeSlideChildren: true,

        breakpoints: {
          315: {
            navigation: {
              enabled: false,
            },
          },
          768: {
            navigation: {
              enabled: true,
            },
          },
        },
      })

      sw.pagination?.render()
      sw.pagination?.update()
    })
  }

  // swiper_catalog_card_imgs_init()

/* Card */
  document.querySelectorAll('[data-js-product-gallery]').forEach((gallery) => {
    const thumbsEl = gallery.querySelector('.product-gallery__thumbs')
    const mainEl = gallery.querySelector('.product-gallery__main')

    if (!thumbsEl || !mainEl) {
      return
    }

    const thumbsSwiper = new Swiper(thumbsEl, {
      slidesPerView: 'auto',
      spaceBetween: 4,
      freeMode: true,
      watchSlidesProgress: true,
      slideToClickedSlide: true,
      slidesOffsetAfter: 4,

      direction: 'horizontal',

      breakpoints: {
        1024: {
          direction: 'vertical',
          slidesPerView: 5,
          spaceBetween: 8,
        },
      },
    });

    new Swiper(mainEl, {
      slidesPerView: 1,
      spaceBetween: 0,
      thumbs: {
        swiper: thumbsSwiper,
      },
    })
  })    

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
          315: {
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
          315: {
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
/* Products slider */

class ProductSlider {
    constructor(root) {
        if (!root) {
            return;
        }

        this.root = root;
        this.element = root.querySelector('.product-slider-swiper');
        this.swiper = null;

        if (!this.element) {
            return;
        }

        this.init();
    }

    init() {
        this.prepareSlides();

        this.swiper = new Swiper(this.element, this.getOptions());
    }

    prepareSlides() {
        const slides = this.element.querySelectorAll('.product');

        slides.forEach((slide) => {
            slide.classList.add('swiper-slide');
        });
    }

    getOptions() {
        const defaults = {
            mobile: 2,
            tablet: 3,
            desktop: 4,
            spaceBetween_mobile: 8,
            spaceBetween_tablet: 12,
            spaceBetween_desktop: 12,
        };

        const slidesPerView = {
            mobile: Number(this.root.dataset.slidesMobile) || defaults.mobile,
            tablet: Number(this.root.dataset.slidesTablet) || defaults.tablet,
            desktop: Number(this.root.dataset.slidesDesktop) || defaults.desktop,
        };
        const spaceBetween = {
            mobile: Number(this.root.dataset.spaceMobile) || defaults.spaceBetween_mobile,
            tablet: Number(this.root.dataset.spaceTablet) || defaults.spaceBetween_tablet,
            desktop: Number(this.root.dataset.spaceDesktop) || defaults.spaceBetween_desktop,
        };

        return {
            navigation: {
                nextEl: this.root.querySelector('.swiper-button-next'),
                prevEl: this.root.querySelector('.swiper-button-prev'),
            },

            scrollbar: {
                el: this.element.querySelector('.swiper-scrollbar'),
                draggable: true,
            },

            slidesPerView: slidesPerView.desktop,
            slidesPerGroup: 1,
            spaceBetween: spaceBetween.desktop,
            loop: true,
            freeMode: true,

            breakpoints: {
                315: {
                    slidesPerView: slidesPerView.mobile,
                    spaceBetween: spaceBetween.mobile,
                    navigation: {
                        enabled: false,
                    },
                    freeMode: true,
                },

                768: {
                    slidesPerView: slidesPerView.tablet,
                    spaceBetween: spaceBetween.tablet,
                    navigation: {
                        enabled: true,
                    },
                },

                1024: {
                    slidesPerView: slidesPerView.desktop,
                    spaceBetween: spaceBetween.desktop,
                    navigation: {
                        enabled: true,
                    },
                },
            },
        };
    }

    destroy() {
        if (!this.swiper) {
            return;
        }

        this.swiper.destroy(true, true);
        this.swiper = null;
    }
}

class ProductSliderCollection {
    constructor(selector = '[data-js-product-slider]') {
        this.selector = selector;
        this.items = new Map();

        this.init();
    }

    init() {
        const elements = document.querySelectorAll(this.selector);

        elements.forEach((element) => {
            this.add(element);
        });
    }

    add(element) {
        if (!element || this.items.has(element)) {
            return;
        }

        const slider = new ProductSlider(element);

        this.items.set(element, slider);
    }

    remove(element) {
        const slider = this.items.get(element);

        if (!slider) {
            return;
        }

        slider.destroy();
        this.items.delete(element);
    }

    destroyAll() {
        this.items.forEach((slider) => {
            slider.destroy();
        });

        this.items.clear();
    }
}

const productSliders = new ProductSliderCollection();
/* Popular slider */

  function swiper_popular_slider_init() {
      // console.log('hi swiper_popular_slider_init')
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
              315: {
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
//         315: {
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
//         315: {
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
            el: ".metki_swiper_wrap .swiper-scrollbar",
            hide: false,
            draggable: true,
        },
        slidesPerView: 'auto',
        spaceBetween: 4,
        loop: false,
        breakpoints: {
            315: {
            navigation: {
                enabled: false,
            },
            scrollbar: {
                enabled: false,
            },
            loop: true,
            },
            768: {
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
const deliverySliderWrap = document.querySelector('.delivery_slider-wrap');
const deliveryField = document.querySelector('#delivery_dates_field');

if (deliverySliderWrap && deliveryField) {
    const deliveryWrapper = deliveryField.querySelector('.woocommerce-input-wrapper');

    if (deliveryWrapper) {
        deliveryWrapper.classList.add('swiper-wrapper');
    }

    deliveryField
        .querySelectorAll('.woocommerce-input-wrapper > label.radio')
        .forEach((label) => {
            label.classList.add('swiper-slide');
        });

    new Swiper(deliveryField, {
        slidesPerView: 'auto',
        slidesPerGroup: 1,
        spaceBetween: 0,

        navigation: {
            prevEl: deliverySliderWrap.querySelector('.swiper-button-prev'),
            nextEl: deliverySliderWrap.querySelector('.swiper-button-next'),
        },
    });
}

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
            315: {
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
            315: {
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
        315: {
        navigation: {
            enabled: false,
        },
        loop: false,
        },
        768: {
        navigation: {
            enabled: false,
        },
        loop: false,
        }
    }
});


