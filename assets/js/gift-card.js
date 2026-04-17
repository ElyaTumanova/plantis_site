// form
const giftForm = document.querySelector('.gift-cards_form');

if (giftForm) {
  const allInputs = giftForm.querySelectorAll('input');
  const allTextareas = giftForm.querySelectorAll('textarea');

  allInputs.forEach((el) => {
    el.addEventListener('focus', function () {
      el.classList.add('focus');
    });

    el.addEventListener('blur', function () {
      if (el.value !== '') {
        el.classList.add('focus');
      } else {
        el.classList.remove('focus');
      }
    });
  });

  allTextareas.forEach((el) => {
    el.addEventListener('focus', function () {
      el.classList.add('focus');
    });

    el.addEventListener('blur', function () {
      if (el.value !== '') {
        el.classList.add('focus');
      } else {
        el.classList.remove('focus');
      }
    });
  });
}

// FAQ toggle
document.querySelectorAll('.faq-item').forEach((item) => {
  const q = item.querySelector('.faq-question');
  if (!q) return;

  q.addEventListener('click', () => {
    item.classList.toggle('open');
  });
});

// Copy gift code
document.querySelectorAll('[data-copy-target]').forEach((btn) => {
  btn.addEventListener('click', async () => {
    const target = document.querySelector(btn.getAttribute('data-copy-target'));
    if (!target) return;

    const text = target.textContent.trim();

    try {
      await navigator.clipboard.writeText(text);
      const prev = btn.textContent;
      btn.textContent = 'Скопировано ✓';

      setTimeout(() => {
        btn.textContent = prev;
      }, 1400);
    } catch (e) {
      btn.textContent = 'Ошибка копирования';

      setTimeout(() => {
        btn.textContent = 'Скопировать';
      }, 1400);
    }
  });
});

//CG result page - switch view


const gcSwitchButtons = document.querySelectorAll('[data-view-btn]');
const gcPanels = document.querySelectorAll('[data-view-panel]');
const copyButtons = document.querySelectorAll('[data-copy-target]');

gcSwitchButtons.forEach((btn) => {
  btn.addEventListener('click', function () {
    const view = btn.dataset.viewBtn;

    gcSwitchButtons.forEach((b) => b.classList.remove('is-active'));
    gcPanels.forEach((panel) => panel.classList.remove('is-active'));

    btn.classList.add('is-active');

    const activePanel = document.querySelector('[data-view-panel="' + view + '"]');
    if (activePanel) activePanel.classList.add('is-active');
  });
});


// checkout page
const giftcardShow = document.querySelector('.ywgc-show-giftcard');
const giftCardApplyBtn = document.querySelector('.ywgc_apply_gift_card_button');

if (giftcardShow) {
  giftcardShow.addEventListener('click', () => {
    giftcardShow.classList.toggle('open');
  });
}

if (giftCardApplyBtn && giftcardShow) {
  giftCardApplyBtn.addEventListener('click', () => {
    giftcardShow.classList.toggle('open');
  });
}

// gift card ui
const wrap = document.querySelector('.gc-main-slide');
const amountCardWrap = document.querySelector('.gc-amount__card-wrap');
const amountCircleBg = document.querySelector('.gc-amount__circle-bg');
const sideCards = Array.from(document.querySelectorAll('.gc-card--side'));

const gradientTrack = document.querySelector('.gift-gradient-picker__list');
const gradientButtons = Array.from(document.querySelectorAll('.gift-gradient-picker__btn'));

const gradientInput = document.getElementById('giftcard_gradient');
const imageInput = document.getElementById('giftcard_image');

const preview = document.getElementById('giftCardPreview');
const hintText = document.getElementById('giftCardAmountHintText');

const gcPrevMedia = document.getElementById('gcPrevMedia');
const gcNextMedia = document.getElementById('gcNextMedia');
const gcNext2Media = document.getElementById('gcNext2Media');

const gcMainSwiperElement = document.querySelector('.gc-main-swiper');

const hasGiftCardUi =
  wrap &&
  gradientInput &&
  imageInput &&
  typeof plntGiftCardData !== 'undefined';
  

