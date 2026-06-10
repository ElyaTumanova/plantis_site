  /* ==========================================================================
     Sticky actions: add/remove .fixed for .card__actions-wrap and .mgm_button_wrap
     ========================================================================== */
  document.addEventListener('DOMContentLoaded', () => {
    const target = document.querySelector('.card__grid');
    const actions = document.querySelector('.card__actions-wrap');
    const btns = document.querySelector('.card__btns-wrap');

    // если нет якорного блока или не нашли ни одну панель кнопок — выходим
    if (!target || (!actions && !btns)) return;

    // На сколько "раньше" включать fixed (в пикселях)
    const OFFSET = 400;

    // Включаем fixed, когда низ target дошёл до верхней части вьюпорта (+OFFSET)
    const isFixedState = () => {
      const r = target.getBoundingClientRect();
      return r.bottom <= OFFSET;
    };

    const apply = () => {
      const fixed = isFixedState();
      if (actions) actions.classList.toggle('fixed', fixed);
      if (btns) btns.classList.toggle('fixed', fixed);
    };

    // throttle через rAF
    let ticking = false;
    const onScroll = () => {
      if (ticking) return;
      ticking = true;

      requestAnimationFrame(() => {
        apply();
        ticking = false;
      });
    };

    apply();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
  })