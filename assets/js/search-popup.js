const searchBtn = document.querySelector('.search-btn');
const searchPopup = document.querySelector('.search-popup');
const searchClose = document.querySelector('.search__close');
const searchOverlay = document.querySelector('.popup-overlay');
const searchResult = document.querySelector('.search-result');
const searchInput = document.querySelector('.search-field');

console.log(searchInput);



searchBtn.addEventListener ("click", (evt)=>{
    searchPopup.classList.toggle ('search-popup_active');
    document.body.classList.toggle ('body-overlay');
});
searchClose.addEventListener ("click", (evt)=>{
    close_search_popup ();
});

searchOverlay.addEventListener ("click", (evt)=>{
    close_search_popup ();
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){ 
        close_search_popup ();
    }
}, true);

function close_search_popup () {
    searchPopup.classList.remove ('search-popup_active');
    document.body.classList.remove ('body-overlay');
    const deleteElement = searchResult.querySelectorAll('div');
    for (let i = 0; i < deleteElement.length; i++) {
      deleteElement[i].remove();
    }
    searchInput.value= "";
};

