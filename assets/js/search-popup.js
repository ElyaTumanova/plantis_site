// переменные для управления попапом
const searchOpenPopupBtn = document.querySelectorAll('.search-btn');
const searchWrap = document.querySelector('.search__wrap');
const headerButns = document.querySelector('.header__main .header__wrap');
const headerButnsMob = document.querySelector('.header__mob .search-btn');
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search__wrap .search-field');
const searchClear = document.querySelector('.search__wrap .search__clean');

searchResult.hidden = true;

// ====== отдельные функции ======
function openSearch(activeBtn = null) {
  if (searchWrap.classList.contains('search_open')) return;

  searchWrap.classList.add('search_open');
  if (activeBtn) activeBtn.classList.add('search_open');

  focusSearch();
  closeSearchResults();
  
}

function closeSearch() {
  // снимаем состояния у обёртки и кнопок
  searchWrap.classList.remove('search_open');
  searchOpenPopupBtn.forEach(btn => btn.classList.remove('search_open'));

  // всегда прячем и очищаем результаты
  closeSearchResults();
}

function focusSearch() {
  searchInput.focus();
  
  // Для iOS - создаем временное событие touch
  if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
      const clickEvent = document.createEvent('MouseEvents');
      clickEvent.initEvent('touchstart', true, true);
      searchInput.dispatchEvent(clickEvent);
  }
  
  // Дополнительный трюк для некоторых Android устройств
  setTimeout(() => {
      searchInput.setSelectionRange(0, 0);
  }, 100);

  searchInput.value = '';
}

function closeSearchResults() {
  if (!searchResult.hidden) {
    searchResult.hidden = true;
    searchResult.innerHTML = '';
    body.classList.remove('fix-body');
  }
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

//очистить строку поиска
searchClear.addEventListener('click', ()=>{
    focusSearch();
    closeSearchResults();
})