const gradients = hasGiftCardUi ? (plntGiftCardData.gradients || {}) : {};
const images = hasGiftCardUi ? (plntGiftCardData.images || {}) : {};
const backgrounds = hasGiftCardUi ? (plntGiftCardData.backgrounds || {}) : {};
const defaults = hasGiftCardUi ? (plntGiftCardData.defaults || {}) : {};

const defaultGradientKey = defaults.gradient || Object.keys(gradients)[0] || 'sky';
const defaultImageKey = defaults.image || Object.keys(images)[0] || 'none';

const gcImageKeys = Array.from(
  document.querySelectorAll('.gc-main-swiper .swiper-wrapper > .swiper-slide[data-image-key]')
).map((slide) => slide.dataset.imageKey || '');

function getGradientCss(key) {
  return gradients[key] || gradients[defaultGradientKey] || '';
}

function getBackgroundColor(key) {
  return backgrounds[key] || backgrounds[defaultGradientKey] || '';
}

function getImageUrlByKey(key) {
  const value = images[key];
  if (!value || value === 'none') {
    return '';
  }
  return value;
}

function getImageCssByKey(key) {
  const url = getImageUrlByKey(key);
  return url ? `url("${url}")` : 'none';
}

function setActiveButton(buttons, dataKey, value) {
  buttons.forEach((button) => {
    button.classList.toggle('is-active', button.dataset[dataKey] === value);
  });
}

function getActiveGradientFromButton() {
  const activeButton = document.querySelector('.gift-gradient-picker__btn.is-active');
  const gradientSpan = activeButton ? activeButton.querySelector('span') : null;

  if (!gradientSpan) {
    return getGradientCss(gradientInput.value || defaultGradientKey);
  }

  return gradientSpan.style.backgroundImage || getGradientCss(gradientInput.value || defaultGradientKey);
}

function applyGradientStyles(element, gradientCss) {
  if (!element || !gradientCss) return;

  element.style.backgroundImage = gradientCss;
  element.style.backgroundSize = 'cover';
  element.style.backgroundPosition = 'center';
  element.style.backgroundRepeat = 'no-repeat';
}

function applySolidBackground(element, color) {
  if (!element || !color) return;

  element.style.backgroundImage = 'none';
  element.style.backgroundColor = color;
}

function getActiveSlideElement() {
  return document.querySelector('.gc-main-swiper .swiper-slide-active .gc-main-slide');
}

function getActiveSlideImageElement() {
  return document.querySelector('.gc-main-swiper .swiper-slide-active .gc-main-slide__image');
}

function getActiveSlideImageUrl() {
  const activeImg = getActiveSlideImageElement();
  return activeImg ? activeImg.getAttribute('src') || '' : '';
}

function syncImageInputByIndex(index) {
  if (!imageInput) return;

  const imageKey = gcImageKeys[index];
  if (!imageKey) return;

  imageInput.value = imageKey;
}

function renderBackground() {
  if (!hasGiftCardUi) return;

  const gradientKey = gradientInput.value || defaultGradientKey;
  const imageKey = imageInput.value || defaultImageKey;

  const gradientCss = getGradientCss(gradientKey);
  const imageCss = getImageCssByKey(imageKey);

  if (!gradientCss) return;

  if (imageCss !== 'none') {
    wrap.style.backgroundImage = `${imageCss}, ${gradientCss}`;
    wrap.style.backgroundSize = 'contain, cover';
    wrap.style.backgroundPosition = 'center, center';
    wrap.style.backgroundRepeat = 'no-repeat, no-repeat';
  } else {
    wrap.style.backgroundImage = gradientCss;
    wrap.style.backgroundSize = 'cover';
    wrap.style.backgroundPosition = 'center';
    wrap.style.backgroundRepeat = 'no-repeat';
  }

  applyGradientToUi();
}

function applyGradientToUi() {
  if (!hasGiftCardUi) return;

  const gradientCss = getActiveGradientFromButton();
  const gradientKey = gradientInput.value || defaultGradientKey;
  const backgroundColor = getBackgroundColor(gradientKey);

  if (!gradientCss) return;

  const activeSlide = getActiveSlideElement();

  applyGradientStyles(activeSlide, gradientCss);
  applyGradientStyles(amountCardWrap, gradientCss);

  applySolidBackground(amountCircleBg, backgroundColor);

  sideCards.forEach((card) => {
    applySolidBackground(card, backgroundColor);
  });
}

