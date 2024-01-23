const searchBtn = document.querySelector('.search-btn');
const searchPopup = document.querySelector('.search-popup');
const searchClose = document.querySelector('.search__close');
const searchOverlay = document.querySelector('.search-popup');
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search-field');

searchBtn.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

searchClose.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

searchOverlay.addEventListener ("click", (evt)=>{
    toggle_search_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){
        if(searchPopup.classList.contains('search-popup_active')) {
            toggle_search_popup ();
        } 
    }
}, true);

function toggle_search_popup () {
    searchPopup.classList.toggle ('search-popup_active');
    // document.body.classList.toggle ('body-overlay');
    const deleteElement = searchResult.querySelectorAll('div');
    for (let i = 0; i < deleteElement.length; i++) {
      deleteElement[i].remove();
    }
    searchInput.value= "";
};

