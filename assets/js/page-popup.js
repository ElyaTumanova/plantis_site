//переменные для управления попапом
const pagePopupContainer = document.querySelector('.page-popup__container'); 
const pageOpenPopupBtn = document.querySelectorAll('.page-popup-open-btn');
const pagePopup = document.querySelector('.page-popup');
const pageClosePopupBtn = document.querySelector('.page-popup__close');
const pagePopupOverlay = document.querySelector('.page-popup__popup-overlay');
const pagePopupContactForm = document.querySelector('.page-popup .wpcf7-form');
const pagePopupPreloader = document.querySelector('.page-popup .preloader');

//для попапа на странице усуги по уходу
let serviceButtons = document.querySelectorAll('.service-page .page-popup-open-btn');
let serviceNameInput = document.querySelector('.ukhod-popup-service-name');


if (pagePopup != null && pageOpenPopupBtn != null) {
    pageOpenPopupBtn.forEach(button => {
      button.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
      });
    });
    
    if (pageClosePopupBtn) {
        pageClosePopupBtn.addEventListener ("click", (evt)=>{
            toggle_page_popup ();
            cleanForm();
        });
    }

    pagePopupOverlay.addEventListener ("click", (evt)=>{
        toggle_page_popup ();
        cleanForm();
    });

    document.addEventListener('keydown', function(e){
        if((e.key=='Escape'||e.key=='Esc')){
            if(pagePopup.classList.contains('popup_active')) {
                toggle_page_popup ();
                cleanForm();
            } 
        }
    }, true);
}

function toggle_page_popup () {
    pagePopup.classList.toggle ('popup_active');
    body.classList.toggle ('fix-body');
};


if(pagePopup != null && serviceButtons != null) {
  serviceButtons.forEach(btn => {
    btn.addEventListener('click', (evt) => {
      if(serviceNameInput != null) {
        serviceNameInput.setAttribute('value',evt.target.name);
      }
    });
  });
}


if(pagePopupContactForm !== null) {
  // pagePopupContactForm.addEventListener('submit', hidePopup);
  pagePopupContactForm.addEventListener('submit', (evt) => {pagePopupPreloader.classList.add('active')});
}


function cleanForm() {
  if(pagePopupContactForm != null) {
    pagePopupContactForm.reset();
  }
}

function hidePopup() {
  setTimeout(() => {
    pagePopupContainer.style.visibility = 'hidden';
  }, 1000);
  setTimeout(() => {
    pagePopup.classList.remove('popup_active');
    body.classList.remove('fix-body');
    cleanForm();
    pagePopupContainer.style.visibility = 'visible';
  }, 5000);
}

document.addEventListener('wpcf7submit', function(event) {
    // Универсальный обработчик отправки
    pagePopupContainer.style.visibility = 'hidden';
    pagePopupPreloader.classList.remove('active');
    setTimeout(() => {
      pagePopup.classList.remove('popup_active');
      body.classList.remove('fix-body');
      cleanForm();
      pagePopupContainer.style.visibility = 'visible';
    }, 4000);

}, false);


