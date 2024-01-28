//переменные для управления попапом
const searchOpenPopupBtn = document.querySelectorAll('.search-btn');
const searchPopup = document.querySelector('.search-popup');
const searchClosePopupBtn = document.querySelector('.search__close');
const searchPopupOverlay = document.querySelector('.search__popup-overlay');

//доп переменные для поиска
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search-field');

searchOpenPopupBtn.forEach((btn)=>
    btn.addEventListener ("click", (evt)=>{
        toggle_search_popup ();
    })
);


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

    // для поиска
    const deleteElement = searchResult.querySelectorAll('div');
    for (let i = 0; i < deleteElement.length; i++) {
      deleteElement[i].remove();
    }
    searchInput.value= "";
};