function getPointX(event) {
  if (event.touches && event.touches[0]) {
    return event.touches[0].clientX;
  }

  if (event.changedTouches && event.changedTouches[0]) {
    return event.changedTouches[0].clientX;
  }

  return event.clientX;
}

function mod(n, m) {
  return ((n % m) + m) % m;
}

function createArcController(params) {
  const track = params.track;
  const buttons = params.buttons;
  const dataKey = params.dataKey;
  const input = params.input;
  const defaultValue = params.defaultValue;

  if (!track || !buttons.length || !input) {
    return {
      layout: function () {}
    };
  }

  let activeIndex = buttons.findIndex((btn) => {
    return btn.dataset[dataKey] === (input.value || defaultValue);
  });

  if (activeIndex < 0) {
    activeIndex = 0;
  }

  let dragOffset = 0;
  let isDragging = false;
  let startX = 0;
  let startDragOffset = 0;
  let velocity = 0;
  let lastX = 0;
  let lastTime = 0;
  let snapAnimationFrame = null;

  function getCenteredIndex() {
    return mod(Math.round(activeIndex + dragOffset), buttons.length);
  }

  function stopSnapAnimation() {
    if (snapAnimationFrame) {
      cancelAnimationFrame(snapAnimationFrame);
      snapAnimationFrame = null;
    }
  }

  function updateActive() {
    const centeredIndex = getCenteredIndex();
    const activeButton = buttons[centeredIndex];

    if (!activeButton) return;

    activeIndex = centeredIndex;
    dragOffset = 0;

    input.value = activeButton.dataset[dataKey] || defaultValue;
    setActiveButton(buttons, dataKey, input.value);
    renderBackground();
  }

  function layout() {
    const total = buttons.length;
    const currentCenter = activeIndex + dragOffset;

    const trackWidth = track.offsetWidth;
    const buttonSize = params.buttonSize;
    const sidePadding = params.sidePadding;
    const arcDepth = params.arcDepth;
    const maxVisibleOffset = Math.min(params.maxVisibleOffset, Math.floor((total - 1) / 2));

    const usableHalfWidth = (trackWidth - buttonSize - sidePadding * 2) / 2;
    const slotSpacing = usableHalfWidth / Math.max(maxVisibleOffset, 1);

    buttons.forEach((button, index) => {
      let offset = index - currentCenter;

      if (offset > total / 2) {
        offset -= total;
      }
      if (offset < -total / 2) {
        offset += total;
      }

      const dist = Math.abs(offset);

      if (dist > maxVisibleOffset + 0.75) {
        button.style.opacity = '0';
        button.style.pointerEvents = 'none';
        button.style.zIndex = '0';
        return;
      }

      button.style.pointerEvents = '';

      const x = offset * slotSpacing;
      const normalized = usableHalfWidth ? x / usableHalfWidth : 0;
      const y = -Math.pow(normalized, 2) * arcDepth;

      const scale = Math.max(params.minScale, 1 - dist * params.scaleStep);
      const opacity = Math.max(params.minOpacity, 1 - dist * params.opacityStep);

      const baseYOffset = 38;
      button.style.transform = `translate(${x}px, ${y + baseYOffset}px) scale(${scale})`;
      button.style.opacity = opacity;
      button.style.zIndex = String(200 - Math.round(dist * 10));
      button.classList.toggle('is-active', dist < 0.5);
    });
  }

  function animateSnap() {
    stopSnapAnimation();

    function tick() {
      const target = Math.round(dragOffset);
      const delta = target - dragOffset;

      if (Math.abs(delta) < 0.001) {
        dragOffset = target;
        layout();
        updateActive();
        stopSnapAnimation();
        return;
      }

      dragOffset += delta * 0.16;
      layout();
      snapAnimationFrame = requestAnimationFrame(tick);
    }

    snapAnimationFrame = requestAnimationFrame(tick);
  }

  function onDragStart(event) {
    stopSnapAnimation();
    isDragging = true;
    startX = getPointX(event);
    startDragOffset = dragOffset;
    velocity = 0;
    lastX = startX;
    lastTime = performance.now();
    track.classList.add('is-dragging');
  }

  function onDragMove(event) {
    if (!isDragging) return;

    const x = getPointX(event);
    const now = performance.now();
    const deltaX = x - startX;

    dragOffset = startDragOffset - deltaX / params.dragDivisor;

    const dt = now - lastTime;
    if (dt > 0) {
      velocity = (x - lastX) / dt;
    }

    lastX = x;
    lastTime = now;

    layout();

    if (event.cancelable) {
      event.preventDefault();
    }
  }

  function onDragEnd() {
    if (!isDragging) return;

    isDragging = false;
    track.classList.remove('is-dragging');

    dragOffset -= velocity * params.velocityFactor;
    animateSnap();
  }

  buttons.forEach((button, index) => {
    button.addEventListener('click', function () {
      stopSnapAnimation();
      activeIndex = index;
      dragOffset = 0;
      layout();
      updateActive();
    });
  });

  track.addEventListener('touchstart', onDragStart, { passive: true });
  track.addEventListener('touchmove', onDragMove, { passive: false });
  track.addEventListener('touchend', onDragEnd);
  track.addEventListener('touchcancel', onDragEnd);

  track.addEventListener('mousedown', onDragStart);
  window.addEventListener('mousemove', onDragMove);
  window.addEventListener('mouseup', onDragEnd);

  return {
    layout: layout
  };
}

