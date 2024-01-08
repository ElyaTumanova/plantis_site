const searchBtn = document.querySelector('.search-btn');
const searchPopup = document.querySelector('.search-popup');
console.log (searchBtn);
searchBtn.addEventListener ("click", (evt)=>{
    searchPopup.classList.add ('search-popup_active');
});