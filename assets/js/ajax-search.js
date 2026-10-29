class AjaxSearch {
  static MIN_LEN = 3;
  static DEBOUNCE_MS = 500;
  static SPINNER_HTML = '<div class="spinner" aria-label="Loading" role="status"></div>';

  constructor(root) {
    this.root = root;
    this.form = root.querySelector('.search-form');
    this.input = root.querySelector('.search-form input[name="s"]');
    this.result = root.querySelector('.search-result');
    this.searchClean = root.querySelector('.search__icon--close');

    this.timerId = null;
    this.controller = null;
    this.lastQuery = '';

    if (!this.form || !this.input || !this.result || !window.search_form) return;

    this.init();
  }

  init() {
    this.input.addEventListener('input', this.onInput);
    this.input.addEventListener('keydown', this.onKeydown);
    if (this.searchClean) {
      this.searchClean.addEventListener('click', this.cleanSearch);
      this.searchClean.addEventListener('click', this.hideResults);
    }
    document.addEventListener('click', this.onDocumentClick);
   
  }

  onInput = () => {
    const query = this.input.value.trim();

    if (this.timerId) clearTimeout(this.timerId);

    if (query.length < AjaxSearch.MIN_LEN) {
      if (this.controller) this.controller.abort();
      // this.hideResults();
      return;
    }

    this.timerId = setTimeout(() => {
      this.runSearch(query);
    }, AjaxSearch.DEBOUNCE_MS);
  };

  onKeydown = (e) => {
    if (e.key === 'Escape') {
      if (this.controller) this.controller.abort();

      this.hideResults();
      this.input.blur();
    }
  };

  hideResults = () => {
    this.result.hidden = true;
    this.result.innerHTML = '';
    this.result.classList.remove('is-loading');

    this.root.classList.remove('is-search-open');
    document.body.classList.remove('fix-body');
  }

  cleanSearch = () => {
    this.input.value = '';
  }

  onDocumentClick = (e) => {
  if (
    this.root.classList.contains('is-search-open') &&
    !this.input.contains(e.target) &&
    !this.result.contains(e.target)
  ) {
    e.preventDefault()
    this.hideResults()
    this.cleanSearch()
  }
};

  makeSearchFD(query) {
    const fd = new FormData();

    fd.append('s', query);
    fd.append('action', 'search-ajax');
    fd.append('nonce', window.search_form.nonce);
    fd.append('form_id', this.form.id);

    return fd;
  }

  async refreshNonce(signal) {
    try {
      const fd = new FormData();
      fd.append('action', 'get_search_nonce');

      const res = await fetch(window.search_form.url, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        signal,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      const data = await res.json().catch(() => null);

      if (data?.success && data?.data?.nonce) {
        window.search_form.nonce = data.data.nonce;
        return true;
      }

      return false;
    } catch (e) {
      return false;
    }
  }

  isBadNonce(res, data) {
    return (
      res.status === 403 ||
      (data?.success === false && data?.data?.message === 'Bad nonce')
    );
  }

  async postSearch(query, signal) {
    const res = await fetch(window.search_form.url, {
      method: 'POST',
      body: this.makeSearchFD(query),
      credentials: 'same-origin',
      signal,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    });

    const data = await res.json().catch(() => null);

    return { res, data };
  }

  async runSearch(query) {
    if (this.controller) this.controller.abort();

    this.controller = 'AbortController' in window
      ? new AbortController()
      : null;

    const signal = this.controller ? this.controller.signal : undefined;

    this.lastQuery = query;

    document.body.classList.add('fix-body');

    this.result.hidden = false;
    this.result.classList.add('is-loading');
    this.result.innerHTML = AjaxSearch.SPINNER_HTML;

    this.root.classList.add('is-search-open');

    try {
      let { res, data } = await this.postSearch(query, signal);

      if (this.input.value.trim() !== this.lastQuery) return;

      if (this.isBadNonce(res, data)) {
        const ok = await this.refreshNonce(signal);

        if (!ok) {
          throw new Error('Bad nonce refresh failed');
        }

        if (this.input.value.trim() !== this.lastQuery) return;

        ({ res, data } = await this.postSearch(query, signal));
      }

      if (!res.ok) {
        throw new Error(`HTTP ${res.status}`);
      }

      if (data?.success === false) {
        throw new Error(data?.data?.message || 'AJAX error');
      }

      if (this.input.value.trim() !== this.lastQuery) return;

      this.result.classList.remove('is-loading');
      this.result.innerHTML = data?.out ?? '';
    } catch (e) {
      if (e.name !== 'AbortError') {
        this.result.classList.remove('is-loading');
        this.result.innerHTML = '';
      }
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  document
    .querySelectorAll('[data-js-search]')
    .forEach((search) => {
      new AjaxSearch(search);
    });
});