if (hasGiftCardUi) {
  if (!gradientInput.value || !gradients[gradientInput.value]) {
    gradientInput.value = defaultGradientKey;
  }

  if (!imageInput.value || (imageInput.value !== 'none' && typeof images[imageInput.value] === 'undefined')) {
    imageInput.value = defaultImageKey;
  }
}

const gradientArc = hasGiftCardUi
  ? createArcController({
      track: gradientTrack,
      buttons: gradientButtons,
      dataKey: 'gradientKey',
      input: gradientInput,
      defaultValue: defaultGradientKey,
      buttonSize: 46,
      sidePadding: 8,
      arcDepth: 42,
      maxVisibleOffset: 5,
      minScale: 0.74,
      scaleStep: 0.06,
      minOpacity: 0.32,
      opacityStep: 0.1,
      dragDivisor: 36,
      velocityFactor: 6
    })
  : { layout: function () {} };

if (hasGiftCardUi) {
  setActiveButton(gradientButtons, 'gradientKey', gradientInput.value);
  renderBackground();
  gradientArc.layout();
  syncImageInputByIndex(0);

  window.addEventListener('resize', function () {
    gradientArc.layout();
  });
}

// slider helpers
const gcMainSlides = Array.from(document.querySelectorAll('.gc-main-slide'));
const gcSlidesCount = gcMainSlides.length;

function gcGetLoopIndex(index, total) {
  return ((index % total) + total) % total;
}

function gcGetSlideImageUrlByIndex(index) {
  const slide = gcMainSlides[index];
  if (!slide) return '';

  const img = slide.querySelector('.gc-main-slide__image');
  return img ? img.getAttribute('src') || '' : '';
}

function gcSetCircleBackground(element, imageUrl) {
  if (!element || !imageUrl) return;

  element.classList.remove('is-visible');

  requestAnimationFrame(() => {
    element.style.setProperty('--gc-bg-image', `url("${imageUrl}")`);
    element.classList.add('is-visible');
  });
}

function gcUpdateSideSlides(activeIndex, total) {
  if (!total) return;

  const prevIndex = gcGetLoopIndex(activeIndex - 1, total);
  const nextIndex = gcGetLoopIndex(activeIndex + 1, total);
  const next2Index = gcGetLoopIndex(activeIndex + 2, total);

  const activeImage = gcGetSlideImageUrlByIndex(activeIndex);
  const prevImage = gcGetSlideImageUrlByIndex(prevIndex);
  const nextImage = gcGetSlideImageUrlByIndex(nextIndex);
  const next2Image = gcGetSlideImageUrlByIndex(next2Index);

  if (preview && activeImage) {
    preview.src = activeImage;
  }

  gcSetCircleBackground(gcPrevMedia, prevImage);
  gcSetCircleBackground(gcNextMedia, nextImage);
  gcSetCircleBackground(gcNext2Media, next2Image);

  syncImageInputByIndex(activeIndex);
  applyGradientToUi();
}

