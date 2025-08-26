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
      if (!/^[+0-9()\/\-\s]+$/.test(v)) return false;
      const digits = v.replace(/\D/g, '').length;
      return digits >= 7 && digits <= 15;
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

