//переменные для управления попапом
const searchOpenPopupBtn = document.querySelector('.search-btn');
const searchOpenPopupBtnMob = document.querySelector('.search-btn_mob');
// const searchPopup = document.querySelector('.search-popup');
const searchWrap = document.querySelector('.search');
const searchWrapMob = document.querySelector('.search_mob');
// const searchClosePopupBtn = document.querySelector('.search__close');
// const searchPopupOverlay = document.querySelector('.search__popup-overlay');
const headerButns = document.querySelector('.header__main .header__wrap');

//доп переменные для поиска
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search .search-field');
const searchInputMob = document.querySelector('.search_mob .search-field');


searchResult.hidden = true;

searchOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        toggleSearch();
    })
);

searchOpenPopupBtn.addEventListener('click', toggleSearch)
searchOpenPopupBtnMob.addEventListener('click', toggleSearchMob)

function toggleSearch() {
  searchWrap.classList.toggle('search_open');
  if (searchWrap.classList.contains('search_open')) {
    requestAnimationFrame(() => {
      setTimeout(() => {
        searchInput.focus();
        searchInput.value = '';
      }, 0);
    });
  }
}
function toggleSearchMob() {
  searchWrapMob.classList.toggle('search_open');
  if (searchWrapMob.classList.contains('search_open')) {
    requestAnimationFrame(() => {
      setTimeout(() => {
        searchInputMob.focus();
        searchInputMob.value = '';
      }, 0);
    });
  }
}

document.addEventListener('pointerdown', (e) => {
  if (searchResult.hidden && !searchWrap.classList.contains('search_open')) return;                 // если закрыта — игнор
  if (searchWrap.contains(e.target)) return;
  if (headerButns.contains(e.target)) return;
  // Если клик пришёл не по контенту модалки и не по её потомкам — закрываем
  if (!searchResult.contains(e.target)) {
    closeSearchResult()
  };
});

function closeSearchResult() {
    searchResult.hidden = true;
    searchResult.innerHTML = '';
    body.classList.remove('fix-body');
    toggleSearch();
}



// searchOpenPopupBtn.forEach((btn)=>
//     btn.addEventListener ("click", (evt)=>{
//         toggle_search_popup ();
//     })
// );

// searchClosePopupBtn.addEventListener ("click", (evt)=>{
//     toggle_search_popup ();
// });

// searchPopupOverlay.addEventListener ("click", (evt)=>{
//     toggle_search_popup ();
// });

// document.addEventListener('keydown', function(e){
//     if((e.key=='Escape'||e.key=='Esc')){
//         if(searchPopup.classList.contains('popup_active')) {
//             toggle_search_popup ();
//         } 
//     }
// }, true);

// function toggle_search_popup () {
//     searchPopup.classList.toggle ('popup_active');
//     // body.classList.toggle ('fix-body');
//     searchInput.focus();

//     // для поиска
//     const searchPopupResultBtn = document.querySelector('.search-result__btn');
//     const deleteElement = searchResult.querySelectorAll('div');
//     for (let i = 0; i < deleteElement.length; i++) {
//       deleteElement[i].remove();
//     }
//     searchInput.value= "";
//     if(searchPopupResultBtn) {
//         searchPopupResultBtn.remove();
//     }

// };