// steps
const gcStepperItems = Array.from(document.querySelectorAll('.gc-sidebar__item'));
const gcStepperButtons = Array.from(document.querySelectorAll('.gc-sidebar__link[data-gc-step]'));
const gcStepPanels = Array.from(document.querySelectorAll('[data-gc-step-panel]'));
const gcPrevStepButtons = Array.from(document.querySelectorAll('[data-gc-prev-step]'));
const gcNextStepButtons = Array.from(document.querySelectorAll('[data-gc-next-step]'));

const gcMinStep = 1;
const gcMaxStep = gcStepPanels.length;

let gcCurrentStep = 1;

function gcSetActiveStep(step) {
  const nextStep = Math.min(Math.max(step, gcMinStep), gcMaxStep);
  gcCurrentStep = nextStep;

  gcStepperItems.forEach((item) => {
    const button = item.querySelector('.gc-sidebar__link[data-gc-step]');
    const itemStep = Number(button?.dataset.gcStep);
    item.classList.toggle('is-active', itemStep === nextStep);
  });

  gcStepPanels.forEach((panel) => {
    const panelStep = Number(panel.dataset.gcStepPanel);
    const isActive = panelStep === nextStep;

    panel.hidden = !isActive;
    panel.classList.toggle('is-active', isActive);
  });

  gcStepPanels.forEach((panel) => {
    const panelStep = Number(panel.dataset.gcStepPanel);
    if (panelStep !== gcCurrentStep) return;

    const prevButton = panel.querySelector('[data-gc-prev-step]');
    const nextButton = panel.querySelector('[data-gc-next-step]');

    if (prevButton) {
      prevButton.disabled = gcCurrentStep === gcMinStep;
    }

    if (nextButton) {
      nextButton.disabled = gcCurrentStep === gcMaxStep;
    }
  });
}

gcStepperButtons.forEach((button) => {
  button.addEventListener('click', () => {
    gcSetActiveStep(Number(button.dataset.gcStep));
  });
});

gcPrevStepButtons.forEach((button) => {
  button.addEventListener('click', () => {
    gcSetActiveStep(gcCurrentStep - 1);
  });
});

gcNextStepButtons.forEach((button) => {
  button.addEventListener('click', () => {
    gcSetActiveStep(gcCurrentStep + 1);
  });
});

// swiper
let gcSwiper = null;

if (gcMainSwiperElement && gcSlidesCount) {
  gcSwiper = new Swiper('.gc-main-swiper', {
    effect: 'fade',
    fadeEffect: {
      crossFade: true
    },
    speed: 450,
    loop: true,
    allowTouchMove: true,
    navigation: {
      prevEl: '.gc-slider__nav--prev',
      nextEl: '.gc-slider__nav--next'
    },
    pagination: {
      el: '#gcSliderDots',
      clickable: true
    },
    on: {
      init: function () {
        gcUpdateSideSlides(this.realIndex, gcSlidesCount);
      },
      slideChangeTransitionStart: function () {
        gcUpdateSideSlides(this.realIndex, gcSlidesCount);
      },
      slideChangeTransitionEnd: function () {
        gcUpdateSideSlides(this.realIndex, gcSlidesCount);
      }
    }
  });
}

gcSetActiveStep(1);

// amount wheel
const amounts = [
  1500, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000,
  10000, 12000, 15000, 18000, 20000, 25000, 30000
];

const wheel = document.getElementById('amountWheel');
const input = document.getElementById('giftCardAmountInput');
const amountHiddenInput = document.getElementById("giftcard_amount");

