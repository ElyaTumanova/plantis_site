document.addEventListener('DOMContentLoaded', () => {
  const gallery = document.querySelector('[data-product-lightgallery]');

  if (!gallery) return;

  const isMobile = window.innerWidth <= 767;

  lightGallery(gallery, {
    selector: '[data-product-lightgallery-item]',
    plugins: [lgThumbnail, lgZoom],
    speed: isMobile ? 200 : 300,
    download: false,
    counter: false,
    thumbnail: true,
    animateThumb: !isMobile,
    showThumbByDefault: true,
    thumbWidth: isMobile ? 72 : 100,
    thumbHeight: isMobile ? '72px' : '100px',
    thumbMargin: 10,
    zoomFromOrigin: false,
    actualSize: false,
    infiniteZoom: false,
    mousewheel: true
  });
});