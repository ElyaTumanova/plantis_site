// переменные для управления попапом
const searchOpenPopupBtn = document.querySelectorAll('.search-btn');
const searchWrap = document.querySelector('.search');
const headerButns = document.querySelector('.header__main .header__wrap');
const headerButnsMob = document.querySelector('.header__mob .search-btn');
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search .search-field');

searchResult.hidden = true;

// ====== отдельные функции ======
function openSearch(activeBtn = null) {
  if (searchWrap.classList.contains('search_open')) return;

  searchWrap.classList.add('search_open');
  if (activeBtn) activeBtn.classList.add('search_open');

  // requestAnimationFrame(() => {
  //   setTimeout(() => {
  //     searchInput.focus();

  //     // Затем принудительный клик для мобильных
  //     const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
  //     if (isTouchDevice) {
  //       searchInput.click(); // Дополнительный клик для вызова клавиатуры
  //     }


  //     searchInput.value = '';
  //   }, 10);
  // });

   const focusSearchInput = () => {
    // Первая попытка - стандартный фокус
    searchInput.focus();
    searchInput.value = '';
    
    // Вторая попытка через небольшой таймаут для мобильных
    setTimeout(() => {
      const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
      
      if (isTouchDevice && document.activeElement !== searchInput) {
        // Для тач-устройств, если фокус не сработал
        searchInput.blur(); // Сначала сбрасываем фокус
        setTimeout(() => {
          searchInput.focus();
          searchInput.click(); // Комбинируем методы
        }, 50);
      }
    }, 100);
  };

  requestAnimationFrame(() => {
    setTimeout(focusSearchInput, 0);
  });
  
  // при открытии чистим/прячем результаты
  if (!searchResult.hidden) {
    searchResult.hidden = true;
    searchResult.innerHTML = '';
    body.classList.remove('fix-body');
  }
}

function closeSearch() {
  // снимаем состояния у обёртки и кнопок
  searchWrap.classList.remove('search_open');
  searchOpenPopupBtn.forEach(btn => btn.classList.remove('search_open'));

  // всегда прячем и очищаем результаты
  searchResult.hidden = true;
  searchResult.innerHTML = '';
  body.classList.remove('fix-body');
}

// ====== события ======
searchOpenPopupBtn.forEach(btn =>
  btn.addEventListener('click', () => {
    if (searchWrap.classList.contains('search_open')) {
      closeSearch();
    } else {
      openSearch(btn);
    }
  })
);

// клик вне области — закрыть
document.addEventListener('pointerdown', (e) => {
  // если уже всё закрыто — игнор
  if (searchResult.hidden && !searchWrap.classList.contains('search_open')) return;

  // клики по самому поиску/кнопкам игнорируем
  if (
    searchWrap.contains(e.target) ||
    searchResult.contains(e.target) ||
    headerButns?.contains(e.target) ||
    headerButnsMob?.contains?.(e.target) ||
    e.target === headerButnsMob
  ) return;

  // иначе закрываем всё
  closeSearch();
});
