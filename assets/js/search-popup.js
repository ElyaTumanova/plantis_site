//переменные для управления попапом
const searchOpenPopupBtn = document.querySelectorAll('.search-btn');
const searchPopup = document.querySelector('.search-popup');
const searchWrap = document.querySelector('.search');
const searchClosePopupBtn = document.querySelector('.search__close');
const searchPopupOverlay = document.querySelector('.search__popup-overlay');

//доп переменные для поиска
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search-field');

searchOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        //toggle_search_popup ();
        toggleSearch();
    })
);

document.addEventListener('pointerdown', (e) => {
  if (searchResult.hidden) return;                 // если закрыта — игнор
  // Если клик пришёл не по контенту модалки и не по её потомкам — закрываем
  if (!searchResult.contains(e.target)) toggleSearch();
});

searchClosePopupBtn.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

searchPopupOverlay.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(searchPopup.classList.contains('popup_active')) {
            toggle_search_popup ();
        } 
    }
}, true);

function toggle_search_popup () {
    searchPopup.classList.toggle ('popup_active');
    // body.classList.toggle ('fix-body');
    searchInput.focus();

    // для поиска
    const searchPopupResultBtn = document.querySelector('.search-result__btn');
    const deleteElement = searchResult.querySelectorAll('div');
    for (let i = 0; i < deleteElement.length; i++) {
      deleteElement[i].remove();
    }
    searchInput.value= "";
    if(searchPopupResultBtn) {
        searchPopupResultBtn.remove();
    }

};

function toggleSearch() {
  searchWrap.classList.toggle('search_open');
  body.classList.toggle ('fix-body');
}

