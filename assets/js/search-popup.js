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

requestAnimationFrame(() => {
    setTimeout(() => {
      // Проверяем, что элемент видим перед фокусом
      const isVisible = searchInput.offsetWidth > 0 && 
                       searchInput.offsetHeight > 0 && 
                       !searchInput.hidden;
      
      if (isVisible) {
        searchInput.focus();
        searchInput.value = '';
        
        // Для мобильных добавляем клик
        if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
          searchInput.click();
        }
      } else {
        // Если не видим, пробуем еще раз через небольшой интервал
        setTimeout(() => {
          searchInput.focus();
          searchInput.value = '';
          if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
            searchInput.click();
          }
        }, 50);
      }
    }, 0);
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
