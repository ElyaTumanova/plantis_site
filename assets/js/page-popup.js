//переменные для управления попапом
const pageOpenPopupBtn = document.querySelectorAll('.page-popup-open-btn');
const pagePopup = document.querySelector('.page-popup');
const pageClosePopupBtn = document.querySelector('.page-popup__close');
const pagePopupOverlay = document.querySelector('.page-popup__popup-overlay');

if (pagePopup != null && pageOpenPopupBtn != null) {
    pageOpenPopupBtn.forEach(button => {
      button.addEventListener ("click", (evt)=>{
          toggle_page_popup ();
      });
    });
    
    if (pageClosePopupBtn) {
        pageClosePopupBtn.addEventListener ("click", (evt)=>{
            toggle_page_popup ();
        });
    }

    pagePopupOverlay.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(pagePopup.classList.contains('popup_active')) {
                toggle_page_popup ();
            } 
        }
    }, true);
}

function toggle_page_popup () {
    pagePopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};


//для попапа на странице усуги по уходу
let ukhodButtons = document.querySelectorAll('.page-ukhod .page-popup-open-btn');
let serviceNameInput = document.querySelector('.ukhod-popup-service-name');

console.log(ukhodButtons);
console.log(serviceNameInput);


if(pagePopup != null && ukhodButtons != null) {
  ukhodButtons.forEach(btn => {
    btn.addEventListener('click', setServiceName);
  });
}

function setServiceName(evt) {
  console.log(evt.target.name);
  if(serviceNameInput != null) {
    serviceNameInput.setAttribute(value,evt.target.name);
  }
}

