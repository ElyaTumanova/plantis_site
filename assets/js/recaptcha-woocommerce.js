(function () {
  // ===== CONFIG =====
  const SITE_KEY = window.recaptchaWoo?.siteKey || "6LcP2rIrAAAAAGxrNXEe4AP0rC_fXZ7v7vKVr4wF"; // <- YOUR_SITE_KEY подставь или передай из PHP
  const REFRESH_MS = 90000; // обновлять токен каждые 90 сек
  const ACTION_IDLE = "woocommerce";
  const ACTION_MODAL = "woocommerce_modal";
  const ACTION_SUBMIT = "woocommerce_submit";

  // Все формы WooCommerce (страница и попапы)
  const FORM_SELECTORS = [
    "form.woocommerce-form-login.login",
    "form.woocommerce-form-register.register",
    "form.woocommerce-form.lost_reset_password",
    "div.login-popup form.woocommerce-form-login.login",
    "div.register-popup form.woocommerce-form-register.register"
  ];

  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));
  const getForms = (root) => FORM_SELECTORS.map(s => $$(s, root)).flat();

  function ensureHiddenInput(form) {
    if (!form.querySelector('input[name="g-recaptcha-response"]')) {
      const inp = document.createElement("input");
      inp.type = "hidden";
      inp.name = "g-recaptcha-response";
      form.appendChild(inp);
    }
  }

  function ensureHiddenInputs(root) {
    getForms(root).forEach(ensureHiddenInput);
  }

  function execRecaptcha(action, cb) {
    if (!window.grecaptcha || !grecaptcha.execute) return;
    grecaptcha.execute(SITE_KEY, { action }).then(token => {
      if (typeof cb === "function") cb(token);
    }).catch(() => {});
  }

  function setToken(action) {
    ensureHiddenInputs(document);
    execRecaptcha(action, (token) => {
      getForms().forEach(form => {
        const inp = form.querySelector('input[name="g-recaptcha-response"]');
        if (inp) inp.value = token;
      });
    });
  }

  function startRecaptcha() {
    setToken(ACTION_IDLE);

    // Обновляем токен каждые 90 сек (v3 живёт ~2 мин)
    setInterval(() => setToken(ACTION_IDLE), REFRESH_MS);

    // Перед сабмитом всегда свежий токен
    document.addEventListener("submit", (e) => {
      const f = e.target;
      if (f && getForms().includes(f)) setToken(ACTION_SUBMIT);
    }, true);

    // MutationObserver: следим за попапами и новыми формами
    const obs = new MutationObserver((mutations) => {
      let needToken = false;
      mutations.forEach(m => {
        if (m.type === "childList") {
          m.addedNodes.forEach(n => {
            if (n.nodeType !== 1) return;
            if (n.matches && n.matches(".login-popup, .register-popup, form")) {
              ensureHiddenInputs(n);
              needToken = true;
            } else if (n.querySelector) {
              const form = n.querySelector("form");
              if (form) { ensureHiddenInputs(n); needToken = true; }
            }
          });
        }
        if (m.type === "attributes" && m.attributeName === "class") {
          const el = m.target;
          if (el.matches && el.matches(".login-popup, .register-popup") && el.classList.contains("popup_active")) {
            ensureHiddenInputs(el);
            needToken = true;
          }
        }
      });
      if (needToken) setToken(ACTION_MODAL);
    });
    obs.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ["class"] });

    // Клики по кнопкам переключения между попапами
    document.addEventListener("click", (e) => {
      if (e.target.closest(".register-form__login-btn") || e.target.closest(".login-form__registration-btn")) {
        setToken(ACTION_MODAL);
      }
    });
  }

  // --- запуск ---
  document.addEventListener("DOMContentLoaded", () => {
    ensureHiddenInputs(document);

    function init() {
      if (window.grecaptcha && grecaptcha.ready) {
        grecaptcha.ready(startRecaptcha);
        return true;
      }
      return false;
    }

    if (!init()) {
      const t = setInterval(() => { if (init()) clearInterval(t); }, 300);
      setTimeout(() => clearInterval(t), 15000); // safety stop
    }
  });
})();
