//переменные для управления попапом
const openPopupBtn = document.querySelector('.search-btn');
const popup = document.querySelector('.search-popup');
const closePopupBtn = document.querySelector('.search__close');
const popupOverlay = document.querySelector('.search__popup-overlay');

//доп переменные для поиска
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search-field');

openPopupBtn.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

closePopupBtn.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

popupOverlay.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(popup.classList.contains('popup_active')) {
            toggle_search_popup ();
        } 
    }
}, true);

function toggle_search_popup () {
    popup.classList.toggle ('popup_active');

    // для поиска
    const deleteElement = searchResult.querySelectorAll('div');
    for (let i = 0; i < deleteElement.length; i++) {
      deleteElement[i].remove();
    }
    searchInput.value= "";
};

