(function () {
  const CONFIG = {
    siteKey: window.recaptchaWoo?.siteKey || "6LezYTQsAAAAAEzapFcvWQ9w9vAP1uCYtNKXKfXy",
    debug: window.recaptchaWoo?.debug ?? false,

    containers: ["#customer_login", ".login-popup", ".register-popup"],

    formSelectors: [
      "form.woocommerce-form-login.login",
      "form.woocommerce-form-register.register",
      "form.woocommerce-form.lost_reset_password"
    ],

    tokenTtlMs: 100 * 1000,

    actions: {
      idle: "woocommerce",
      modal: "woocommerce_modal",
      login: "woocommerce_login",
      register: "woocommerce_register",
      lost: "woocommerce_lost_password"
    }
  };

  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));
  const getForms = (root) => CONFIG.formSelectors.map(s => $$(s, root)).flat();
  const log = (...args) => { if (CONFIG.debug) console.log("[reCAPTCHA]", ...args); };

  // Кэш токенов ПО ACTION (чтобы не мешались login/register)
  const TokenCache = {
    map: new Map(), // action -> {token, ts}
    fresh(action) {
      const v = this.map.get(action);
      return v && (Date.now() - v.ts) < CONFIG.tokenTtlMs;
    },
    get(action) {
      return this.map.get(action)?.token || null;
    },
    set(action, token) {
      this.map.set(action, { token, ts: Date.now() });
    }
  };

  // защита от гонок по action
  const runningByAction = new Map();

  function obtainToken(action, cb) {
    const run = () => {
      if (runningByAction.get(action)) {
        log("⏳ execute уже идёт для action:", action);
        return;
      }
      runningByAction.set(action, true);

      grecaptcha.execute(CONFIG.siteKey, { action })
        .then(token => {
          TokenCache.set(action, token);
          runningByAction.set(action, false);
          log(`✅ token(${action}) получен`);
          cb && cb(token);
        })
        .catch(err => {
          runningByAction.set(action, false);
          log("❌ execute error:", action, err);
        });
    };

    if (window.grecaptcha && typeof grecaptcha.ready === "function") {
      grecaptcha.ready(run);
    } else {
      let tries = 0;
      const t = setInterval(() => {
        if (window.grecaptcha && typeof grecaptcha.ready === "function") {
          clearInterval(t); grecaptcha.ready(run);
        } else if (++tries > 75) {
          clearInterval(t);
          log("⛔ не дождались grecaptcha");
        }
      }, 200);
    }
  }

  function ensureHiddenInputs(form) {
    // token
    if (!form.querySelector('input[name="g-recaptcha-response"]')) {
      const inp = document.createElement("input");
      inp.type = "hidden";
      inp.name = "g-recaptcha-response";
      form.appendChild(inp);
      log("＋ добавили g-recaptcha-response", form);
    }
    // action
    if (!form.querySelector('input[name="g-recaptcha-action"]')) {
      const inp = document.createElement("input");
      inp.type = "hidden";
      inp.name = "g-recaptcha-action";
      form.appendChild(inp);
      log("＋ добавили g-recaptcha-action", form);
    }
  }

  function formAction(form) {
    if (form.matches('form.woocommerce-form-login.login')) return CONFIG.actions.login;
    if (form.matches('form.woocommerce-form-register.register')) return CONFIG.actions.register;
    if (form.matches('form.woocommerce-form.lost_reset_password')) return CONFIG.actions.lost;
    return CONFIG.actions.idle;
  }

  function setTokenToForm(form, token, action) {
    const tokenInp = form.querySelector('input[name="g-recaptcha-response"]');
    const actInp = form.querySelector('input[name="g-recaptcha-action"]');
    if (tokenInp) tokenInp.value = token;
    if (actInp) actInp.value = action;
  }

  function warmUpVisibleForms() {
    const visible = getForms(document).filter(f => f.offsetParent !== null);
    if (!visible.length) return;
    // прогрев idle токена (не для сабмита, просто чтобы google "видел" активность)
    obtainToken(CONFIG.actions.idle, () => {});
  }

  function prepareForm(form) {
    if (form.__recaptchaPrepared) return;

    ensureHiddenInputs(form);

    const act = formAction(form);

    // При первом фокусе — возьмём токен именно под эту форму (login/register/lost)
    form.addEventListener("focusin", () => {
      if (!TokenCache.fresh(act)) {
        log("👀 focus → берём token для", act);
        obtainToken(act, (t) => setTokenToForm(form, t, act));
      }
    }, { once: true });

    // На submit — гарантированно свежий токен под конкретный action
    form.addEventListener("submit", (e) => {
      const action = formAction(form);

      if (TokenCache.fresh(action)) {
        const t = TokenCache.get(action);
        setTokenToForm(form, t, action);
        log("🚀 submit с кэшированным token:", action);
        return;
      }

      e.preventDefault();
      log("♻️ submit → обновляем token:", action);

      obtainToken(action, (t) => {
        setTokenToForm(form, t, action);
        form.submit();
      });
    }, true);

    form.__recaptchaPrepared = true;
  }

  function initIn(root) {
    getForms(root).forEach(prepareForm);
  }

  function setupObservers() {
    CONFIG.containers.forEach(sel => {
      $$(sel).forEach(container => {
        initIn(container);

        const obs = new MutationObserver((muts) => {
          let changed = false;

          muts.forEach(m => {
            if (m.type === "childList" && m.addedNodes?.length) {
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
            log("🔎 изменения в контейнере:", container);
            initIn(container);
            // при открытии попапа — лёгкий прогрев
            obtainToken(CONFIG.actions.modal, () => {});
          }
        });

        obs.observe(container, {
          childList: true,
          subtree: true,
          attributes: true,
          attributeFilter: ["class"]
        });
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    log("🌐 DOM готов");
    CONFIG.containers.forEach(sel => $$(sel).forEach(initIn));
    setupObservers();
    warmUpVisibleForms();

    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "visible") {
        warmUpVisibleForms();
      }
    });
  });
})();
