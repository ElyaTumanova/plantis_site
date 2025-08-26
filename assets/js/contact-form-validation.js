document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('.wpcf7 form');

  function escapeForAttrName(name) {
    return name ? name.replace(/(["\\])/g, '\\$1') : '';
  }

  // ===== Маска телефона (+7 (XXX) XXX-XX-XX) =====
  function normalizeDigits(value) {
    let digits = (value || '').replace(/\D/g, '');
    // Если номер начинается с 8/7 — считаем, что это RU и убираем префикс
    if (digits.startsWith('8') || digits.startsWith('7')) {
      digits = digits.slice(1);
    }
    // оставляем максимум 10 цифр тела номера
    return digits.slice(0, 10);
  }

  function formatRuPhone(value) {
    const body = normalizeDigits(value);
    let out = '+7';
    if (body.length > 0) {
      out += ' (' + body.slice(0, 3);
      if (body.length >= 3) out += ')';
    }
    if (body.length > 3) out += ' ' + body.slice(3, 6);
    if (body.length > 6) out += '-' + body.slice(6, 8);
    if (body.length > 8) out += '-' + body.slice(8, 10);
    return out;
  }

  function attachPhoneMask(input, onChange) {
    function onInput(e) {
      const formatted = formatRuPhone(input.value);
      input.value = formatted;
      // курсор в конец (просто и надёжно)
      const pos = input.value.length;
      try { input.setSelectionRange(pos, pos); } catch (err) {}
      if (typeof onChange === 'function') onChange();
    }
    function onFocus() {
      if (!input.value.trim()) {
        input.value = '+7 ';
        try {
          const pos = input.value.length;
          input.setSelectionRange(pos, pos);
        } catch (err) {}
      } else {
        onInput();
      }
    }
    function onBlur() {
      // если введено меньше 10 цифр — очищаем (чтобы не оставался «полуформат»)
      const digits = input.value.replace(/\D/g, '');
      if (digits.length < 11) input.value = '';
      if (typeof onChange === 'function') onChange();
    }

    input.setAttribute('maxlength', '18'); // "+7 (XXX) XXX-XX-XX"
    input.setAttribute('inputmode', 'tel');
    input.addEventListener('focus', onFocus, false);
    input.addEventListener('input', onInput, false);
    input.addEventListener('paste', onInput, false);
    input.addEventListener('blur', onBlur, false);
  }

  // ===== Валидации =====
  function isRequired(field) {
    return field.classList.contains('wpcf7-validates-as-required') ||
           field.hasAttribute('required') ||
           field.getAttribute('aria-required') === 'true';
  }

  function isFilled(field, form) {
    if (field.type === 'checkbox' || field.type === 'radio') {
      const safeName = escapeForAttrName(field.name);
      const group = form.querySelectorAll('[name="' + safeName + '"]');
      for (let i = 0; i < group.length; i++) {
        if (group[i].checked) return true;
      }
      return false;
    }
    if (field.tagName === 'SELECT') return !!field.value;
    return !!(field.value && field.value.trim() !== '');
  }

  // Телефон — допускаем: 903XXXXXXXX (10 цифр, начинается с 9),
  // 8XXXXXXXXXX / 7XXXXXXXXXX (11 цифр), +7XXXXXXXXXX; скобки/пробелы/дефисы — ок.
  function phoneOk(field) {
    const raw = (field.value || '').trim();
    if (raw === '') return !isRequired(field);

    // только допустимые символы
    if (!/^[+0-9()\/\-\s]+$/.test(raw)) return false;

    const normalized = raw.replace(/[\s()\/-]/g, ''); // убрали форматирование

    if (!/^\+?\d+$/.test(normalized)) return false;

    if (/^\+7\d{10}$/.test(normalized)) return true;   // +7XXXXXXXXXX
    if (/^[87]\d{10}$/.test(normalized)) return true;  // 8XXXXXXXXXX или 7XXXXXXXXXX
    if (/^9\d{9}$/.test(normalized)) return true;      // 903XXXXXXXX

    // как резерв: 7–15 цифр общих
    const digitsOnly = normalized.replace(/\D/g, '');
    return digitsOnly.length >= 7 && digitsOnly.length <= 15;
  }

  function emailOk(field) {
    const v = (field.value || '').trim();
    if (v === '') return !isRequired(field);
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  // ===== Инициализация каждой формы =====
  Array.prototype.forEach.call(forms, function (form) {
    const submit = form.querySelector('input[type=submit], button[type=submit]');
    if (!submit) return;

    const requiredFields = form.querySelectorAll('.wpcf7-validates-as-required');
    const phoneFields    = form.querySelectorAll('.wpcf7-validates-as-tel, input[type="tel"]');
    const emailFields    = form.querySelectorAll('.wpcf7-validates-as-email');

    function check() {
      let ok = true;

      // обязательные поля
      for (let i = 0; i < requiredFields.length; i++) {
        if (!isFilled(requiredFields[i], form)) { ok = false; break; }
      }

      // телефон(ы)
      if (ok && phoneFields.length) {
        for (let i = 0; i < phoneFields.length; i++) {
          if (!phoneOk(phoneFields[i])) { ok = false; break; }
        }
      }

      // email(ы)
      if (ok && emailFields.length) {
        for (let i = 0; i < emailFields.length; i++) {
          if (!emailOk(emailFields[i])) { ok = false; break; }
        }
      }

      submit.disabled = !ok;
      if (ok) submit.classList.remove('is-disabled');
      else submit.classList.add('is-disabled');
    }

    // Маска на все телефонные поля формы
    Array.prototype.forEach.call(phoneFields, function (input) {
      attachPhoneMask(input, check);
    });

    // Старт: выключено
    submit.disabled = true;
    submit.classList.add('is-disabled');

    // Слушатели
    form.addEventListener('input',  check, true);
    form.addEventListener('change', check, true);
    form.addEventListener('wpcf7invalid', check);
    form.addEventListener('wpcf7mailsent', function () {
      submit.disabled = true;
      submit.classList.add('is-disabled');
    });
    form.addEventListener('reset', function () { setTimeout(check, 0); });

    check();
  });
});