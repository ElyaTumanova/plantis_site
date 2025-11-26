document.addEventListener('DOMContentLoaded', () => {
  const input = document.querySelector('.search-form input[name="s"]');
  const result = document.querySelector('.search-result');

  console.log(window.search_form);


  if (!input || !result || !window.search_form) return;

  const MIN_LEN = 3;
  const DEBOUNCE_MS = 300;
  const SPINNER_HTML = '<div class="spinner" aria-label="Loading" role="status"></div>';

  let timerId = null;
  let controller = null;
  let lastQuery = '';

  function hideResults() {
    result.hidden = true;
    result.innerHTML = '';
    result.classList.remove('is-loading');
    document.body.classList.remove('fix-body');
  }

  async function runSearch(query) {
    if (controller) controller.abort();
    controller = ('AbortController' in window) ? new AbortController() : null;

    lastQuery = query;

    document.body.classList.add('fix-body');
    result.hidden = false;
    result.classList.add('is-loading');
    result.innerHTML = SPINNER_HTML;

    const formData = new FormData();
    formData.append('s', query);
    formData.append('action', 'search-ajax');
    formData.append('nonce', search_form.nonce);

    try {
      const res = await fetch(search_form.url, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        signal: controller ? controller.signal : undefined,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const data = await res.json();

      // если пользователь уже ввёл другое — игнорим результат
      if (input.value.trim() !== lastQuery) return;

      result.classList.remove('is-loading');
      result.innerHTML = data.out ?? '';
    } catch (e) {
      if (e.name !== 'AbortError') {
        // если ошибка — можно спрятать или показать текст
        result.classList.remove('is-loading');
        result.innerHTML = '';
        // console.error(e);
      }
    }
  }

  input.addEventListener('input', () => {
    const query = input.value.trim();

    if (timerId) clearTimeout(timerId);

    if (query.length < MIN_LEN) {
      if (controller) controller.abort();
      hideResults();
      return;
    }

    timerId = setTimeout(() => runSearch(query), DEBOUNCE_MS);
  });

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (controller) controller.abort();
      hideResults();
      input.blur();
    }
  });
});
