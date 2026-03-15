let giftAmounts = document.querySelectorAll('.gift__amounts p')
let amountInput = document.querySelector('#gift-manual-amount')
let imageAmount = document.querySelector('.gift-image-amount')
let submitBtn = document.querySelector('.gift_card_add_to_cart_button')
let giftForm = document.querySelector('.gift-cards_form')
let giftAmountPost = document.querySelector('#giftcard_amount')
console.log(giftAmountPost)


if (giftForm) {
  let allInputs = giftForm.querySelectorAll('input')
  let allTextareas = giftForm.querySelectorAll('textarea')

  // let minAmount = 10
  // let maxAmount = 30000
  // amountInput.value = minAmount
  // amountInput.setAttribute('value',minAmount)
  // imageAmount.innerHTML = `${minAmount}<span>₽</span>`
  // giftAmountPost.value = minAmount

  //for dev
  // let mail = document.querySelector('#gift-recipient-email')
  // mail.value = 'eleonoraatumanova@gmail.com'
  // mail.classList.add('focus')
  // let nameF = document.querySelector('#gift-recipient-name')
  // nameF.value = 'Ela'
  // nameF.classList.add('focus')
  //

  // для упралвения label
  allInputs.forEach(el=>{
    el.addEventListener('focus', function(){
      el.classList.add('focus')
    })
    el.addEventListener('blur', function(){
      if(el.value != '') {
        el.classList.add('focus')
      } else {
        el.classList.remove('focus')
      }
    })
  })
  allTextareas.forEach(el=>{
    el.addEventListener('focus', function(){
      el.classList.add('focus')
    })
    el.addEventListener('blur', function(){
      if(el.value != '') {
        el.classList.add('focus')
      } else {
        el.classList.remove('focus')
      }
    })
  })

  // giftAmounts.forEach(el => {
  //   el.addEventListener('click', function () {
  //     let amount = el.childNodes[0].textContent
  //     amountInput.value = amount
  //     giftAmountPost.value = amount
  //     imageAmount.innerHTML = `${amount}<span>₽</span>`
  //     // updateState()
  //   })
  // })

  // validation
  // function isValidAmount(v) {
  //     if (v == null) return false;
  //     let n = Number(v);
  //     return Number.isFinite(n) && n >= minAmount & n<= maxAmount;
  //   }

  // function updateState() {
  //   console.log(amountInput.value)
  //   let ok = isValidAmount(amountInput.value)
  //   submitBtn.disabled = !ok
  //   submitBtn.classList.toggle('is-disabled', !ok)
  // }

  // // function submitGiftForm() {
  // //   console.log('form submitted')
  // //   let buyNowInput = document.createElement('input');
  // //   buyNowInput.type = 'hidden';
  // //   buyNowInput.name = 'buy_now';
  // //   buyNowInput.value = '1';
  // //   giftForm.appendChild(buyNowInput);
  // //   giftForm.submit()
  // // }

  // updateState()
  // //amountInput.addEventListener('input', updateState)
  // amountInput.addEventListener('blur', updateState)

  // //input field control
  // amountInput.addEventListener('input', function () {
  //     // Удаляем все нецифровые символы
  //     let digits = this.value.replace(/\D/g, '');

  //     if (digits !== '') {
  //       let num = parseInt(digits, 10);

  //       // Если число больше максимального, запрещаем добавление лишней цифры
  //       if (num > maxAmount) {
  //         // возвращаем старое значение (до ввода этой цифры)
  //         this.value = this.dataset.prevValue || maxAmount;
  //         return;
  //       }

  //       // Обновляем значение и запоминаем как «предыдущее валидное»
  //       this.value = digits;
  //       this.dataset.prevValue = this.value;
  //     } else {
  //       // Позволяем временно очистить поле
  //       this.value = '';
  //       this.dataset.prevValue = '';
  //     }
  // });

  //   // После потери фокуса проверяем минимум
  //   amountInput.addEventListener('blur', function () {
  //     if (this.value === '' || parseInt(this.value, 10) < minAmount) {
  //       this.value = minAmount;
  //       this.setAttribute('value',minAmount)
  //       imageAmount.innerHTML = `${minAmount}<span>₽</span>`
  //       updateState()
  //     } else {
  //       imageAmount.innerHTML = `${this.value}<span>₽</span>`
  //     }
  //   });


  // let buyNowInput = document.createElement('input');
  // buyNowInput.type = 'hidden';
  // buyNowInput.name = 'buy_now';
  // buyNowInput.value = '1';
  // giftForm.appendChild(buyNowInput);
}


// gift card page

