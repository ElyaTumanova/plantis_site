const searchBtn = document.querySelector('.search-btn');
const searchPopup = document.querySelector('.search-popup');
// const searchInput = document.querySelector('.search'); 
console.log (searchBtn);
console.log (searchPopup);
searchBtn.addEventListener ("click", (evt)=>{
    searchPopup.classList.toggle ('search-popup_active');
    // searchInput.classList.add ('container');
});