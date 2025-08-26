document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.wpcf7 form').forEach(function(form){
    const submit = form.querySelector('input[type=submit], button[type=submit]');
    if (!submit) return;

    const requiredFields = form.querySelectorAll('.wpcf7-validates-as-required');
    const phoneFields    = form.querySelectorAll('.wpcf7-validates-as-tel');
    const emailFields    = form.querySelectorAll('.wpcf7-validates-as-email');

    const isRequired = (field) =>
      field.classList.contains('wpcf7-validates-as-required') ||
      field.hasAttribute('required') ||
      field.getAttribute('aria-required') === 'true';

    // обязательные поля заполнены
    const isFilled = (field) => {
      if (field.type === 'checkbox' || field.type === 'radio') {
        const group = form.querySelectorAll(`[name="${CSS.escape(field.name)}"]`);
        return Array.from(group).some(el => el.checked);
      }
      if (field.tagName === 'SELECT') return !!field.value;
      return field.value && field.value.trim() !== '';
    };

    // телефон: разрешаем + ( ) - / пробелы; 7–15 цифр
    const phoneOk = (field) => {
        const v = field.value.trim();
        if (v === '') return !isRequired(field);

        // только разрешённые символы
        if (!/^[+0-9()\/\-\s]+$/.test(v)) return false;

        // нормализуем: убираем пробелы, скобки, дефисы, слэши
        const normalized = v.replace(/[\s()\/-]/g, '');

        // после нормализации допускаем только опциональный '+' и цифры
        if (!/^\+?\d+$/.test(normalized)) return false;

        // целевые форматы для RU:
        // 1) +7XXXXXXXXXX
        if (/^\+7\d{10}$/.test(normalized)) return true;
        // 2) 8XXXXXXXXXX или 7XXXXXXXXXX
        if (/^[87]\d{10}$/.test(normalized)) return true;
        // 3) 9XXXXXXXXX (10-значный мобильный)
        if (/^9\d{9}$/.test(normalized)) return true;

        // общий фолбэк: 7–15 цифр
        const digitsOnly = normalized.replace(/\D/g, '');
        return digitsOnly.length >= 7 && digitsOnly.length <= 15;
    };

    // email: простая и надёжная проверка
    const emailOk = (field) => {
      const v = field.value.trim();
      if (v === '') return !isRequired(field);
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    };

    const check = () => {
      let ok = Array.from(requiredFields).every(isFilled);
      if (ok && phoneFields.length) ok = Array.from(phoneFields).every(phoneOk);
      if (ok && emailFields.length) ok = Array.from(emailFields).every(emailOk);

      submit.disabled = !ok;
      submit.classList.toggle('is-disabled', !ok);
    };

    // старт: выключено
    submit.disabled = true;
    submit.classList.add('is-disabled');

    // слушатели
    form.addEventListener('input',  check, true);
    form.addEventListener('change', check, true);
    form.addEventListener('wpcf7invalid', check);
    form.addEventListener('wpcf7mailsent', () => {
      submit.disabled = true;
      submit.classList.add('is-disabled');
    });
    form.addEventListener('reset', () => setTimeout(check, 0));

    check();
  });
});

