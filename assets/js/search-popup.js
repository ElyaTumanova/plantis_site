//переменные для управления попапом
const searchOpenPopupBtn = document.querySelectorAll('.search-btn');
const searchPopup = document.querySelector('.search-popup');
const searchWrap = document.querySelector('.search');
const searchClosePopupBtn = document.querySelector('.search__close');
const searchPopupOverlay = document.querySelector('.search__popup-overlay');

//доп переменные для поиска
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search-field');


searchResult.hidden = true;

searchOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        toggleSearch();
    })
);

function toggleSearch() {
  searchWrap.classList.toggle('search_open');
  body.classList.toggle ('fix-body');
  searchInput.focus();
  searchInput.value= "";
}

document.addEventListener('pointerdown', (e) => {
  if (searchResult.hidden) return;                 // если закрыта — игнор
  if(searchWrap.contains(e.target)) return;
  // Если клик пришёл не по контенту модалки и не по её потомкам — закрываем
  if (!searchResult.contains(e.target)) {
    closeSearchResult()
  };
});

function closeSearchResult() {
    searchResult.hidden = true;
    searchResult.innerHTML = '';
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
