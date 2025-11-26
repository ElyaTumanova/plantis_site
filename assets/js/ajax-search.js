document.addEventListener('DOMContentLoaded', () => {
  const input = document.querySelector('.search-form input[name="s"]');
  const result = document.querySelector('.search-result');

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

  // ✅ Возвращает true/false и принимает signal
  async function refreshNonce(signal) {
    try {
      const fd = new FormData();
      fd.append('action', 'get_search_nonce');

      const r = await fetch(search_form.url, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        signal,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const j = await r.json().catch(() => null);

      if (j?.success && j?.data?.nonce) {
        search_form.nonce = j.data.nonce;
        return true;
      }
      return false;
    } catch (e) {
      if (e.name === 'AbortError') return false;
      return false;
    }
  }

  async function runSearch(query) {
    // отменяем предыдущий поиск
    if (controller) controller.abort();
    controller = ('AbortController' in window) ? new AbortController() : null;

    const signal = controller ? controller.signal : undefined;
    lastQuery = query;

    document.body.classList.add('fix-body');
    result.hidden = false;
    result.classList.add('is-loading');
    result.innerHTML = SPINNER_HTML;

    const makeSearchFD = () => {
      const fd = new FormData();
      fd.append('s', query);
      fd.append('action', 'search-ajax');
      fd.append('nonce', search_form.nonce);
      return fd;
    };

    try {
      for (let attempt = 0; attempt < 2; attempt++) {
        const res = await fetch(search_form.url, {
          method: 'POST',
          body: makeSearchFD(), // каждый раз новый FormData с актуальным nonce
          credentials: 'same-origin',
          signal,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await res.json().catch(() => null);

        const isBadNonce =
          res.status === 403 ||
          (data?.success === false && data?.data?.message === 'Bad nonce');

        // ✅ 1-я попытка: если nonce плохой — обновляем и повторяем
        if (isBadNonce && attempt === 0) {
          const ok = await refreshNonce(signal);
          if (ok) continue; // повторить цикл => уйдёт второй search-ajax
          throw new Error('Bad nonce (refresh failed)');
        }

        // если пользователь уже ввёл другое — игнорим результат
        if (input.value.trim() !== lastQuery) return;

        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        if (data?.success === false) throw new Error(data?.data?.message || 'AJAX error');

        result.classList.remove('is-loading');
        result.innerHTML = data?.out ?? '';
        return;
      }
    } catch (e) {
      if (e.name !== 'AbortError') {
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
