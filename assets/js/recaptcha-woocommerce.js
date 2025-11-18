(function () {
  // ===== CONFIG =====
  const CONFIG = {
    siteKey: window.recaptchaWoo?.siteKey || "6LcP2rIrAAAAAGxrNXEe4AP0rC_fXZ7v7vKVr4wF",
    debug: window.recaptchaWoo?.debug ?? false,
    // Контейнеры, внутри которых могут появляться формы:
    containers: [
      "#customer_login",           // /my-account
      ".login-popup",              // попап входа
      ".register-popup"            // попап регистрации
    ],
    formSelectors: [
      "form.woocommerce-form-login.login",
      "form.woocommerce-form-register.register",
      "form.woocommerce-form.lost_reset_password"
    ],
    tokenTtlMs: 100 * 1000,        // считаем токен «свежим» 100 сек (~< 2 мин жизни)
    actions: {
      idle: "woocommerce",
      submit: "woocommerce_submit",
      modal: "woocommerce_modal"
    }
  };

  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));
  const getForms = (root) =>
    CONFIG.formSelectors.map(s => $$(s, root)).flat();

  const log = (...args) => { if (CONFIG.debug) console.log("[reCAPTCHA]", ...args); };

  // --- кэш токена ---
  const TokenCache = {
    token: null,
    action: null,
    ts: 0,
    fresh() {
      return this.token && (Date.now() - this.ts) < CONFIG.tokenTtlMs;
    },
    set(token, action) {
      this.token = token; this.action = action; this.ts = Date.now();
    },
    get() { return this.token; }
  };

  // --- безопасный execute с ожиданием готовности ---
  let running = false; // защита от гонок
  function obtainToken(action, cb) {
    const run = () => {
      if (running) { // сливаем частые повторы
        log("⏳ execute уже идёт, пропускаем дубликат");
        return;
      }
      running = true;
      grecaptcha.execute(CONFIG.siteKey, { action }).then(token => {
        TokenCache.set(token, action);
        log(`✅ token(${action}) получен`);
        running = false;
        cb && cb(token);
      }).catch(err => {
        running = false;
        log("❌ ошибка execute:", err);
      });
    };

    if (window.grecaptcha && typeof grecaptcha.ready === "function") {
      grecaptcha.ready(run);
    } else {
      // редкий случай: api.js ещё не готов
      let tries = 0;
      const t = setInterval(() => {
        if (window.grecaptcha && typeof grecaptcha.ready === "function") {
          clearInterval(t); grecaptcha.ready(run);
        } else if (++tries > 75) { clearInterval(t); log("⛔ не дождались grecaptcha"); }
      }, 200);
    }
  }

  function ensureHiddenInput(form) {
    if (!form.querySelector('input[name="g-recaptcha-response"]')) {
      const inp = document.createElement("input");
      inp.type = "hidden";
      inp.name = "g-recaptcha-response";
      form.appendChild(inp);
      log("＋ добавили скрытое поле", form);
    }
  }

  function setTokenToForms(token, root) {
    getForms(root || document).forEach(f => {
      const inp = f.querySelector('input[name="g-recaptcha-response"]');
      if (inp) inp.value = token;
    });
  }

  // --- подготовка конкретной формы (лениво при первом взаимодействии) ---
  function prepareForm(form) {
    if (form.__recaptchaPrepared) return;
    ensureHiddenInput(form);

    // При первом фокусе на любом поле — если нет свежего токена, запросим
    const focusHandler = () => {
      if (!TokenCache.fresh()) {
        log("👀 первый фокус → берём token(idle)");
        obtainToken(CONFIG.actions.idle, (t) => setTokenToForms(t, form));
      }
      form.removeEventListener("focusin", focusHandler);
    };
    form.addEventListener("focusin", focusHandler, { once: true });

    // Перед сабмитом — гарантированно свежий токен
    form.addEventListener("submit", (e) => {
      if (TokenCache.fresh()) {
        const t = TokenCache.get();
        setTokenToForms(t, form);
        log("🚀 submit с кэшированным token");
        return; // используем свежий кэш
      }
      // если токен протух — синхронно получим новый и дадим форме отправиться чуть позже
      e.preventDefault();
      log("♻️ submit → токен просрочен, обновляем");
      obtainToken(CONFIG.actions.submit, (t) => {
        setTokenToForms(t, form);
        // повторно сабмитим после установки токена
        form.submit();
      });
    }, true);

    form.__recaptchaPrepared = true;
  }

  // --- инициализация внутри контейнера ---
  function initIn(root) {
    getForms(root).forEach(prepareForm);
  }

  // --- наблюдаем только за нужными контейнерами, без total-subtree ---
  function setupObservers() {
    CONFIG.containers.forEach(sel => {
      $$(sel).forEach(container => {
        initIn(container); // на всякий случай
        const obs = new MutationObserver((muts) => {
          let changed = false;
          muts.forEach(m => {
            if (m.type === "childList" && m.addedNodes && m.addedNodes.length) {
              m.addedNodes.forEach(n => {
                if (n.nodeType === 1 && (n.matches?.("form") || n.querySelector?.("form"))) {
                  changed = true;
                }
              });
            }
            if (m.type === "attributes" && m.attributeName === "class") {
              if (container.classList.contains("popup_active")) changed = true;
            }
          });
          if (changed) {
            log("🔎 обнаружены изменения в контейнере:", container);
            initIn(container);
            // при открытии попапа — если токен старый, обновим
            if (!TokenCache.fresh()) {
              obtainToken(CONFIG.actions.modal, (t) => setTokenToForms(t, container));
            }
          }
        });
        obs.observe(container, { childList: true, subtree: true, attributes: true, attributeFilter: ["class"] });
      });
    });
  }

  // --- старт ---
  document.addEventListener("DOMContentLoaded", () => {
    log("🌐 DOM готов (lite)");
    // Ленивая подготовка: только найденные контейнеры
    CONFIG.containers.forEach(sel => $$(sel).forEach(initIn));
    setupObservers();

    // Если страница уже содержит видимую форму — «прогреем» токен один раз по idle
    const hasVisibleForm = getForms(document).some(f => f.offsetParent !== null);
    if (hasVisibleForm) {
      obtainToken(CONFIG.actions.idle, (t) => setTokenToForms(t));
    }

    // Пауза, если вкладка неактивна
    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "visible" && !TokenCache.fresh()) {
        log("👁 вкладка активировалась → обновим token");
        obtainToken(CONFIG.actions.idle, (t) => setTokenToForms(t));
      }
    });
  });
})();