// FAQ toggle
document.querySelectorAll('.faq-item').forEach(item => {
  const q = item.querySelector('.faq-question');
  if(!q) return;
  q.addEventListener('click', () => item.classList.toggle('open'));
});

// Copy gift code
document.querySelectorAll('[data-copy-target]').forEach(btn=>{
  btn.addEventListener('click', async ()=>{
    const target = document.querySelector(btn.getAttribute('data-copy-target'));
    if(!target) return;
    const text = target.textContent.trim();
    try{
      await navigator.clipboard.writeText(text);
      const prev = btn.textContent;
      btn.textContent = 'Скопировано ✓';
      setTimeout(()=> btn.textContent = prev, 1400);
    }catch(e){
      btn.textContent = 'Ошибка копирования';
      setTimeout(()=> btn.textContent = 'Скопировать', 1400);
    }
  });
});

// checkout page

const giftcardShow = document.querySelector('.ywgc-show-giftcard')
const giftCardApplyBtn = document.querySelector('.ywgc_apply_gift_card_button')
if(giftcardShow) {
  giftcardShow.addEventListener('click', () => giftcardShow.classList.toggle('open'));
}
if(giftCardApplyBtn) {
  giftCardApplyBtn.addEventListener('click', () => giftcardShow.classList.toggle('open'));
}

//GC image & gradient

