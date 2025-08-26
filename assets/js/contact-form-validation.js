document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('.wpcf7 form');

  function escapeForAttrName(name) {
    return name ? name.replace(/(["\\])/g, '\\$1') : '';
  }

  /* ===== МАСКА +7 (XXX) XXX-XX-XX с поддержкой Backspace ===== */
  function digitsBody(value) {
    let d = (value || '').replace(/\D/g, '');
    // RU-префиксы 8/7 убираем, оставляем «тело» из 10 цифр
    if (d.startsWith('8') || d.startsWith('7')) d = d.slice(1);
    return d.slice(0, 10);
  }

  function formatFromDigits(body) {
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
    function apply(d) {
      input.value = formatFromDigits(d);
      input.dataset.prevDigits = d;
      // курсор в конец — стабильно на мобилках
      try { input.setSelectionRange(input.value.length, input.value.length); } catch (e) {}
    }

    function onInput(e) {
      const prev = input.dataset.prevDigits || '';
      const type = (e && e.inputType) || '';
      const isDelete = type.indexOf('delete') === 0 || input.dataset.backspace === '1';

      let d = digitsBody(input.value);

      // Если удалили только символ маски (а цифры не уменьшились) — удалим ещё одну цифру.
      if (isDelete && prev.length === d.length && prev.length > 0) {
        d = prev.slice(0, -1);
      }

      apply(d);
      input.dataset.backspace = '';
      if (typeof onChange === 'function') onChange();
    }

    function onKeydown(e) {
      if (e.key === 'Backspace') input.dataset.backspace = '1';
    }

    function onFocus() {
      if (!input.value.trim()) {
        input.value = '+7 ';
        try { input.setSelectionRange(input.value.length, input.value.length); } catch (e) {}
      }
      input.dataset.prevDigits = digitsBody(input.value);
    }

    function onBlur() {
      // если цифр нет — очищаем поле от «+7 »
      if (digitsBody(input.value).length === 0) input.value = '';
      if (typeof onChange === 'function') onChange();
    }

    // инициализация
    input.dataset.prevDigits = '';
    input.dataset.backspace = '';
    input.setAttribute('maxlength', '18'); // "+7 (XXX) XXX-XX-XX"
    input.setAttribute('inputmode', 'tel');
    input.addEventListener('keydown', onKeydown, false);
    input.addEventListener('input', onInput, false);
    input.addEventListener('paste', onInput, false);
    input.addEventListener('focus', onFocus, false);
    input.addEventListener('blur', onBlur, false);

    // стартовое выравнивание
    apply(digitsBody(input.value));
  }

  /* ===== ВАЛИДАЦИИ ===== */
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

  // Телефон: допускаем 903XXXXXXXX, 8XXXXXXXXXX, 7XXXXXXXXXX, +7XXXXXXXXXX и любые () - пробелы /.
  function phoneOk(field) {
    const raw = (field.value || '').trim();
    if (raw === '') return !isRequired(field);

    // разрешённые символы
    if (!/^[+0-9()\/\-\s]+$/.test(raw)) return false;

    // убираем форматирование
    const normalized = raw.replace(/[\s()\/-]/g, '');
    if (!/^\+?\d+$/.test(normalized)) return false;

    // RU-правила:
    // +7XXXXXXXXXX  — страна + 10 цифр
    if (/^\+7\d{10}$/.test(normalized)) return true;
    // 8XXXXXXXXXX или 7XXXXXXXXXX — всего 11 цифр
    if (/^[87]\d{10}$/.test(normalized)) return true;
    // 9XXXXXXXXX — локальный мобильный без кода страны (10 цифр)
    if (/^9\d{9}$/.test(normalized)) return true;

    // Никаких «>=7» — короткие номера не проходят
    return false;
    }

  function emailOk(field) {
    const v = (field.value || '').trim();
    if (v === '') return !isRequired(field);
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  /* ===== ИНИЦИАЛИЗАЦИЯ КАЖДОЙ ФОРМЫ ===== */
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

    // Маску ставим на все телефонные поля, чтобы ввод шёл в формате +7 (XXX) XXX-XX-XX
    Array.prototype.forEach.call(phoneFields, function (input) {
      attachPhoneMask(input, check);
    });

    // старт: выключено
    submit.disabled = true;
    submit.classList.add('is-disabled');

    // слушатели
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
