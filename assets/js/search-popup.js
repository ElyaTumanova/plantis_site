const searchBtn = document.querySelector('.search-btn');
const searchPopup = document.querySelector('.search-popup');
const searchClose = document.querySelector('.search__close');
const searchOverlay = document.querySelector('.popup-overlay');


// const searchInput = document.querySelector('.search'); 
console.log (searchBtn);
console.log (searchClose);
searchBtn.addEventListener ("click", (evt)=>{
    searchPopup.classList.toggle ('search-popup_active');
    // searchInput.classList.add ('container');
});
searchClose.addEventListener ("click", (evt)=>{
    searchPopup.classList.remove ('search-popup_active');
});
searchOverlay.addEventListener ("click", (evt)=>{
    searchPopup.classList.remove ('search-popup_active');
});

document.addEventListener('keydown', function(e){
    if((e.key=='Escape'||e.key=='Esc')){ 
        searchPopup.classList.remove ('search-popup_active');
    }
}, true);