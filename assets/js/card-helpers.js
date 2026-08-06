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

  /* Tooltip for delivery */
class DeliveryTooltip {
  constructor(element) {
    this.element = element;
    this.button = element.querySelector('.card__delivery-tooltip-btn');
    this.content = element.querySelector('.card__delivery-tooltip-content');
    this.closeButton = element.querySelector('.card__delivery-tooltip-close');
    this.isDesktop = window.matchMedia('(hover: hover) and (pointer: fine)');

    if (!this.button || !this.content) return;

    this.bindEvents();
  }

  bindEvents() {
    this.button.addEventListener('click', (event) => {
      event.stopPropagation();

      if (this.isDesktop.matches) {
        this.open();
        return;
      }

      this.toggle();
    });

    this.button.addEventListener('mouseenter', () => {
      if (this.isDesktop.matches) this.open();
    });

    this.button.addEventListener('focus', () => {
      if (this.isDesktop.matches) this.open();
    });

    this.element.addEventListener('mouseleave', () => {
      if (this.isDesktop.matches) this.close();
    });

    this.element.addEventListener('focusout', (event) => {
      if (this.isDesktop.matches && !this.element.contains(event.relatedTarget)) this.close();
    });

    this.closeButton?.addEventListener('click', (event) => {
      event.stopPropagation();
      this.close();
      this.button.focus();
    });

    this.content.addEventListener('click', (event) => event.stopPropagation());
    document.addEventListener('click', () => this.close());

    document.addEventListener('keydown', (event) => {
      if (event.key !== 'Escape' || !this.element.classList.contains('is-open')) return;

      this.close();
      this.button.focus();
    });
  }

  toggle() {
    this.element.classList.contains('is-open') ? this.close() : this.open();
  }

  open() {
    document.querySelectorAll('.card__delivery-tooltip.is-open').forEach((tooltip) => {
      if (tooltip === this.element) return;

      tooltip.classList.remove('is-open');
      tooltip.querySelector('.card__delivery-tooltip-btn')?.setAttribute('aria-expanded', 'false');
      tooltip.querySelector('.card__delivery-tooltip-content')?.setAttribute('aria-hidden', 'true');
    });

    this.setState(true);
  }

  close() {
    this.setState(false);
  }

  setState(isOpen) {
    this.element.classList.toggle('is-open', isOpen);
    this.button.setAttribute('aria-expanded', String(isOpen));
    this.content.setAttribute('aria-hidden', String(!isOpen));

    if (!this.isDesktop.matches) document.body.classList.toggle('delivery-tooltip-open', isOpen);
  }
}

document.querySelectorAll('.card__delivery-tooltip').forEach((element) => new DeliveryTooltip(element));