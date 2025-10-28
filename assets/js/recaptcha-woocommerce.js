(function () {
  // ===== CONFIG =====
  const CONFIG = {
    siteKey: window.recaptchaWoo?.siteKey || "6LcP2rIrAAAAAGxrNXEe4AP0rC_fXZ7v7vKVr4wF", // <-- твой site key
    refreshMs: 90000, // обновление токена каждые 90 сек
    debug: window.recaptchaWoo?.debug ?? true // переключатель логов (true / false)
  };

  const ACTIONS = {
    idle: "woocommerce",
    modal: "woocommerce_modal",
    submit: "woocommerce_submit"
  };

  const FORM_SELECTORS = [
    "form.woocommerce-form-login.login",
    "form.woocommerce-form-register.register",
    "form.woocommerce-form.lost_reset_password",
    "div.login-popup form.woocommerce-form-login.login",
    "div.register-popup form.woocommerce-form-register.register"
  ];

  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));
  const getForms = (root) => FORM_SELECTORS.map(s => $$(s, root)).flat();

  function log(...args) {
    if (CONFIG.debug) console.log("[reCAPTCHA]", ...args);
  }

  function ensureHiddenInput(form) {
    if (!form.querySelector('input[name="g-recaptcha-response"]')) {
      const inp = document.createElement("input");
      inp.type = "hidden";
      inp.name = "g-recaptcha-response";
      form.appendChild(inp);
      log("Добавлено скрытое поле в форму:", form);
    }
  }

  function ensureHiddenInputs(root) {
    getForms(root).forEach(ensureHiddenInput);
  }

  function execRecaptcha(action, cb) {
    if (!window.grecaptcha || !grecaptcha.execute) {
      log("⚠️ grecaptcha.execute недоступен (скрипт не загрузился?)");
      return;
    }
    log(`Запрашиваем токен для action="${action}"`);
    grecaptcha.execute(CONFIG.siteKey, { action })
      .then(token => {
        log(`✅ Получен токен для action="${action}"`, token);
        if (typeof cb === "function") cb(token);
      })
      .catch(err => log("❌ Ошибка при получении токена:", err));
  }

  function setToken(action) {
    ensureHiddenInputs(document);
    execRecaptcha(action, (token) => {
      getForms().forEach(form => {
        const inp = form.querySelector('input[name="g-recaptcha-response"]');
        if (inp) {
          inp.value = token;
          log(`🔄 Токен обновлён (${action}) →`, form);
        }
      });
    });
  }

  function startRecaptcha() {
    log("✅ grecaptcha готов, инициализация...");
    setToken(ACTIONS.idle);

    // обновляем токен каждые ~90 сек
    setInterval(() => {
      log("⏱ Обновление токена по таймеру...");
      setToken(ACTIONS.idle);
    }, CONFIG.refreshMs);

    // перед сабмитом — свежий токен
    document.addEventListener("submit", (e) => {
      const f = e.target;
      if (f && getForms().includes(f)) {
        log("🚀 Сабмит формы, обновляем токен перед отправкой:", f);
        setToken(ACTIONS.submit);
      }
    }, true);

    // MutationObserver — для попапов и динамических вставок
    const obs = new MutationObserver((mutations) => {
      let needToken = false;
      mutations.forEach(m => {
        if (m.type === "childList") {
          m.addedNodes.forEach(n => {
            if (n.nodeType !== 1) return;
            if (n.matches && n.matches(".login-popup, .register-popup, form")) {
              log("👀 Обнаружена новая форма или попап:", n);
              ensureHiddenInputs(n);
              needToken = true;
            } else if (n.querySelector) {
              const form = n.querySelector("form");
              if (form) {
                log("👀 Обнаружена форма в новом блоке:", form);
                ensureHiddenInputs(n);
                needToken = true;
              }
            }
          });
        }
        if (m.type === "attributes" && m.attributeName === "class") {
          const el = m.target;
          if (el.matches && el.matches(".login-popup, .register-popup") && el.classList.contains("popup_active")) {
            log("🟢 Попап стал активен:", el);
            ensureHiddenInputs(el);
            needToken = true;
          }
        }
      });
      if (needToken) {
        log("⚙️ Обновляем токен после появления попапа/формы...");
        setToken(ACTIONS.modal);
      }
    });
    obs.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ["class"] });

    // Клики по переключателям попапов
    document.addEventListener("click", (e) => {
      if (e.target.closest(".register-form__login-btn") || e.target.closest(".login-form__registration-btn")) {
        log("🔁 Переключение между попапами (вход/регистрация)");
        setToken(ACTIONS.modal);
      }
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    log("🌐 DOM загружен, ждём grecaptcha...");
    ensureHiddenInputs(document);

    function init() {
      if (window.grecaptcha && grecaptcha.ready) {
        log("📡 grecaptcha найден, запускаем reCAPTCHA...");
        grecaptcha.ready(startRecaptcha);
        return true;
      }
      return false;
    }

    if (!init()) {
      const t = setInterval(() => { if (init()) clearInterval(t); }, 300);
      setTimeout(() => clearInterval(t), 15000);
    }
  });
})();