document.addEventListener('DOMContentLoaded', function () {
    const wrap = document.querySelector('.gift-image-wrap');
    const arcTrack = document.querySelector('.gift-gradient-arc__track');
    const gradientButtons = Array.from(document.querySelectorAll('.gift-gradient-picker__btn'));
    const imageButtons = Array.from(document.querySelectorAll('.gift-image-picker__btn'));
    const gradientInput = document.getElementById('giftcard_gradient');
    const imageInput = document.getElementById('giftcard_image');

    if (!wrap || !arcTrack || !gradientInput || !imageInput || typeof plntGiftCardData === 'undefined') {
        return;
    }

    const gradients = plntGiftCardData.gradients || {};
    const images = plntGiftCardData.images || {};
    const defaults = plntGiftCardData.defaults || {};

    const defaultGradientKey = defaults.gradient || Object.keys(gradients)[0] || 'sky';
    const defaultImageKey = defaults.image || 'none';

    let activeGradientIndex = gradientButtons.findIndex(function (btn) {
        return btn.dataset.gradientKey === (gradientInput.value || defaultGradientKey);
    });

    if (activeGradientIndex < 0) {
        activeGradientIndex = 0;
    }

    let dragOffset = 0;
    let isDragging = false;
    let startX = 0;
    let startDragOffset = 0;
    let velocity = 0;
    let lastX = 0;
    let lastTime = 0;
    let snapAnimationFrame = null;

    function getGradientCss(key) {
        return gradients[key] || gradients[defaultGradientKey] || '';
    }

    function getImageCss(key) {
        const value = images[key];

        if (!value || value === 'none') {
            return 'none';
        }

        return 'url("' + value + '")';
    }

    function setActiveButton(buttons, dataKey, value) {
        buttons.forEach(function (button) {
            button.classList.toggle('is-active', button.dataset[dataKey] === value);
        });
    }

    function renderBackground() {
        const gradientKey = gradientInput.value || defaultGradientKey;
        const imageKey = imageInput.value || defaultImageKey;

        const gradientCss = getGradientCss(gradientKey);
        const imageCss = getImageCss(imageKey);

        if (!gradientCss) {
            return;
        }

        if (imageCss !== 'none') {
            wrap.style.backgroundImage = imageCss + ', ' + gradientCss;
            wrap.style.backgroundSize = 'contain, contain';
            wrap.style.backgroundPosition = 'center, center';
            wrap.style.backgroundRepeat = 'no-repeat, no-repeat';
        } else {
            wrap.style.backgroundImage = gradientCss;
            wrap.style.backgroundSize = 'contain';
            wrap.style.backgroundPosition = 'center';
            wrap.style.backgroundRepeat = 'no-repeat';
        }
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

    function getCenteredIndex() {
        const total = gradientButtons.length;
        return mod(Math.round(activeGradientIndex + dragOffset), total);
    }

    function updateActiveGradient() {
        const centeredIndex = getCenteredIndex();
        const activeButton = gradientButtons[centeredIndex];

        if (!activeButton) {
            return;
        }

        activeGradientIndex = centeredIndex;
        dragOffset = 0;

        gradientInput.value = activeButton.dataset.gradientKey || defaultGradientKey;
        setActiveButton(gradientButtons, 'gradientKey', gradientInput.value);
        renderBackground();
    }

    function layoutGradientArc() {
        if (!gradientButtons.length || !arcTrack) {
            return;
        }

        const total = gradientButtons.length;
        const currentCenter = activeGradientIndex + dragOffset;

        const trackWidth = arcTrack.offsetWidth;

        const isMobile = trackWidth < 600;
        const isSmallMobile = trackWidth < 420;

        const radiusX = isSmallMobile
            ? trackWidth * 0.46
            : isMobile
                ? trackWidth * 0.42
                : trackWidth * 0.38;

        const radiusY = isSmallMobile ? 30 : isMobile ? 42 : 42;
        const angleStep = isSmallMobile ? 14 : isMobile ? 12 : 12;

        gradientButtons.forEach(function (button, index) {
            let offset = index - currentCenter;

            if (offset > total / 2) {
                offset -= total;
            }
            if (offset < -total / 2) {
                offset += total;
            }

            const angleDeg = offset * angleStep;
            const angleRad = angleDeg * Math.PI / 180;

            const x = Math.sin(angleRad) * radiusX;
            const y = Math.cos(angleRad) * radiusY;

            const dist = Math.abs(offset);
            const scale = Math.max(0.72, 1 - dist * 0.06);
            const opacity = Math.max(0.28, 1 - dist * 0.08);

            button.style.transform = 'translate(' + x + 'px, ' + y + 'px) scale(' + scale + ')';
            button.style.opacity = opacity;
            button.style.zIndex = String(200 - Math.round(dist * 10));
            button.classList.toggle('is-active', dist < 0.5);
        });
    }

    function stopSnapAnimation() {
        if (snapAnimationFrame) {
            cancelAnimationFrame(snapAnimationFrame);
            snapAnimationFrame = null;
        }
    }

    function animateSnap() {
        stopSnapAnimation();

        function tick() {
            const target = Math.round(dragOffset);
            const delta = target - dragOffset;

            if (Math.abs(delta) < 0.001) {
                dragOffset = target;
                layoutGradientArc();
                updateActiveGradient();
                stopSnapAnimation();
                return;
            }

            dragOffset += delta * 0.16;
            layoutGradientArc();
            snapAnimationFrame = requestAnimationFrame(tick);
        }

        snapAnimationFrame = requestAnimationFrame(tick);
    }

    gradientButtons.forEach(function (button, index) {
        button.addEventListener('click', function () {
            stopSnapAnimation();
            activeGradientIndex = index;
            dragOffset = 0;
            layoutGradientArc();
            updateActiveGradient();
        });
    });

    imageButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const imageKey = this.dataset.imageKey || defaultImageKey;
            imageInput.value = imageKey;
            setActiveButton(imageButtons, 'imageKey', imageKey);
            renderBackground();
        });
    });

    function onDragStart(event) {
        stopSnapAnimation();
        isDragging = true;
        startX = getPointX(event);
        startDragOffset = dragOffset;
        velocity = 0;
        lastX = startX;
        lastTime = performance.now();
        arcTrack.classList.add('is-dragging');
    }

    function onDragMove(event) {
        if (!isDragging) {
            return;
        }

        const x = getPointX(event);
        const now = performance.now();
        const deltaX = x - startX;

        dragOffset = startDragOffset - (deltaX / 36);

        const dt = now - lastTime;
        if (dt > 0) {
            velocity = (x - lastX) / dt;
        }

        lastX = x;
        lastTime = now;

        layoutGradientArc();

        if (event.cancelable) {
            event.preventDefault();
        }
    }

    function onDragEnd() {
        if (!isDragging) {
            return;
        }

        isDragging = false;
        arcTrack.classList.remove('is-dragging');

        dragOffset -= velocity * 6;

        animateSnap();
    }

    if (!gradientInput.value || !gradients[gradientInput.value]) {
        gradientInput.value = defaultGradientKey;
    }

    if (!imageInput.value || typeof images[imageInput.value] === 'undefined') {
        imageInput.value = defaultImageKey;
    }

    setActiveButton(imageButtons, 'imageKey', imageInput.value);
    renderBackground();
    layoutGradientArc();

    window.addEventListener('resize', layoutGradientArc);

    arcTrack.addEventListener('touchstart', onDragStart, { passive: true });
    arcTrack.addEventListener('touchmove', onDragMove, { passive: false });
    arcTrack.addEventListener('touchend', onDragEnd);
    arcTrack.addEventListener('touchcancel', onDragEnd);

    arcTrack.addEventListener('mousedown', onDragStart);
    window.addEventListener('mousemove', onDragMove);
    window.addEventListener('mouseup', onDragEnd);
});