if (wheel && input) {
  let currentIndex = 0;
  let currentValue = amounts[0];
  let currentFloatIndex = 0;
  let resizeObserver = null;

  let isDragging = false;
  let dragStartY = 0;
  let dragStartFloatIndex = 0;
  let dragMoved = false;
  let animationFrame = null;

  function formatAmount(value) {
    return new Intl.NumberFormat('ru-RU').format(value);
  }

  function parseAmount(value) {
    return Number(String(value).replace(/[^\d]/g, '')) || 0;
  }

  function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
  }

  function findNearestAmount(value) {
    let nearest = amounts[0];
    let nearestIndex = 0;
    let minDiff = Math.abs(value - nearest);

    amounts.forEach((amount, index) => {
      const diff = Math.abs(value - amount);
      if (diff < minDiff) {
        minDiff = diff;
        nearest = amount;
        nearestIndex = index;
      }
    });

    return { value: nearest, index: nearestIndex };
  }

  function getHintTextByAmount(value) {
    if (value < 5000) return 'небольшое растение в кашпо';
    if (value < 10000) return 'стильную композицию из растений';
    if (value < 15000) return 'крупное интерьерное растение';
    if (value < 20000) return 'набор растений для дома';
    if (value < 25000) return 'выразительное растение в кашпо';
    if (value < 30000) return 'премиальную зелёную композицию';
    return 'масштабное озеленение пространства';
  }

  function updateHint(value) {
    if (!hintText) return;
    hintText.textContent = getHintTextByAmount(value);
  }

  function updateInput(value) {
    input.value = formatAmount(value);
    
    if (amountHiddenInput) {
      amountHiddenInput.value = value || "";
    }
  }


  function clearActiveWheelItems() {
    wheel.querySelectorAll('.gc-amount__wheel-item').forEach((item) => {
      item.classList.remove('is-active');
      item.setAttribute('aria-pressed', 'false');
    });
  }

  function updateActiveWheelItem(index) {
    const activeIndex = clamp(Math.round(index), 0, amounts.length - 1);

    wheel.querySelectorAll('.gc-amount__wheel-item').forEach((item, i) => {
      const isActive = i === activeIndex;
      item.classList.toggle('is-active', isActive);
      item.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    });
  }

  function stopWheelAnimation() {
    if (animationFrame) {
      cancelAnimationFrame(animationFrame);
      animationFrame = null;
    }
  }

  function setSelectedIndex(index, syncInput = true) {
    currentIndex = clamp(Math.round(index), 0, amounts.length - 1);
    currentValue = amounts[currentIndex];

    updateActiveWheelItem(currentIndex);
    updateHint(currentValue);

    if (syncInput) {
      updateInput(currentValue);
    }
  }

  function setSelectedFromFloat(syncInput = true) {
    setSelectedIndex(currentFloatIndex, syncInput);
  }

  function renderWheel() {
    if (!wheel.clientWidth || !wheel.clientHeight) return;

    wheel.innerHTML = '';

    const centerX = wheel.clientWidth * 0.5;
    const centerY = wheel.clientHeight * 0.44;
    const radius = Math.min(wheel.clientWidth, wheel.clientHeight) * 0.39;

    const activeAngleDeg = 8;
    const startDeg = -18;
    const endDeg = 122;
    const stepDeg = (endDeg - startDeg) / (amounts.length - 1);

    amounts.forEach((amount, index) => {
      const relativeIndex = index - currentFloatIndex;
      const angleDeg = activeAngleDeg + relativeIndex * stepDeg;
      const angleRad = (angleDeg * Math.PI) / 180;

      const x = centerX + Math.cos(angleRad) * radius;
      const y = centerY + Math.sin(angleRad) * radius;

      const distanceFromActive = Math.abs(relativeIndex);
      const scale = Math.max(0.86, 1 - distanceFromActive * 0.02);

      let opacity = 1;
      const fadeStartDeg = -8;
      const fadeEndDeg = -28;

      if (angleDeg < fadeStartDeg) {
        const fadeProgress = (fadeStartDeg - angleDeg) / (fadeStartDeg - fadeEndDeg);
        opacity = Math.max(0, 1 - fadeProgress);
      }

      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'gc-amount__wheel-item';
      item.dataset.index = index;
      item.dataset.amount = amount;
      item.style.left = `${x}px`;
      item.style.top = `${y}px`;
      item.style.opacity = String(opacity);
      item.style.zIndex = String(200 - Math.round(distanceFromActive * 10));
      item.style.transform = `translate(-50%, -50%) rotate(${angleDeg}deg) scale(${scale})`;
      item.innerHTML = `<span>${formatAmount(amount)}</span>`;

      if (angleDeg <= fadeEndDeg) {
        item.style.opacity = '0';
        item.style.pointerEvents = 'none';
        item.style.visibility = 'hidden';
      } else {
        item.style.pointerEvents = 'auto';
        item.style.visibility = 'visible';
      }

      item.addEventListener('click', () => {
        if (dragMoved) return;
        animateToIndex(index, true);
      });

      wheel.appendChild(item);
    });

    updateActiveWheelItem(currentIndex);
  }

  function animateToIndex(targetIndex, syncInput = true) {
    const clampedTarget = clamp(targetIndex, 0, amounts.length - 1);

    stopWheelAnimation();

    function tick() {
      const delta = clampedTarget - currentFloatIndex;

      if (Math.abs(delta) < 0.001) {
        currentFloatIndex = clampedTarget;
        setSelectedIndex(clampedTarget, syncInput);
        renderWheel();
        animationFrame = null;
        return;
      }

      currentFloatIndex += delta * 0.18;
      setSelectedFromFloat(syncInput);
      renderWheel();
      animationFrame = requestAnimationFrame(tick);
    }

    animationFrame = requestAnimationFrame(tick);
  }

  function onWheelScroll(event) {
    event.preventDefault();

    const direction = event.deltaY > 0 ? 1 : -1;
    const targetIndex = clamp(currentIndex + direction, 0, amounts.length - 1);

    animateToIndex(targetIndex, true);
  }

  function onPointerDown(event) {
    if (event.button !== 0) return;

    stopWheelAnimation();

    isDragging = true;
    dragMoved = false;
    dragStartY = event.clientY;
    dragStartFloatIndex = currentFloatIndex;

    wheel.classList.add('is-dragging');
    event.preventDefault();
  }

  function onPointerMove(event) {
    if (!isDragging) return;

    const deltaY = event.clientY - dragStartY;

    if (Math.abs(deltaY) > 3) {
      dragMoved = true;
    }

    const dragStepPx = 42;
    currentFloatIndex = clamp(
      dragStartFloatIndex - deltaY / dragStepPx,
      0,
      amounts.length - 1
    );

    setSelectedFromFloat(true);
    renderWheel();
    event.preventDefault();
  }

  function onPointerUp() {
    if (!isDragging) return;

    isDragging = false;
    wheel.classList.remove('is-dragging');

    const snappedIndex = Math.round(currentFloatIndex);
    animateToIndex(snappedIndex, true);

    setTimeout(() => {
      dragMoved = false;
    }, 0);
  }

  function onInput(event) {
    const digits = String(event.target.value).replace(/[^\d]/g, '');

    if (!digits) {
      currentValue = 0;
      event.target.value = '';
      clearActiveWheelItems();
      updateHint(amounts[0]);

      if (amountHiddenInput) {
        amountHiddenInput.value = '';
      }

      return;
    }

    const rawValue = Number(digits);
    currentValue = clamp(rawValue, amounts[0], amounts[amounts.length - 1]);

    const nearest = findNearestAmount(currentValue);
    currentIndex = nearest.index;
    currentFloatIndex = nearest.index;

    event.target.value = digits;

    if (amountHiddenInput) {
      amountHiddenInput.value = currentValue;
    }

    updateActiveWheelItem(currentIndex);
    updateHint(currentValue);
    renderWheel();
  }

  function onInputBlur() {
    const raw = parseAmount(input.value);

    if (!raw) {
      input.value = '';
      currentValue = 0;
      clearActiveWheelItems();
      updateHint(amounts[0]);
      return;
    }

    currentValue = clamp(raw, amounts[0], amounts[amounts.length - 1]);

    const nearest = findNearestAmount(currentValue);
    currentIndex = nearest.index;
    currentFloatIndex = nearest.index;

    updateInput(currentValue);
    updateActiveWheelItem(currentIndex);
    updateHint(currentValue);
    renderWheel();
  }

  function initResizeObserver() {
    if (resizeObserver) {
      resizeObserver.disconnect();
    }

    resizeObserver = new ResizeObserver(() => {
      if (wheel.clientWidth && wheel.clientHeight) {
        renderWheel();
      }
    });

    resizeObserver.observe(wheel);
  }

  wheel.addEventListener('wheel', onWheelScroll, { passive: false });
  wheel.addEventListener('mousedown', onPointerDown);
  window.addEventListener('mousemove', onPointerMove);
  window.addEventListener('mouseup', onPointerUp);

  input.addEventListener('input', onInput);
  input.addEventListener('blur', onInputBlur);
  window.addEventListener('resize', renderWheel);

  initResizeObserver();

  currentValue = amounts[currentIndex];
  currentFloatIndex = currentIndex;
  renderWheel();
  updateInput(currentValue);
  updateHint(currentValue);
}
